<?php

namespace Cap\Commercio\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacebookPromoMessage
 *
 * @ORM\Table(name="facebook_promo_message", uniqueConstraints={@ORM\UniqueConstraint(name="facebook_promo_message_uuid_key", columns={"uuid"})}, indexes={@ORM\Index(name="IDX_D6A648EA77FBEAF", columns={"blog_post_id"}), @ORM\Index(name="IDX_D6A648E83FA6DD0", columns={"commercant_id"}), @ORM\Index(name="IDX_D6A648E4BD166F5", columns={"commercio_administrator_id"}), @ORM\Index(name="IDX_D6A648E71F7E88B", columns={"event_id"})})
 * @ORM\Entity
 */
class FacebookPromoMessage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="facebook_promo_message_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, options={"default"="uuid_generate_v4()"})
     */
    private $uuid = 'uuid_generate_v4()';

    /**
     * @var string
     *
     * @ORM\Column(name="message_text", type="text", nullable=false)
     */
    private $messageText;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime", nullable=false)
     */
    private $postDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_posted", type="boolean", nullable=false)
     */
    private $isPosted = false;

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
     * @var \BlogPost
     *
     * @ORM\ManyToOne(targetEntity="BlogPost")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog_post_id", referencedColumnName="id")
     * })
     */
    private $blogPost;

    /**
     * @var \CommercioCommercant
     *
     * @ORM\ManyToOne(targetEntity="CommercioCommercant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercant_id", referencedColumnName="id")
     * })
     */
    private $commercant;

    /**
     * @var \CommercioAdministrator
     *
     * @ORM\ManyToOne(targetEntity="CommercioAdministrator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commercio_administrator_id", referencedColumnName="id")
     * })
     */
    private $commercioAdministrator;

    /**
     * @var \EventEvent
     *
     * @ORM\ManyToOne(targetEntity="EventEvent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;

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

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->postDate;
    }

    public function setPostDate(\DateTimeInterface $postDate): self
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
