<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\LinePriced;
use ChingShop\Modules\Sales\Domain\Money;
use Illuminate\Support\Collection;

/**
 * An offer that might be applied.
 */
class PotentialOffer implements LinePriced
{
    /** @var Offer */
    private $offer;

    /** @var Collection|OfferComponent[] */
    private $components;

    /**
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
        $this->components = new Collection();
    }

    /**
     * @param OfferComponent $component
     *
     * @return bool
     */
    public function takeComponent(OfferComponent $component): bool
    {
        // Don't accept if this offer does not apply to the product.
        if (!$this->appliesToProduct($component->product())) {
            return false;
        }

        // Don't accept if this offer is already fulfilled.
        if ($this->requirementsAreMet()) {
            return false;
        }

        $this->components->put($component->id(), $component);

        return true;
    }

    /**
     * @return bool
     */
    public function requirementsAreMet(): bool
    {
        return $this->components->count() >= $this->offer->quantity;
    }

    /**
     * @return Offer
     */
    public function offer(): Offer
    {
        return $this->offer;
    }

    /**
     * The discount amount as a negative price.
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function linePrice(): Money
    {
        if (!$this->requirementsAreMet()) {
            return Money::fromInt(0);
        }

        if ($this->offer->isAbsolute()) {
            return $this->priceFromAbsolute();
        }

        return $this->priceFromRelative();
    }

    /**
     * @return string
     */
    public function listComponents(): string
    {
        $identifiers = $this->components->map(
            function (OfferComponent $component) {
                return $component->product()->sku;
            }
        );

        if ($identifiers->count() > 1) {
            return sprintf(
                '%s and %s',
                $identifiers->slice(0, -1)->implode(', '),
                $identifiers->last()
            );
        }

        return $identifiers->pop();
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return "{$this->offer()->name} on {$this->listComponents()}";
    }

    /**
     * What would the offer components have cost without the offer?
     *
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function originalPrice(): Money
    {
        return $this->components->reduce(
            function (Money $price, OfferComponent $component) {
                return $price->add($component->product()->price()->asMoney());
            },
            Money::fromInt(0)
        );
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    private function priceFromAbsolute(): Money
    {
        if ($this->offer->isPriced()) {
            // Absolute price offer (i.e. fixed price offer).
            return $this->originalPrice()
                ->subtract($this->offer->price)
                ->negative();
        }

        // Absolute percentage offer.
        return $this->originalPrice()
            ->multiply((100 - $this->offer->percentage) / 100)
            ->negative();
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    private function priceFromRelative(): Money
    {
        if ($this->offer->isPriced()) {
            // Relative price offer (i.e. a set discount).
            return $this->offer->price->negative();
        }

        // Relative percentage offer (i.e. a percentage discount).
        return $this->originalPrice()
            ->multiply($this->offer->percentage / 100)
            ->negative();
    }

    /**
     * @param Product $product
     *
     * @return bool
     */
    private function appliesToProduct(Product $product): bool
    {
        return $this->offer->products->contains('id', $product->id);
    }
}
