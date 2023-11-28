<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Api;
use App\Response;

class CurrencyController
{
    private Api $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    public function index(): Response
    {
        /**
         *  Place to define base currency if it is not provided
         */
        $baseCurrency = $_GET['baseCurrency'] ?? 'EUR';

        $allCurrencies = $this->api->getExchangeRates($baseCurrency);

        /**
         * Here is place to add or remove currencies in main page
         */
        $currenciesToShow = ['BTC', 'ETH', 'XRP'];

        $currencies = [];

        foreach ($allCurrencies->currencies() as $currency) {
            foreach ($currenciesToShow as $item) {
                if ($currency->getName() === $item) {
                    $current = [
                        'id' => $currency->getName(),
                        'name' => $this->api->getCurrencyName($currency->getName()),
                        'rate' => number_format(1/(float)$currency->getRate(), 2)
                    ];

                    $currencies[] = $current;
                }
            }
        }

        return new Response('index', [
            'currencies' => $currencies,
            'baseCurrency' => $baseCurrency
            ]);
    }

    public function show(): Response
    {
        $currencyPairs = trim($_GET['currencyPair']);

        $currencies = preg_split('/[\s,]+/', $currencyPairs);

        //var_dump((int)$currencies[1]);die;

        if (count($currencies) < 2 || (is_numeric($currencies[0]) || is_numeric($currencies[1]))) {
            return new Response('show', [
                'error' => 'Please provide two currencies separated by space or comma'
            ]);
        }

        $currencyToSearch = strtoupper($currencies[0]);
        $baseCurrency = strtoupper($currencies[1]);

        $allCurrencies = $this->api->getExchangeRates($baseCurrency);

        $current = [];

        foreach ($allCurrencies->currencies() as $currency) {
            if ($currency->getName() === $currencyToSearch) {
                $current = [
                    'id' => $currency->getName(),
                    'name' => $this->api->getCurrencyName($currency->getName()),
                    'rate' => number_format(1/(float)$currency->getRate(), 2)
                ];
            }
        }

        return new Response('show', [
            'currency' => $current,
            'baseCurrency' => $baseCurrency,
            ]);
    }
}