<?php

namespace Cap\Commercio\Shop;

use Cap\Commercio\Bill\Generator\OrderGenerator;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantAddress;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\CommercantGalleryRepository;
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
        public readonly CommercantGalleryRepository $galleryRepository,
        private readonly OrderGenerator $orderGenerator
    ) {
    }

    /**
     * @throws \Exception
     */
    public function newMember(CommercioCommercant $commercant, bool $generateOrder): ?PaymentOrder
    {
        $this->commercantRepository->flush();

        if (!$commercioCommercantAddress = $this->commercioCommercantAddressRepository->findOneByCommercant(
            $commercant
        )) {
            $commercioCommercantAddress = new CommercioCommercantAddress();
            $commercioCommercantAddress->setCommercioCommercant($commercant);
            $commercioCommercantAddress->setUuid($commercioCommercantAddress->generateUuid());
            $commercioCommercantAddress->setInsertDate(new \DateTime());
            $this->commercantRepository->persist($commercioCommercantAddress);
        }

        $commercioCommercantAddress->setAddress($commercant->address);
        $commercioCommercantAddress->setModifyDate(new \DateTime());

        $this->commercantRepository->flush();
        $order = null;

        if ($generateOrder) {
            $order = $this->generateNewOrder($commercant);
        }

        return $order;
    }

    /**
     * @param CommercioCommercant $commercant
     * @return PaymentOrder
     * @throws \Exception
     */
    public function generateNewOrder(CommercioCommercant $commercant): PaymentOrder
    {
        return $this->orderGenerator->newOne($commercant, $this->memberPrice);
    }

    /**
     * @param PaymentOrder $order
     * @return void
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateOrderPdf(PaymentOrder $order): void
    {
        $this->orderGenerator->generatePdf($order);
    }

    public function isMemberCompleted(CommercioCommercant $commercant): bool
    {
        $galleries = $this->galleryRepository->findByCommercant($commercant);
        if (count($galleries) > 0 && $commercant->getCommercialWordMediaPath() &&
            $commercant->getCommercialWordTitle() && $commercant->getCommercialWordDescription(
            ) && $commercant->getAffiliationDate()) {
            return true;
        }

        return false;
    }

    public function affiliated(int $commercantId): void
    {
        if ($commercant = $this->commercantRepository->findByIdCommercant($commercantId)) {
            $commercant->setAffiliationDate(new \DateTime());
            $commercant->setIsMember(true);
            $commercant->setModifyDate(new \DateTime());
            $this->commercantRepository->flush();
        }
    }

    public function disaffiliated(int $commercantId): void
    {
        if ($commercant = $this->commercantRepository->findByIdCommercant($commercantId)) {
            $commercant->setAffiliationDate(null);
            $commercant->setIsMember(false);
            $commercant->setModifyDate(new \DateTime());
            $this->commercantRepository->flush();
        }
    }
}