<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'news_news')]
#[ORM\UniqueConstraint(name: 'news_news_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class News
{
    use UuidTrait;
    use IdTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'title', type: 'text', nullable: false)]
    private ?string $title = null;

    #[ORM\Column(name: 'description', type: 'text', nullable: false)]
    private ?string $description = null;

    #[ORM\Column(name: 'media_path', type: 'text', nullable: false)]
    private ?string $mediaPath = null;

    #[ORM\Column(name: 'send_by_mail', type: 'boolean', nullable: false)]
    private bool $sendByMail = false;

    #[ORM\Column(name: 'is_send', type: 'boolean', nullable: false)]
    private bool $isSend = false;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    #[ORM\Column(name: 'send_to_bottin', type: 'boolean', nullable: false)]
    private bool $sendToBottin = false;

    #[Assert\Image()]
    public UploadedFile $image;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    public function isSendByMail(): ?bool
    {
        return $this->sendByMail;
    }

    public function setSendByMail(bool $sendByMail): self
    {
        $this->sendByMail = $sendByMail;

        return $this;
    }

    public function isIsSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): self
    {
        $this->isSend = $isSend;

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

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function isSendToBottin(): ?bool
    {
        return $this->sendToBottin;
    }

    public function setSendToBottin(bool $sendToBottin): self
    {
        $this->sendToBottin = $sendToBottin;

        return $this;
    }
}
