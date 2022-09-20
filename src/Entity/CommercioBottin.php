<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommercioBottin
 *
 * @ORM\Table(name="commercio_bottin")
 * @ORM\Entity
 */
class CommercioBottin
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commercio_bottin_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="bottin", type="json", nullable=false)
     */
    private $bottin;

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
     * @var int
     *
     * @ORM\Column(name="commercant_id", type="bigint", nullable=false)
     */
    private $commercantId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBottin(): ?array
    {
        return $this->bottin;
    }

    public function setBottin(array $bottin): self
    {
        $this->bottin = $bottin;

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

    public function getCommercantId(): ?string
    {
        return $this->commercantId;
    }

    public function setCommercantId(string $commercantId): self
    {
        $this->commercantId = $commercantId;

        return $this;
    }


}
