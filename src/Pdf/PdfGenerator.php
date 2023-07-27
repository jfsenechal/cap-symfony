<?php

namespace Cap\Commercio\Pdf;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Twig\Environment;

class PdfGenerator
{
    use PdfDownloaderTrait;

    public function __construct(
        private readonly Environment $environment,
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly PaymentOrderAddressRepository $paymentOrderAddressRepository
    ) {
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generateContentForOrder(PaymentOrder $order): string
    {
        $orderCommercant = $order->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($order);
        $address = $this->paymentOrderAddressRepository->findOneByOrder($order);

        return $this->environment->render('@CapCommercio/pdf/order.html.twig', [
            'order' => $order,
            'orderCommercant' => $orderCommercant,
            'line' => $line,
            'address' => $address,
        ]);

    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generateContentForBill(PaymentBill $bill): string
    {
        $order = $bill->getOrder();
        $orderCommercant = $order->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($order);
        $address = $this->paymentOrderAddressRepository->findOneByOrder($order);

        return $this->environment->render('@CapCommercio/pdf/bill.html.twig', [
            'bill' => $bill,
            'order' => $order,
            'orderCommercant' => $orderCommercant,
            'line' => $line,
            'address' => $address,
        ]);
    }

}