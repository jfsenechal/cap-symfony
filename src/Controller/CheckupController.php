<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/checkup')]
#[IsGranted('ROLE_CAP')]
class CheckupController extends AbstractController
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
    ) {
    }

    #[Route(path: '/', name: 'cap_checkup_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render(
            '@CapCommercio/checkup/index.html.twig',
            [

            ]
        );
    }

    #[Route(path: '/bill', name: 'cap_checkup_bill', methods: ['GET', 'POST'])]
    public function bill(): Response
    {
        $orders = $this->paymentOrderRepository->findAllOrdered();
        $multiplePayments = [];
        foreach ($orders as $order) {
            try {
                $this->paymentBillRepository->findOneByOrder($order);
            } catch (\Exception $exception) {
                $multiplePayments[] = $order;
            }
        }

        return $this->render(
            '@CapCommercio/checkup/bill.html.twig',
            [
                'orders' => $multiplePayments,
            ]
        );
    }

}