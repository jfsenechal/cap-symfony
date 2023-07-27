<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddressIsoCountries
 */
#[ORM\Table(name: 'address_iso_countries')]
#[ORM\Entity]
class AddressIsoCountries
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'address_iso_countries_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'code', type: 'bigint', nullable: false)]
    private ?string $code = null;

    #[ORM\Column(name: 'iso1', type: 'string', length: 2, nullable: false)]
    private ?string $iso1 = null;

    #[ORM\Column(name: 'iso2', type: 'string', length: 3, nullable: false)]
    private ?string $iso2 = null;

    #[ORM\Column(name: 'en_us', type: 'string', length: 120, nullable: false)]
    private ?string $enUs = null;

    #[ORM\Column(name: 'fr_fr', type: 'string', length: 120, nullable: false)]
    private ?string $frFr = null;

    #[ORM\Column(name: 'displayed', type: 'boolean', nullable: false)]
    private bool $displayed = false;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getIso1(): ?string
    {
        return $this->iso1;
    }

    public function setIso1(string $iso1): self
    {
        $this->iso1 = $iso1;

        return $this;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(string $iso2): self
    {
        $this->iso2 = $iso2;

        return $this;
    }

    public function getEnUs(): ?string
    {
        return $this->enUs;
    }

    public function setEnUs(string $enUs): self
    {
        $this->enUs = $enUs;

        return $this;
    }

    public function getFrFr(): ?string
    {
        return $this->frFr;
    }

    public function setFrFr(string $frFr): self
    {
        $this->frFr = $frFr;

        return $this;
    }

    public function isDisplayed(): ?bool
    {
        return $this->displayed;
    }

    public function setDisplayed(bool $displayed): self
    {
        $this->displayed = $displayed;

        return $this;
    }


}
