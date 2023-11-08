<?php

namespace Cap\Commercio\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: 'right_access')]
#[ORM\UniqueConstraint(name: 'right_access_email_key', columns: ['email'])]
#[ORM\Entity]
class RightAccess implements UserInterface, PasswordHasherAwareInterface, PasswordAuthenticatedUserInterface, Stringable
{
    use IdTrait,UuidTrait;

    #[ORM\Column(name: 'email', type: 'string', length: 200, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(name: 'password', type: 'string', length: 100, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'privilege_id', type: 'smallint', nullable: false, options: ['default' => '1'])]
    private int $privilegeId = 1;

    #[ORM\Column(name: 'insert_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $insertDate;

    #[ORM\Column(name: 'modify_date', type: 'datetime', nullable: false, options: ['default' => 'now()'])]
    private \DateTimeInterface $modifyDate;

    #[ORM\Column(name: 'uuid', type: 'guid', nullable: false)]
    private string $uuid = '';

    #[ORM\Column(name: 'first_time', type: 'boolean', nullable: false)]
    private bool $firstTime = false;

    private array $roles = ['ROLE_CAP'];

    public ?string $password_plain = null;

    public function __toString(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPrivilegeId(): ?int
    {
        return $this->privilegeId;
    }

    public function setPrivilegeId(int $privilegeId): self
    {
        $this->privilegeId = $privilegeId;

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

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function isFirstTime(): ?bool
    {
        return $this->firstTime;
    }

    public function setFirstTime(bool $firstTime): self
    {
        $this->firstTime = $firstTime;

        return $this;
    }

    public function getPasswordHasherName(): ?string
    {
        return 'cap_hasher';
    }
}
