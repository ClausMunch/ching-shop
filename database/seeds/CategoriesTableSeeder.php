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
        for ($i = 0; $i < 3; $i++) {
            $this->seedCategory();
        }
    }

    /**
     * Create a category and attach to some products.
     *
     * @throws \InvalidArgumentException
     */
    private function seedCategory()
    {
        /** @var Category $category */
        $category = factory(Category::class)->make();
        $category->save();
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
