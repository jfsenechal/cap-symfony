<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bill\Generator\BillGenerator;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Form\OrderSearchType;
use Cap\Commercio\Form\PaidOrderType;
use Cap\Commercio\Pdf\PdfGenerator;
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
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route(path: '/order')]
#[IsGranted('ROLE_CAP')]
class OrderController extends AbstractController
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
        private PaymentOrderLineRepository $paymentOrderLineRepository,
        private PaymentOrderAddressRepository $paymentOrderAddressRepository,
        private BillGenerator $billGenerator,
        private PdfGenerator $pdfGenerator,
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
            } catch (NonUniqueResultException $e) {
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

    #[Route(path: '/show/{id}', name: 'cap_order_show', methods: ['GET', 'POST'])]
    public function show(PaymentOrder $paymentOrder): Response
    {
        $orderCommercant = $paymentOrder->getOrderCommercant();
        $line = $this->paymentOrderLineRepository->findOneByOrder($paymentOrder);
        $addresses = $this->paymentOrderAddressRepository->findByOrder($paymentOrder);
        $bills = [];
        try {
            $bill = $this->paymentBillRepository->findOneByOrder($paymentOrder);
        } catch (NonUniqueResultException $e) {
            $bill = null;
            $bills = $this->paymentBillRepository->findByOrder($paymentOrder);
            if (count($bills) > 0) {
                $this->addFlash('danger', 'Attention plusieurs paiements pour cette facture');
            }
        }

        return $this->render(
            '@CapCommercio/order/show.html.twig',
            [
                'order' => $paymentOrder,
                'orderCommercant' => $orderCommercant,
                'line' => $line,
                'addresses' => $addresses,
                'bill' => $bill,
                'bills' => $bills,
            ]
        );
    }

    #[Route(path: '/paid/{id}', name: 'cap_order_paid', methods: ['POST'])]
    public function paid(Request $request, PaymentOrder $paymentOrder): Response
    {
        if ($this->isCsrfTokenValid('paid'.$paymentOrder->getId(), $request->request->get('_token'))) {

            if ($bill = $this->paymentBillRepository->findByOrder($paymentOrder)) {
                $this->addFlash('danger', 'Cette commande a déjà une facture liée');

                return $this->redirectToRoute('cap_bill_show', ['id' => $bill->getId()]);
            }

            try {
                $bill = $this->billGenerator->generateFromOrder($paymentOrder);
                $this->addFlash('success', 'Facture générée');

                try {
                    $html = $this->pdfGenerator->generateContentForBill($bill);
                    $fileName = 'bill-'.$bill->getUuid().'.pdf';
                    $this->pdfGenerator->savePdfToDisk($html, $fileName);
                    $bill->setPdfPath('pdf-docs/'.$fileName);
                    $this->paymentBillRepository->flush();

                } catch (LoaderError|RuntimeError|SyntaxError|\Exception $e) {
                    throw new \Exception($e->getMessage());
                }

                return $this->redirectToRoute('cap_bill_show', ['id' => $bill->getId()]);
            } catch (\Exception $exception) {
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
            $bills = $this->paymentBillRepository->findByOrder($paymentOrder);

            $this->paymentOrderRepository->remove($orderCommercant);
            $this->paymentOrderRepository->remove($line);

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