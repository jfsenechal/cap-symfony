<?php

namespace Cap\Commercio\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'payment_bill')]
#[ORM\UniqueConstraint(columns: ['order_id'])]
#[UniqueEntity(fields: ['order'], message: 'Déjà facturé')]
#[ORM\Entity]
class PaymentBill
{
    use IdTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'bill_number', type: 'text', nullable: false)]
    private ?string $billNumber = null;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'price_evat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $priceEvat = null;

    #[ORM\Column(name: 'price_vat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $priceVat = null;

    #[ORM\Column(name: 'vat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $vat = null;

    #[ORM\Column(name: 'vat_amount', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $vatAmount = null;

    #[ORM\Column(name: 'pdf_path', type: 'text', nullable: true)]
    private ?string $pdfPath = null;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'PaymentOrder')]
    private ?PaymentOrder $order = null;

    #[ORM\Column(name: 'wallet_transaction_id', type: 'text', nullable: true)]
    public ?string $walletTransactionId = null;

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

    public function getBillNumber(): ?string
    {
        return $this->billNumber;
    }

    public function setBillNumber(string $billNumber): self
    {
        $this->billNumber = $billNumber;

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

    public function getPriceEvat(): ?float
    {
        return $this->priceEvat;
    }

    public function setPriceEvat(float $priceEvat): self
    {
        $this->priceEvat = $priceEvat;

        return $this;
    }

    public function getPriceVat(): ?float
    {
        return $this->priceVat;
    }

    public function setPriceVat(float $priceVat): self
    {
        $this->priceVat = $priceVat;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getVatAmount(): ?float
    {
        return $this->vatAmount;
    }

    public function setVatAmount(float $vatAmount): self
    {
        $this->vatAmount = $vatAmount;

        return $this;
    }

    public function getPdfPath(): ?string
    {
        return $this->pdfPath;
    }

    public function setPdfPath(?string $pdfPath): self
    {
        $this->pdfPath = $pdfPath;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

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
