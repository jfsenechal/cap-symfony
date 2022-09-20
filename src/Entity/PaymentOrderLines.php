<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentOrderLines
 *
 * @ORM\Table(name="payment_order_lines", indexes={@ORM\Index(name="IDX_3A1328B18D9F6D38", columns={"order_id"})})
 * @ORM\Entity
 */
class PaymentOrderLines
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="payment_order_lines_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference", type="text", nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", nullable=false)
     */
    private $label;

    /**
     * @var float
     *
     * @ORM\Column(name="price_evat", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceEvat;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price_evat", type="float", precision=10, scale=0, nullable=false)
     */
    private $totalPriceEvat;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity_label", type="text", nullable=false)
     */
    private $quantityLabel;

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
     * @var \PaymentOrder
     *
     * @ORM\ManyToOne(targetEntity="PaymentOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

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
