<?php

use ChingShop\Modules\Catalogue\Domain\Category;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TagsTableSeeder.
 */
class CategoriesTableSeeder extends Seed
{
    /** @var Collection|Product[] */
    private $products;

    /**
     * Run the database seeds.
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function run()
    {
        $this->repeat(
            function () {
                $this->seedCategory();
            },
            3
        );
    }

    /**
     * Create a category and attach to some products.
     *
     * @throws \InvalidArgumentException
     */
    private function seedCategory()
    {
        /** @var Category $parentCategory */
        $parentCategory = factory(Category::class)->create();
        $this->addProductsToCategory($parentCategory);

        /** @var Category $childCategory */
        $childCategory = factory(Category::class)->create();
        $childCategory->makeChildOf($parentCategory);
        $this->addProductsToCategory($childCategory);
    }

    /**
     * @param Category $category
     *
     * @throws \InvalidArgumentException
     */
    private function addProductsToCategory(Category $category)
    {
        for ($i = 0, $count = random_int(2, 8); $i < $count; $i++) {
            $product = $this->products()->random();
            if ($category->products->contains('id', $product->id)) {
                continue;
            }
            $category->products()->save($product);
            $category->products->add($product);
        }
    }

    /**
     * @return Collection|Product[]
     */
    private function products(): Collection
    {
        if ($this->products === null) {
            $this->products = Product::all();
        }

        return $this->products;
    }
}
