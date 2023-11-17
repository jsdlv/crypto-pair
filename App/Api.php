<?php

declare(strict_types=1);

namespace App;

use App\Models\Currency;
use App\Models\CurrencyCollection;
use GuzzleHttp\Client;

class Api
{
    private Client $client;
    private const EXCHANGE_RATE = "https://api.coinbase.com/v2/exchange-rates";
    private const CURRENCY_NAME = "https://api.coinbase.com/v2/currencies";

    private const CRYPTO_CURRENCY_NAME = "https://api.coinbase.com/v2/currencies/crypto";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getExchangeRates(string $baseCurrency): CurrencyCollection
    {
        $currencies = new CurrencyCollection();

        $response = $this->client->get(self::EXCHANGE_RATE . "?currency=$baseCurrency");
        $response = (string)$response->getBody();
        $response = json_decode($response);

        $rates = $response->data->rates;

        foreach ($rates as $name => $rate) {
            $currency = new Currency($name, $rate);
            $currencies->add($currency);
        }

        return $currencies;
    }

    public function getCurrencyName(string $currencyId): string
    {
        $currencyName = '';

        $currencyResponse = $this->client->get(self::CURRENCY_NAME);
        $currencyResponse = (string)$currencyResponse->getBody();
        $currencyResponse = json_decode($currencyResponse);

        $cryptoResponse = $this->client->get(self::CRYPTO_CURRENCY_NAME);
        $cryptoResponse = (string)$cryptoResponse->getBody();
        $cryptoResponse = json_decode($cryptoResponse);

        foreach ($currencyResponse->data as $currency) {
            if ($currency->id === $currencyId) {
                $currencyName = $currency->name;
            }
        }

        foreach ($cryptoResponse->data as $currency) {
            if ($currency->code === $currencyId) {
                $currencyName = $currency->name;
            }
        }

        return $currencyName;
    }
}