<?php

namespace Cap\Commercio\Shop;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\CommercioCommercantHolidayRepository;
use Cap\Commercio\Repository\CommercioCommercantHoursRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\EventRepository;
use Cap\Commercio\Repository\FacebookConnectRepository;
use Cap\Commercio\Repository\FacebookPromoMessageRepository;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderAddressRepository;
use Cap\Commercio\Repository\PaymentOrderLineRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Repository\PixelRepository;
use Cap\Commercio\Repository\RightAccessRepository;
use Cap\Commercio\Repository\TagRepository;

class ShopHandler
{
    public function __construct(
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly CommercantGalleryRepository $commercantGalleryRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly CommercioCommercantHoursRepository $commercioCommercantHoursRepository,
        private readonly CommercioCommercantHolidayRepository $commercioCommercantHolidayRepository,
        private readonly PaymentOrderLineRepository $paymentOrderLineRepository,
        private readonly RightAccessRepository $rightAccessRepository,
        private readonly PaymentOrderAddressRepository $paymentOrderAddressRepository,
        private readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
        private readonly FacebookConnectRepository $facebookConnectRepository,
        private readonly FacebookPromoMessageRepository $facebookPromoMessageRepository,
        private readonly PixelRepository $pixelRepository,
        private readonly TagRepository $tagRepository,
        private readonly EventRepository $eventRepository
    ) {

    }

    public function removeCommercant(CommercioCommercant $commercant): void
    {
        if ($commercantBottin = $this->commercioBottinRepository->findByFicheId($commercant->getId())) {
            $this->commercioBottinRepository->remove($commercantBottin);
        }

        $gallery = $this->commercantGalleryRepository->findByCommercant($commercant);
        $orders = $this->paymentOrderRepository->findByCommercantId($commercant->getId());
        $bills = $this->paymentBillRepository->findByCommercant($commercant);
        $hours = $this->commercioCommercantHoursRepository->findByCommercant($commercant);
        $holidays = $this->commercioCommercantHolidayRepository->findByCommercant($commercant);
        $access = $this->rightAccessRepository->findByCommercant($commercant);
        $address = $this->commercioCommercantAddressRepository->findByCommercant($commercant);
        $events = $this->eventRepository->findByCommercant($commercant);
        $facebookConnects = $this->facebookConnectRepository->findByCommercant($commercant);
        $facebookMessages = $this->facebookPromoMessageRepository->findByCommercant($commercant);
        $pixels = $this->pixelRepository->findByCommercant($commercant);
        $tags = $this->tagRepository->findByCommercant($commercant);

        $this->commercioCommercantAddressRepository->remove($address);
        foreach ($gallery as $photo) {
            $this->commercantGalleryRepository->remove($photo);
        }
        foreach ($orders as $order) {
            $line = $this->paymentOrderLineRepository->findOneByOrder($order);
            $this->paymentOrderRepository->remove($line);
            foreach ($this->paymentOrderAddressRepository->findByOrder($order) as $orderAddress) {
                $this->paymentOrderRepository->remove($orderAddress);
            }
            $this->paymentOrderRepository->remove($order);
        }
        foreach ($bills as $bill) {
            $this->paymentBillRepository->remove($bill);

        }
        foreach ($hours as $hour) {
            $this->commercioCommercantHoursRepository->remove($hour);
        }
        foreach ($holidays as $holiday) {
            $this->commercioCommercantHolidayRepository->remove($holiday);
        }
        if ($access) {
            $this->rightAccessRepository->remove($access);
        }


        foreach ($events as $event) {
            $this->eventRepository->remove($event);
        }
        foreach ($facebookConnects as $facebookConnect) {
            $this->facebookConnectRepository->remove($facebookConnect);
        }
        foreach ($facebookMessages as $facebookMessage) {
            $this->facebookPromoMessageRepository->remove($facebookMessage);
        }
        foreach ($pixels as $pixel) {
            $this->pixelRepository->remove($pixel);
        }
        foreach ($tags as $tag) {
            $this->tagRepository->remove($tag);
        }
        $this->commercantRepository->remove($commercant);
        $this->commercantRepository->flush();
    }
}