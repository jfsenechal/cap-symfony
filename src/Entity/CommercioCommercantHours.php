<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioCommercantHours
 *
 * @ORM\Table(name="commercio_commercant_hours", uniqueConstraints={@ORM\UniqueConstraint(name="commercio_commercant_hours_uuid_key", columns={"uuid"})}, indexes={@ORM\Index(name="IDX_9375526879D40486", columns={"commercio_commercant_id"})})
 * @ORM\Entity
 */
class CommercioCommercantHours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commercio_commercant_hours_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_closed_at_lunch", type="boolean", nullable=false, options={"default"="1"})
     */
    private $isClosedAtLunch = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_rdv", type="boolean", nullable=false)
     */
    private $isRdv = false;

    /**
     * @var int
     *
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="morning_start", type="time", nullable=false)
     */
    private $morningStart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="morning_end", type="time", nullable=true)
     */
    private $morningEnd;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="noon_start", type="time", nullable=true)
     */
    private $noonStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="noon_end", type="time", nullable=false)
     */
    private $noonEnd;

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
     * @var \CommercioCommercant
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

    public function isIsClosedAtLunch(): ?bool
    {
        return $this->isClosedAtLunch;
    }

    public function setIsClosedAtLunch(bool $isClosedAtLunch): self
    {
        $this->isClosedAtLunch = $isClosedAtLunch;

        return $this;
    }

    public function isIsRdv(): ?bool
    {
        return $this->isRdv;
    }

    public function setIsRdv(bool $isRdv): self
    {
        $this->isRdv = $isRdv;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getMorningStart(): ?\DateTimeInterface
    {
        return $this->morningStart;
    }

    public function setMorningStart(\DateTimeInterface $morningStart): self
    {
        $this->morningStart = $morningStart;

        return $this;
    }

    public function getMorningEnd(): ?\DateTimeInterface
    {
        return $this->morningEnd;
    }

    public function setMorningEnd(?\DateTimeInterface $morningEnd): self
    {
        $this->morningEnd = $morningEnd;

        return $this;
    }

    public function getNoonStart(): ?\DateTimeInterface
    {
        return $this->noonStart;
    }

    public function setNoonStart(?\DateTimeInterface $noonStart): self
    {
        $this->noonStart = $noonStart;

        return $this;
    }

    public function getNoonEnd(): ?\DateTimeInterface
    {
        return $this->noonEnd;
    }

    public function setNoonEnd(\DateTimeInterface $noonEnd): self
    {
        $this->noonEnd = $noonEnd;

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


}
