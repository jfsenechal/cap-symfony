<?php

namespace Cap\Commercio\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioCommercantGallery
 *
 * @ORM\Table(name="commercio_commercant_gallery", uniqueConstraints={@ORM\UniqueConstraint(name="commercio_commercant_gallery_uuid_key", columns={"uuid"})}, indexes={@ORM\Index(name="IDX_5497991579D40486", columns={"commercio_commercant_id"})})
 * @ORM\Entity
 */
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
])
]
class CommercioCommercantGallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commercio_commercant_gallery_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="media_path", type="text", nullable=false)
     */
    private $mediaPath;

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
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="text", nullable=false)
     */
    private $alt = '';

    /**
     * @var CommercioCommercant
     *
     * @ORM\ManyToOne(targetEntity="CommercioCommercant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercio_commercant_id", referencedColumnName="id")
     * })
     */
    private $commercioCommercant;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

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


}
