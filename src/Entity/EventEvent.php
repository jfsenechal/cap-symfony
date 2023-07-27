<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * EventEvent
 */
#[ORM\Table(name: 'event_event')]
#[ORM\Index(name: 'IDX_7AB5BB8B4BD166F5', columns: ['commercio_administrator_id'])]
#[ORM\Index(name: 'IDX_7AB5BB8B79D40486', columns: ['commercio_commercant_id'])]
#[ORM\Index(name: 'IDX_7AB5BB8B401B253C', columns: ['event_type_id'])]
#[ORM\UniqueConstraint(name: 'event_event_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class EventEvent
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'event_event_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'title', type: 'text', nullable: true)]
    private ?string $title = null;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'media_path', type: 'text', nullable: true)]
    private ?string $mediaPath = null;

    #[ORM\Column(name: 'pdf_path', type: 'text', nullable: true)]
    private ?string $pdfPath = null;

    #[ORM\Column(name: 'eventbrite_link', type: 'text', nullable: true)]
    private ?string $eventbriteLink = null;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'begin_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $beginDate = null;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'end_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $endDate = null;

    #[ORM\Column(name: 'is_facebook', type: 'boolean', nullable: false)]
    private bool $isFacebook = false;

    #[ORM\Column(name: 'is_newletter', type: 'boolean', nullable: false)]
    private bool $isNewletter = false;

    #[ORM\Column(name: 'visible', type: 'boolean', nullable: false)]
    private bool $visible = false;

    #[ORM\Column(name: 'tab_name', type: 'text', nullable: true)]
    private ?string $tabName = null;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'is_entire_day', type: 'boolean', nullable: false)]
    private bool $isEntireDay = false;

    #[ORM\Column(name: 'is_time_slot', type: 'boolean', nullable: false)]
    private bool $isTimeSlot = false;

    #[ORM\Column(name: 'external_link', type: 'text', nullable: true)]
    private ?string $externalLink = null;

    #[ORM\Column(name: 'price', type: 'text', nullable: true)]
    private ?string $price = null;

    #[ORM\Column(name: 'location', type: 'text', nullable: true)]
    private ?string $location = null;

    #[ORM\Column(name: 'url_id', type: 'text', nullable: true)]
    private ?string $urlId = null;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'time_slot_start', type: 'time', nullable: true)]
    private ?DateTimeInterface $timeSlotStart = null;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'time_slot_end', type: 'time', nullable: true)]
    private ?DateTimeInterface $timeSlotEnd = null;

    #[ORM\JoinColumn(name: 'commercio_administrator_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioAdministrator')]
    private ?CommercioAdministrator $commercioAdministrator = null;

    #[ORM\JoinColumn(name: 'commercio_commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercant')]
    private ?CommercioCommercant $commercioCommercant = null;

    #[ORM\JoinColumn(name: 'event_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'EventType')]
    private ?EventType $eventType = null;

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

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(?string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    public function getPdfPath(): ?string
    {
        return $this->pdfPath;
    }

    public function setPdfPath(?string $pdfPath): self
    {
        $this->pdfPath = $pdfPath;

        return $this;
    }

    public function getEventbriteLink(): ?string
    {
        return $this->eventbriteLink;
    }

    public function setEventbriteLink(?string $eventbriteLink): self
    {
        $this->eventbriteLink = $eventbriteLink;

        return $this;
    }

    public function getBeginDate(): ?DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(?DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isIsFacebook(): ?bool
    {
        return $this->isFacebook;
    }

    public function setIsFacebook(bool $isFacebook): self
    {
        $this->isFacebook = $isFacebook;

        return $this;
    }

    public function isIsNewletter(): ?bool
    {
        return $this->isNewletter;
    }

    public function setIsNewletter(bool $isNewletter): self
    {
        $this->isNewletter = $isNewletter;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getTabName(): ?string
    {
        return $this->tabName;
    }

    public function setTabName(?string $tabName): self
    {
        $this->tabName = $tabName;

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

    public function isIsEntireDay(): ?bool
    {
        return $this->isEntireDay;
    }

    public function setIsEntireDay(bool $isEntireDay): self
    {
        $this->isEntireDay = $isEntireDay;

        return $this;
    }

    public function isIsTimeSlot(): ?bool
    {
        return $this->isTimeSlot;
    }

    public function setIsTimeSlot(bool $isTimeSlot): self
    {
        $this->isTimeSlot = $isTimeSlot;

        return $this;
    }

    public function getExternalLink(): ?string
    {
        return $this->externalLink;
    }

    public function setExternalLink(?string $externalLink): self
    {
        $this->externalLink = $externalLink;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getUrlId(): ?string
    {
        return $this->urlId;
    }

    public function setUrlId(?string $urlId): self
    {
        $this->urlId = $urlId;

        return $this;
    }

    public function getTimeSlotStart(): ?DateTimeInterface
    {
        return $this->timeSlotStart;
    }

    public function setTimeSlotStart(?DateTimeInterface $timeSlotStart): self
    {
        $this->timeSlotStart = $timeSlotStart;

        return $this;
    }

    public function getTimeSlotEnd(): ?DateTimeInterface
    {
        return $this->timeSlotEnd;
    }

    public function setTimeSlotEnd(?DateTimeInterface $timeSlotEnd): self
    {
        $this->timeSlotEnd = $timeSlotEnd;

        return $this;
    }

    public function getCommercioAdministrator(): ?CommercioAdministrator
    {
        return $this->commercioAdministrator;
    }

    public function setCommercioAdministrator(?CommercioAdministrator $commercioAdministrator): self
    {
        $this->commercioAdministrator = $commercioAdministrator;

        return $this;
    }

    public function getCommercioCommercant(): ?CommercioCommercant
    {
        return $this->commercioCommercant;
    }

    public function setCommercioCommercant(?CommercioCommercant $commercioCommercant): self
    {
        $this->commercioCommercant = $commercioCommercant;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    public function setEventType(?EventType $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }


}
