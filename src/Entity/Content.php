<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'content')]
#[ORM\Index(name: 'IDX_FEC530A9C4663E4', columns: ['page_id'])]
#[ORM\UniqueConstraint(name: 'content_url_key', columns: ['url'])]
#[ORM\Entity]
class Content
{
    use IdTrait;

    #[ORM\Column(name: 'url', type: 'string', nullable: false)]
    private string $url = '';

    #[ORM\Column(name: 'language', type: 'string', length: 10, nullable: false, options: ['default' => 'FR'])]
    private string $language = 'FR';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'name', type: 'string', length: 200, nullable: false)]
    private string $name = '';

    #[ORM\JoinColumn(name: 'page_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Page')]
    private ?Page $page = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }
}
