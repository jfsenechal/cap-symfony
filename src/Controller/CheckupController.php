<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\CommercioCommercantRepository;
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
        private CommercioCommercantRepository $commercantRepository
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

    #[Route(path: '/order/nopaid', name: 'cap_checkup_order_without_paid', methods: ['GET', 'POST'])]
    public function nopaid(): Response
    {
        $orders = $this->paymentOrderRepository->findPaid();
        $noBill = [];
        foreach ($orders as $order) {
            $bills = $this->paymentBillRepository->findByOrder($order);
            if (count($bills) == 0) {
                $noBill[] = $order;
            }
        }

        return $this->render(
            '@CapCommercio/checkup/nobill.html.twig',
            [
                'orders' => $noBill,
            ]
        );
    }

    #[Route(path: '/expired', name: 'cap_checkup_expired', methods: ['GET', 'POST'])]
    public function expired(): Response
    {
        $today = new \DateTime('+1 year');
        $commercants = $this->commercantRepository->findExpired($today);

        return $this->render(
            '@CapCommercio/checkup/expired.html.twig',
            [
                'commercants' => $commercants,
                'today' => $today,
            ]
        );
    }

}