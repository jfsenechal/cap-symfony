<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Service\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/apijf')]
class ApiController extends AbstractController
{
    public function __construct(
        private CommercioCommercantRepository $commercioCommercantRepository,
        private CommercioBottinRepository $commercioBottinRepository,
        private ImagesRepository $imagesRepository
    ) {
    }

    /**
     * Get id bottin
     * Check if a cap fiche exist
     * Get images cap
     * @param int $id id du bottin
     * @return JsonResponse
     */
    #[Route('/shop/{id}', name: 'cap_api_shop', methods: ['GET'])]
    public function shop(int $id): JsonResponse
    {
        if ($commercioBottin = $this->commercioBottinRepository->findOneBy(['commercantId' => $id])) {
            if (!$commercant = $this->commercioCommercantRepository->find($commercioBottin->getCommercantId())) {
                return $this->json(null);
            }
            $this->imagesRepository->set($commercant);

            return $this->json($commercant);
        }

        return $this->json(null);
    }

    #[Route('/bottin/{id}', name: 'cap_api_commercant', methods: ['GET'])]
    public function bottin(int $id): JsonResponse
    {
        return $this->json($this->commercioBottinRepository->findOneBy(['commercantId' => $id]));
    }

    #[Route('/images/{id}', name: 'cap_api_images', methods: ['GET'])]
    public function images(int $id): JsonResponse
    {
        $commercant = $this->commercioCommercantRepository->find($id);
        if (!$commercant) {
            return $this->json([]);
        }
        $images = $this->imagesRepository->set($commercant);

        return $this->json($images);
    }

}
