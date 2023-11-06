<?php

namespace Cap\Commercio\Shop;

use Cap\Commercio\Bill\Generator\OrderGenerator;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantAddress;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;

class MemberHandler
{
    public function __construct(
        public readonly CommercioCommercantRepository $commercantRepository,
        public readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
        private readonly OrderGenerator $orderGenerator
    ) {
    }

    public function newMember(CommercioCommercant $commercioCommercant, bool $generateOrder): ?PaymentOrder
    {
        $commercioCommercant->setIsMember(true);
        $commercioCommercant->setAffiliationDate(new \DateTime());
        $this->commercantRepository->persist($commercioCommercant);
        $this->commercantRepository->flush();

        if (!$commercioCommercantAddress = $this->commercioCommercantAddressRepository->findByCommercant(
            $commercioCommercant
        )) {
            $commercioCommercantAddress = new CommercioCommercantAddress();
            $commercioCommercantAddress->setCommercioCommercant($commercioCommercant);
        }
        $commercioCommercantAddress->setAddress($commercioCommercant->address);
        $commercioCommercantAddress->setUuid($commercioCommercantAddress->generateUuid());
        $commercioCommercantAddress->setInsertDate(new \DateTime());
        $commercioCommercantAddress->setModifyDate(new \DateTime());
        $this->commercantRepository->persist($commercioCommercantAddress);

        $this->commercantRepository->flush();
        $order = null;

        if ($generateOrder) {
            $order = $this->orderGenerator->newOne($commercioCommercant, $commercioCommercantAddress);
        }

        return $order;
    }

    /**
     * @param PaymentOrder $order
     * @return void
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generatePdf(PaymentOrder $order): void
    {
         $this->orderGenerator->generatePdf($order);
    }
}