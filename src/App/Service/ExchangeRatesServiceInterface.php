<?php declare(strict_types=1);

namespace App\Service;

use App\Currency;
use \DateTime;

interface ExchangeRatesServiceInterface
{
    public function getRatesForDate(DateTime $date);
    public function getCurrency(string $currencyCode) : Currency;
    public function ifWeSell(string $currencyCode) : bool;
    public function ifWeBuy(string $currencyCode) : bool;
    public function isCurrencyActive(string $currencyCode) : bool;
}
