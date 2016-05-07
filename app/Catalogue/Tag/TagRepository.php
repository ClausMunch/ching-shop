<?php

namespace ChingShop\Catalogue\Tag;

use ChingShop\Catalogue\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
     * @param int   $id
     * @param array $with
     *
     * @return Tag
     */
    public function loadById(
        int $id,
        array $with = ['products', 'products.images']
    ) {
        return $this->tagResource
            ->where('id', '=', $id)
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
