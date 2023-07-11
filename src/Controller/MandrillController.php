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
        'commercio_commande',
        'commercio_commercant_contact',
        'commercio_commercant_contact_confirm',
        'commercio_contact_site',
        'commercio_contact_site_confirm',
        'commercio_double_optin',
        'commercio_facture',
        'commercio_news',
        'commercio_newsletter',
        'commercio_newsletter_inscription',
        'commercio_noncommercant_contact',
        'commercio_recovery',
        'commercio_reminder_days',
        'commercio_reminder_expired',
        'commercio_reminder_month',
        'commercio_revendique',
        'commercio_revendique_admin',
        'commercio_tender',
        'commercio_visits_expired_members',
        'commercio_visits_incomplete_members',
        'commercio_visits_members',
        'commercio_visits_non_members',
    ];

    public function __construct(
        private MailerCap $mailer,
        private CommercioCommercantRepository $commercantRepository
    ) {

    }

    #[Route(path: '/', name: 'cap_mandrill', methods: ['GET'])]
    public function index(): Response
    {

        //dd($this->mailer->infos());
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

    #[Route(path: '/template/list', name: 'cap_commercio_mandrill_listtemplate', methods: ['GET'])]
    public function listTemplates(): Response
    {
        $templates = $this->mailer->getTemplates();

        return $this->render(
            '@CapCommercio/mandrill/template_list.html.twig',
            [
                'templates' => $templates,
            ]
        );
    }

    #[Route(path: '/template/show/{name}', name: 'cap_commercio_mandrill_showtemplate', methods: ['GET'])]
    public function showTemplate(string $name): Response
    {
        $template = $this->mailer->templateShow($name);

        return $this->render(
            '@CapCommercio/mandrill/template_show.html.twig',
            [
                'template' => $template,
            ]
        );
    }


}
