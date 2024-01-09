<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bill\Generator\OrderGenerator;
use Cap\Commercio\Bill\Handler\PaymentOrderHandler;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\OrderEditType;
use Cap\Commercio\Form\OrderSearchType;
use Cap\Commercio\Form\OrderType;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route(path: '/order')]
#[IsGranted('ROLE_CAP')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly PaymentOrderAddressRepository $paymentOrderAddressRepository,
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly PaymentOrderHandler $paymentOrderHandler,
        private readonly OrderGenerator $orderGenerator,
    ) {
    }

    #[Route(path: '/', name: 'cap_order_all', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(OrderSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $orders = $this->paymentOrderRepository->search(
                $data['number'],
                $data['name'],
                $data['year'],
                $data['paid']
            );
        } else {
            $orders = $this->paymentOrderRepository->findAllOrdered();
        }

        foreach ($orders as $order) {
            try {
                $order->bill = $this->paymentBillRepository->findOneByOrder($order);
            } catch (NonUniqueResultException) {
                $order->bill = null;
                $this->addFlash('danger', 'Le bon de commande a plusieurs paiements '.$order->getOrderNumber());
            }
        }

        return $this->render(
            '@CapCommercio/order/index.html.twig',
            [
                'orders' => $orders,
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/{id}/new', name: 'cap_order_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommercioCommercant $commercant): Response
    {
        $order = new PaymentOrder();
        $order->setCommercantId($commercant->getId());
        $order->setVat(0.21);

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setVat($order->getVat() * 100);
            try {
                $order = $this->orderGenerator->newOne(
                    $commercant,
                    $order->getPriceEvat(),
                    $order->getVat(),
                    $order->label
                );
            } catch (Exception $exception) {
                $this->addFlash('danger', 'Erreur lors de l\'enregistrement: '.$exception->getMessage());

                return $this->redirectToRoute('cap_commercant_show', ['id' => $commercant->getId()]);
            }

            try {
                $this->orderGenerator->generatePdf($order);
            } catch (Html2PdfException|LoaderError|RuntimeError|SyntaxError $e) {
                $this->addFlash('danger', 'Erreur pour la création du pdf '.$e->getMessage());
            }

            return $this->redirectToRoute('cap_order_show', ['id' => $order->getId()]);
        }

        return $this->render(
            '@CapCommercio/order/new.html.twig',
            [
                'commercant' => $commercant,
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/show/{id}', name: 'cap_order_show', methods: ['GET', 'POST'])]
    public function show(PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);
        $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
        try {
            $bill = $this->paymentBillRepository->findOneByOrder($paymentOrder);
        } catch (NonUniqueResultException) {
            $bill = null;
        }

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
                'orderCommercant' => $orderCommercant,
                'line' => $line,
                'addresses' => $addresses,
                'bill' => $bill,
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: 'cap_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentOrder $order): Response
    {
        $commercant = $this->commercantRepository->findByIdCommercant($order->getCommercantId());

        $form = $this->createForm(OrderEditType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->paymentOrderRepository->flush();

            return $this->redirectToRoute('cap_order_show', ['id' => $order->getId()]);
        }

        return $this->render(
            '@CapCommercio/order/edit.html.twig',
            [
                'commercant' => $commercant,
                'order' => $order,
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/paid/{id}', name: 'cap_order_paid', methods: ['POST'])]
    public function paid(Request $request, PaymentOrder $paymentOrder): Response
    {
        if ($this->isCsrfTokenValid('paid'.$paymentOrder->getId(), $request->request->get('_token'))) {

            if ($bill = $this->paymentBillRepository->findOneByOrder($paymentOrder)) {
                $this->addFlash('danger', 'Cette commande a déjà été payée');

                return $this->redirectToRoute('cap_bill_show', ['id' => $bill->getId()]);
            }

            try {
                $bill = $this->paymentOrderHandler->paid($paymentOrder);
                $this->addFlash('success', 'Facture générée');

                return $this->redirectToRoute('cap_bill_show', ['id' => $bill->getId()]);
            } catch (Exception $exception) {
                $this->addFlash('danger', 'Une erreur est survenue '.$exception->getMessage());
            }
        }

        return $this->redirectToRoute('cap_order_show', ['id' => $paymentOrder->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'cap_order_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentOrder $paymentOrder): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentOrder->getId(), $request->request->get('_token'))) {
            $orderCommercant = $paymentOrder->getOrderCommercant();
            $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);
            $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
            $bill = $this->paymentBillRepository->findOneByOrder($paymentOrder);

            $this->paymentOrderRepository->remove($orderCommercant);
            $this->paymentOrderRepository->remove($line);

            foreach ($addresses as $address) {
                $this->paymentOrderRepository->remove($address);
            }
            if ($bill) {
                $this->paymentOrderRepository->remove($bill);
            }
            $this->paymentOrderRepository->remove($paymentOrder);
            $this->paymentOrderRepository->flush();

            $this->addFlash('success', 'Commande supprimée');
        }

        return $this->redirectToRoute('cap_order_all');
    }
}
