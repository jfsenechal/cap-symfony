<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Service\MandrillMail;
use Cap\Commercio\Service\MandrillMailDataItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

//http://localhost/commerciosf/vendor/mandrill/mandrill/docs/
#[Route(path: '/mandrill')]
#[IsGranted(data: 'ROLE_CAP')]
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
        private MailerInterface $mailer
    ) {
    }

    #[Route(path: '/', name: 'cap_mandrill', methods: ['GET'])]
    public function index(): Response
    {
        $mail = new Email();
        $mail->to('jf@marche.be');
        $mail->from('jf@marche.be');
        $mail->subject('Coucou');

        $api = $this->getParameter('MANDRILL_API');

        $mailchimp = new \Mandrill($api);
        $template = new \Mandrill_Templates($mailchimp);
        $message = new \Mandrill_Messages($mailchimp);
        // $template->render();
        //  $message->send();

        $this->testMandrill();

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

    private function testMandrill(): bool
    {
        define('PREFIX', 'https://cap.marche.be'); //url site
        define('PREFIX_RESOURCES', ''); // vide
        define('TEMPLATES_PATH', '/templates/');
        define('TEMPLATES_FOLDER_NAME', 'commercio');
        define('MANDRILL_SUBACCOUNT','commercio');
        $email = 'jf@marche.be';
        $api = $this->getParameter('MANDRILL_API');
        $mandrillMail = new MandrillMail($api);

        $code = 1235;
        $recovery_path = "admin/renew/".$code;

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';

        $mandrillMail->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $mandrillMail->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $mandrillMail->addMailDataItem(new MandrillMailDataItem("RENEW_LINK", $recovery_path));

        $mandrillMail->addReceiver($email, "", $email);
        $mandrillMail->template = "commercio_recovery";
        $mandrillMail->subject = "Récupération de mot de passe."; //translator ?
        $mandrillMail->senderName = 'jf@marche.be';
        $mandrillMail->senderEmail = 'jf@marche.be';
        $mandrillMail->website = PREFIX.PREFIX_RESOURCES;
        try {
            $mandrillMail->sendMe();
        } catch (\Exception $exception) {
            dump($exception);
            throw $exception;
        }

        return false;
    }


    private function infos()
    {

        $api = $this->getParameter('MANDRILL_API');
        $mailchimp = new \Mandrill($api);
        $template = new \Mandrill_Templates($mailchimp);
        $message = new \Mandrill_Messages($mailchimp);
        $account = new \Mandrill_Subaccounts($mailchimp);
        $user = new \Mandrill_Users($mailchimp);
        $senders = new \Mandrill_Senders($mailchimp);
        //$hooks = new \Mandrill_Webhooks($mailchimp);
        //$urls = new \Mandrill_Urls(            $mailchimp        );//Due to changes to our infrastructure, we no longer support URL tracking reports in Mandrill

        // dump($hooks->getList());
        //  dump($user->info());
        //   dump($senders->getList());
        //    dump($senders->domains());
        //    dump($account->getList());
        //    dump($mailchimp->readConfigs());
        dump($mailchimp);
        dump($account->info('ql'));
        //   dump($message->render());
        dump($message->search());
    }

}
