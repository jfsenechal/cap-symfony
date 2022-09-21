<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioPixel
 *
 * @ORM\Table(name="commercio_pixel", uniqueConstraints={@ORM\UniqueConstraint(name="commercio_pixel_uuid_key", columns={"uuid"})}, indexes={@ORM\Index(name="IDX_E138FC5B79D40486", columns={"commercio_commercant_id"}), @ORM\Index(name="IDX_E138FC5B1D25D02A", columns={"pixel_type_id"})})
 * @ORM\Entity
 */
class CommercioPixel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commercio_pixel_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var string
     *
     * @ORM\Column(name="pixel_id", type="text", nullable=false)
     */
    private $pixelId;

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
     * @var CommercioCommercant
     *
     * @ORM\ManyToOne(targetEntity="CommercioCommercant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercio_commercant_id", referencedColumnName="id")
     * })
     */
    private $commercioCommercant;

    /**
     * @var CommercioPixelType
     *
     * @ORM\ManyToOne(targetEntity="CommercioPixelType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pixel_type_id", referencedColumnName="id")
     * })
     */
    private $pixelType;

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

    public function getPixelId(): ?string
    {
        return $this->pixelId;
    }

    public function setPixelId(string $pixelId): self
    {
        $this->pixelId = $pixelId;

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

    public function getCommercioCommercant(): ?CommercioCommercant
    {
        return $this->commercioCommercant;
    }

    public function setCommercioCommercant(?CommercioCommercant $commercioCommercant): self
    {
        $this->commercioCommercant = $commercioCommercant;

        return $this;
    }

    public function getPixelType(): ?CommercioPixelType
    {
        return $this->pixelType;
    }

    public function setPixelType(?CommercioPixelType $pixelType): self
    {
        $this->pixelType = $pixelType;

        return $this;
    }


}
