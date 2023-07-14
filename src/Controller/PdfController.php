<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfDownloaderTrait;
use Cap\Commercio\Pdf\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pdf')]
#[IsGranted('ROLE_CAP')]
class PdfController extends AbstractController
{
    use PdfDownloaderTrait;

    public function __construct(private PdfGenerator $pdfGenerator)
    {
    }

    #[Route('/order/{id}', name: 'cap_order_pdf', methods: ['GET', 'POST'])]
    public function order(PaymentOrder $order): Response
    {
        $html = $this->pdfGenerator->generateForOrder($order);

        return $this->downloadPdf($html, 'commande-'.$order->getOrderNumber().'.pdf',false);
    }
}