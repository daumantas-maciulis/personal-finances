<?php

namespace App\Dto\Output\UserBalance;

class UserBalanceReportOutput
{
    private string $id;

    private string $title;

    private ?string $description;

    private float $currentValue;

    private float $transactionValue;

    private bool $isIncome;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

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

    public function getCurrentValue(): float
    {
        return $this->currentValue;
    }

    public function setCurrentValue(float $currentValue): void
    {
        $this->currentValue = $currentValue;
    }

    public function getTransactionValue(): float
    {
        return $this->transactionValue;
    }

    public function setTransactionValue(float $transactionValue): void
    {
        $this->transactionValue = $transactionValue;
    }

    public function isIncome(): bool
    {
        return $this->isIncome;
    }

    public function setIsIncome(bool $isIncome): void
    {
        $this->isIncome = $isIncome;
    }


}