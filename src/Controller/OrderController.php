<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\OrderSearchType;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderCommercantRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/order')]
#[IsGranted(data: 'ROLE_CAP')]
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
    public function order(Request $request): Response
    {
        $form = $this->createForm(OrderSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $payementsOrder = $this->paymentOrderRepository->search($data['name']);
        } else {
            $payementsOrder = $this->paymentOrderRepository->findAllOrdered();
        }

        $payementsOrderCommercant = $this->paymentCommercantOrderRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/order/index.html.twig',
            [
                'payementsOrder' => $payementsOrder,
                'form' => $form,
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
        $bills = $this->paymentBillRepository->findByOrder($paymentOrder);

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
                'orderCommercant' => $orderCommercant,
                'lines' => $lines,
                'addresses' => $addresses,
                'bills' => $bills,
            ]
        );
    }

    #[Route(path: '/delete/{id}', name: 'cap_order_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentOrder $paymentOrder): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentOrder->getId(), $request->request->get('_token'))) {
            $orderCommercant = $paymentOrder->getOrderCommercant();
            $lines = $this->paymentOrderLineRepository->findByOrder($paymentOrder);
            $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
            $bills = $this->paymentBillRepository->findByOrder($paymentOrder);

            $this->paymentOrderRepository->remove($orderCommercant);
            foreach ($lines as $line) {
                $this->paymentOrderRepository->remove($line);
            }
            foreach ($addresses as $address) {
                $this->paymentOrderRepository->remove($address);
            }
            foreach ($bills as $bill) {
                $this->paymentOrderRepository->remove($bill);
            }
            $this->paymentOrderRepository->remove($paymentOrder);

            $this->paymentOrderRepository->flush();

            $this->addFlash('success', 'Commande supprim??e');
        }

        return $this->redirectToRoute('cap_order_all');
    }

}