<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\SettingRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/')]
#[IsGranted('ROLE_CAP')]
class DefaultController extends AbstractController
{
    public function __construct(
        private SettingRepository $settingRepository,
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
}
