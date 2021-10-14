<?php

namespace App\Entity;

use App\Repository\UserBalanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBalanceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class UserBalance
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $previousValue;

    /**
     * @ORM\Column(type="float")
     */
    private $currentValue;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isIncome;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userBalances")
     */
    private $owner;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreviousValue(): ?float
    {
        return $this->previousValue;
    }

    public function setPreviousValue(float $previousValue): self
    {
        $this->previousValue = $previousValue;

        return $this;
    }

    public function getCurrentValue(): ?float
    {
        return $this->currentValue;
    }

    public function setCurrentValue(float $currentValue): self
    {
        $this->currentValue = $currentValue;

        return $this;
    }

    public function getIsIncome(): ?bool
    {
        return $this->isIncome;
    }

    public function setIsIncome(bool $isIncome): self
    {
        $this->isIncome = $isIncome;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateTimestamps(): void
    {
        $this->setDateCreated(new \DateTime('now'));
    }
}
