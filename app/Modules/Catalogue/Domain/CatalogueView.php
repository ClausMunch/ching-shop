<?php

namespace ChingShop\Modules\Catalogue\Domain;

use Carbon\Carbon;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Produce catalogue views with caching.
 */
class CatalogueView
{
    const KEY_PREFIX = 'catalogue.view.';

    /** @var Repository */
    private $cache;

    /** @var Factory */
    private $viewFactory;

    /** @var CatalogueRepository */
    private $catalogueRepository;

    /**
     * @param Repository          $cache
     * @param Factory             $viewFactory
     * @param CatalogueRepository $catalogueRepository
     */
    public function __construct(
        Repository $cache,
        Factory $viewFactory,
        CatalogueRepository $catalogueRepository
    ) {
        $this->cache = $cache;
        $this->viewFactory = $viewFactory;
        $this->catalogueRepository = $catalogueRepository;
    }

    /**
     * @return string
     */
    public function suggestions(): string
    {
        $key = $this->key('suggestions');
        if ($this->cache->has($key)) {
            return (string) $this->cache->get($key);
        }

        $content = $this->viewFactory->make(
            'customer.partials.suggestions',
            ['suggestions' => $this->loadSuggestions()]
        )->render();
        $this->cache->put($key, $content, Carbon::tomorrow());

        return $content;
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function productBody(Product $product): string
    {
        $key = $this->key("product.{$product->id}.body");
        if ($this->cache->has($key)) {
            return (string) $this->cache->get($key);
        }

        $product->loadStandardRelations();
        $similar = $this->catalogueRepository->loadSimilarProducts($product);
        $content = $this->viewFactory->make(
            'customer.product.partials.body',
            compact('product', 'similar')
        )->render();
        $this->cache->put($key, $content, Carbon::tomorrow());

        return $content;
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function productMeta(Product $product): string
    {
        $key = $this->key("product.{$product->id}.meta");
        if ($this->cache->has($key)) {
            return (string) $this->cache->get($key);
        }

        $product->loadStandardRelations();
        $content = $this->viewFactory->make(
            'customer.product.meta',
            compact('product')
        )->render();
        $this->cache->put($key, $content, Carbon::now()->addDays(7));

        return $content;
    }

    /**
     * @param int $productId
     *
     * @return bool
     */
    public function cleanProductView(int $productId)
    {
        return $this->cache->forget($this->key("product.{$productId}.body"))
        && $this->cache->forget($this->key("product.{$productId}.meta"));
    }

    /**
     * @param string $view
     * @param array  $data
     *
     * @return View
     */
    public function make(string $view, array $data = []): View
    {
        return $this->viewFactory->make($view, $data);
    }

    /**
     * @param string $suffix
     *
     * @return string
     */
    private function key(string $suffix)
    {
        return self::KEY_PREFIX.$suffix;
    }

    /**
     * @return \Illuminate\Support\Collection|string[]
     */
    private function loadSuggestions()
    {
        return $this->catalogueRepository
            ->tag()
            ->limit(100)
            ->get(['name'])
            ->map(
                function (Tag $tag) {
                    return str_singular(strtolower($tag->name));
                }
            );
    }
}
