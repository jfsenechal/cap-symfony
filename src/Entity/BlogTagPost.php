<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'blog_tag_post')]
#[ORM\Index(name: 'IDX_C0D8F71C4B89032C', columns: ['post_id'])]
#[ORM\Index(name: 'IDX_C0D8F71CBAD26311', columns: ['tag_id'])]
#[ORM\Entity]
class BlogTagPost
{
    use IdTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: BlogPost::class)]
    private ?BlogPost $post = null;

    #[ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: BlogTag::class)]
    private ?BlogTag $tag = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

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

    public function getPost(): ?BlogPost
    {
        return $this->post;
    }

    public function setPost(?BlogPost $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getTag(): ?BlogTag
    {
        return $this->tag;
    }

    public function setTag(?BlogTag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
