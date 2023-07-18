<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioCommercant
 *
 * @ORM\Table(name="commercio_commercant", uniqueConstraints={@ORM\UniqueConstraint(name="commercio_commercant_uuid_key", columns={"uuid"})}, indexes={@ORM\Index(name="IDX_F8F60C85296A161C", columns={"cta_id"}), @ORM\Index(name="IDX_F8F60C8561835A4B", columns={"hours_type_id"}), @ORM\Index(name="IDX_F8F60C85A95B5196", columns={"right_access_id"})})
 * @ORM\Entity
 */
class CommercioCommercant
{
    public array $images = [];
    public array $hours=[];
    public int $bottin_id = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commercio_commercant_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_media_path", type="text", nullable=true)
     */
    private $profileMediaPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="media_path", type="text", nullable=true)
     */
    private $mediaPath;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_receive_tender", type="boolean", nullable=false, options={"default"="1"})
     */
    private $canReceiveTender = true;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_id", type="text", nullable=true)
     */
    private $facebookId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="open_sunday", type="boolean", nullable=true)
     */
    private $openSunday = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commercial_word_title", type="string", length=100, nullable=true)
     */
    private $commercialWordTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commercial_word_media_path", type="text", nullable=true)
     */
    private $commercialWordMediaPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_entity", type="text", nullable=true)
     */
    private $legalEntity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vat_number", type="text", nullable=true)
     */
    private $vatNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="text", nullable=true)
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_member", type="boolean", nullable=false)
     */
    private $isMember = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="affiliation_date", type="datetime", nullable=true)
     */
    private $affiliationDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean", nullable=false)
     */
    private $archived = false;

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
     * @var string|null
     *
     * @ORM\Column(name="stripe_user_ref", type="text", nullable=true)
     */
    private $stripeUserRef;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commercial_word_video_path", type="text", nullable=true)
     */
    private $commercialWordVideoPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commercial_word_description", type="string", length=1000, nullable=true)
     */
    private $commercialWordDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_email", type="text", nullable=true)
     */
    private $legalEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_phone", type="text", nullable=true)
     */
    private $legalPhone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_firstname", type="text", nullable=true)
     */
    private $legalFirstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_lastname", type="text", nullable=true)
     */
    private $legalLastname;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_receive_news", type="boolean", nullable=false, options={"default"="1"})
     */
    private $canReceiveNews = true;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legal_email_2", type="text", nullable=true)
     */
    private $legalEmail2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_link", type="text", nullable=true)
     */
    private $facebookLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="twitter_link", type="text", nullable=true)
     */
    private $twitterLink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="linkedin_link", type="text", nullable=true)
     */
    private $linkedinLink;

    /**
     * @var CommercioCta
     *
     * @ORM\ManyToOne(targetEntity="CommercioCta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cta_id", referencedColumnName="id")
     * })
     */
    private $cta;

    /**
     * @var CommercioCommercantHoursType
     *
     * @ORM\ManyToOne(targetEntity="CommercioCommercantHoursType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hours_type_id", referencedColumnName="id")
     * })
     */
    private $hoursType;

    /**
     * @var RightAccess
     *
     * @ORM\ManyToOne(targetEntity="RightAccess")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="right_access_id", referencedColumnName="id")
     * })
     */
    private $rightAccess;

    public bool $sendMailExpired = false;

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

    public function getAffiliationDate(): ?\DateTimeInterface
    {
        return $this->affiliationDate;
    }

    public function setAffiliationDate(?\DateTimeInterface $affiliationDate): self
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
