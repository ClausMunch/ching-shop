<?php

namespace ChingShop\Modules\Sales\Domain;

use Money\Currency;
use Money\MoneyFormatter;

/**
 * Convenience wrapper for money-php.
 */
class Money
{
    /** @var \Money\Money */
    private $money;

    /** @var Currency */
    private $currency;

    /**
     * @param int    $amount   in sub-units
     * @param string $currency
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $amount, string $currency = 'GBP')
    {
        $this->currency = new Currency($currency);
        $this->money = new \Money\Money($amount, $this->currency);
    }

    /**
     * @param float $amount
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public static function fromDecimal(float $amount): self
    {
        return new self((int) ($amount * 100));
    }

    /**
     * @param int $units
     * @param int $subunits
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public static function fromSplit(int $units, int $subunits): self
    {
        return new self((int) (($units * 100) + $subunits));
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return (int) $this->money->getAmount();
    }

    /**
     * @param int $amount
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function add(int $amount): self
    {
        return new self(
            (int) $this->money->getAmount() + $amount,
            $this->currency->getCode()
        );
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
        return (float) $this->money->getAmount() / 100;
    }
}
