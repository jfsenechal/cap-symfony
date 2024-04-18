<?php

namespace Cap\Commercio\Mailer;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Wallet\Handler\WallHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerJf
{
    private string $senderName = 'Cap sur Marche';

    public function __construct(
        #[Autowire('%env(CAP_URL)%')] private readonly string $capUrl,
        #[Autowire('%env(EMAIL_FROM)%')] private readonly string $senderEmail,
        #[Autowire('%env(EMAIL_BCC)%')] private readonly string $bcc,
        private readonly MailerInterface $mailer,
        private readonly WallHandler $wallHandler,
    ) {
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

    /**
     * @param CommercioCommercant $commercant
     * @param PaymentOrder $paymentOrder
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendNewAffiliation(CommercioCommercant $commercant, PaymentOrder $paymentOrder): void
    {
        $virementPath = 'https://cap.marche.be/'.$paymentOrder->getPdfPath();
        $paymentUrl = $this->wallHandler->generateUrlForPayment($paymentOrder);
        $addresses = $this->getAddresses($commercant);

        $email = (new TemplatedEmail())
            ->from($this->senderEmail)
            ->subject($this->senderName." - Nouvelle affiliation Cap sur Marche")
            ->addTo(...$addresses)
            ->htmlTemplate('@CapCommercio/mail/reminder-expired.html.twig')
            ->context([
                'TEMPLATEPATH' => $this->capUrl.'/templates/commercio/',
                'PREFIX' => $this->capUrl,
                "SOCIETE" => $commercant->getLegalEntity(),
                "C_EMAIL" => $commercant->getLegalEmail(),
                "VIREMENT_PDF" => $virementPath,
                "VIREMENT_URL" => $paymentUrl,
                "C_VAT" => $commercant->getVatNumber(),
            ]);

        $this->addBcc($email);
        $this->send($email);
    }

    /**
     * @param CommercioCommercant $commercant
     * @param PaymentOrder $paymentOrder
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendAffiliationExpired(CommercioCommercant $commercant, PaymentOrder $paymentOrder): void
    {
        $dateTime = $commercant->getAffiliationDate();
        $addresses = $this->getAddresses($commercant);
        $date = $dateTime instanceof \DateTimeInterface ? $dateTime->format("d/m/Y") : date('d/m/Y');
        $virementPath = 'https://cap.marche.be/'.$paymentOrder->getPdfPath();
        $paymentUrl = $this->wallHandler->generateUrlForPayment($paymentOrder);

        $email = (new TemplatedEmail())
            ->from($this->senderEmail)
            ->subject($this->senderName." - Votre affiliation a expiré")
            ->addTo(...$addresses)
            ->htmlTemplate('@CapCommercio/mail/reminder-expired.html.twig')
            ->context([
                'TEMPLATEPATH' => $this->capUrl.'/templates/commercio/',
                'PREFIX' => $this->capUrl,
                "SOCIETE" => $commercant->getLegalEntity(),
                "C_EMAIL" => $commercant->getLegalEmail(),
                "VIREMENT_PDF" => $virementPath,
                "VIREMENT_URL" => $paymentUrl,
                "START_DATE" => $date,
            ]);

        $this->addBcc($email);
        $this->send($email);
    }

    /**
     * @param CommercioCommercant $commercant
     * @return Address[]
     */
    private function getAddresses(CommercioCommercant $commercant): array
    {
        $addresses = [
            new Address(
                $commercant->getLegalEmail(),
                $commercant->getLegalFirstname().' '.$commercant->getLegalLastname()
            ),
        ];

        if ($commercant->getLegalEmail2()) {
            $addresses[] = new Address(
                $commercant->getLegalEmail2(),
                $commercant->getLegalFirstname().' '.$commercant->getLegalLastname()
            );
        }

        return $addresses;
    }

    private function addBcc(TemplatedEmail $email)
    {
        $email->addBcc($this->bcc);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function send(TemplatedEmail $email): void
    {
        $this->mailer->send($email);
    }
}