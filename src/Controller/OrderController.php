<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\OrderSearchType;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/order')]
#[IsGranted('ROLE_CAP')]
class OrderController extends AbstractController
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
        private PaymentOrderLineRepository $paymentOrderLineRepository,
        private PaymentOrderAddressRepository $paymentOrderAddressRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_order_all', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(OrderSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $orders = $this->paymentOrderRepository->search($data['name'], $data['year'], $data['paid']);
        } else {
            $orders = $this->paymentOrderRepository->findAllOrdered();
        }

        return $this->render(
            '@CapCommercio/order/index.html.twig',
            [
                'orders' => $orders,
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
        $bills = [];
        try {
            $bill = $this->paymentBillRepository->findOneByOrder($paymentOrder);
        } catch (NonUniqueResultException $e) {
            $bill = null;
            $bills = $this->paymentBillRepository->findByOrder($paymentOrder);
        }

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
                'orderCommercant' => $orderCommercant,
                'lines' => $lines,
                'addresses' => $addresses,
                'bill' => $bill,
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
            $bills = $this->paymentBillRepository->findOneByOrder($paymentOrder);

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

            $this->addFlash('success', 'Commande supprimée');
        }

        return $this->redirectToRoute('cap_order_all');
    }

}