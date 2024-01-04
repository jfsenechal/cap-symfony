<?php

namespace Cap\Commercio\Mailer;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerJf
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function sendError(string $subject, string $body): void
    {
        $email = (new Email())
            ->from('jf@marche.be')
            ->to('jf@marche.be')
            ->subject('[CAP] '.$subject)
            ->text($body)
            ->html($body);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {

        }
    }
}