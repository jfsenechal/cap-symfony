<?php

namespace Cap\Commercio\Bill\Handler;

use Cap\Commercio\Bill\Generator\BillGenerator;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaymentOrderHandler
{
    public function __construct(
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly BillGenerator $billGenerator,
        private readonly PdfGenerator $pdfGenerator,
    ) {
    }

    /**
     * @param PaymentOrder $paymentOrder
     * @return PaymentBill
     * @throws \Exception
     */
    public function paid(PaymentOrder $paymentOrder): PaymentBill
    {
        try {
            $bill = $this->billGenerator->generateFromOrder($paymentOrder);
            try {
                $html = $this->pdfGenerator->generateContentForBill($bill);
                $fileName = 'bill-'.$bill->getUuid().'.pdf';
                $this->pdfGenerator->savePdfToDisk($html, $fileName);
                $bill->setPdfPath('pdf-docs/'.$fileName);
                $paymentOrder->setIsPaid(true);
                $this->paymentBillRepository->flush();

                return $bill;
            } catch (LoaderError|RuntimeError|SyntaxError|\Exception $e) {
                throw new \Exception($e->getMessage(), $e->getCode(), $e);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}