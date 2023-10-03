<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'content_item')]
#[ORM\Index(name: 'IDX_D279C8DB84A0A3ED', columns: ['content_id'])]
#[ORM\Entity]
class ContentItem
{
    use IdTrait;

    #[ORM\Column(name: 'tag_type', type: 'string', length: 50, nullable: false)]
    private string $tagType = '';

    #[ORM\Column(name: 'order_index', type: 'bigint', nullable: false, options: ['default' => '1'])]
    private string $orderIndex = '1';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'content_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Content')]
    private ?Content $content = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getOrderIndex(): ?string
    {
        return $this->orderIndex;
    }

    public function setOrderIndex(string $orderIndex): self
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

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }
}
