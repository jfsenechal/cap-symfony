<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * AddressAddress
 */
#[ORM\Table(name: 'address_address')]
#[ORM\Index(name: 'IDX_56AB98199EA97B0B', columns: ['address_type_id'])]
#[ORM\Index(name: 'IDX_56AB9819F92F3E70', columns: ['country_id'])]
#[ORM\Entity]
class AddressAddress
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'address_address_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'street1', type: 'text', nullable: false)]
    private ?string $street1 = null;

    #[ORM\Column(name: 'street2', type: 'text', nullable: true)]
    private ?string $street2 = null;

    #[ORM\Column(name: 'zipcode', type: 'text', nullable: false)]
    private ?string $zipcode = null;

    #[ORM\Column(name: 'city', type: 'text', nullable: false)]
    private ?string $city = null;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'address_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'AddressType')]
    private ?AddressType $addressType = null;

    #[ORM\JoinColumn(name: 'country_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'AddressIsoCountries')]
    private ?AddressIsoCountries $country = null;

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

    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    public function setStreet1(string $street1): self
    {
        $this->street1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(?string $street2): self
    {
        $this->street2 = $street2;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getAddressType(): ?AddressType
    {
        return $this->addressType;
    }

    public function setAddressType(?AddressType $addressType): self
    {
        $this->addressType = $addressType;

        return $this;
    }

    public function getCountry(): ?AddressIsoCountries
    {
        return $this->country;
    }

    public function setCountry(?AddressIsoCountries $country): self
    {
        $this->country = $country;

        return $this;
    }


}
