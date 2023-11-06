<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'commercio_commercant')]
#[ORM\Index(name: 'IDX_F8F60C85296A161C', columns: ['cta_id'])]
#[ORM\Index(name: 'IDX_F8F60C8561835A4B', columns: ['hours_type_id'])]
#[ORM\Index(name: 'IDX_F8F60C85A95B5196', columns: ['right_access_id'])]
#[ORM\UniqueConstraint(name: 'commercio_commercant_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class CommercioCommercant
{
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'profile_media_path', type: 'text', nullable: true)]
    private ?string $profileMediaPath = null;

    #[ORM\Column(name: 'media_path', type: 'text', nullable: true)]
    private ?string $mediaPath = null;

    #[ORM\Column(name: 'can_receive_tender', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $canReceiveTender = true;

    #[ORM\Column(name: 'facebook_id', type: 'text', nullable: true)]
    private ?string $facebookId = null;

    #[ORM\Column(name: 'open_sunday', type: 'boolean', nullable: true)]
    private ?bool $openSunday = false;

    #[ORM\Column(name: 'commercial_word_title', type: 'string', length: 100, nullable: true)]
    private ?string $commercialWordTitle = null;

    #[ORM\Column(name: 'commercial_word_media_path', type: 'text', nullable: true)]
    private ?string $commercialWordMediaPath = null;

    #[ORM\Column(name: 'legal_entity', type: 'text', nullable: false)]
    private ?string $legalEntity = null;

    #[ORM\Column(name: 'vat_number', type: 'text', nullable: true)]
    private ?string $vatNumber = null;

    #[ORM\Column(name: 'phone', type: 'text', nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(name: 'is_member', type: 'boolean', nullable: false)]
    private bool $isMember = false;

    #[ORM\Column(name: 'affiliation_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $affiliationDate = null;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'stripe_user_ref', type: 'text', nullable: true)]
    private ?string $stripeUserRef = null;

    #[ORM\Column(name: 'commercial_word_video_path', type: 'text', nullable: true)]
    private ?string $commercialWordVideoPath = null;

    #[ORM\Column(name: 'commercial_word_description', type: 'string', length: 1000, nullable: true)]
    private ?string $commercialWordDescription = null;

    #[ORM\Column(name: 'legal_email', type: 'text', nullable: false)]
    private ?string $legalEmail = null;

    #[ORM\Column(name: 'legal_phone', type: 'text', nullable: true)]
    private ?string $legalPhone = null;

    #[ORM\Column(name: 'legal_firstname', type: 'text', nullable: true)]
    private ?string $legalFirstname = null;

    #[ORM\Column(name: 'legal_lastname', type: 'text', nullable: true)]
    private ?string $legalLastname = null;

    #[ORM\Column(name: 'can_receive_news', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $canReceiveNews = true;

    #[ORM\Column(name: 'legal_email_2', type: 'text', nullable: true)]
    private ?string $legalEmail2 = null;

    #[ORM\Column(name: 'facebook_link', type: 'text', nullable: true)]
    private ?string $facebookLink = null;

    #[ORM\Column(name: 'twitter_link', type: 'text', nullable: true)]
    private ?string $twitterLink = null;

    #[ORM\Column(name: 'linkedin_link', type: 'text', nullable: true)]
    private ?string $linkedinLink = null;

    #[ORM\JoinColumn(name: 'cta_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCta')]
    private ?CommercioCta $cta = null;

    #[ORM\JoinColumn(name: 'hours_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercantHoursType')]
    private ?CommercioCommercantHoursType $hoursType = null;

    #[ORM\JoinColumn(name: 'right_access_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'RightAccess')]
    private ?RightAccess $rightAccess = null;

    public bool $sendMailExpired = false;
    public array $images = [];
    public array $hours = [];
    public int $bottin_id = 0;
    public AddressAddress $address;
    public ?string $urlBottin = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProfileMediaPath(): ?string
    {
        return $this->profileMediaPath;
    }

    public function setProfileMediaPath(?string $profileMediaPath): self
    {
        $this->profileMediaPath = $profileMediaPath;

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

    public function isCanReceiveTender(): ?bool
    {
        return $this->canReceiveTender;
    }

    public function setCanReceiveTender(bool $canReceiveTender): self
    {
        $this->canReceiveTender = $canReceiveTender;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function isOpenSunday(): ?bool
    {
        return $this->openSunday;
    }

    public function setOpenSunday(?bool $openSunday): self
    {
        $this->openSunday = $openSunday;

        return $this;
    }

    public function getCommercialWordTitle(): ?string
    {
        return $this->commercialWordTitle;
    }

    public function setCommercialWordTitle(?string $commercialWordTitle): self
    {
        $this->commercialWordTitle = $commercialWordTitle;

        return $this;
    }

    public function getCommercialWordMediaPath(): ?string
    {
        return $this->commercialWordMediaPath;
    }

    public function setCommercialWordMediaPath(?string $commercialWordMediaPath): self
    {
        $this->commercialWordMediaPath = $commercialWordMediaPath;

        return $this;
    }

    public function getLegalEntity(): ?string
    {
        return $this->legalEntity;
    }

    public function setLegalEntity(?string $legalEntity): self
    {
        $this->legalEntity = $legalEntity;

        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isIsMember(): ?bool
    {
        return $this->isMember;
    }

    public function setIsMember(bool $isMember): self
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getAffiliationDate(): ?DateTimeInterface
    {
        return $this->affiliationDate;
    }

    public function setAffiliationDate(?DateTimeInterface $affiliationDate): self
    {
        $this->affiliationDate = $affiliationDate;

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

    public function getStripeUserRef(): ?string
    {
        return $this->stripeUserRef;
    }

    public function setStripeUserRef(?string $stripeUserRef): self
    {
        $this->stripeUserRef = $stripeUserRef;

        return $this;
    }

    public function getCommercialWordVideoPath(): ?string
    {
        return $this->commercialWordVideoPath;
    }

    public function setCommercialWordVideoPath(?string $commercialWordVideoPath): self
    {
        $this->commercialWordVideoPath = $commercialWordVideoPath;

        return $this;
    }

    public function getCommercialWordDescription(): ?string
    {
        return $this->commercialWordDescription;
    }

    public function setCommercialWordDescription(?string $commercialWordDescription): self
    {
        $this->commercialWordDescription = $commercialWordDescription;

        return $this;
    }

    public function getLegalEmail(): ?string
    {
        return $this->legalEmail;
    }

    public function setLegalEmail(?string $legalEmail): self
    {
        $this->legalEmail = $legalEmail;

        return $this;
    }

    public function getLegalPhone(): ?string
    {
        return $this->legalPhone;
    }

    public function setLegalPhone(?string $legalPhone): self
    {
        $this->legalPhone = $legalPhone;

        return $this;
    }

    public function getLegalFirstname(): ?string
    {
        return $this->legalFirstname;
    }

    public function setLegalFirstname(?string $legalFirstname): self
    {
        $this->legalFirstname = $legalFirstname;

        return $this;
    }

    public function getLegalLastname(): ?string
    {
        return $this->legalLastname;
    }

    public function setLegalLastname(?string $legalLastname): self
    {
        $this->legalLastname = $legalLastname;

        return $this;
    }

    public function isCanReceiveNews(): ?bool
    {
        return $this->canReceiveNews;
    }

    public function setCanReceiveNews(bool $canReceiveNews): self
    {
        $this->canReceiveNews = $canReceiveNews;

        return $this;
    }

    public function getLegalEmail2(): ?string
    {
        return $this->legalEmail2;
    }

    public function setLegalEmail2(?string $legalEmail2): self
    {
        $this->legalEmail2 = $legalEmail2;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): self
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getTwitterLink(): ?string
    {
        return $this->twitterLink;
    }

    public function setTwitterLink(?string $twitterLink): self
    {
        $this->twitterLink = $twitterLink;

        return $this;
    }

    public function getLinkedinLink(): ?string
    {
        return $this->linkedinLink;
    }

    public function setLinkedinLink(?string $linkedinLink): self
    {
        $this->linkedinLink = $linkedinLink;

        return $this;
    }

    public function getCta(): ?CommercioCta
    {
        return $this->cta;
    }

    public function setCta(?CommercioCta $cta): self
    {
        $this->cta = $cta;

        return $this;
    }

    public function getHoursType(): ?CommercioCommercantHoursType
    {
        return $this->hoursType;
    }

    public function setHoursType(?CommercioCommercantHoursType $hoursType): self
    {
        $this->hoursType = $hoursType;

        return $this;
    }

    public function getRightAccess(): ?RightAccess
    {
        return $this->rightAccess;
    }

    public function setRightAccess(?RightAccess $rightAccess): self
    {
        $this->rightAccess = $rightAccess;

        return $this;
    }
}
