<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'payment_order_commercant')]
#[ORM\Entity]
class PaymentOrderCommercant
{
    use IdTrait, UuidTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'firstname', type: 'text', nullable: false)]
    private string $firstname = '';

    #[ORM\Column(name: 'lastname', type: 'text', nullable: false)]
    private string $lastname = '';

    #[ORM\Column(name: 'email', type: 'text', nullable: false)]
    private ?string $email = null;

    #[ORM\Column(name: 'company_name', type: 'text', nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(name: 'company_vat', type: 'text', nullable: true)]
    private ?string $companyVat = null;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyVat(): ?string
    {
        return $this->companyVat;
    }

    public function setCompanyVat(?string $companyVat): self
    {
        $this->companyVat = $companyVat;

        return $this;
    }

    public function getInsertDate(): ?DateTimeInterface
    {
        return $this->insertDate;
    }

    public function setInsertDate(DateTimeInterface $insertDate): self
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    public function getModifyDate(): ?DateTimeInterface
    {
        return $this->modifyDate;
    }

    public function setModifyDate(DateTimeInterface $modifyDate): self
    {
        $this->modifyDate = $modifyDate;

        return $this;
    }
}
