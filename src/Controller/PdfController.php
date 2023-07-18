<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfDownloaderTrait;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
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

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private PdfGenerator $pdfGenerator,
        private PaymentOrderRepository $orderRepository,
        private PaymentBillRepository $billRepository
    ) {
    }

    #[Route('/order/{id}/{save}/{debug}', name: 'cap_order_pdf', methods: ['GET', 'POST'])]
    public function order(PaymentOrder $order, bool $save = false, bool $debug = false): Response
    {
        try {
            $html = $this->pdfGenerator->generateForOrder($order);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'commande-'.$order->getUuid().'.pdf';

        return $this->saveResponse($order, $fileName, $html, $save, $debug);

    }

    #[Route('/facture/{id}/{save}/{debug}', name: 'cap_facture_pdf', methods: ['GET', 'POST'])]
    public function facture(PaymentBill $bill, bool $save = false, bool $debug = false): Response
    {
        try {
            $html = $this->pdfGenerator->generateForBill($bill);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }

        $fileName = 'bill-'.$bill->getUuid().'.pdf';

        return $this->saveResponse($bill, $fileName, $html, $save, $debug);
    }

    private function saveResponse(
        PaymentBill|PaymentOrder $object,
        string $fileName,
        string $html,
        bool $save,
        bool $debug
    ): Response {
        if ($save) {
            $path = $this->parameterBag->get('CAP_PATH').'pdf-docs/'.$fileName;
        }

        try {
            $response = $this->downloadPdfH2Pdf($html, $path, $save, $debug);
            if ($response instanceof Response) {
                return $response;
            }
            $object->setPdfPath('pdf-docs/'.$fileName);
            $this->billRepository->flush();

            return new Response($response);
        } catch (Html2PdfException $e) {
            $this->addFlash('danger', 'Erreur: '.$e->getMessage());

            return $this->redirectToRoute('cap_home');
        }
    }
}