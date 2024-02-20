<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\AddressRepository;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/address')]
#[IsGranted('ROLE_CAP')]
class AddressController extends AbstractController
{
    public function __construct(
        public readonly AddressRepository $addressRepository,
        public readonly CommercioCommercantRepository $commercioCommercantRepository,
        public readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
    ) {
    }

    #[Route(path: '/', name: 'cap_address_index', methods: ['GET'])]
    public function index(): Response
    {
        $commercants = $this->commercioCommercantRepository->findAllOrdered();
        $noRelation = $notFound = [];

        foreach ($commercants as $commercant) {
            $commercantAddress = $this->commercioCommercantAddressRepository->findOneByCommercant($commercant);
            if ($commercantAddress) {
                if (!$commercantAddress->getAddress()) {
                    $notFound[] = $commercant;
                }
            } else {
                $notFound[] = $commercant;
            }
        }

        foreach ($this->addressRepository->findAllOrdered() as $address) {
            $commercantAddress = $this->commercioCommercantAddressRepository->findOneByAddress($address);
            if (!$commercantAddress) {
                $noRelation[] = $address;
            }
        }

        return $this->render(
            '@CapCommercio/address/index.html.twig',
            [
                'noRelation' => $noRelation,
                'notFound' => $notFound,
            ]
        );
    }

}
