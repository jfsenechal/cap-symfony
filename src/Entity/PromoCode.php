<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCode
 *
 * @ORM\Table(name="promo_code", uniqueConstraints={@ORM\UniqueConstraint(name="promo_code_code_key", columns={"code"})}, indexes={@ORM\Index(name="IDX_3D8C939EC7313306", columns={"promo_code_status_id"})})
 * @ORM\Entity
 */
class PromoCode
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="promo_code_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emission_date", type="datetime", nullable=false, options={"default"="now()"})
     */
    private $emissionDate = 'now()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", nullable=false)
     */
    private $expirationDate;

    /**
     * @var float
     *
     * @ORM\Column(name="reduction", type="float", precision=10, scale=0, nullable=false)
     */
    private $reduction;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_percent", type="boolean", nullable=false)
     */
    private $isPercent;

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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=30, nullable=false)
     */
    private $code = '';

    /**
     * @var \PromoCodeStatus
     *
     * @ORM\ManyToOne(targetEntity="PromoCodeStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promo_code_status_id", referencedColumnName="id")
     * })
     */
    private $promoCodeStatus;

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

    public function getEmissionDate(): ?\DateTimeInterface
    {
        return $this->emissionDate;
    }

    public function setEmissionDate(\DateTimeInterface $emissionDate): self
    {
        $this->emissionDate = $emissionDate;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function isIsPercent(): ?bool
    {
        return $this->isPercent;
    }

    public function setIsPercent(bool $isPercent): self
    {
        $this->isPercent = $isPercent;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPromoCodeStatus(): ?PromoCodeStatus
    {
        return $this->promoCodeStatus;
    }

    public function setPromoCodeStatus(?PromoCodeStatus $promoCodeStatus): self
    {
        $this->promoCodeStatus = $promoCodeStatus;

        return $this;
    }


}
