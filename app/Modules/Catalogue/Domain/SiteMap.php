<?php

namespace ChingShop\Modules\Catalogue\Domain;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Subelements\Image;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Class SiteMap.
 */
class SiteMap
{
    /** @var CatalogueRepository */
    private $catalogueRepository;

    /** @var Output */
    private $output;

    /**
     * SiteMap constructor.
     *
     * @param CatalogueRepository $catalogueRepository
     * @param Output              $output
     */
    public function __construct(
        CatalogueRepository $catalogueRepository,
        Output $output
    ) {
        $this->catalogueRepository = $catalogueRepository;
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $urlSet = new Urlset();
        $this->addProducts($urlSet);
        $this->addTags($urlSet);

        $this->output->addProcessingInstruction(
            'xml-stylesheet',
            'type="text/xsl" href="/xml-sitemap.xsl"'
        );

        return $this->output->getOutput($urlSet);
    }

    /**
     * @param Urlset $urlSet
     */
    private function addProducts(Urlset $urlSet)
    {
        foreach ($this->catalogueRepository->iterateAllProducts() as $product) {
            $this->addProduct($urlSet, $product);
        }
    }

    /**
     * @param Urlset  $urlSet
     * @param Product $product
     */
    private function addProduct(Urlset $urlSet, Product $product)
    {
        $url = new Url(htmlspecialchars($product->url()));
        $url->setLastMod($product->updated_at->toW3cString());
        $url->setChangeFreq('weekly');
        $url->setPriority(0.7);

        foreach ($product->images as $image) {
            $url->addSubElement(new Image($image->sizeUrl()));
        }

        $urlSet->addUrl($url);
    }

    /**
     * @param Urlset $urlSet
     */
    private function addTags(Urlset $urlSet)
    {
        foreach ($this->catalogueRepository->loadAllTags() as $tag) {
            $url = new Url(htmlspecialchars($tag->url()));
            $url->setLastMod($tag->updated_at->toW3cString());
            $url->setChangeFreq('monthly');
            $url->setPriority(0.3);

            $urlSet->addUrl($url);
        }
    }
}
