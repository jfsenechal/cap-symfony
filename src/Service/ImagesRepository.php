<?php

namespace Cap\Commercio\Service;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercantGalleryRepository;

class ImagesRepository
{
    public function __construct(private CommercantGalleryRepository $commercantGalleryRepository)
    {
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
        if (count($images) > 0) {
            if (!$commercant->getProfileMediaPath()) {
                $commercant->setProfileMediaPath($images[0]);
            }
        } else {
            $commercant->setProfileMediaPath(null);
        }

        return $images;
    }
}