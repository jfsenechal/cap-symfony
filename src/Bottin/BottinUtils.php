<?php

namespace Cap\Commercio\Bottin;

use Cap\Commercio\Entity\AddressAddress;
use Cap\Commercio\Entity\CommercioBottin;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\AddressIsoCountriesRepository;
use Cap\Commercio\Repository\AddressTypeRepository;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\CtaRepository;

class BottinUtils
{
    public function __construct(
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly CommercioCommercantRepository $commercioCommercantRepository,
        public readonly AddressTypeRepository $addressTypeRepository,
        private readonly CtaRepository $ctaRepository,
        public readonly AddressIsoCountriesRepository $addressIsoCountriesRepository,
    ) {
    }

    public function urlCap(CommercioCommercant $commercant): ?string
    {
        if (($ficheBottin = $this->commercioBottinRepository->findByFicheId(
                $commercant->getId()
            )) instanceof CommercioBottin) {
            $bottin = $ficheBottin->getBottin();
            if (!$bottin) {
                return null;
            }
            $classement = $bottin['classements'][0]['slugname'];

            return 'https://cap.marche.be/commerces-et-entreprises/'.$classement.'/'.$bottin['slugname'];
        }

        return null;
    }

    public static function urlBottin(int $id): string
    {
        return 'https://bottin.marche.be/admin/fiche/'.$id;
    }


    public function newFromBottin(\stdClass $fiche): CommercioCommercant
    {
        if (!$commercioBottin = $this->commercioBottinRepository->findByFicheId($fiche->id)) {
            $commercioBottin = new CommercioBottin();
            $commercioBottin->setBottin($fiche);
            $commercioBottin->setCommercantId($fiche->id);
            $commercioBottin->setInsertDate(new \DateTime());
            $this->commercioBottinRepository->persist($commercioBottin);
        }
        $commercioBottin->setModifyDate(new \DateTime());

        if (!$commercioCommercant = $this->commercioCommercantRepository->findByIdCommercant($fiche->id)) {
            $commercioCommercant = new CommercioCommercant();
            $commercioCommercant->setUuid($commercioCommercant->generateUuid());
            $cta = $this->ctaRepository->find(2);
            $commercioCommercant->setCta($cta);
            $this->commercioCommercantRepository->persist($commercioCommercant);
        }

        $commercioCommercant->setLegalEntity($fiche->societe);
        $commercioCommercant->setLegalEmail($fiche->email);
        $commercioCommercant->setLegalEmail2($fiche->contact_email);
        $commercioCommercant->setLegalFirstname($fiche->prenom);
        $commercioCommercant->setLegalLastname($fiche->nom);
        $commercioCommercant->setLegalPhone($fiche->telephone);
        $commercioCommercant->setInsertDate(new \DateTime());
        $commercioCommercant->setModifyDate(new \DateTime());
        // $commercioCommercant->setVatNumber($fiche->numero_tva);

        $address = new AddressAddress();
        $address->setUuid($address->generateUuid());
        $address->setStreet1($fiche->rue);
        $address->setZipcode($fiche->cp);
        $address->setCity($fiche->localite);
        $address->setAddressType($this->addressTypeRepository->find(1));
        $address->setCountry($this->addressIsoCountriesRepository->find(1));
        $address->setInsertDate(new \DateTime());
        $address->setModifyDate(new \DateTime());
        $this->commercioCommercantRepository->persist($address);
        $commercioCommercant->address = $address;

        return $commercioCommercant;
    }
}
