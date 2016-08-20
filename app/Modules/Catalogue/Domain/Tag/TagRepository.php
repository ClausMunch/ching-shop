<?php

namespace ChingShop\Modules\Catalogue\Domain\Tag;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TagRepository.
 */
class TagRepository
{
    /** @var Tag|Builder */
    private $tagResource;

    /**
     * TagRepository constructor.
     *
     * @param Tag $tagResource
     */
    public function __construct(Tag $tagResource)
    {
        $this->tagResource = $tagResource;
    }

    /**
     * @return Collection|Tag[]
     */
    public function loadAll(): Collection
    {
        return $this->tagResource
            ->orderBy('updated_at', 'desc')
            ->with('products')
            ->get();
    }

    /**
     * @param int   $tagId
     * @param array $with
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Tag
     */
    public function loadById(
        int $tagId,
        array $with = ['products', 'products.images']
    ) {
        return $this->tagResource
            ->where('id', '=', $tagId)
            ->with($with)
            ->firstOrFail();
    }

    /**
     * @param array $tagData
     *
     * @return Tag
     */
    public function create(array $tagData)
    {
        return $this->tagResource->create($tagData);
    }

    /**
     * @param int $tagId
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deleteById(int $tagId): bool
    {
        return (bool) $this->tagResource
            ->where('id', '=', $tagId)
            ->first()
            ->delete();
    }

    /**
     * @param Product $product
     * @param array   $tagIds
     */
    public function syncProductTagIds(Product $product, array $tagIds)
    {
        $product->tags()->sync($tagIds);
    }
}
