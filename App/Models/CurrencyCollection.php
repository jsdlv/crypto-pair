<?php

declare(strict_types=1);

namespace App\Models;

class CurrencyCollection
{
    private array $currencies;

    public function __construct()
    {
        $this->currencies = [];
    }

    public function add(Currency $currency): void
    {
        $this->currencies[] = $currency;
    }

    public function currencies(): array
    {
        return $this->currencies;
    }
}