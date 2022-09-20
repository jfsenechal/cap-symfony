<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="page_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="template_type", type="string", length=130, nullable=false)
     */
    private $templateType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="insert_date", type="datetime", nullable=false, options={"default"="now()"})
     */
    private $insertDate = 'now()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modify_date", type="datetime", nullable=false, options={"default"="now()"})
     */
    private $modifyDate = 'now()';

    /**
     * @var int
     *
     * @ORM\Column(name="menu_order_index", type="smallint", nullable=false)
     */
    private $menuOrderIndex = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="in_menu", type="boolean", nullable=false)
     */
    private $inMenu = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cachable", type="boolean", nullable=false, options={"default"="1"})
     */
    private $isCachable = true;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    private $title = '';

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

    public function getInsertDate(): ?\DateTimeInterface
    {
        return $this->insertDate;
    }

    public function setInsertDate(\DateTimeInterface $insertDate): self
    {
        $this->insertDate = $insertDate;

        return $this;
    }

    public function getModifyDate(): ?\DateTimeInterface
    {
        return $this->modifyDate;
    }

    public function setModifyDate(\DateTimeInterface $modifyDate): self
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
