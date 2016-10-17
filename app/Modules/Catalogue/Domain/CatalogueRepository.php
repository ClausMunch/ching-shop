<?php

namespace ChingShop\Modules\Catalogue\Domain;

use ChingShop\Image\Image;
use ChingShop\Image\ImageOwner;
use ChingShop\Image\ImageRepository;
use ChingShop\Modules\Catalogue\Domain\Attribute\AttributeRepository;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOptionRepository;
use ChingShop\Modules\Catalogue\Domain\Product\ProductRepository;
use ChingShop\Modules\Catalogue\Domain\Tag\TagRepository;
use Generator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

/**
 * A facade for managing the persistence of various catalogue models.
 *
 * Class CatalogueRepository.
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
     * @return Generator|Product[]
     */
    public function iterateAllProducts(): Generator
    {
        foreach ($this->productRepository->iterateAll() as $product) {
            yield $product;
        }
    }

    /**
     * @return Collection|LengthAwarePaginator|Product[]
     */
    public function loadInStockProducts()
    {
        return $this->productRepository->loadInStock();
    }

    /**
     * @return Collection|LengthAwarePaginator|Product[]
     */
    public function loadLatestProducts()
    {
        return $this->productRepository->loadLatest();
    }

    /**
     * @param int $productId
     *
     * @return Product
     */
    public function loadProductById(int $productId): Product
    {
        return $this->productRepository->loadById($productId);
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
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * @throws \Exception
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
     * @param int $imageId
     *
     * @return Image
     */
    public function loadImageById(int $imageId): Image
    {
        return $this->imageRepository->loadById($imageId);
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
