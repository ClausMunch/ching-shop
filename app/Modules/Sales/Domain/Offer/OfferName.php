<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use Illuminate\Contracts\View\View;
use League\Uri\Schemes\Http;

/**
 * Presentation object for the name of an offer.
 */
class OfferName
{
    const LENGTH_SHORT = 10;
    /** @var string */
    private $preSet;

    /** @var Offer */
    private $offer;

    /**
     * @param Offer  $offer
     * @param string $preSet
     */
    public function __construct(Offer $offer, string $preSet = '')
    {
        $this->offer = $offer;
        $this->preSet = $preSet;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->preSet) {
            return htmlentities($this->preSet);
        }

        if ($this->offer->price->amount()) {
            return $this->byPrice();
        }

        if ($this->offer->percentage) {
            return $this->byPercentage();
        }

        return '';
    }

    /**
     * @return View
     */
    public function render(): View
    {
        if (mb_strlen((string) $this) > self::LENGTH_SHORT) {
            return view('sales::offer.name-long', ['name' => $this]);
        }

        return view('sales::offer.name-short', ['name' => $this]);
    }

    /**
     * @return Http
     */
    public function url(): Http
    {
        return $this->offer->url();
    }

    /**
     * @return string
     */
    public function style(): string
    {
        return "background:{$this->offer->colour}";
    }

    /**
     * @return string
     */
    private function byPrice(): string
    {
        if ($this->offer->quantity > 1) {
            return $this->byPriceWithQuantity();
        }

        if ($this->offer->isAbsolute()) {
            return "Sale: {$this->offer->price->intFormatted()}";
        }

        return "{$this->offer->price->intFormatted()} off";
    }

    /**
     * @return string
     */
    private function byPriceWithQuantity(): string
    {
        if ($this->offer->isAbsolute()) {
            return sprintf(
                '%d for %s',
                $this->offer->quantity,
                $this->offer->price->intFormatted()
            );
        }

        return sprintf(
            '%s off when you buy %d',
            $this->offer->price->intFormatted(),
            $this->offer->quantity
        );
    }

    /**
     * @return string
     */
    private function byPercentage(): string
    {
        $saving = $this->offer->percentage;
        if ($this->offer->isAbsolute()) {
            $saving = 100 - $this->offer->percentage;
        }

        if ($this->offer->quantity > 1) {
            return "{$saving}% off when you buy {$this->offer->quantity}";
        }

        return "{$saving}% off";
    }
}
