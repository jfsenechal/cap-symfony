<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookConnect
 *
 * @ORM\Table(name="facebook_connect", indexes={@ORM\Index(name="IDX_2F4E56483FA6DD0", columns={"commercant_id"}), @ORM\Index(name="IDX_2F4E5644BD166F5", columns={"commercio_administrator_id"}), @ORM\Index(name="IDX_2F4E56440FE4270", columns={"facebook_connect_type_id"})})
 * @ORM\Entity
 */
class FacebookConnect
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="facebook_connect_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text", nullable=false)
     */
    private $token = '';

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
     * @ORM\Column(name="facebook_id", type="string", length=100, nullable=false)
     */
    private $facebookId = '';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var \CommercioCommercant
     *
     * @ORM\ManyToOne(targetEntity="CommercioCommercant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercant_id", referencedColumnName="id")
     * })
     */
    private $commercant;

    /**
     * @var \CommercioAdministrator
     *
     * @ORM\ManyToOne(targetEntity="CommercioAdministrator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercio_administrator_id", referencedColumnName="id")
     * })
     */
    private $commercioAdministrator;

    /**
     * @var \FacebookConnectType
     *
     * @ORM\ManyToOne(targetEntity="FacebookConnectType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facebook_connect_type_id", referencedColumnName="id")
     * })
     */
    private $facebookConnectType;

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
