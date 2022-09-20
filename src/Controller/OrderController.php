<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\PaymentOrderCommercantRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentOrderCommercantRepository $paymentCommercantOrderRepository
    ) {
    }

    #[Route(path: '/', name: 'order_all', methods: ['GET'])]
    public function contact(): Response
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

}