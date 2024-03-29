<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'media')]
#[ORM\Entity]
class Media
{
    use IdTrait;

    #[ORM\Column(name: 'name', type: 'string', length: 200, nullable: false)]
    private string $name = '';

    #[ORM\Column(name: 'url', type: 'string', length: 200, nullable: false)]
    private ?string $url = null;

    #[ORM\Column(name: 'type', type: 'text', nullable: false, options: ['default' => 'IMAGE'])]
    private string $type = 'IMAGE';

    #[ORM\Column(name: 'language', type: 'string', nullable: true)]
    private ?string $language = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'displayed', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $displayed = true;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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

    public function isDisplayed(): ?bool
    {
        return $this->displayed;
    }

    public function setDisplayed(bool $displayed): self
    {
        $this->displayed = $displayed;

        return $this;
    }
}
