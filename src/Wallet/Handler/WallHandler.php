<?php

namespace Cap\Commercio\Wallet\Handler;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Repository\SettingRepository;
use Cap\Commercio\Setting\SettingEnum;
use Cap\Commercio\Wallet\Customer;
use Cap\Commercio\Wallet\OrderStatusEnum;
use Cap\Commercio\Wallet\WalletApi;
use Cap\Commercio\Wallet\WalletOrder;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class WallHandler
{
    public function __construct(
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly SettingRepository $settingRepository,
        private readonly WalletApi $walletApi,
        private readonly RouterInterface $router
    ) {
    }

    public function createWalletOrderFromPaymentOrder(PaymentOrder $paymentOrder): WalletOrder
    {
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);
        $orderCommercant = $paymentOrder->getOrderCommercant();
        //todo remove jf
        $customer = new Customer('maureen.cap@marche.be', $orderCommercant->getCompanyName());
        $walletOrder = new WalletOrder($paymentOrder->getPriceVat() * 100, $customer, $line->getLabel());
        $walletOrder->sourceCode = '6912';
        $walletOrder->merchantTrns = $this->settingRepository->findValue(SettingEnum::SITE_NAME->value)->getParamValue(
        );

        return $walletOrder;
    }

    /**
     *
     */
    public function checkPaymentOrderStillValid(object $data): bool
    {
        if (!isset($data->StateId) || !isset($data->ExpirationDate)) {
            return false;
        }
        if ($data->StateId > OrderStatusEnum::PENDING->value) {
            return false;
        }
        $now = new \DateTime();
        $dateTime = \DateTime::createFromFormat("Y-m-d\TH:i:s.u", $data->ExpirationDate);
        if ($now->format('Y-m-d H:i') > $dateTime->format('Y-m-d H:i')) {
            return false;
        }

        return true;
    }


    /**
     * @param PaymentOrder $paymentOrder
     * @param WalletOrder $walletOrder
     * @return void
     * @throws \Exception
     */
    public function createOrderForOnlinePayment(PaymentOrder $paymentOrder, WalletOrder $walletOrder): void
    {
        try {
            $data = $this->walletApi->getToken();
        } catch (\Exception|InvalidArgumentException $exception) {
            throw new \Exception('Erreur pour obtenir le token: '.$exception->getMessage());
        }

        $token = $data->access_token;
        try {
            $codeOrderString = $this->walletApi->createOrder($walletOrder, $token);
            $response = json_decode($codeOrderString);
            $paymentOrder->walletCodeOrder = $response->orderCode;
            $this->paymentOrderLineRepository->flush();

        } catch (\Exception $exception) {
            throw new \Exception('Erreur pour générer la demande de paiement en ligne: '.$exception->getMessage());
        }

    }

    public function retrievePaymentOrderByCodeOrder(string $orderCode): ?PaymentOrder
    {
        return $this->paymentOrderRepository->findOneByWalletCodeOrder($orderCode);
    }

    public function failure()
    {

    }

    public function generateUrlForPayment(PaymentOrder $paymentOrder): string
    {
        return $this->router->generate(
            'cap_wallet_order_new',
            ['uuid' => $paymentOrder->getUuid()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}