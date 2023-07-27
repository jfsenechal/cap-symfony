<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfDownloaderTrait;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/pdf')]
#[IsGranted('ROLE_CAP')]
class PdfController extends AbstractController
{
    use PdfDownloaderTrait;

    public function __construct(
        private readonly PdfGenerator $pdfGenerator,
        private readonly PaymentOrderRepository $orderRepository,
        private readonly PaymentBillRepository $billRepository
    ) {
    }

    #[Route('/order/{id}/{save}/{debug}', name: 'cap_order_pdf', methods: ['GET', 'POST'])]
    public function order(PaymentOrder $order, bool $debug = false): Response
    {
        try {
            $html = $this->pdfGenerator->generateContentForOrder($order);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'commande-'.$order->getUuid().'.pdf';

        return $this->downloadPdf($html, $fileName);
    }

    #[Route('/facture/{id}/{save}/{debug}', name: 'cap_facture_pdf', methods: ['GET', 'POST'])]
    public function facture(PaymentBill $bill, bool $debug = false): Response
    {
        try {
            $html = $this->pdfGenerator->generateContentForBill($bill);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'bill-'.$bill->getUuid().'.pdf';

        return $this->downloadPdf($html, $fileName);
    }

}