<?php

namespace ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductOptionRepository.
 */
class ProductOptionRepository
{
    /** @var ProductOption|Builder */
    private $optionResource;

    /**
     * ProductOptionRepository constructor.
     *
     * @param ProductOption $optionResource
     */
    public function __construct(ProductOption $optionResource)
    {
        $this->optionResource = $optionResource;
    }

    /**
     * @param int $optionId
     *
     * @return ProductOption
     */
    public function loadById(int $optionId)
    {
        return $this->optionResource->where('id', '=', $optionId)->first();
    }

    /**
     * @param Product $product
     * @param string  $label
     *
     * @return ProductOption
     */
    public function addOptionForProduct(Product $product, string $label)
    {
        $productOption = new ProductOption(['label' => $label]);

        return $product->options()->save($productOption);
    }
}
