<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

use Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * Class ProductRepository.
 */
class ProductRepository
{
    /** @var Product|Builder */
    private $productResource;

    /**
     * @param Product $productResource
     */
    public function __construct(Product $productResource)
    {
        $this->productResource = $productResource;
    }

    /**
     * @return Generator|Product[]
     */
    public function iterateAll(): Generator
    {
        /** @var Product $product */
        foreach ($this->productResource->with('images')->cursor() as $product) {
            if (!$product->relationLoaded('images')) {
                $product->load('images');
            }
            yield $product;
        }
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function loadLatest()
    {
        return $this->productResource
            ->inStock()
            ->orderBy('updated_at', 'desc')
            ->has('images')
            ->with($this->relations())
            ->paginate();
    }

    /**
     * @param array $productData
     *
     * @return Product
     */
    public function create(array $productData): Product
    {
        return $this->productResource->create($productData);
    }

    /**
     * @param string $sku
     * @param array  $newData
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Product
     */
    public function update(string $sku, array $newData): Product
    {
        $product = $this->productResource->where('sku', $sku)->firstOrFail();
        $product->fill($newData);
        $product->save();

        return $product;
    }

    /**
     * @param string $sku
     *
     * @return Product
     */
    public function loadBySku(string $sku): Product
    {
        $product = $this->productResource
            ->where('sku', $sku)
            ->with($this->relations())
            ->first();

        return $product ?: new Product();
    }

    /**
     * @param int $productId
     *
     * @return Product
     */
    public function loadById(int $productId): Product
    {
        return $this->productResource
            ->where('id', $productId)
            ->with($this->relations())
            ->first();
    }

    /**
     * @param Product $product
     *
     * @return Collection
     */
    public function loadSimilar(Product $product): Collection
    {
        return $this->productResource
            ->search($product->name)
            ->get()
            ->filter(
                function (Product $similarProduct) use ($product) {
                    return $similarProduct->id !== $product->id;
                }
            );
    }

    /**
     * @param string $sku
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deleteBySku(string $sku)
    {
        return (bool) $this->productResource
            ->where('sku', $sku)
            ->limit(1)
            ->first()
            ->delete();
    }

    /**
     * @param string $sku
     * @param int    $units
     * @param int    $subunits
     *
     * @return bool
     */
    public function setPriceBySku(string $sku, int $units, int $subunits)
    {
        /** @var Product $product */
        $product = $this->productResource
            ->where('sku', $sku)
            ->with('prices')
            ->limit(1)
            ->first();

        $price = $product->prices()->firstOrNew([]);
        $price->setAttribute('units', $units);
        $price->setAttribute('subunits', $subunits);
        $price->setAttribute('currency', 'GBP');

        return $price->save();
    }

    /**
     * @return array
     */
    private function relations(): array
    {
        return [
            'images',
            'prices',
            'tags',
            'options' => function (HasMany $query) {
                /** @var ProductOption $query */
                $query->orderBy('position', 'asc');
            },
            'options.availableStock',
        ];
    }
}
