<?php

namespace Cap\Commercio\Service;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioCommercantHoursRepository;
use DateTime;

class ImagesRepository
{
    public function __construct(
        private readonly CommercantGalleryRepository $commercantGalleryRepository,
        private readonly CommercioCommercantHoursRepository $commercioCommercantHoursRepository,
    ) {
    }

    public function set(CommercioCommercant $commercant): array
    {
        $galleries = $this->commercantGalleryRepository->findByCommercant($commercant);
        $images = [];
        foreach ($galleries as $gallery) {
            $img = [
                'id' => $gallery->getId(),
                'name' => $gallery->getName(),
                'path' => $gallery->getMediaPath(),
                'alt' => $gallery->getAlt(),
            ];
            $images[] = $img;
        }
        $commercant->images = $images;
        if ($images !== []) {
            if (!$commercant->getProfileMediaPath()) {
                $commercant->setProfileMediaPath($images[0]['path']);
            }
        } else {
            $commercant->setProfileMediaPath(null);
        }

        foreach ($this->commercioCommercantHoursRepository->findByCommercant($commercant) as $hour) {
            $hour->setCommercioCommercant(null);//bug serialize
            if ($hour->getMorningStart() instanceof DateTime) {
                $hour->morning_start_short = $hour->getMorningStart()->format('H:i');
            }
            if ($hour->getMorningEnd() instanceof DateTime) {
                $hour->morning_end_short = $hour->getMorningEnd()->format('H:i');
            }
            if ($hour->getNoonStart() instanceof DateTime) {
                $hour->noon_start_short = $hour->getNoonStart()->format('H:i');
            }
            if ($hour->getNoonEnd() instanceof DateTime) {
                $hour->noon_end_short = $hour->getNoonEnd()->format('H:i');
            }
            $commercant->hours[] = $hour;
        }

        return $images;
    }
}
