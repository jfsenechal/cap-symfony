<?php

namespace Cap\Commercio\Pdf;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Twig\Environment;

class PdfGenerator
{
    public function __construct(
        private Environment $environment,
        private PaymentOrderLineRepository $paymentOrderLineRepository,
        private PaymentOrderAddressRepository $paymentOrderAddressRepository
    ) {
    }

    /**
     * @param PaymentOrder $order
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateForOrder(PaymentOrder $order): string
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
}