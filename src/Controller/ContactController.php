<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\ContactRepository;
use Cap\Commercio\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/')]
class ContactController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private SettingRepository $settingRepository,
        private AdministratorRepository $administratorRepository
    ) {
    }

    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@CapCommercio/default/index.html.twig',
            [

            ]
        );
    }

    #[Route(path: '/contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        $contacts = $this->contactRepository->findAll();

        return $this->render(
            '@CapCommercio/default/contact.html.twig',
            [
                'contacts' => $contacts
            ]
        );
    }

    #[Route(path: '/setting', name: 'setting', methods: ['GET'])]
    public function setting(): Response
    {
        $settings = $this->settingRepository->findAll();

        return $this->render(
            '@CapCommercio/default/setting.html.twig',
            [
                'settings' => $settings
            ]
        );
    }

    #[Route(path: '/administrator', name: 'administrator', methods: ['GET'])]
    public function administrator(): Response
    {
        $administrators = $this->administratorRepository->findAll();

        return $this->render(
            '@CapCommercio/default/administrator.html.twig',
            [
                'administrators' => $administrators
            ]
        );
    }
}