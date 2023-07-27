<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioPixel
 */
#[ORM\Table(name: 'commercio_pixel')]
#[ORM\Index(name: 'IDX_E138FC5B79D40486', columns: ['commercio_commercant_id'])]
#[ORM\Index(name: 'IDX_E138FC5B1D25D02A', columns: ['pixel_type_id'])]
#[ORM\UniqueConstraint(name: 'commercio_pixel_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class CommercioPixel
{
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'commercio_pixel_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'pixel_id', type: 'text', nullable: false)]
    private ?string $pixelId = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'commercio_commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercant')]
    private ?CommercioCommercant $commercioCommercant = null;

    #[ORM\JoinColumn(name: 'pixel_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioPixelType')]
    private ?CommercioPixelType $pixelType = null;

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
