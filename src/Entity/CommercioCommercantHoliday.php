<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioCommercantHoliday
 */
#[ORM\Table(name: 'commercio_commercant_holiday')]
#[ORM\UniqueConstraint(name: 'commercio_commercant_holiday_uuid_key', columns: ['uuid'])]
#[ORM\UniqueConstraint(name: 'commercio_commercant_holiday_commercio_commercant_id_key', columns: ['commercio_commercant_id'])]
#[ORM\Entity]
class CommercioCommercantHoliday
{
    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'commercio_commercant_holiday_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'begin_date', type: 'datetime', nullable: false)]
    private ?DateTimeInterface $beginDate = null;

    #[ORM\Column(name: 'end_date', type: 'datetime', nullable: false)]
    private ?DateTimeInterface $endDate = null;

    #[ORM\Column(name: 'note', type: 'string', length: 200, nullable: true)]
    private ?string $note = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

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

    public function getBeginDate(): ?DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

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


}
