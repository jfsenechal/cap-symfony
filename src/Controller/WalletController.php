<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\WalletOrderType;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Wallet\Customer;
use Cap\Commercio\Wallet\EventIdCodesEnum;
use Cap\Commercio\Wallet\WalletApi;
use Cap\Commercio\Wallet\WalletOrder;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/wallet')]
class WalletController extends AbstractController
{
    public function __construct(
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly WalletApi $walletApi
    ) {
    }

    #[Route(path: '/', name: 'cap_wallet_index', methods: ['GET', 'POST'])]
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

    #[Route(path: '/new/order/{id}', name: 'cap_wallet_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);

        $customer = new Customer('jf@marche.be', $orderCommercant->getCompanyName());
        $order = new WalletOrder($paymentOrder->getPriceVat(), $customer, $line->getLabel());

        $order->sourceCode = '1619';
        $order->merchantTrns = 'Cap sur Marche';

        $form = $this->createForm(WalletOrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $this->walletApi->getToken();
            } catch (\Exception|InvalidArgumentException $exception) {
                $this->addFlash('danger', 'Erreur pour obtenir le token: '.$exception->getMessage());

                return $this->redirectToRoute('cap_order_show', ['id' => $paymentOrder->getId()]);
            }

            $token = $data->access_token;
            try {
                $responseString = $this->walletApi->createOrder($order, $token);
                $response = json_decode($responseString);
                $orderCode = $response->orderCode;

                return $this->redirect($this->walletApi->url.'/web/checkout?ref='.$orderCode.'&color=50afd2');
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'Erreur pour générer le paiement: '.$exception->getMessage());

                return $this->redirectToRoute('cap_order_show', ['id' => $paymentOrder->getId()]);
            }
        }

        return $this->render(
            '@CapCommercio/wallet/new_order.html.twig',
            [
                'order' => $order,
                'form' => $form,
            ]
        );

    }

    #[Route(path: '/success', name: 'cap_wallet_transaction_success', methods: ['GET', 'POST'])]
    public function transactionSuccess(Request $request): Response
    {
        $s = $request->query->get('s');
        $lang = $request->query->get('lang');
        $eventId = $request->query->get('eventId');
        $transactionId = $request->query->get('t');
        $eci = $request->query->get('eci');
        $eventIdCodeEnum = EventIdCodesEnum::from($eventId);

        return $this->render(
            '@CapCommercio/wallet/success.html.twig',
            [
                's' => $s,
                'eventId' => $eventId,
                'eventIdCodeEnum' => $eventIdCodeEnum,
                'transactionId' => $transactionId,
                'eci' => $eci,
            ]
        );
    }

    #[Route(path: '/failure', name: 'cap_wallet_transaction_failure', methods: ['GET', 'POST'])]
    public function transactionFailure(Request $request): Response
    {
        dd($request);
        $s = $request->query->get('s');
        $lang = $request->query->get('lang');
        $eventId = $request->query->get('eventId');

        return $this->render(
            '@CapCommercio/wallet/failure.html.twig',
            [
                'eventId' => $eventId,
                's' => $s,
            ]
        );
    }

    #[Route(path: '/retrieve/order/{orderCode}', name: 'cap_wallet_order_retrieve', methods: ['GET', 'POST'])]
    public function orderRetrieve(string $orderCode): Response
    {
        try {
            $order = $this->walletApi->retrieveOrder($orderCode);
        } catch (\Exception|InvalidArgumentException $exception) {
            dd($exception);
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
    public function transactionRetrieve(string $transactionId): Response
    {
        try {
            $data = $this->walletApi->getToken();
        } catch (\Exception|InvalidArgumentException $exception) {
            dd($exception);
        }

        $token = $data->access_token;

        try {
            $transactionString = $this->walletApi->retrieveTransaction($transactionId, $token);
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'Erreur pour récupérer le paiement: '.$exception->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        return $this->render(
            '@CapCommercio/wallet/transaction.html.twig',
            [
                'transaction' => json_decode($transactionString),
                'transactionId' => $transactionId,
            ]
        );
    }

    #[Route(path: '/webhook', name: 'cap_wallet_webhook', methods: ['GET', 'POST'])]
    public function webhook(Request $request): JsonResponse
    {
        try {
            $data = $this->walletApi->getToken();
        } catch (\Exception|InvalidArgumentException $exception) {
            dd($exception);
        }

        $token = $data->access_token;

        return $this->json(['Key' => $token]);
    }
}
