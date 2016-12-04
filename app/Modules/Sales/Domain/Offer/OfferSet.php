<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Support\Collection;

/**
 * A set of fulfilled offers.
 */
class OfferSet
{
    /** @var Collection|OfferComponent[] */
    private $components;

    /** @var Collection|PotentialOffer[] */
    private $collection;

    /**
     * Offers constructor.
     *
     * @param Collection $components
     */
    public function __construct(Collection $components)
    {
        $this->components = $components;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Collection|PotentialOffer[]
     */
    public function collection(): Collection
    {
        if ($this->collection === null) {
            $this->collection = $this->collect();
        }

        return $this->collection;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Collection|PotentialOffer[]
     */
    private function collect(): Collection
    {
        /*
         * Create buckets for each offer, keeping duplicate offers.
         * Assign each product to the first applicable bucket with spaces left.
         * Filter the buckets to those that are full.
         */
        return $this->components
            // Sort to prefer cheaper products in offers.
            ->sort(
                function (OfferComponent $a, OfferComponent $b) {
                    $priceA = $a->product()->price()->asFloat();
                    $priceB = $b->product()->price()->asFloat();

                    return $priceA <=> $priceB;
                }
            )
            // Assign each product to a potential offer bucket.
            ->reduce(
                function (Collection $potentials, OfferComponent $component) {
                    $potentials->first(
                        function (PotentialOffer $potential) use ($component) {
                            return $potential->takeComponent($component);
                        }
                    );

                    return $potentials;
                },
                $this->potentials()
            )
            // Filter to fulfilled offers.
            ->filter(
                function (PotentialOffer $offer) {
                    return $offer->requirementsAreMet();
                }
            )
            // Order offers in ascending discount amount.
            ->sort(
                function (PotentialOffer $offer) {
                    return $offer->linePrice()->negative()->amount();
                }
            );
    }

    /**
     * Buckets for each potential offer, keeping duplicate offers.
     *
     * @throws \InvalidArgumentException
     *
     * @return Collection|PotentialOffer[]
     */
    private function potentials(): Collection
    {
        return $this->components
            ->map(
                function (OfferComponent $component) {
                    return $component->product();
                }
            )
            ->filter(
                function (Product $product) {
                    return $product->offers->count();
                }
            )
            ->reduce(
                function (Collection $potentials, Product $product) {
                    return $potentials->push(
                        new PotentialOffer($product->offers->first())
                    );
                },
                new Collection()
            );
    }
}
