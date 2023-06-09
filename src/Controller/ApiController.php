<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/apijf')]
class ApiController extends AbstractController
{
    public function __construct(
        private CommercantGalleryRepository $commercantGalleryRepository,
        private CommercioCommercantRepository $commercioCommercantRepository,
        private CommercioBottinRepository $commercioBottinRepository,
    ) {
    }

    #[Route('/shop/{id}', name: 'cap_api_shop', methods: ['GET'])]
    public function show(CommercioCommercant $commercant): JsonResponse
    {
        return $this->json($commercant);
    }

    #[Route('/bottin/{id}', name: 'cap_api_commercant', methods: ['GET'])]
    public function bottin(int $id): JsonResponse
    {
        $commercant = $this->commercioBottinRepository->findBy(['commercantId'=>$id]);
        return $this->json($commercant);
    }

    #[Route('/images/{id}', name: 'cap_api_images', methods: ['GET'])]
    public function images(CommercioCommercant $commercant): JsonResponse
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

        return $this->json($images);
    }

}
