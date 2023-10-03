<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'settings')]
#[ORM\Entity]
class Settings
{
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private int $id;

    #[ORM\Column(name: 'param_key', type: 'text', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $paramKey;

    #[ORM\Column(name: 'param_value', type: 'text', nullable: false)]
    private ?string $paramValue = null;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'settings_type_id', type: 'bigint', nullable: false, options: ['default' => '3'])]
    private string $settingsTypeId = '3';

    #[ORM\Column(name: 'label', type: 'text', nullable: false)]
    private string $label = '';

    #[ORM\Column(name: 'description', type: 'text', nullable: false)]
    private string $description = '';

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getParamKey(): ?string
    {
        return $this->paramKey;
    }

    public function getParamValue(): ?string
    {
        return $this->paramValue;
    }

    public function setParamValue(string $paramValue): self
    {
        $this->paramValue = $paramValue;

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

    public function getSettingsTypeId(): ?string
    {
        return $this->settingsTypeId;
    }

    public function setSettingsTypeId(string $settingsTypeId): self
    {
        $this->settingsTypeId = $settingsTypeId;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
