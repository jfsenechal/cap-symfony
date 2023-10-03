<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'recovery_demand')]
#[ORM\Entity]
class RecoveryDemand
{
    use IdTrait;

    #[ORM\Column(name: 'email', type: 'string', length: 100, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(name: 'right_access_id', type: 'bigint', nullable: false)]
    private ?string $rightAccessId = null;

    #[ORM\Column(name: 'code', type: 'string', length: 150, nullable: false)]
    private string $code = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getRightAccessId(): ?string
    {
        return $this->rightAccessId;
    }

    public function setRightAccessId(string $rightAccessId): self
    {
        $this->rightAccessId = $rightAccessId;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
