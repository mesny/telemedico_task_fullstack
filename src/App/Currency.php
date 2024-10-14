<?php declare(strict_types=1);

namespace App;

use RuntimeException;
use InvalidArgumentException as InvalidArg;

class Currency
{
    public const USD = 'USD';
    public const EUR = 'EUR';
    public const PLN = 'PLN';
    public const CZK = 'CZK';
    public const IDR = 'IDR';
    public const BRL = 'BRL';

    public static function isRegistered(string $currency): bool
    {
        // warning prior to PHP 8.0 if not defined, no need for try-catch
        return is_string(constant('self::' . strtoupper($currency)));
    }

    public static function validateFloatValues(Currency $self): void
    {
        // we only accept values rounded to 2 digits after the comma,
        // and don't want to silently round the digits under the hood
        $filtered = array_filter([$self->bid, $self->ask]);
        if (empty($filtered))
            throw new InvalidArg("At least one must be present, for currency: ".$self->code);

        foreach ($filtered as $val) {
            if ($val !== (float) number_format($val, 2, '.', ''))
                throw new InvalidArg('Invalid value: (' . $val . ') for currency: '.$self->code);
        }
    }

    // factory method
    public static function create(string $code, float $bid = null, float $ask = null): Currency
    {
        if (!self::isRegistered($code = strtoupper($code)))
            throw new RuntimeException("Unknown currency '$code'.");

        $currency = new Currency();
        $currency->code = $code;
        $currency->bid = $bid;
        $currency->ask = $ask;

        self::validateFloatValues($currency);
        return $currency;
    }

    protected $code;
    protected $bid;
    protected $ask;

    private function __construct()
    {}

    public function getAskPrice(float $base) : string
    {
        if (!($base > 0) or ($base))
            if (!$this->isAskOffered())
                throw new RuntimeException($this->code.' is not being sold');
        return number_format($this->ask + $base, 4, '.', '');
    }

    public function getBidPrice(float $base) : string
    {
        if (!($base > 0) or ($base))
            if (!$this->isBidOffered())
                throw new RuntimeException($this->code.' is not being sold');
        return number_format($this->bid + $base, 4, '.', '');
    }

    public function isBidOffered() : bool
    {
        return $this->bid !== null;
    }

    public function isAskOffered() : bool
    {
        return $this->ask !== null;
    }

    public function getAskMargin()
    {
        return $this->ask;
    }

    public function setBidMargin()
    {
        return $this->bid;
    }


}