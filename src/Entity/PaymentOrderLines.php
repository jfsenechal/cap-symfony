<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'payment_order_lines')]
#[ORM\Index(name: 'IDX_3A1328B18D9F6D38', columns: ['order_id'])]
#[ORM\Entity]
class PaymentOrderLines
{
    use IdTrait;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'reference', type: 'text', nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(name: 'label', type: 'text', nullable: false)]
    private ?string $label = null;

    #[ORM\Column(name: 'price_evat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $priceEvat = null;

    #[ORM\Column(name: 'total_price_evat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $totalPriceEvat = null;

    #[ORM\Column(name: 'quantity', type: 'integer', nullable: false)]
    private ?int $quantity = null;

    #[ORM\Column(name: 'quantity_label', type: 'text', nullable: false)]
    private ?string $quantityLabel = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'PaymentOrder')]
    private ?PaymentOrder $order = null;

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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getTotalPriceEvat(): ?float
    {
        return $this->totalPriceEvat;
    }

    public function setTotalPriceEvat(float $totalPriceEvat): self
    {
        $this->totalPriceEvat = $totalPriceEvat;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityLabel(): ?string
    {
        return $this->quantityLabel;
    }

    public function setQuantityLabel(string $quantityLabel): self
    {
        $this->quantityLabel = $quantityLabel;

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
