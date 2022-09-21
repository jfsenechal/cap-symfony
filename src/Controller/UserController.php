<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\ContactRepository;
use Cap\Commercio\Repository\RightAccessRepository;
use Cap\Commercio\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user')]
class UserController extends AbstractController
{
    public function __construct(
        private RightAccessRepository $rightAccessRepository,
    ) {
    }

    #[Route(path: '/', name: 'cap_user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->rightAccessRepository->findAll();

        return $this->render(
            '@CapCommercio/user/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }
}
