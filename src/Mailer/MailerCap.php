<?php

namespace Cap\Commercio\Mailer;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Wallet\Handler\WallHandler;
use DateTimeInterface;
use Exception;
use Mandrill;
use Mandrill_Messages;
use Mandrill_Templates;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mime\Email;

/**
 * @deprecated
 */
class MailerCap
{
    private string $senderEmail = 'info@capsurmarche.com';
    private string $senderName = 'Cap sur Marche';
    public ?string $template = null;
    public array $data = [];
    //private $receiver;
    public ?string $subject = null;
    private array $receivers = [];
    public bool $website = false;
    public bool $track_opens = true;
    public bool $track_clicks = true;
    public bool $important = false;
    private bool $sendable = false;
    private ?string $subaccount = null;
    public array $errors = [];

    public function __construct(
        #[Autowire('%env(MANDRILL_API)%')] private readonly string $api,
        #[Autowire('%env(APP_ENV)%')] private readonly string $env,
        private readonly WallHandler $wallHandler
    ) {
        define('PREFIX', 'https://cap.marche.be'); //url site
        define('PREFIX_RESOURCES', ''); // vide
        define('TEMPLATES_PATH', '/templates/');
        define('TEMPLATES_FOLDER_NAME', 'commercio');
        define('MANDRILL_SUBACCOUNT', 'commercio');
        $this->subaccount = defined('MANDRILL_SUBACCOUNT') ? MANDRILL_SUBACCOUNT : null;
    }

    public function sendNewAffiliation(CommercioCommercant $commercant, PaymentOrder $paymentOrder)
    {
        $dateTime = $commercant->getAffiliationDate();

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';
        if ($this->env == "dev") {
            $this->addReceiver('jf@marche.be', 'jfs', 'senechal');
        } else {
            $this->addReceiver(
                $commercant->getLegalEmail(),
                $commercant->getLegalFirstname(),
                $commercant->getLegalLastname()
            );
            if ($commercant->getLegalEmail2()) {
                $this->addReceiver(
                    $commercant->getLegalEmail2(),
                    $commercant->getLegalFirstname(),
                    $commercant->getLegalLastname()
                );
            }
        }

        $this->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $this->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $this->addMailDataItem(new MandrillMailDataItem("SOCIETE", $commercant->getLegalEntity()));
        $this->addMailDataItem(new MandrillMailDataItem("C_EMAIL", $commercant->getLegalEmail()));
        $virementPath = 'https://cap.marche.be/'.$paymentOrder->getPdfPath();
        $paymentUrl = $this->wallHandler->generateUrlForPayment($paymentOrder);
        $this->addMailDataItem(new MandrillMailDataItem("VIREMENT_PDF", $virementPath));
        $this->addMailDataItem(new MandrillMailDataItem("VIREMENT_URL", $paymentUrl));
        $this->addMailDataItem(new MandrillMailDataItem("C_VAT", $commercant->getVatNumber()));

        $this->template = "commercio_revendique";
        $this->subject = $this->senderName." - Nouvelle affiliation Cap sur Marche";
        $this->website = PREFIX.PREFIX_RESOURCES;
        try {
            $this->sendMe();
        } catch (Exception $exception) {
            throw new $exception();
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function sendAffiliationExpired(CommercioCommercant $commercant, PaymentOrder $paymentOrder): void
    {
        $dateTime = $commercant->getAffiliationDate();

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';
        if ($this->env == "dev") {
            $this->addReceiver('jf@marche.be', 'jfs', 'senechal');
        } else {
            $this->addReceiver(
                $commercant->getLegalEmail(),
                $commercant->getLegalFirstname(),
                $commercant->getLegalLastname()
            );
            if ($commercant->getLegalEmail2()) {
                $this->addReceiver(
                    $commercant->getLegalEmail2(),
                    $commercant->getLegalFirstname(),
                    $commercant->getLegalLastname()
                );
            }
        }
        $date = $dateTime instanceof DateTimeInterface ? $dateTime->format("d/m/Y") : date('d/m/Y');

        $this->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $this->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $this->addMailDataItem(new MandrillMailDataItem("START_DATE", $date));
        $virementPath = 'https://cap.marche.be/'.$paymentOrder->getPdfPath();
        $paymentUrl = $this->wallHandler->generateUrlForPayment($paymentOrder);
        $this->addMailDataItem(new MandrillMailDataItem("VIREMENT_PDF", $virementPath));
        $this->addMailDataItem(new MandrillMailDataItem("VIREMENT_URL", $paymentUrl));
        $this->template = "commercio_reminder_expired";
        $this->subject = $this->senderName." - Votre affiliation a expiré";
        $this->website = PREFIX.PREFIX_RESOURCES;
        try {
            $this->sendMe();
        } catch (Exception $exception) {
            throw new $exception();
        }
    }

    public function testMandrill(): bool
    {
        $email = 'jf@marche.be';

        $code = 1235;
        $recovery_path = "/admin/renew/".$code;

        $templatePath = PREFIX.PREFIX_RESOURCES.TEMPLATES_PATH.TEMPLATES_FOLDER_NAME.'/';

        $this->addMailDataItem(new MandrillMailDataItem("PREFIX", PREFIX.PREFIX_RESOURCES));
        $this->addMailDataItem(new MandrillMailDataItem("TEMPLATEPATH", $templatePath));
        $this->addMailDataItem(new MandrillMailDataItem("RENEW_LINK", $recovery_path));

        $this->addReceiver($email, "", $email);
        $this->template = "commercio_recovery";
        $this->subject = "Récupération de mot de passe."; //translator ?
        $this->senderName = 'Cap sur Marche';
        $this->website = PREFIX.PREFIX_RESOURCES;
        try {
            $this->sendMe();
        } catch (Exception $exception) {
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

        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);
        $message = new Mandrill_Messages($mailchimp);
        // $template->render();
        //  $message->send();

        $this->testMandrill();
    }


    public function addMailDataItem(MandrillMailDataItem $item)
    {
        $this->data[] = $item->toArray();
    }


    private function checkSendingCapabilities()
    {
        $this->sendable = false;

        if (!defined("MANDRILL_SUBACCOUNT")) {
            exit();
        }
        if ((count($this->data) == 0)) {
            exit();
        }


        if ((!$this->template) || ($this->template == '')) {
            exit();
        }

        if ((!$this->subject) || ($this->subject == '')) {
            exit();
        }

        if ((!$this->senderName) || ($this->senderName == '')) {
            exit();
        }

        if ((!$this->senderEmail) || ($this->senderEmail == '')) {
            exit();
        }

        if (count($this->receivers) == 0) {
            exit();
        }


        $this->sendable = true;
    }


    public function sendMe()
    {
        $this->checkSendingCapabilities();

        if ($this->sendable) {
            $this->addBccReceiver();
            $template_content = [];
            $mandrill = new Mandrill($this->api);
            $message = [
                "subject" => $this->subject,
                "from_email" => $this->senderEmail,
                "from_name" => $this->senderName,
                "to" => $this->receivers,
                "headers" => ['Reply-To' => $this->senderEmail],
                "important" => $this->important,
                "track_opens" => $this->track_opens,
                "track_clicks" => $this->track_clicks,
                "inline_css" => true,
                "view_content_clink" => null,
                "bcc_address" => 'amelie.cap@marche.be',
                "tracking_domain" => null,
                "signing_domain" => null,
                "return_path_domain" => null,
                "merge" => true,
                "merge_vars" => $this->setMultiRecipients(),
                "metadata" => ['website' => $this->website],
                "recipient_metadata" => null,
                "attachments" => null,
                "images" => null,
            ];
            $async = false;
            $ip_pool = "Main Pool";

            //gestion du subAccount
            if ($this->subaccount) {
                $message['subaccount'] = $this->subaccount;
            }

            $res = $mandrill->messages->sendTemplate(
                $this->template,
                $template_content,
                $message,
                $async,
                $ip_pool,
                ''
            );

            if (isset($res[0]['reject_reason']) && $res[0]['reject_reason'] != null) {
                $this->errors = $res[0]['reject_reason'];
                throw new Exception($this->errors);
            } else {
                $result = true;
            }
        } else {
            throw new Exception("Required properties are missing");
        }

        return $result;
    }

    public function getSubAccounts()
    {
        $mandrill = new Mandrill($this->api);
        $list = $mandrill->subaccounts->getList();
        $toReturn = [];
        foreach ($list as $subaccount) {
            $toReturn[] = $subaccount['id'];
        }

        return $toReturn;
    }


    public function addReceiver($email, $firstname = "", $lastname = "", $type = "to")
    {
        $receiver = [];
        $receiver['email'] = $email;
        $receiver['name'] = $firstname." ".$lastname;
        $receiver['type'] = $type; //Types : to, cc, bcc
        $this->receivers[] = $receiver;
    }

    public function addBccReceiver()
    {
        $receiver = [];
        $receiver['email'] = 'jf@marche.be';
        $receiver['name'] = 'jf copy';
        $receiver['type'] = 'bcc'; //Types : to, cc, bcc
        $this->receivers[] = $receiver;
    }

    private function setMultiRecipients()
    {
        $recipients = [];
        foreach ($this->receivers as $r) {
            $rec = [];
            $rec['rcpt'] = $r['email'];
            $this->addMailDataItem(new MandrillMailDataItem("RECEIVER", $r['email']));
            $rec['vars'] = $this->data;
            $recipients[] = $rec;
        }

        return $recipients;
    }

}
