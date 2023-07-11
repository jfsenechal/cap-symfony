<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Mailer\MailerCap;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\CacheInterface;

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
        private CommercioCommercantRepository $commercantRepository,
        private CacheInterface $cache
    ) {

    }

    #[Route(path: '/', name: 'cap_mandrill_index', methods: ['GET'])]
    public function index(): Response
    {
        //$this->mailer->infos();

        return $this->render(
            '@CapCommercio/mandrill/index.html.twig',
            [
                'messages' => [],
                'senders' => [],
                'domains' => [],
                'accounts' => [],
            ]
        );
    }

    #[Route(path: '/test', name: 'cap_mandrill_test', methods: ['GET'])]
    public function test(): Response
    {
        //dd($this->mailer->infos());
        $adl = $this->commercantRepository->find(1384);

        try {
            $this->mailer->sendAffiliationExpired($adl, $this->getParameter('kernel.environment'));
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('cap_mandrill_index');

    }

    #[Route(path: '/messages/list', name: 'cap_commercio_mandrill_listmessages', methods: ['GET'])]
    public function listMessages(): Response
    {
        $tmp = $this->mailer->getMessages(10000);
        $messages = [];
        foreach ($tmp as $message) {
            $message['createdAt'] = $message['@timestamp'];
            $messages[] = $message;
        }

        return $this->render(
            '@CapCommercio/mandrill/email_list.html.twig',
            [
                'messages' => $messages,
            ]
        );
    }

    #[Route(path: '/messages/show/{id}', name: 'cap_commercio_mandrill_message_show', methods: ['GET'])]
    public function messageShow(string $id): Response
    {
        $message = $this->mailer->getMessage($id);
        $content = $this->mailer->getMessageContent($id);

        return $this->render(
            '@CapCommercio/mandrill/email_show.html.twig',
            [
                'message' => $message,
                'content' => $content,
            ]
        );
    }

    #[Route(path: '/template/list', name: 'cap_commercio_mandrill_listtemplate', methods: ['GET'])]
    public function listTemplates(): Response
    {
        $templates = $this->cache->get('mandrill_templates', fn() => $this->mailer->getTemplates());

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
