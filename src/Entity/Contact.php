<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'contact')]
#[ORM\Entity]
class Contact
{
    use IdTrait;

    #[ORM\Column(name: 'firstname', type: 'string', length: 100, nullable: false)]
    private string $firstname = '';

    #[ORM\Column(name: 'lastname', type: 'string', length: 100, nullable: false)]
    private string $lastname = '';

    #[ORM\Column(name: 'email', type: 'string', length: 200, nullable: false)]
    private string $email = '';

    #[ORM\Column(name: 'phone', type: 'string', length: 100, nullable: false)]
    private string $phone = '';

    #[ORM\Column(name: 'message', type: 'text', nullable: false)]
    private string $message = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
