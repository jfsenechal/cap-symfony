<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'part_item')]
#[ORM\Entity]
class PartItem
{
    use IdTrait;

    #[ORM\Column(name: 'partname', type: 'string', length: 100, nullable: false)]
    private ?string $partname = null;

    #[ORM\Column(name: 'tag_type', type: 'string', length: 100, nullable: false)]
    private ?string $tagType = null;

    
    #[ORM\Column(name: 'order_index', type: 'smallint', nullable: false, options: ['default' => '1'])]
    private $orderIndex = '1';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'language', type: 'string', length: 5, nullable: false)]
    private ?string $language = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPartname(): ?string
    {
        return $this->partname;
    }

    public function setPartname(string $partname): self
    {
        $this->partname = $partname;

        return $this;
    }

    public function getTagType(): ?string
    {
        return $this->tagType;
    }

    public function setTagType(string $tagType): self
    {
        $this->tagType = $tagType;

        return $this;
    }

    public function getOrderIndex(): ?int
    {
        return $this->orderIndex;
    }

    public function setOrderIndex(int $orderIndex): self
    {
        $this->orderIndex = $orderIndex;

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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }
}
