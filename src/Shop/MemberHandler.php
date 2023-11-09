<?php

namespace Cap\Commercio\Shop;

use Cap\Commercio\Bill\Generator\OrderGenerator;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantAddress;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MemberHandler
{
    public function __construct(
        #[Autowire(env: 'MEMBER_PRICE')]
        public readonly float $memberPrice,
        public readonly CommercioCommercantRepository $commercantRepository,
        public readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
        private readonly OrderGenerator $orderGenerator
    ) {
    }

    public function newMember(CommercioCommercant $commercioCommercant, bool $generateOrder): ?PaymentOrder
    {
        $this->commercantRepository->flush();

        if (!$commercioCommercantAddress = $this->commercioCommercantAddressRepository->findOneByCommercant(
            $commercioCommercant
        )) {
            $commercioCommercantAddress = new CommercioCommercantAddress();
            $commercioCommercantAddress->setCommercioCommercant($commercioCommercant);
            $commercioCommercantAddress->setUuid($commercioCommercantAddress->generateUuid());
            $commercioCommercantAddress->setInsertDate(new \DateTime());
            $this->commercantRepository->persist($commercioCommercantAddress);
        }

        $commercioCommercantAddress->setAddress($commercioCommercant->address);
        $commercioCommercantAddress->setModifyDate(new \DateTime());
        $this->commercantRepository->persist($commercioCommercantAddress);

        $this->commercantRepository->flush();
        $order = null;

        if ($generateOrder) {
            $order = $this->orderGenerator->newOne($commercioCommercant, $this->memberPrice);
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