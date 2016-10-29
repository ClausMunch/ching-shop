<?php

namespace Testing\Customer;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Http\Controllers\SearchController;
use ChingShop\Modules\Catalogue\Http\Requests\SearchRequest;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test basic product search functionality.
 */
class SearchTest extends FunctionalTest
{
    use CreateCatalogue;

    /**
     * Should be able to find a product in search results.
     */
    public function testSearchForProduct()
    {
        // Given there is a product;
        $product = $this->createProduct();

        // When we search for the product's name;
        $this->searchFor($product->name);

        // Then we should see the product in the search results.
        $this->see($product->name);
        $this->see($product->description);
        $this->click($product->name)->seePageIs($product->url());
    }

    /**
     * Product search must be exclusive to be useful.
     */
    public function testSearchExclusive()
    {
        // Given there is a product with one name;
        $product = $this->createProduct();

        // And another product with a different name;
        $otherProduct = $this->createProduct(['name' => uniqid('', false)]);

        // When we search for the first product's name;
        $this->searchFor($product->name);

        // Then we should see the first product;
        $this->see($product->description);

        // And we should not see the other product.
        $this->dontSee($otherProduct->name);
        $this->dontSee($otherProduct->description);
    }

    /**
     * Should be able to paginate through search results.
     *
     * @slowThreshold 5000
     */
    public function testSearchPagination()
    {
        // Given there are products with similar names;
        $similarName = 'foobar';
        /** @var Product[] $products */
        $products = [];
        for ($i = 0; $i < SearchController::PAGE_SIZE + 5; $i++) {
            $products[] = $this->createProduct(
                ['name' => uniqid("{$similarName} ", false)]
            );
        }
        (new Product())->where('name', 'like', "{$similarName}%")->searchable();

        // When we search for that name;
        $this->searchFor($similarName);

        // Then we should be able to navigate to the next page of results.
        $this->click('2');
    }

    /**
     * @param string $query
     */
    private function searchFor(string $query)
    {
        $this->visit('/')
            ->type($query, SearchRequest::QUERY_PARAMETER)
            ->press('Search');
    }
}
