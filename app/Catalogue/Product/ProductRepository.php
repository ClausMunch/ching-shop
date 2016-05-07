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
     *
     * @return Collection
     */
    public function loadLatest($limit = 100): Collection
    {
        return $this->productResource
            ->orderBy('updated_at', 'desc')
            ->has('images')
            ->with(['images', 'tags'])
            ->limit($limit)
            ->get();
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function presentLatest($limit = 100): array
    {
        return array_map(
            function (Product $product) : ProductPresenter {
                return $this->presentProduct($product);
            },
            $this->loadLatest($limit)->all()
        );
    }

    /**
     * @return ProductPresenter
     */
    public function presentEmpty(): ProductPresenter
    {
        return new ProductPresenter(new Product());
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
     * @return ProductPresenter
     */
    public function presentBySku(string $sku): ProductPresenter
    {
        /** @var Product $product */
        $product = $this->productResource
            ->where('sku', $sku)
            ->with(['images', 'prices', 'tags'])
            ->limit(1)
            ->first();

        if (!$product) {
            return $this->presentEmpty();
        }

        return $this->presentProduct($product);
    }

    /**
     * @param int $id
     *
     * @return ProductPresenter
     */
    public function presentById(int $id): ProductPresenter
    {
        /** @var Product $product */
        $product = $this->productResource
            ->where('id', $id)
            ->with(['images', 'prices', 'tags'])
            ->limit(1)
            ->first();

        if (!$product) {
            return $this->presentEmpty();
        }

        return $this->presentProduct($product);
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function mustLoadById(int $id): Product
    {
        return $this->productResource
            ->where('id', $id)
            ->with(['images', 'prices', 'tags'])
            ->limit(1)
            ->first();
    }

    /**
     * @param string $sku
     *
     * @return Product
     */
    public function mustLoadBySku(string $sku): Product
    {
        return $this->productResource
            ->where('sku', $sku)
            ->with(['images', 'prices', 'tags'])
            ->limit(1)
            ->first();
    }

    /**
     * @param string $sku
     *
     * @throws \Exception
     *
     * @return bool|null
     */
    public function deleteBySku(string $sku)
    {
        return $this->productResource
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
     * @param int   $productId
     * @param array $imageOrder
     *
     * @return bool
     */
    public function updateImageOrder(int $productId, array $imageOrder): bool
    {
        /** @var Product $product */
        $product = $this->productResource
            ->where('id', '=', $productId)
            ->with('images')
            ->limit(1)
            ->first();

        foreach ($product->images as $image) {
            if (!array_key_exists($image->id, $imageOrder)) {
                continue;
            }
            $product->images()->updateExistingPivot(
                $image->id,
                ['position' => $imageOrder[$image->id]]
            );
        }

        return true;
    }

    /**
     * @param $product
     *
     * @return ProductPresenter
     */
    private function presentProduct($product)
    {
        return new ProductPresenter($product);
    }
}
