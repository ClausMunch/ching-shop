<?php

namespace ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection
     */
    public function loadLatest($limit = 100): Collection
    {
        return $this->productResource
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function presentLatest($limit = 100): array
    {
        return array_map(function (Product $product): ProductPresenter {
            return new ProductPresenter($product);
        }, $this->loadLatest($limit)->all());
    }

    /**
     * @return ProductPresenter
     */
    public function presentEmpty(): ProductPresenter
    {
        return new ProductPresenter(new Product);
    }

    /**
     * @param array $productData
     * @return Product
     */
    public function create(array $productData): Product
    {
        $newProduct = $this->productResource->create($productData);
        $newProduct->save();
        return $newProduct;
    }

    /**
     * @param string $sku
     * @param array $newData
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
     * @return ProductPresenter
     */
    public function presentBySKU(string $sku): ProductPresenter
    {
        /** @var Product $product */
        $product = $this->productResource->where('sku', $sku)->first();
        if (!$product) {
            return $this->presentEmpty();
        }
        return new ProductPresenter($product);
    }
}