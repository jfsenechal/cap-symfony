<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bill\Handler\PaymentOrderHandler;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\WalletOrderType;
use Cap\Commercio\Mailer\MailerJf;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Wallet\EciEnum;
use Cap\Commercio\Wallet\EventIdCodesEnum;
use Cap\Commercio\Wallet\Handler\WallHandler;
use Cap\Commercio\Wallet\WalletApi;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/wallet')]
class WalletController extends AbstractController
{
    public function __construct(
        private readonly WalletApi $walletApi,
        private readonly WallHandler $wallHandler,
        private readonly PaymentOrderHandler $paymentOrderHandler,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly MailerJf $mailerJf
    ) {
    }

    #[Route(path: '/', name: 'cap_wallet_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CAP')]
    public function index(): Response
    {
        $url = $this->walletApi->url.'/selfcare/en/sales/paynotifications';

        return $this->render(
            '@CapCommercio/wallet/index.html.twig',
            [
                'url' => $url,
            ]
        );
    }

    #[Route(path: '/new/order/{uuid}', name: 'cap_wallet_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentOrder $paymentOrder): Response
    {
        if ($bill = $this->paymentBillRepository->findOneByOrder($paymentOrder)) {
            $this->addFlash('danger', 'Cette commande a déjà été payée');
        }

        $walletOrder = $this->wallHandler->createWalletOrderFromPaymentOrder($paymentOrder);

        $form = $this->createForm(WalletOrderType::class, $walletOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !$bill) {
            $walletCodeOrder = null;
            if ($paymentOrder->walletCodeOrder) {
                try {
                    $dataString = $this->walletApi->retrieveOrder($paymentOrder->walletCodeOrder);
                    $data = json_decode($dataString);
                    if ($this->wallHandler->checkPaymentOrderStillValid($data)) {
                        $walletCodeOrder = $data->OrderCode;
                    }
                } catch (\Exception $exception) {
                    $this->mailerJf->sendError('Error retrieve payment order on wallet', $exception->getMessage());
                }
            }

            if (!$walletCodeOrder) {
                try {
                    $this->wallHandler->createOrderForOnlinePayment($paymentOrder, $walletOrder);
                    $walletCodeOrder = $paymentOrder->walletCodeOrder;

                } catch (\Exception $e) {
                    $this->addFlash('danger', $e->getMessage());
                    $this->mailerJf->sendError('Error create bill', $exception->getMessage());

                    return $this->redirectToRoute('cap_wallet_order_new', ['uuid' => $paymentOrder->getUuid()]);
                }
            }

            return $this->redirect(
                $this->walletApi->url.'/web/checkout?ref='.$walletCodeOrder.'&color=50afd2'
            );
        }

        return $this->render(
            '@CapCommercio/wallet/new_order.html.twig',
            [
                'paymentOrder' => $paymentOrder,
                'orderCommercant' => $paymentOrder->getOrderCommercant(),
                'walletOrder' => $walletOrder,
                'form' => $form,
                'bill' => $bill,
            ]
        );

    }

    #[Route(path: '/success', name: 'cap_wallet_transaction_success', methods: ['GET', 'POST'])]
    public function transactionSuccess(Request $request): Response
    {
        if (!$orderCode = $request->query->get('s')) {
            $this->addFlash('danger', 'Aucun code de commande trouvé');

            return $this->redirectToRoute('cap_home');
        }

        $eventId = $request->query->get('eventId');
        $eci = $request->query->get('eci');
        $eventIdCodeEnum = EventIdCodesEnum::from($eventId);
        $eciEnum = EciEnum::from($eci);

        $transactionId = $request->query->get('t');
        $paymentOrder = $this->wallHandler->retrievePaymentOrderByCodeOrder($orderCode);

        if ($paymentOrder) {
            try {
                $bill = $this->paymentOrderHandler->paid($paymentOrder);
                $bill->walletTransactionId = $transactionId;
                $this->paymentOrderRepository->flush();
            } catch (\Exception $exception) {
                $this->mailerJf->sendError('Error create bill', $exception->getMessage());
            }
        }
        if ($eventId != 0) {
            $this->mailerJf->sendError(
                'Paiement eventId '.$eventId,
                $eventId.' transaction id'.$transactionId.' order Code '.$orderCode
            );
        }

        return $this->render(
            '@CapCommercio/wallet/success.html.twig',
            [
                'orderCode' => $orderCode,
                'eventIdCodeEnum' => $eventIdCodeEnum,
            ]
        );
    }

    #[Route(path: '/failure', name: 'cap_wallet_transaction_failure', methods: ['GET', 'POST'])]
    public function transactionFailure(Request $request): Response
    {
        $orderCode = $request->query->get('s');
        $eventId = $request->query->get('eventId');
        $eventIdCodeEnum = EventIdCodesEnum::from($eventId);
        $lang = $request->query->get('lang');
        $eci = $request->query->get('eci');
        $eciEnum = EciEnum::from($eci);
        $transactionId = $request->query->get('t');

        $this->mailerJf->sendError(
            'Error transaction failure',
            'order code: '.$orderCode.' transactionId '.$transactionId.' raison'.$eventIdCodeEnum->reason()
        );

        return $this->render(
            '@CapCommercio/wallet/failure.html.twig',
            [
                'eventId' => $eventId,
                'eventIdCodeEnum' => $eventIdCodeEnum,
                'transactionId' => $transactionId,
                'orderCode' => $orderCode,
            ]
        );
    }

    #[Route(path: '/retrieve/order/{orderCode}', name: 'cap_wallet_order_retrieve', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CAP')]
    public function orderRetrieve(string $orderCode): Response
    {
        $order = null;
        try {
            $order = json_decode($this->walletApi->retrieveOrder($orderCode));
        } catch (\Exception|InvalidArgumentException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->render(
            '@CapCommercio/wallet/order.html.twig',
            [
                'order' => $order,
            ]
        );
    }

    #[Route(path: '/retrieve/transaction/{transactionId}', name: 'cap_wallet_transaction_retrieve', methods: [
        'GET',
        'POST',
    ])]
    #[IsGranted('ROLE_CAP')]
    public function transactionRetrieve(string $transactionId): Response
    {
        try {
            $data = $this->walletApi->getToken();
        } catch (\Exception|InvalidArgumentException $exception) {
            $this->addFlash('danger', 'Erreur pour récupérer le paiement: '.$exception->getMessage());

            return $this->redirectToRoute('cap_home');
        }
        $token = $data->access_token;

        try {
            $transactionString = $this->walletApi->retrieveTransaction($transactionId, $token);
            $transaction = json_decode($transactionString);
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Erreur pour récupérer le paiement: '.$exception->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        return $this->render(
            '@CapCommercio/wallet/transaction.html.twig',
            [
                'transaction' => $transaction,
                'transactionId' => $transactionId,
            ]
        );
    }

    #[Route(path: '/webhook', name: 'cap_wallet_webhook', methods: ['GET', 'POST'])]
    public function webhook(Request $request): JsonResponse
    {
        try {
            $data = $this->walletApi->hookToken();

            return $this->json(json_decode($data));
        } catch (\Exception|InvalidArgumentException $exception) {
            dd($exception);
        }

        return $this->json([]);
    }
}
