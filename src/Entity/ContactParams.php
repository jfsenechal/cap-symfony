<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'contact_params')]
#[ORM\Index(name: 'IDX_1F5C3972E7A1254A', columns: ['contact_id'])]
#[ORM\Entity]
class ContactParams
{
    use IdTrait;

    #[ORM\Column(name: 'param_key', type: 'text', nullable: false)]
    private string $paramKey = '';

    #[ORM\Column(name: 'param_value', type: 'text', nullable: false)]
    private string $paramValue = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'contact_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: Contact::class)]
    private ?Contact $contact = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function setParamValue(string $paramValue): self
    {
        $this->paramValue = $paramValue;

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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
