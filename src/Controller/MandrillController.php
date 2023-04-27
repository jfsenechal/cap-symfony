<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Mailer\MailerCap;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//http://localhost/commerciosf/vendor/mandrill/mandrill/docs/
#[Route(path: '/mandrill')]
#[IsGranted('ROLE_CAP')]
class MandrillController extends AbstractController
{
    const templates = [
        'commercio_access_demand',
        "commercio_recovery",
        "commercio_reminder_days",
        "commercio_reminder_month",
        "commercio_reminder_month",
    ];

    public function __construct(
        private MailerCap $mailer,
        private CommercioCommercantRepository $commercantRepository
    ) {

    }

    #[Route(path: '/', name: 'cap_mandrill', methods: ['GET'])]
    public function index(): Response
    {

        $adl = $this->commercantRepository->find(1384);

       // $this->mailer->sendAffiliationExpired($adl,$this->getParameter('kernel.environment'));

        return $this->render(
            '@CapCommercio/default/message.html.twig',
            [
                'messages' => [],
                'senders' => [],
                'domains' => [],
                'accounts' => [],
            ]
        );
    }



}
