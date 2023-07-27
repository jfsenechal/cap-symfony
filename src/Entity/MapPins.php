<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * MapPins
 */
#[ORM\Table(name: 'map_pins')]
#[ORM\Index(name: 'IDX_AC274E15F8D02414', columns: ['pin_type_id'])]
#[ORM\UniqueConstraint(name: 'map_pins_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class MapPins
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'map_pins_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'longitude', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $longitude = null;

    #[ORM\Column(name: 'latitude', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $latitude = null;

    #[ORM\Column(name: 'title', type: 'text', nullable: true)]
    private ?string $title = null;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'commercio_commercant_id', type: 'bigint', nullable: true)]
    private ?string $commercioCommercantId = null;

    #[ORM\Column(name: 'street1', type: 'text', nullable: true)]
    private ?string $street1 = '';

    #[ORM\Column(name: 'zipcode', type: 'text', nullable: true)]
    private ?string $zipcode = '';

    #[ORM\Column(name: 'city', type: 'text', nullable: true)]
    private ?string $city = '';

    #[ORM\Column(name: 'sectors', type: 'string', nullable: true)]
    private ?string $sectors = null;

    #[ORM\Column(name: 'sectors_name', type: 'string', nullable: true)]
    private ?string $sectorsName = null;

    #[ORM\Column(name: 'slugname', type: 'text', nullable: true)]
    private ?string $slugname = null;

    #[ORM\Column(name: 'pmr', type: 'boolean', nullable: true)]
    private ?bool $pmr = false;

    #[ORM\Column(name: 'telephone', type: 'text', nullable: true)]
    private ?string $telephone = '';

    #[ORM\Column(name: 'horaires', type: 'text', nullable: true)]
    private ?string $horaires = null;

    #[ORM\JoinColumn(name: 'pin_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'MapPinsType')]
    private ?MapPinsType $pinType = null;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

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

    public function getCommercioCommercantId(): ?string
    {
        return $this->commercioCommercantId;
    }

    public function setCommercioCommercantId(?string $commercioCommercantId): self
    {
        $this->commercioCommercantId = $commercioCommercantId;

        return $this;
    }

    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    public function setStreet1(?string $street1): self
    {
        $this->street1 = $street1;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getSectors(): ?string
    {
        return $this->sectors;
    }

    public function setSectors(?string $sectors): self
    {
        $this->sectors = $sectors;

        return $this;
    }

    public function getSectorsName(): ?string
    {
        return $this->sectorsName;
    }

    public function setSectorsName(?string $sectorsName): self
    {
        $this->sectorsName = $sectorsName;

        return $this;
    }

    public function getSlugname(): ?string
    {
        return $this->slugname;
    }

    public function setSlugname(?string $slugname): self
    {
        $this->slugname = $slugname;

        return $this;
    }

    public function isPmr(): ?bool
    {
        return $this->pmr;
    }

    public function setPmr(?bool $pmr): self
    {
        $this->pmr = $pmr;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getHoraires(): ?string
    {
        return $this->horaires;
    }

    public function setHoraires(?string $horaires): self
    {
        $this->horaires = $horaires;

        return $this;
    }

    public function getPinType(): ?MapPinsType
    {
        return $this->pinType;
    }

    public function setPinType(?MapPinsType $pinType): self
    {
        $this->pinType = $pinType;

        return $this;
    }


}
