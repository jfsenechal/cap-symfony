<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PartItemParams
 *
 * @ORM\Table(name="part_item_params", uniqueConstraints={@ORM\UniqueConstraint(name="part_item_params_id_key", columns={"id"})})
 * @ORM\Entity
 */
class PartItemParams
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="part_item_params_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="part_item_id", type="bigint", nullable=false)
     */
    private $partItemId;

    /**
     * @var string
     *
     * @ORM\Column(name="param_key", type="string", length=250, nullable=false)
     */
    private $paramKey;

    /**
     * @var string|null
     *
     * @ORM\Column(name="param_value", type="string", length=250, nullable=true)
     */
    private $paramValue = '';

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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPartItemId(): ?string
    {
        return $this->partItemId;
    }

    public function setPartItemId(string $partItemId): self
    {
        $this->partItemId = $partItemId;

        return $this;
    }

    public function getParamKey(): ?string
    {
        return $this->paramKey;
    }

    public function setParamKey(string $paramKey): self
    {
        $this->paramKey = $paramKey;

        return $this;
    }

    public function getParamValue(): ?string
    {
        return $this->paramValue;
    }

    public function setParamValue(?string $paramValue): self
    {
        $this->paramValue = $paramValue;

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


}
