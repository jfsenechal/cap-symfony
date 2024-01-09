<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tender_attachment')]
#[ORM\Index(name: 'IDX_D49FB3D9245DE54', columns: ['tender_id'])]
#[ORM\UniqueConstraint(name: 'tender_attachment_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class TenderAttachment
{
    use IdTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'media_path', type: 'text', nullable: false)]
    private ?string $mediaPath = null;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'tender_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: TenderTender::class)]
    private ?TenderTender $tender = null;

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

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

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

    public function getTender(): ?TenderTender
    {
        return $this->tender;
    }

    public function setTender(?TenderTender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }
}
