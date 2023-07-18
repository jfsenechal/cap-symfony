<?php

namespace Cap\Commercio\Pdf;

use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

trait PdfDownloaderTrait
{
    public Pdf $pdf;

    #[Required]
    public function setPdf(Pdf $pdf): void
    {
        $this->pdf = $pdf;
    }

    public function getPdf(): Pdf
    {
        return $this->pdf;
    }

    public function downloadPdf(string $html, string $fileName, bool $debug = false): Response
    {
        if ($debug) {
            return new Response($html);
        }

        /*   $this->pdf->setOption('footer-html','    CAP sur Marche ASBL - Avenue de France, 126 - 6900 Marche-en-Famenne (Belgique)
       <br/>
       N° de téléphone : 0478/34 69 42 - TVA : BE 0525 968 345 - cap@marche.be - IBAN : BE51 0689 3813 9062
       <br/>
       Les conditions dutilisation sont dapplication pour laffiliation. Vous retrouvez ces conditions sur notre
       site.');*/

        return new PdfResponse(
            $this->pdf->getOutputFromHtml($html),
            $fileName
        );
    }

    public function downloadPdfDom(string $html, string $fileName, bool $debug = false)
    {
        if ($debug) {
            return new Response($html);
        }

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setIsRemoteEnabled(true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->setOptions($options);
        $dompdf->render();
        $dompdf->stream($fileName);
    }

    public function downloadPdfH2Pdf(string $html, string $fileName, bool $debug = false)
    {
        if ($debug) {
            return new Response($html);
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);

        return $html2pdf->output($fileName, 'D');

    }
}
