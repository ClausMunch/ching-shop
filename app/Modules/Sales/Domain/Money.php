<?php

namespace ChingShop\Modules\Sales\Domain;

use Money\Currency;
use Money\MoneyFormatter;

/**
 * A monetary amount.
 * Wrapper for money-php.
 */
class Money
{
    /** @var \Money\Money */
    private $money;

    /**
     * @param \Money\Money $money
     */
    public function __construct(\Money\Money $money)
    {
        $this->money = $money;
    }

    /**
     * @param int    $amount
     * @param string $currency
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function fromInt(
        int $amount,
        string $currency = 'GBP'
    ): Money {
        return new self(
            new \Money\Money($amount, new Currency($currency))
        );
    }

    /**
     * @param float  $amount
     *
     * @param string $currency
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function fromDecimal(
        float $amount,
        string $currency = 'GBP'
    ): Money {
        return new self(
            new \Money\Money((int) ($amount * 100), new Currency($currency))
        );
    }

    /**
     * @param int    $units
     * @param int    $subunits
     * @param string $currency
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function fromSplit(
        int $units,
        int $subunits,
        string $currency = 'GBP'
    ): Money {
        return self::fromInt((int) (($units * 100) + $subunits), $currency);
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return (int) $this->money->getAmount();
    }

    /**
     * @param Money $amount
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function add(Money $amount): Money
    {
        return new self($this->money->add($amount->wrapped()));
    }

    /**
     * @param Money $amount
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function subtract(Money $amount): Money
    {
        return new self($this->money->subtract($amount->wrapped()));
    }

    /**
     * @param float $multiplier
     *
     * @return Money
     */
    public function multiply(float $multiplier): Money
    {
        return new self($this->money->multiply($multiplier));
    }

    /**
     * @return Money
     */
    public function negative(): Money
    {
        return new self($this->money->multiply(-1));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->formatted();
    }

    /**
     * @return string
     */
    public function formatted(): string
    {
        return app(MoneyFormatter::class)->format($this->money);
    }

    /**
     * @return string
     */
    public function intFormatted(): string
    {
        return str_replace_last('.00', '', $this->formatted());
    }

    /**
     * @return float
     */
    public function asFloat(): float
    {
        return (float) ($this->money->getAmount() / 100);
    }

    /**
     * @return \Money\Money
     */
    public function wrapped(): \Money\Money
    {
        return $this->money;
    }
}
