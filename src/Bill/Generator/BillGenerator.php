<?php

namespace Cap\Commercio\Bill\Generator;

use Exception;
use DateTime;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Symfony\Component\Uid\Uuid;

class BillGenerator
{
    public function __construct(
        private readonly PaymentBillRepository $billRepository,
    ) {
    }


    /**
     * @throws Exception
     */
    public function generateFromOrder(PaymentOrder $order): PaymentBill
    {
        $bill = new PaymentBill();
        $bill->setModifyDate(new DateTime());
        $bill->setInsertDate(new DateTime());
        $bill->setUuid(Uuid::v4());
        $bill->setBillNumber($this->generateBillNumber());
        $bill->setOrder($order);
        $bill->setPriceEvat($order->getPriceEvat());
        $bill->setPriceVat($order->getPriceVat());
        $bill->setVat($order->getVat());
        $bill->setVatAmount($order->getVatAmount());
        $this->billRepository->flush();

        return $bill;
    }

    private function getBillNextSequenceNumber(): string
    {
        $startDate = date('Y-m-01 00:00:00');

        $date = new DateTime('now');
        $last_day_of_month = $date->format('t');

        $endDate = date('Y-m-'.$last_day_of_month.' 23:59:59');

        $result = $this->billRepository->countBetweenDates($startDate, $endDate);

        $count = count($result);
        $count++;
        $count_str = (string) $count;
        $str = "00000";
        $length = strlen($str);

        return substr_replace($str, $count_str, $length - strlen($count_str), $length);
    }

    private function generateBillNumber(): string
    {
        $prefix = "BIL";
        $period = date('Ym');
        $sequence = $this->getBillNextSequenceNumber();

        return $prefix.$period.$sequence;
    }

}