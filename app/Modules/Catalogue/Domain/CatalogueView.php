<?php

namespace ChingShop\Modules\Catalogue\Domain;

use Carbon\Carbon;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

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
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function suggestions(): string
    {
        $key = 'suggestions';
        if ($this->cacheHas($key)) {
            return (string) $this->cacheGet($key);
        }

        $content = $this->viewFactory->make(
            'customer.partials.suggestions',
            ['suggestions' => $this->loadSuggestions()]
        )->render();
        $this->cachePut($key, $content, Carbon::tomorrow());

        return $content;
    }

    /**
     * @param Product $product
     *
     * @throws \BadMethodCallException
     *
     * @return Product
     */
    public function getProduct(Product $product): Product
    {
        $key = $this->key("product.{$product->id}");
        if ($this->cacheHas($key)) {
            return $this->cacheGet($key);
        }

        $product->loadStandardRelations();
        $this->cachePut($key, $product, Carbon::tomorrow());

        return $product;
    }

    /**
     * @param Product $product
     *
     * @throws \BadMethodCallException
     *
     * @return Collection
     */
    public function similarProducts(Product $product): Collection
    {
        $key = $this->key("product.{$product->id}.similar");
        if ($this->cacheHas($key)) {
            return $this->cacheGet($key);
        }

        $similar = $this->catalogueRepository->loadSimilarProducts($product);
        $this->cachePut($key, $similar, Carbon::now()->addDays(7));

        return $similar;
    }

    /**
     * @param Product $product
     *
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function productMeta(Product $product): string
    {
        $key = "product.{$product->id}.meta";
        if ($this->cacheHas($key)) {
            return (string) $this->cacheGet($key);
        }

        $product->loadStandardRelations();
        $content = $this->viewFactory->make(
            'customer.product.meta',
            compact('product')
        )->render();
        $this->cachePut($key, $content, Carbon::now()->addDays(7));

        return $content;
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return Collection
     */
    public function frontProducts(): Collection
    {
        $key = $this->key('product.front');
        if ($this->cacheHas($key)) {
            return $this->cacheGet($key);
        }

        $products = $this->catalogueRepository->loadFrontProducts();
        $this->cachePut($key, $products, Carbon::tomorrow());

        return $products;
    }

    /**
     * @param int $productId
     *
     * @return bool
     * @throws \BadMethodCallException
     */
    public function clearProduct(int $productId)
    {
        return $this->cacheForget("product.{$productId}")
        && $this->cacheForget("product.{$productId}.meta")
        && $this->cacheForget("product.{$productId}.similar");
    }

    /**
     * @throws \BadMethodCallException
     */
    public function clearAll()
    {
        $this->cache->tags(self::KEY_PREFIX)->flush();
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

    /**
     * @param string    $key
     * @param mixed     $value
     * @param \DateTime $expiry
     *
     * @throws \BadMethodCallException
     */
    private function cachePut(string $key, $value, \DateTime $expiry)
    {
        $this->cache->tags(self::KEY_PREFIX)->put(
            $this->key($key),
            $value,
            $expiry
        );
    }

    /**
     * @param string $key
     *
     * @throws \BadMethodCallException
     *
     * @return bool
     */
    private function cacheHas(string $key): bool
    {
        return $this->cache->tags(self::KEY_PREFIX)->has($this->key($key));
    }

    /**
     * @param string $key
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    private function cacheGet(string $key)
    {
        return $this->cache->tags(self::KEY_PREFIX)->get($this->key($key));
    }

    /**
     * @param string $key
     *
     * @throws \BadMethodCallException
     */
    private function cacheForget(string $key)
    {
        $this->cache->tags(self::KEY_PREFIX)->forget($this->key($key));
    }

    /**
     * @param string $suffix
     *
     * @return string
     */
    private function key(string $suffix)
    {
        if (strpos(self::KEY_PREFIX, $suffix) === 0) {
            return $suffix;
        }

        return self::KEY_PREFIX . $suffix;
    }
}
