<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'commercio_commercant_address')]
#[ORM\Index(name: 'IDX_1EF28EAEF5B7AF75', columns: ['address_id'])]
#[ORM\Index(name: 'IDX_1EF28EAE79D40486', columns: ['commercio_commercant_id'])]
#[ORM\UniqueConstraint(name: 'commercio_commercant_address_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class CommercioCommercantAddress
{
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'AddressAddress')]
    private ?AddressAddress $address = null;

    #[ORM\JoinColumn(name: 'commercio_commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercant')]
    private ?CommercioCommercant $commercioCommercant = null;

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

    public function getAddress(): ?AddressAddress
    {
        return $this->address;
    }

    public function setAddress(?AddressAddress $address): self
    {
        $this->address = $address;

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
