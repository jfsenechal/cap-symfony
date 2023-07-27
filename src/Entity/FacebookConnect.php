<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookConnect
 */
#[ORM\Table(name: 'facebook_connect')]
#[ORM\Index(name: 'IDX_2F4E56483FA6DD0', columns: ['commercant_id'])]
#[ORM\Index(name: 'IDX_2F4E5644BD166F5', columns: ['commercio_administrator_id'])]
#[ORM\Index(name: 'IDX_2F4E56440FE4270', columns: ['facebook_connect_type_id'])]
#[ORM\Entity]
class FacebookConnect
{
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'facebook_connect_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'token', type: 'text', nullable: false)]
    private string $token = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'facebook_id', type: 'string', length: 100, nullable: false)]
    private string $facebookId = '';

    #[ORM\Column(name: 'name', type: 'string', length: 100, nullable: false)]
    private string $name = '';

    #[ORM\Column(name: 'image', type: 'text', nullable: true)]
    private ?string $image = null;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\JoinColumn(name: 'commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercant')]
    private ?CommercioCommercant $commercant = null;

    #[ORM\JoinColumn(name: 'commercio_administrator_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioAdministrator')]
    private ?CommercioAdministrator $commercioAdministrator = null;

    #[ORM\JoinColumn(name: 'facebook_connect_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'FacebookConnectType')]
    private ?FacebookConnectType $facebookConnectType = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(string $facebookId): self
    {
        $this->facebookId = $facebookId;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getCommercant(): ?CommercioCommercant
    {
        return $this->commercant;
    }

    public function setCommercant(?CommercioCommercant $commercant): self
    {
        $this->commercant = $commercant;

        return $this;
    }

    public function getCommercioAdministrator(): ?CommercioAdministrator
    {
        return $this->commercioAdministrator;
    }

    public function setCommercioAdministrator(?CommercioAdministrator $commercioAdministrator): self
    {
        $this->commercioAdministrator = $commercioAdministrator;

        return $this;
    }

    public function getFacebookConnectType(): ?FacebookConnectType
    {
        return $this->facebookConnectType;
    }

    public function setFacebookConnectType(?FacebookConnectType $facebookConnectType): self
    {
        $this->facebookConnectType = $facebookConnectType;

        return $this;
    }
}
