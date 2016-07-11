<?php

namespace ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * Class ProductRepository
 *
 * @package ChingShop\Catalogue\Product
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
     * @param int $limit
     *
     * @return Collection
     */
    public function loadLatest(int $limit = 100): Collection
    {
        return $this->productResource
            ->orderBy('updated_at', 'desc')
            ->has('images')
            ->with($this->relations())
            ->limit($limit)
            ->get();
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
     * @return Product
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
                $query->orderBy('position', 'asc');
            },
        ];
    }
}
