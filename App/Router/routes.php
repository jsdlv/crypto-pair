<?php

return [
    ['GET', '/', ['App\Controllers\CurrencyController', 'index']],
    ['GET', '/search', ['App\Controllers\CurrencyController', 'show']]
];