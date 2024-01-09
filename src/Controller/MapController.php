<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\MapPinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/map')]
#[IsGranted('ROLE_CAP')]
class MapController extends AbstractController
{
    public function __construct(
        private readonly MapPinRepository $mapPinRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_pin', methods: ['GET'])]
    public function index(): Response
    {
        $pins = $this->mapPinRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/map/pin.html.twig',
            [
                'pins' => $pins,
            ]
        );
    }
}
