<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderCommercantRepository;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentOrderCommercantRepository $paymentCommercantOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
        private PaymentOrderLineRepository $paymentOrderLineRepository,
        private PaymentOrderAddressRepository $paymentOrderAddressRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_order_all', methods: ['GET'])]
    public function order(): Response
    {
        $payementsOrder = $this->paymentOrderRepository->findAllOrdered();
        $payementsOrderCommercant = $this->paymentCommercantOrderRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/order/index.html.twig',
            [
                'payementsOrder' => $payementsOrder,
            ]
        );
    }

    #[Route(path: '/bill', name: 'cap_bill_all', methods: ['GET'])]
    public function bill(): Response
    {
        $bills = $this->paymentBillRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/order/bill.html.twig',
            [
                'bills' => $bills,
            ]
        );
    }

    #[Route(path: '/show/{id}', name: 'cap_order_show', methods: ['GET', 'POST'])]
    public function show(PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $lines = $this->paymentOrderLineRepository->findByOrder($paymentOrder);
        $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
        $bill = $this->paymentBillRepository->findByOrder($paymentOrder);

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
                'orderCommercant' => $orderCommercant,
                'lines' => $lines,
                'addresses' => $addresses,
                'bill' => $bill,
            ]
        );
    }

    #[Route(path: '/delete/{id}', name: 'cap_order_delete', methods: ['GET', 'POST'])]
    public function delete(PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $lines = $this->paymentOrderLineRepository->findByOrder($paymentOrder);
        $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
        $bill = $this->paymentBillRepository->findByOrder($paymentOrder);

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
            ]
        );
    }

}