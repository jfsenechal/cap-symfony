<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CommercantSearchType;
use Cap\Commercio\Form\CommercantType;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commercant')]
#[IsGranted(data: 'ROLE_CAP')]
class CommercantController extends AbstractController
{
    public function __construct(
        private CommercioCommercantRepository $commercantRepository,
        private CommercantGalleryRepository $commercantGalleryRepository
    ) {
    }

    #[Route('/', name: 'cap_commercant_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CommercantSearchType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commercants = $this->commercantRepository->search($data['name'], $data['isMember']);
        } else {
            $commercants = $this->commercantRepository->findAllOrdered();
        }

        return $this->render('@CapCommercio/commercant/index.html.twig', [
            'commercants' => $commercants,
            'form' => $form,
        ]);
    }

    #[Route('/membres', name: 'cap_commercant_membres', methods: ['GET', 'POST'])]
    public function membres(): Response
    {
        $commercants = $this->commercantRepository->membres();

        return $this->render('@CapCommercio/commercant/members.html.twig', [
            'commercants' => $commercants,
        ]);
    }

    #[Route('/new', name: 'cap_commercant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commercioCommercant = new CommercioCommercant();
        $form = $this->createForm(CommercantType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commercioCommercant);
            $entityManager->flush();

            return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/commercant/new.html.twig', [
            'commercant' => $commercioCommercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_commercant_show', methods: ['GET'])]
    public function show(CommercioCommercant $commercant): Response
    {
        $gallery = $this->commercantGalleryRepository->findByCommercant($commercant);

        return $this->render('@CapCommercio/commercant/show.html.twig', [
            'commercant' => $commercant,
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_commercant_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        CommercioCommercant $commercioCommercant,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CommercantType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/commercant/edit.html.twig', [
            'commercant' => $commercioCommercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_commercant_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        CommercioCommercant $commercioCommercant,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$commercioCommercant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commercioCommercant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
    }
}
