<?php

namespace Testing\Functional\Console;

use Artisan;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use DOMDocument;
use GuzzleHttp\Client;
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

        $getSiteMap = (new Client(['verify' => false]))->get(
            url('/storage/sitemap.xml')
        );
        $this->assertEquals(200, $getSiteMap->getStatusCode());

        $xml = new DOMDocument();
        $xml->load($getSiteMap->getBody());
        $errors = libxml_get_errors();
        $this->assertEmpty($errors, print_r($errors, true));

        foreach (Product::all() as $product) {
            $this->assertContains(
                $product->url(),
                (string) $getSiteMap->getBody(),
                'Sitemap should contain all product URLs.'
            );
        }
    }
}
