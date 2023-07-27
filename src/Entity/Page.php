<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 */
#[ORM\Table(name: 'page')]
#[ORM\Entity]
class Page
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'page_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', nullable: true)]
    private ?string $name = '';

    #[ORM\Column(name: 'template_type', type: 'string', length: 130, nullable: false)]
    private ?string $templateType = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    
    #[ORM\Column(name: 'menu_order_index', type: 'smallint', nullable: false)]
    private $menuOrderIndex = '0';

    #[ORM\Column(name: 'in_menu', type: 'boolean', nullable: false)]
    private bool $inMenu = false;

    #[ORM\Column(name: 'is_cachable', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $isCachable = true;

    #[ORM\Column(name: 'title', type: 'string', length: 200, nullable: false)]
    private string $title = '';

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemplateType(): ?string
    {
        return $this->templateType;
    }

    public function setTemplateType(string $templateType): self
    {
        $this->templateType = $templateType;

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

    public function getMenuOrderIndex(): ?int
    {
        return $this->menuOrderIndex;
    }

    public function setMenuOrderIndex(int $menuOrderIndex): self
    {
        $this->menuOrderIndex = $menuOrderIndex;

        return $this;
    }

    public function isInMenu(): ?bool
    {
        return $this->inMenu;
    }

    public function setInMenu(bool $inMenu): self
    {
        $this->inMenu = $inMenu;

        return $this;
    }

    public function isIsCachable(): ?bool
    {
        return $this->isCachable;
    }

    public function setIsCachable(bool $isCachable): self
    {
        $this->isCachable = $isCachable;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


}
