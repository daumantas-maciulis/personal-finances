<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct()
    {
        $strings = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = substr(str_shuffle($strings), 0, 50);
        $this->setConfirmationHash($hash);
        $this->expenses = new ArrayCollection();
        $this->userBalances = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(max=64)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(max=64)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $cratedAt;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $activated = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $confirmationHash;

    /**
     * @ORM\OneToMany(targetEntity=Expenses::class, mappedBy="owner")
     */
    private $expenses;

    /**
     * @ORM\OneToMany(targetEntity=UserBalance::class, mappedBy="owner")
     */
    private $userBalances;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCratedAt(): ?\DateTimeImmutable
    {
        return $this->cratedAt;
    }

    public function setCratedAt(\DateTimeImmutable $cratedAt): void
    {
        $this->cratedAt = $cratedAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable('now'));
        if($this->getCratedAt() === null) {
            $this->setCratedAt(new \DateTimeImmutable('now'));
        }
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(?bool $activated): void
    {
        $this->activated = $activated;
    }

    public function getConfirmationHash(): string
    {
        return $this->confirmationHash;
    }

    public function setConfirmationHash(string $confirmationHash): void
    {
        $this->confirmationHash = $confirmationHash;
    }

    /**
     * @return Collection|Expenses[]
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expenses $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
            $expense->setOwner($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getOwner() === $this) {
                $expense->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserBalance[]
     */
    public function getUserBalances(): Collection
    {
        return $this->userBalances;
    }

    public function addUserBalance(UserBalance $userBalance): self
    {
        if (!$this->userBalances->contains($userBalance)) {
            $this->userBalances[] = $userBalance;
            $userBalance->setOwner($this);
        }

        return $this;
    }

    public function removeUserBalance(UserBalance $userBalance): self
    {
        if ($this->userBalances->removeElement($userBalance)) {
            // set the owning side to null (unless already changed)
            if ($userBalance->getOwner() === $this) {
                $userBalance->setOwner(null);
            }
        }

        return $this;
    }
}

