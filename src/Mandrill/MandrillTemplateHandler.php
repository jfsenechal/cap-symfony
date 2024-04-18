<?php

namespace Cap\Commercio\Mandrill;

use Mandrill;
use Mandrill_Messages;
use Mandrill_Senders;
use Mandrill_Subaccounts;
use Mandrill_Templates;
use Mandrill_Users;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class MandrillTemplateHandler
{
    public function __construct(
        #[Autowire('%env(MANDRILL_API)%')] private readonly string $api,
    ) {

    }

    public function getTemplates(): array
    {
        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);

        return $template->getList('commercio');
    }

    public function templateShow(string $name): array
    {
        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);

        return $template->info($name);
    }

    public function templateSet(string $name, string $content): array
    {
        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);

        return $template->update($name, code: $content);
    }

    public function templateRender(string $name, array $vars): string
    {
        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);
        $content = [];

        return $template->render($name, $content, $vars);
    }

    public function getMessages(int $limit): array
    {
        $mailchimp = new Mandrill($this->api);
        $message = new Mandrill_Messages($mailchimp);

        return $message->search(
            limit: $limit,
            senders: ['info@capsurmarche.com'],
            date_from: '2021-01-01',
            date_to: '2025-12-31'
        );
    }

    public function getMessage(string $id)
    {
        $mailchimp = new Mandrill($this->api);
        $message = new Mandrill_Messages($mailchimp);

        return $message->info($id);
    }

    public function getMessageContent(string $id)
    {
        $mailchimp = new Mandrill($this->api);
        $message = new Mandrill_Messages($mailchimp);

        return $message->content($id);
    }

    public function infos()
    {
        $mailchimp = new Mandrill($this->api);
        $template = new Mandrill_Templates($mailchimp);
        $account = new Mandrill_Subaccounts($mailchimp);
        $user = new Mandrill_Users($mailchimp);
        $senders = new Mandrill_Senders($mailchimp);
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

    public function saveAll(string $path)
    {
        $filesystem = new Filesystem();
        foreach ($this->getTemplates() as $template) {
            $templateDetails = $this->templateShow($template['name']);
            $filesystem->dumpFile($path.$template['slug'].'.html', $templateDetails['code']);
        }
    }
}