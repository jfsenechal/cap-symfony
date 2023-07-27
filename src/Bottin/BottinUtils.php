<?php

namespace Cap\Commercio\Bottin;

use Cap\Commercio\Entity\CommercioBottin;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercioBottinRepository;

class BottinUtils
{
    public function __construct(private readonly CommercioBottinRepository $commercioBottinRepository)
    {
    }

    public function urlCap(CommercioCommercant $commercant): ?string
    {
        if (($ficheBottin = $this->commercioBottinRepository->findByCommercerant($commercant)) instanceof CommercioBottin) {

            $bottin = $ficheBottin->getBottin();
            if (!$bottin) {
                return null;
            }
            $classement = $bottin['classements'][0]['slugname'];
            return
                'https://cap.marche.be/commerces-et-entreprises/'.$classement.'/'.$bottin['slugname'];
        }

        return null;

    }
}