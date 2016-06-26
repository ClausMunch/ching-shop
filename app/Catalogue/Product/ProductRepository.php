<?php

namespace ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

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
    public function loadLatest($limit = 100): Collection
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
        $newProduct = $this->productResource->create($productData);

        return $newProduct;
    }

    /**
     * @param string $sku
     * @param array  $newData
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

        return $product ? $product : new Product();
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function loadById(int $id): Product
    {
        return $this->productResource
            ->where('id', $id)
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
