<?php

namespace Cap\Commercio\Entity;

use Stringable;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 */
#[ORM\Table(name: 'blog_post')]
#[ORM\Index(name: 'IDX_BA5AE01D82F1BAF4', columns: ['language_id'])]
#[ORM\Index(name: 'IDX_BA5AE01DF675F31B', columns: ['author_id'])]
#[ORM\Entity]
class BlogPost implements Stringable
{
    use TagTrait,CategoriesTrait;

    
    #[ORM\Column(name: 'id', type: 'bigint', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'blog_post_id_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false, options: ['default' => 'uuid_generate_v4()'])]
    private string $uuid = '';

    #[ORM\Column(name: 'title', type: 'string', length: 150, nullable: false)]
    private string $title = '';

    #[ORM\Column(name: 'summary', type: 'string', length: 255, nullable: false)]
    private string $summary = '';

    #[ORM\Column(name: 'post_text', type: 'text', nullable: false)]
    private string $postText = '';

    
    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    
    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'archived', type: 'boolean', nullable: false)]
    private bool $archived = false;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(name: 'publish_date', type: 'datetime', nullable: true, options: ['default' => 'now()'])]
    private $publishDate = 'now()';

    #[ORM\Column(name: 'is_online', type: 'boolean', nullable: false)]
    private bool $isOnline = false;

    #[ORM\Column(name: 'url_id', type: 'text', nullable: false)]
    private string $urlId = '';

    #[ORM\Column(name: 'media_path', type: 'text', nullable: true)]
    private ?string $mediaPath = '';

    #[ORM\Column(name: 'first_publication', type: 'boolean', nullable: false, options: ['default' => '1'])]
    private bool $firstPublication = true;

    #[ORM\JoinColumn(name: 'language_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Language')]
    private ?Language $language = null;

    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'BlogAuthor')]
    private ?BlogAuthor $author = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPostText(): ?string
    {
        return $this->postText;
    }

    public function setPostText(string $postText): self
    {
        $this->postText = $postText;

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

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getPublishDate(): ?DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(?DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function isIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function getUrlId(): ?string
    {
        return $this->urlId;
    }

    public function setUrlId(string $urlId): self
    {
        $this->urlId = $urlId;

        return $this;
    }

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(?string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    public function isFirstPublication(): ?bool
    {
        return $this->firstPublication;
    }

    public function setFirstPublication(bool $firstPublication): self
    {
        $this->firstPublication = $firstPublication;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getAuthor(): ?BlogAuthor
    {
        return $this->author;
    }

    public function setAuthor(?BlogAuthor $author): self
    {
        $this->author = $author;

        return $this;
    }


}
