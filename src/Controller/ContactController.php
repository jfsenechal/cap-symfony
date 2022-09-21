<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\ContactRepository;
use Cap\Commercio\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/contact')]
class ContactController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private SettingRepository $settingRepository,
        private AdministratorRepository $administratorRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_contact', methods: ['GET'])]
    public function contact(): Response
    {
        $contacts = $this->contactRepository->findAll();

        return $this->render(
            '@CapCommercio/default/contact.html.twig',
            [
                'contacts' => $contacts,
            ]
        );
    }

    #[Route(path: '/setting', name: 'cap_setting', methods: ['GET'])]
    public function setting(): Response
    {
        $settings = $this->settingRepository->findAll();

        return $this->render(
            '@CapCommercio/default/setting.html.twig',
            [
                'settings' => $settings,
            ]
        );
    }

    #[Route(path: '/administrator', name: 'cap_administrator', methods: ['GET'])]
    public function administrator(): Response
    {
        $administrators = $this->administratorRepository->findAll();

        return $this->render(
            '@CapCommercio/default/administrator.html.twig',
            [
                'administrators' => $administrators,
            ]
        );
    }
}