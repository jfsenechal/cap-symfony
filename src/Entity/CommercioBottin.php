<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'commercio_bottin')]
#[ORM\Entity]
class CommercioBottin
{
    use IdTrait;

    #[ORM\Column(name: 'bottin', type: 'json', nullable: false)]
    private null|array|\stdClass $bottin = null;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'commercant_id', type: 'bigint', nullable: false)]
    private ?string $commercantId = null;

    #[ORM\Column(name: 'bottin_id', type: 'bigint', nullable: false, unique: true)]
    public ?string $bottinId = null;

    public function __construct(string $commercantId, string $bottinId)
    {
        $this->commercantId = $commercantId;
        $this->bottinId = $bottinId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBottin(): ?array
    {
        return $this->bottin;
    }

    public function setBottin(array|\stdClass $bottin): self
    {
        $this->bottin = $bottin;

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

    public function getCommercantId(): ?string
    {
        return $this->commercantId;
    }

}
