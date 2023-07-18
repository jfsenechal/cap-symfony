<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfDownloaderTrait;
use Cap\Commercio\Pdf\PdfGenerator;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

    public function __construct(private PdfGenerator $pdfGenerator, private ParameterBagInterface $parameterBag)
    {
    }

    #[Route('/order/{id}/{save}/{debug}', name: 'cap_order_pdf', methods: ['GET', 'POST'])]
    public function order(PaymentOrder $order, bool $save = false, bool $debug = true): Response
    {
        try {
            $html = $this->pdfGenerator->generateForOrder($order);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'commande-'.$order->getUuid().'.pdf';
        if ($save) {
            $fileName = $this->parameterBag->get('CAP_PATH').'pdf-docs/'.$fileName;
        }

        try {
            return $this->downloadPdfH2Pdf($html, $fileName, $save, $debug);
        } catch (Html2PdfException $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }
    }

    #[Route('/facture/{id}/{save}/{debug}', name: 'cap_facture_pdf', methods: ['GET', 'POST'])]
    public function facture(PaymentBill $bill, bool $save = false, bool $debug = true): Response
    {
        try {
            $html = $this->pdfGenerator->generateForBill($bill);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'facture-'.$bill->getUuid().'.pdf';
        if ($save) {
            $fileName = $this->parameterBag->get('CAP_PATH').'pdf-docs/'.$fileName;
        }

        try {
            return $this->downloadPdfH2Pdf($html, $fileName, $save, $debug);
        } catch (Html2PdfException $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }
    }
}