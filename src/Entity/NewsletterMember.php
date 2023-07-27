<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterMember
 */
#[ORM\Table(name: 'newsletter_member')]
#[ORM\UniqueConstraint(name: 'newsletter_member_email_key', columns: ['email'])]
#[ORM\Entity]
class NewsletterMember
{
    
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'newsletter_member_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'lastname', type: 'text', nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(name: 'firstname', type: 'text', nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(name: 'email', type: 'text', nullable: false)]
    private string $email = '';

    #[ORM\Column(name: 'sending', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $sending = true;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: true, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: true, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'pending', type: 'boolean', nullable: true)]
    private ?bool $pending = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isSending(): ?bool
    {
        return $this->sending;
    }

    public function setSending(bool $sending): self
    {
        $this->sending = $sending;

        return $this;
    }

    public function getInsertDate(): ?DateTimeInterface
    {
        return $this->insertDate;
    }

    public function setInsertDate(?DateTimeInterface $insertDate): self
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    public function getModifyDate(): ?DateTimeInterface
    {
        return $this->modifyDate;
    }

    public function setModifyDate(?DateTimeInterface $modifyDate): self
    {
        $this->modifyDate = $modifyDate;

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function isPending(): ?bool
    {
        return $this->pending;
    }

    public function setPending(?bool $pending): self
    {
        $this->pending = $pending;

        return $this;
    }


}
