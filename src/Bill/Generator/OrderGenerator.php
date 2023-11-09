<?php

namespace Cap\Commercio\Bill\Generator;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Entity\PaymentOrderAddress;
use Cap\Commercio\Entity\PaymentOrderCommercant;
use Cap\Commercio\Entity\PaymentOrderLines;
use Cap\Commercio\Helper\PriceCalculator;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Repository\PaymentOrderStatutRepository;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class OrderGenerator
{
    public function __construct(
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentOrderStatutRepository $paymentOrderStatutRepository,
        public readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
        private readonly PdfGenerator $pdfGenerator
    ) {
    }

    public function newOne(
        CommercioCommercant $commercant,
        float $priceEvat,
        float $vat = 21,
        string $title = 'Affiliation annuelle à Cap sur Marche'
    ): PaymentOrder {
        $paymentOrderCommercant = new PaymentOrderCommercant();
        $paymentOrderCommercant->setCompanyName($commercant->getLegalEntity());
        $paymentOrderCommercant->setCompanyVat($commercant->getVatNumber());
        $paymentOrderCommercant->setEmail($commercant->getLegalEmail());
        $paymentOrderCommercant->setFirstname($commercant->getLegalFirstname());
        $paymentOrderCommercant->setLastname($commercant->getLegalLastname());
        $paymentOrderCommercant->setUuid($paymentOrderCommercant->generateUuid());
        $paymentOrderCommercant->setInsertDate(new  \DateTime());
        $paymentOrderCommercant->setModifyDate(new \DateTime());
        $this->paymentOrderRepository->persist($paymentOrderCommercant);

        $orderStatut = $this->paymentOrderStatutRepository->find(3);//traite

        $order = new PaymentOrder();
        $order->setOrderStatus($orderStatut);
        $order->setCommercantId($commercant->getId());
        $order->setOrderCommercant($paymentOrderCommercant);
        $order->setOrderNumber($this->generateOrderNumber());
        $order->setPriceEvat($priceEvat);
        $order->setVat($vat);
        $vatAmount = PriceCalculator::calculateTvaAmount($priceEvat, $vat);
        $order->setVatAmount($vatAmount);
        $order->setPriceVat(PriceCalculator::calculatePriceVat($priceEvat, $vatAmount));
        $order->setIsPaid(false);
        $order->setUuid($order->generateUuid());
        $order->setInsertDate(new  \DateTime());
        $order->setModifyDate(new \DateTime());
        $this->paymentOrderRepository->persist($order);

        $line = new PaymentOrderLines();
        $line->setOrder($order);
        $line->setLabel($title);
        $line->setPriceEvat($priceEvat);
        $line->setTotalPriceEvat($priceEvat);
        $line->setQuantity(1);
        $line->setQuantityLabel(' ');
        $line->setUuid($line->generateUuid());
        $line->setInsertDate(new  \DateTime());
        $line->setModifyDate(new \DateTime());
        $this->paymentOrderRepository->persist($line);

        $address = $commercant->address;
        if (!$address) {
            $commercantAddress = $this->commercioCommercantAddressRepository->findOneByCommercant($commercant);
            if ($commercantAddress) {
                $address = $commercantAddress->getAddress();
            }
        }
        if ($address) {
            $orderAddress = new PaymentOrderAddress();
            $orderAddress->setOrder($order);
            $orderAddress->setAddressTypeId(1);
            $orderAddress->setStreet1($address->getStreet1());
            $orderAddress->setCity($address->getCity());
            $orderAddress->setZipcode($address->getZipcode());
            $orderAddress->setCountryId(18);
            $orderAddress->setUuid($orderAddress->generateUuid());
            $orderAddress->setInsertDate(new  \DateTime());
            $orderAddress->setModifyDate(new \DateTime());
            $this->paymentOrderRepository->persist($orderAddress);
        }

        $this->paymentOrderRepository->flush();

        return $order;
    }

    public function generateOrderNumber(): string
    {
        $str = "00000";
        $startDate = \DateTime::createFromFormat('Y-m-d', date('Y').'-01-01');
        $endDate = \DateTime::createFromFormat('Y-m-d', date('Y').'-12-31');

        $numbers = $this->paymentOrderRepository->findBetweenDates($startDate, $endDate);

        $count = count($numbers);
        $count++;

        $count_str = strval($count);

        $length = strlen($str);
        $result = substr_replace($str, $count_str, $length - strlen($count_str), $length);

        return 'ORD'.date('Ym').$result;
    }

    /**
     * @param PaymentOrder $order
     * @return void
     * @throws Html2PdfException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generatePdf(PaymentOrder $order): void
    {
        $html = $this->pdfGenerator->generateContentForOrder($order);
        $fileName = 'order-'.$order->getUuid().'.pdf';
        $this->pdfGenerator->savePdfToDisk($html, $fileName);
        $order->setPdfPath('pdf-docs/'.$fileName);
        $this->paymentOrderRepository->flush();
    }
}