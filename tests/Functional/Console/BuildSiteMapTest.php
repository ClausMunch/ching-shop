<?php

namespace Testing\Functional\Console;

use Artisan;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use DOMDocument;
use GuzzleHttp\Client;
use Storage;
use Testing\Functional\FunctionalTest;

/**
 * Test the sitemap generation functionality.
 */
class BuildSiteMapTest extends FunctionalTest
{
    /**
     * Enable XML errors.
     */
    public function setUp()
    {
        parent::setUp();

        libxml_use_internal_errors(true);
    }

    /**
     * Clear XML errors.
     */
    public function tearDown()
    {
        parent::tearDown();

        libxml_clear_errors();
    }

    /**
     * Should be able to build and access a valid XML sitemap.
     *
     * @slowThreshold 10000
     */
    public function testSiteMap()
    {
        Artisan::call('sitemap:build');

        $siteMapContent = $this->getSiteMap();

        $xml = new DOMDocument();
        $xml->load($siteMapContent);
        $errors = libxml_get_errors();
        $this->assertEmpty($errors, print_r($errors, true));

        foreach (Product::all() as $product) {
            $this->assertContains(
                $product->url(),
                $siteMapContent,
                'Sitemap should contain all product URLs.'
            );
        }
    }

    /**
     * @return string
     */
    private function getSiteMap(): string
    {
        if (env('NETWORK_TEST')) {
            $getSiteMap = (new Client(['verify' => false]))->get(
                url('/storage/sitemap.xml')
            );
            $this->assertEquals(200, $getSiteMap->getStatusCode());

            return (string) $getSiteMap->getBody();
        }

        return Storage::disk('public')->get('sitemap.xml');
    }
}
