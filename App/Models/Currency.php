<?php

namespace App\Models;

class Currency
{
    private string $name;
    private string $rate;

    public function __construct(string $name, string $rate)
    {
        $this->name = $name;
        $this->rate = $rate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRate(): string
    {
        return $this->rate;
    }
}