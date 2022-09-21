<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\ContactRepository;
use Cap\Commercio\Repository\SettingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/')]
#[IsGranted(data: 'ROLE_CAP')]
class DefaultController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private SettingRepository $settingRepository,
        private AdministratorRepository $administratorRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@CapCommercio/default/index.html.twig',
            [

            ]
        );
    }
}
