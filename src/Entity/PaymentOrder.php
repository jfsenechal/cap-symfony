<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentOrder
 */
#[ORM\Table(name: 'payment_order')]
#[ORM\Index(name: 'IDX_A260A52A2E992502', columns: ['order_commercant_id'])]
#[ORM\Index(name: 'IDX_A260A52AD7707B45', columns: ['order_status_id'])]
#[ORM\Entity]
class PaymentOrder
{
    public ?PaymentBill $bill = null;

    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'payment_order_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'commercant_id', type: 'bigint', nullable: false)]
    private ?string $commercantId = null;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'promo_code_id', type: 'bigint', nullable: true)]
    private ?string $promoCodeId = null;

    #[ORM\Column(name: 'order_number', type: 'text', nullable: false)]
    private ?string $orderNumber = null;

    #[ORM\Column(name: 'price_evat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $priceEvat = null;

    #[ORM\Column(name: 'price_vat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $priceVat = null;

    #[ORM\Column(name: 'vat', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $vat = null;

    #[ORM\Column(name: 'vat_amount', type: 'float', precision: 10, scale: 0, nullable: false)]
    private ?float $vatAmount = null;

    #[ORM\Column(name: 'is_paid', type: 'boolean', nullable: false)]
    private ?bool $isPaid = null;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'payment_ref', type: 'text', nullable: true)]
    private ?string $paymentRef = null;

    #[ORM\Column(name: 'pdf_path', type: 'text', nullable: true)]
    private ?string $pdfPath = null;

    #[ORM\JoinColumn(name: 'order_commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'PaymentOrderCommercant')]
    private ?PaymentOrderCommercant $orderCommercant = null;

    #[ORM\JoinColumn(name: 'order_status_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'PaymentOrderStatus')]
    private ?PaymentOrderStatus $orderStatus = null;

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

    public function getCommercantId(): ?string
    {
        return $this->commercantId;
    }

    public function setCommercantId(string $commercantId): self
    {
        $this->commercantId = $commercantId;

        return $this;
    }

    public function getPromoCodeId(): ?string
    {
        return $this->promoCodeId;
    }

    public function setPromoCodeId(?string $promoCodeId): self
    {
        $this->promoCodeId = $promoCodeId;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

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

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

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

    public function getPaymentRef(): ?string
    {
        return $this->paymentRef;
    }

    public function setPaymentRef(?string $paymentRef): self
    {
        $this->paymentRef = $paymentRef;

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

    public function getOrderCommercant(): ?PaymentOrderCommercant
    {
        return $this->orderCommercant;
    }

    public function setOrderCommercant(?PaymentOrderCommercant $orderCommercant): self
    {
        $this->orderCommercant = $orderCommercant;

        return $this;
    }

    public function getOrderStatus(): ?PaymentOrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?PaymentOrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }


}
