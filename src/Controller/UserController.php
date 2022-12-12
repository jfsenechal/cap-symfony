<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Form\UserSearchType;
use Cap\Commercio\Repository\AdministratorRepository;
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
        private AdministratorRepository $administratorRepository
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
