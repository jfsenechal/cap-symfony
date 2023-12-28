<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\WalletOrderType;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Wallet\Customer;
use Cap\Commercio\Wallet\WalletApi;
use Cap\Commercio\Wallet\WalletOrder;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request): Response
    {
        return $this->render(
            '@CapCommercio/wallet/index.html.twig',
            [

            ]
        );
    }

    #[Route(path: '/new/{id}', name: 'cap_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);

        $customer = new Customer('jf@marche.be', $orderCommercant->getCompanyName());
        $order = new WalletOrder($paymentOrder->getPriceVat(), $customer, $line->getLabel());

        $order->sourceCode = '1619';

        $form = $this->createForm(WalletOrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $this->walletApi->getToken();
            } catch (\Exception|InvalidArgumentException $exception) {
                dd($exception);
            }

            $token = $data->access_token;
            try {
                $responseString = $this->walletApi->createOrder($order, $token);
                $response = json_decode($responseString);
                $orderCode = $response->orderCode;

                return $this->redirect('https://demo.vivapayments.com/web/checkout?ref='.$orderCode.'&color=50afd2');
            } catch (\Exception $e) {
                dd($e);
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

    #[Route(path: '/success', name: 'cap_wallet_success', methods: ['GET', 'POST'])]
    public function success(Request $request): Response
    {

        $s = $request->query->get('s');
        $lang = $request->query->get('lang');
        $eventId = $request->query->get('eventId');
        $transactionId = $request->query->get('t');
        $eci = $request->query->get('eci');

        return $this->render(
            '@CapCommercio/wallet/success.html.twig',
            [
                'request' => $request,
            ]
        );
    }

    #[Route(path: '/failure', name: 'cap_wallet_failure', methods: ['GET', 'POST'])]
    public function failure(Request $request): Response
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

    #[Route(path: '/webhook', name: 'cap_wallet_webhook', methods: ['GET', 'POST'])]
    public function webhook(Request $request): Response
    {
        return $this->render(
            '@CapCommercio/wallet/index.html.twig',
            [

            ]
        );
    }
}
