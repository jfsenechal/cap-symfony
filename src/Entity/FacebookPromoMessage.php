<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookPromoMessage
 */
#[ORM\Table(name: 'facebook_promo_message')]
#[ORM\Index(name: 'IDX_D6A648EA77FBEAF', columns: ['blog_post_id'])]
#[ORM\Index(name: 'IDX_D6A648E83FA6DD0', columns: ['commercant_id'])]
#[ORM\Index(name: 'IDX_D6A648E4BD166F5', columns: ['commercio_administrator_id'])]
#[ORM\Index(name: 'IDX_D6A648E71F7E88B', columns: ['event_id'])]
#[ORM\UniqueConstraint(name: 'facebook_promo_message_uuid_key', columns: ['uuid'])]
#[ORM\Entity]
class FacebookPromoMessage
{
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'facebook_promo_message_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'message_text', type: 'text', nullable: false)]
    private ?string $messageText = null;

    #[ORM\Column(name: 'post_date', type: 'datetime', nullable: false)]
    private ?DateTimeInterface $postDate = null;

    #[ORM\Column(name: 'is_posted', type: 'boolean', nullable: false)]
    private bool $isPosted = false;

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\JoinColumn(name: 'blog_post_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'BlogPost')]
    private ?BlogPost $blogPost = null;

    #[ORM\JoinColumn(name: 'commercant_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioCommercant')]
    private ?CommercioCommercant $commercant = null;

    #[ORM\JoinColumn(name: 'commercio_administrator_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'CommercioAdministrator')]
    private ?CommercioAdministrator $commercioAdministrator = null;

    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'EventEvent')]
    private ?EventEvent $event = null;

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

    public function getMessageText(): ?string
    {
        return $this->messageText;
    }

    public function setMessageText(string $messageText): self
    {
        $this->messageText = $messageText;

        return $this;
    }

    public function getPostDate(): ?DateTimeInterface
    {
        return $this->postDate;
    }

    public function setPostDate(DateTimeInterface $postDate): self
    {
        $this->postDate = $postDate;

        return $this;
    }

    public function isIsPosted(): ?bool
    {
        return $this->isPosted;
    }

    public function setIsPosted(bool $isPosted): self
    {
        $this->isPosted = $isPosted;

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

    public function getBlogPost(): ?BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(?BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

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

    public function getEvent(): ?EventEvent
    {
        return $this->event;
    }

    public function setEvent(?EventEvent $event): self
    {
        $this->event = $event;

        return $this;
    }
}
