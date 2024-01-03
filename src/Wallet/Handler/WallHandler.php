<?php

namespace Cap\Commercio\Wallet\Handler;

use Cap\Commercio\Bill\Generator\BillGenerator;
use Cap\Commercio\Bill\Generator\OrderGenerator;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Repository\SettingRepository;
use Cap\Commercio\Setting\SettingEnum;
use Cap\Commercio\Wallet\Customer;
use Cap\Commercio\Wallet\EventIdCodesEnum;
use Cap\Commercio\Wallet\WalletApi;
use Cap\Commercio\Wallet\WalletOrder;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class WallHandler
{
    public function __construct(
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly SettingRepository $settingRepository,
        private readonly WalletApi $walletApi,
        private readonly BillGenerator $billGenerator,
        private readonly OrderGenerator $orderGenerator,
        private readonly PdfGenerator $pdfGenerator,
    ) {
    }

    public function createWalletOrderFromPaymentOrder(PaymentOrder $paymentOrder): WalletOrder
    {
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);

        $orderCommercant = $paymentOrder->getOrderCommercant();
        $customer = new Customer('jf@marche.be', $orderCommercant->getCompanyName());
        $walletOrder = new WalletOrder($paymentOrder->getPriceVat(), $customer, $line->getLabel());
        $walletOrder->sourceCode = '1619';
        $walletOrder->merchantTrns = $this->settingRepository->findValue(SettingEnum::SITE_NAME->value)->getParamValue(
        );

        return $walletOrder;
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
            $responseString = $this->walletApi->createOrder($walletOrder, $token);
            $response = json_decode($responseString);
            $paymentOrder->walletCodeOrder = $response->orderCode;
            $this->paymentOrderLineRepository->flush();

        } catch (\Exception $exception) {
            throw new \Exception('Erreur pour générer la demande de paiement en ligne: '.$exception->getMessage());
        }

    }

    public function success(Request $request): ?PaymentOrder
    {
        if (!$request->query->get('s')) {
            return null;
        }
        $orderCode = $request->query->get('s');
        $lang = $request->query->get('lang');
        $eventId = $request->query->get('eventId');
        $transactionId = $request->query->get('t');
        $eci = $request->query->get('eci');
        $eciCodeEnum = $eci;
        $eventIdCodeEnum = EventIdCodesEnum::from($eventId);

        return $this->paymentOrderRepository->findOneByWalletCodeOrder($orderCode);
    }

    public function failure()
    {

    }
}