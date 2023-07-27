<?php

namespace Cap\Commercio\Mailer;

use Cap\Commercio\Entity\CommercioCommercant;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Email;

class MailerCap
{
    private string $senderEmail;
    private string $api;
    private string $senderName;

    public function __construct(private ParameterBagInterface $parameterBag, private MandrillMail $mandrillMail)
    {
        define('PREFIX', 'https://cap.marche.be'); //url site
        define('PREFIX_RESOURCES', ''); // vide
        define('TEMPLATES_PATH', '/templates/');
        define('TEMPLATES_FOLDER_NAME', 'commercio');
        define('MANDRILL_SUBACCOUNT', 'commercio');

        $this->api = $this->parameterBag->get('MANDRILL_API');
        $this->senderEmail = 'info@capsurmarche.com';
        $this->senderName = 'Cap sur Marche';
    }

    /**
     * @param CommercioCommercant $commercant
     * @param string $env
     * @return void
     * @throws Exception
     */
    public function sendAffiliationExpired(CommercioCommercant $commercant, string $env): void
    {
        $dateTime = $commercant->getAffiliationDate();

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';
        if ($env == "dev") {
            $this->mandrillMail->addReceiver('jf@marche.be', 'jfs', 'senechal');
        } else {
            $this->mandrillMail->addReceiver(
                $commercant->getLegalEmail(),
                $commercant->getLegalFirstname(),
                $commercant->getLegalLastname()
            );
            if ($commercant->getLegalEmail2()) {
                $this->mandrillMail->addReceiver(
                    $commercant->getLegalEmail2(),
                    $commercant->getLegalFirstname(),
                    $commercant->getLegalLastname()
                );
            }
        }
        if ($dateTime) {
            $date = $dateTime->format("d/m/Y");
        } else {
            $date = date('d/m/Y');
        }

        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("START_DATE", $date));
        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("ORDER_PDF", "/admin"));
        $this->mandrillMail->template = "commercio_reminder_expired";
        $this->mandrillMail->subject = $this->senderName." - Votre affiliation a expiré";
        $this->mandrillMail->senderName = $this->senderName;
        $this->mandrillMail->senderEmail = $this->senderEmail;
        $this->mandrillMail->website = PREFIX.PREFIX_RESOURCES;
        try {
            $this->mandrillMail->sendMe();
        } catch (Exception $exception) {
            throw new $exception;
        }
    }

    public function testMandrill(): bool
    {
        $email = 'jf@marche.be';

        $code = 1235;
        $recovery_path = "/admin/renew/".$code;

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';

        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $this->mandrillMail->addMailDataItem(new MandrillMailDataItem("RENEW_LINK", $recovery_path));

        $this->mandrillMail->addReceiver($email, "", $email);
        $this->mandrillMail->template = "commercio_recovery";
        $this->mandrillMail->subject = "Récupération de mot de passe."; //translator ?
        $this->mandrillMail->senderName = 'Cap sur Marche';
        $this->mandrillMail->senderEmail = $this->senderEmail;
        $this->mandrillMail->website = PREFIX.PREFIX_RESOURCES;
        try {
            $this->mandrillMail->sendMe();
        } catch (\Exception $exception) {
            dump($exception);
            throw $exception;
        }

        return false;
    }

    public function testNewApi()
    {
        $mail = new Email();
        $mail->to('jf@marche.be');
        $mail->from('jf@marche.be');
        $mail->subject('Coucou');

        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);
        $message = new \Mandrill_Messages($mailchimp);
        // $template->render();
        //  $message->send();

        $this->testMandrill();
    }

    public function getTemplates(): array
    {
        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);

        return $template->getList('commercio');
    }

    public function templateShow(string $name): array
    {
        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);

        return $template->info($name);
    }

    public function templateSet(string $name, string $content): array
    {
        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);

        return $template->update($name, code: $content);
    }

    public function templateRender(string $name, array $vars): string
    {
        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);
        $content = [];

        return $template->render($name, $content, $vars);
    }

    public function getMessages(int $limit): array
    {
        $mailchimp = new \Mandrill($this->api);
        $message = new \Mandrill_Messages($mailchimp);

        return $message->search(
            limit: $limit,
            senders: ['info@capsurmarche.com'],
            date_from: '2021-01-01',
            date_to: '2025-12-31'
        );
    }

    public function getMessage(string $id)
    {
        $mailchimp = new \Mandrill($this->api);
        $message = new \Mandrill_Messages($mailchimp);

        return $message->info($id);
    }

    public function getMessageContent(string $id)
    {
        $mailchimp = new \Mandrill($this->api);
        $message = new \Mandrill_Messages($mailchimp);

        return $message->content($id);
    }


    public function infos()
    {
        $mailchimp = new \Mandrill($this->api);
        $template = new \Mandrill_Templates($mailchimp);
        $account = new \Mandrill_Subaccounts($mailchimp);
        $user = new \Mandrill_Users($mailchimp);
        $senders = new \Mandrill_Senders($mailchimp);
        //$hooks = new \Mandrill_Webhooks($mailchimp);
        //$urls = new \Mandrill_Urls(            $mailchimp        );//Due to changes to our infrastructure, we no longer support URL tracking reports in Mandrill

        dump($senders->getList());
        //dump($hooks->getList());
        dump($user->info());
        dump($senders->domains());
        dump($account->getList());
        dump($mailchimp->readConfigs());
        dump($mailchimp);
        dump($account->info('commercio'));
        //   dump($message->render());

    }
}