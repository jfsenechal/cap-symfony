<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\SettingRepository;
use Cap\Commercio\Wallet\WalletApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/')]
#[IsGranted('ROLE_CAP')]
class DefaultController extends AbstractController
{
    public function __construct(
        private readonly SettingRepository $settingRepository,
        private readonly WalletApi $walletApi,
    ) {
    }

    #[Route(path: '/', name: 'cap_home', methods: ['GET'])]
    public function index(): Response
    {
        $url = $this->walletApi->url.'/selfcare/en/sales/paynotifications';

        return $this->render(
            '@CapCommercio/default/index.html.twig',
            [
                'url' => $url,
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
