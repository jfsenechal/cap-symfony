<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Wallet\Customer;
use Cap\Commercio\Wallet\WalletApi;
use Cap\Commercio\Wallet\WalletOrder;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/wallet')]
#[IsGranted('ROLE_CAP')]
class WalletController extends AbstractController
{
    public function __construct(
        private readonly WalletApi $walletApi
    ) {
    }

    #[Route(path: '/', name: 'cap_wallet_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $data = $this->walletApi->getToken();
        } catch (\Exception|InvalidArgumentException $exception) {
            dd($exception);
        }

        $token = $data->access_token;
        $expires_in = $data->expires_in;

        //return $this->json(['token' => $token, 'expires_in' => $expires_in]);

        $customer = new Customer('someone@vivawallet.com', 'George Seferis');
        $order = new WalletOrder(100, $customer, 'This is a description displayed to the customer');

        try {
            dd($this->walletApi->createOrder($order, $token));
        } catch (\Exception $e) {
            dd($e);
        }

        return $this->json([]);
    }
}
