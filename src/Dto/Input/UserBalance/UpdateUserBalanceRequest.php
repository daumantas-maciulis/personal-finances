<?php

namespace App\Dto\Input\UserBalance;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserBalanceRequest
{
    /**
     * @Groups("balance_write")
     * @Assert\NotBlank
     */
    private string $title;

    /**
     * @Groups("balance_write")
     * @Assert\Length(max=200)
     */
    private ?string $description = null;

    /**
     * @Groups("balance_write")
     * @Assert\NotBlank
     */
    private float $value;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }


}