<?php
/**
 * Created by JetBrains PhpStorm.
 * User: geoffrey
 * Date: 17/03/15
 * Time: 14:12
 * To change this template use File | Settings | File Templates.
 */

namespace Cap\Commercio\Mailer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MandrillMail
{

    public ?string $template = null;
    public array $data = [];
    //private $receiver;
    public ?string $subject = null;
    public ?string $senderName = null;
    public ?string $senderEmail = null;
    private array $receivers = array();
    public bool $website = false;
    public bool $track_opens = true;
    public bool $track_clicks = true;
    public bool $important = false;
    private bool $sendable = false;
    private string $mandrillApiKey;
    private ?string $subaccount;
    public array $errors = array();

    public function __construct(#[Autowire(env: 'MANDRILL_API')] $api)
    {
        $this->data = array();
        if (defined('MANDRILL_SUBACCOUNT')) {
            $this->subaccount = MANDRILL_SUBACCOUNT;
        } else {
            $this->subaccount = null;
        }

        $this->mandrillApiKey = $api;
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
            $template_content = array();
            $mandrill = new \Mandrill($this->mandrillApiKey);
            $message = array(
                "subject" => $this->subject,
                "from_email" => $this->senderEmail,
                "from_name" => $this->senderName,
                "to" => $this->receivers,
                "headers" => array('Reply-To' => $this->senderEmail),
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
                "metadata" => array('website' => $this->website),
                "recipient_metadata" => null,
                "attachments" => null,
                "images" => null,
            );
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
                throw new \Exception($this->errors);
            } else {
                $result = true;
            }

        } else {
            throw new \Exception("Required properties are missing");
        }

        return $result;
    }

    public function getSubAccounts()
    {
        $mandrill = new \Mandrill($this->mandrillApiKey);
        $list = $mandrill->subaccounts->getList();
        $toReturn = array();
        foreach ($list as $subaccount) {
            $toReturn[] = $subaccount['id'];
        }

        return $toReturn;
    }


    public function addReceiver($email, $firstname = "", $lastname = "", $type = "to")
    {
        $receiver = array();
        $receiver['email'] = $email;
        $receiver['name'] = $firstname." ".$lastname;
        $receiver['type'] = $type; //Types : to, cc, bcc
        $this->receivers[] = $receiver;

    }

    public function addBccReceiver()
    {
        $receiver = array();
        $receiver['email'] = 'jf@marche.be';
        $receiver['name'] = 'jf copy';
        $receiver['type'] = 'bcc'; //Types : to, cc, bcc
        $this->receivers[] = $receiver;
    }

    private function setMultiRecipients()
    {
        $recipients = array();
        foreach ($this->receivers as $r) {
            $rec = array();
            $rec['rcpt'] = $r['email'];
            $this->addMailDataItem(new MandrillMailDataItem("RECEIVER", $r['email']));
            $rec['vars'] = $this->data;
            $recipients[] = $rec;
        }

        return $recipients;
    }

}
