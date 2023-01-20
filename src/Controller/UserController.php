<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\RightAccess;
use Cap\Commercio\Form\UserSearchType;
use Cap\Commercio\Form\UserType;
use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\RightAccessRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user')]
#[IsGranted(data: 'ROLE_CAP')]
class UserController extends AbstractController
{
    public function __construct(
        private RightAccessRepository $rightAccessRepository,
        private AdministratorRepository $administratorRepository,
        private CommercioCommercantRepository $commercantRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_user_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UserSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $users = $this->rightAccessRepository->search($data['name']);
        } else {
            $users = $this->rightAccessRepository->findAll();
        }

        return $this->render(
            '@CapCommercio/user/index.html.twig',
            [
                'users' => $users,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}', name: 'cap_user_show', methods: ['GET'])]
    public function show(RightAccess $rightAccess): Response
    {
        $commercants = $this->commercantRepository->findBy(['rightAccess' => $rightAccess]);

        return $this->render('@CapCommercio/user/show.html.twig', [
            'user' => $rightAccess,
            'commercants' => $commercants,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        RightAccess $user,
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($this->rightAccessRepository->checkExist($data->getEmail(), $user)) {
                $this->addFlash('success', 'L\'adresse email est déjà prise sur un autre compte');
            } else {
                $this->addFlash('success', 'ok to modif');
            }
            // $this->rightAccessRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_user_show',
                ['id' => $user->getId()],
                Response::HTTP_SEE_OTHER
            );

        }

        return $this->render('@CapCommercio/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route(path: '/administrator', name: 'cap_administrator', methods: ['GET'])]
    public function administrator(): Response
    {
        $administrators = $this->administratorRepository->findAll();

        return $this->render(
            '@CapCommercio/user/administrator.html.twig',
            [
                'administrators' => $administrators,
            ]
        );
    }
}
