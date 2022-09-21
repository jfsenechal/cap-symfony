<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CommercioCommercantType;
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
    public function __construct(private CommercioCommercantRepository $commercantRepository)
    {
    }

    #[Route('/', name: 'cap_commercant_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commercants = $this->commercantRepository->findAll();

        return $this->render('@CapCommercio/commercant/index.html.twig', [
            'commercants' => $commercants,
        ]);
    }

    #[Route('/new', name: 'cap_commercant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commercioCommercant = new CommercioCommercant();
        $form = $this->createForm(CommercioCommercantType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commercioCommercant);
            $entityManager->flush();

            return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commercant/new.html.twig', [
            'commercio_commercant' => $commercioCommercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_commercant_show', methods: ['GET'])]
    public function show(CommercioCommercant $commercant): Response
    {
        return $this->render('@CapCommercio/commercant/show.html.twig', [
            'commercant' => $commercant,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_commercant_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        CommercioCommercant $commercioCommercant,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CommercioCommercantType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commercant/edit.html.twig', [
            'commercio_commercant' => $commercioCommercant,
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
