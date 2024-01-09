<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'payment_order_address')]
#[ORM\Index(name: 'IDX_630CF078D9F6D38', columns: ['order_id'])]
#[ORM\Entity]
class PaymentOrderAddress
{
    use IdTrait, UuidTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'address_type_id', type: 'bigint', nullable: false)]
    private ?string $addressTypeId = null;

    #[ORM\Column(name: 'street1', type: 'text', nullable: false)]
    private ?string $street1 = null;

    #[ORM\Column(name: 'street2', type: 'text', nullable: true)]
    private ?string $street2 = null;

    #[ORM\Column(name: 'zipcode', type: 'text', nullable: false)]
    private ?string $zipcode = null;

    #[ORM\Column(name: 'city', type: 'text', nullable: false)]
    private ?string $city = null;

    #[ORM\Column(name: 'country_id', type: 'bigint', nullable: false)]
    private ?string $countryId = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: PaymentOrder::class)]
    private ?PaymentOrder $order = null;

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

    public function getAddressTypeId(): ?string
    {
        return $this->addressTypeId;
    }

    public function setAddressTypeId(string $addressTypeId): self
    {
        $this->addressTypeId = $addressTypeId;

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

    public function getCountryId(): ?string
    {
        return $this->countryId;
    }

    public function setCountryId(string $countryId): self
    {
        $this->countryId = $countryId;

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

    public function getOrder(): ?PaymentOrder
    {
        return $this->order;
    }

    public function setOrder(?PaymentOrder $order): self
    {
        $this->order = $order;

        return $this;
    }
}
