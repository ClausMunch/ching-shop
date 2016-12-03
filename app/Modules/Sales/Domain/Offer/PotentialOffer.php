<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Money;
use Illuminate\Support\Collection;

/**
 * An offer that might be applied.
 */
class PotentialOffer
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
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function price(): Money
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
     * What would the offer components have cost without the offer?
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    private function originalPrice(): Money
    {
        return $this->components->reduce(
            function (Money $price, OfferComponent $component) {
                return $price->add($component->product()->price()->asMoney());
            },
            Money::fromInt(0)
        );
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
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
     * @return Money
     * @throws \InvalidArgumentException
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
