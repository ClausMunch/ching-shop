<?php

namespace ChingShop\Catalogue;

use ChingShop\Catalogue\Attribute\AttributeRepository;
use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductOption;
use ChingShop\Catalogue\Product\ProductOptionRepository;
use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Catalogue\Tag\TagRepository;
use ChingShop\Image\Image;
use ChingShop\Image\ImageOwner;
use ChingShop\Image\ImageRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

/**
 * Class CatalogueRepository
 *
 * @package ChingShop\Catalogue
 *
 * Facade access to common catalogue i/o operations.
 */
class CatalogueRepository
{
    /** @var AttributeRepository */
    private $attributeRepository;

    /** @var ProductRepository */
    private $productRepository;

    /** @var TagRepository */
    private $tagRepository;

    /** @var ImageRepository */
    private $imageRepository;

    /** @var ProductOptionRepository */
    private $optionRepository;

    /**
     * CatalogueRepository constructor.
     *
     * @param AttributeRepository     $attributeRepository
     * @param ProductRepository       $productRepository
     * @param TagRepository           $tagRepository
     * @param ImageRepository         $imageRepository
     * @param ProductOptionRepository $optionRepository
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        TagRepository $tagRepository,
        ImageRepository $imageRepository,
        ProductOptionRepository $optionRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->productRepository = $productRepository;
        $this->tagRepository = $tagRepository;
        $this->imageRepository = $imageRepository;
        $this->optionRepository = $optionRepository;
    }

    /**
     * @param int $limit
     *
     * @return Collection|Product[]
     */
    public function loadLatestProducts(int $limit = 100): Collection
    {
        return $this->productRepository->loadLatest($limit);
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function loadProductById(int $id): Product
    {
        return $this->productRepository->loadById($id);
    }

    /**
     * @param string $sku
     *
     * @return Product
     */
    public function loadProductBySku(string $sku): Product
    {
        return $this->productRepository->loadBySku($sku);
    }

    /**
     * @param array $productData
     *
     * @return Product
     */
    public function createProduct(array $productData): Product
    {
        return $this->productRepository->create($productData);
    }

    /**
     * @param string $sku
     * @param array  $productData
     *
     * @return Product
     */
    public function updateProduct(string $sku, array $productData): Product
    {
        return $this->productRepository->update($sku, $productData);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function deleteProductBySku(string $sku)
    {
        return $this->productRepository->deleteBySku($sku);
    }

    /**
     * @return Tag\Tag[]|Collection
     */
    public function loadAllTags(): Collection
    {
        return $this->tagRepository->loadAll();
    }

    /**
     * @param int $id
     *
     * @return Image
     */
    public function loadImageById(int $id): Image
    {
        return $this->imageRepository->loadById($id);
    }

    /**
     * @param Image      $image
     * @param ImageOwner $owner
     *
     * @return int
     */
    public function detachImageFromOwner(Image $image, ImageOwner $owner)
    {
        return $owner->images()->detach($image->id);
    }

    /**
     * @param ImageOwner $owner
     * @param array      $imageOrder
     */
    public function updateImageOrder(ImageOwner $owner, array $imageOrder)
    {
        $owner->images()->sync(array_keys($imageOrder));

        foreach ($owner->imageCollection() as $image) {
            if (!array_key_exists($image->id, $imageOrder)) {
                continue;
            }
            $owner->images()->updateExistingPivot(
                $image->id,
                ['position' => $imageOrder[$image->id]]
            );
        }
    }

    /**
     * @param FileBag|UploadedFile[] $images
     * @param Product                $product
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function attachUploadedImagesToProduct($images, Product $product)
    {
        $product->attachImages(
            array_map(
                function (UploadedFile $image) {
                    return $this->imageRepository
                        ->storeUploadedImage($image)
                        ->id;
                },
                $images instanceof FileBag ? $images->all() : (array) $images
            )
        );
    }

    /**
     * @return Attribute\Colour[]|Collection
     */
    public function loadAllColours()
    {
        return $this->attributeRepository->loadAllColours();
    }

    /**
     * @param Product $product
     * @param string  $label
     *
     * @return ProductOption
     */
    public function addOptionForProduct(Product $product, string $label)
    {
        return $this->optionRepository->addOptionForProduct($product, $label);
    }

    /**
     * @param int $optionId
     *
     * @return ProductOption
     */
    public function loadOptionById(int $optionId): ProductOption
    {
        return $this->optionRepository->loadById($optionId);
    }
}
