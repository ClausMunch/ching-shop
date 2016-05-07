<?php

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Tag\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagsTableSeeder extends Seed
{
    /** @var Collection|Product[] */
    private $products;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 16; $i++) {
            $this->seedTag();
        }
    }

    /**
     * Create a tag and attach to some products.
     */
    private function seedTag()
    {
        $tag = Tag::create(['name' => ucfirst($this->faker()->unique()->word)]);
        for ($i = 0; $i < rand(1,8); $i++) {
            $product = $this->products()->random();
            if ($tag->products->contains('id', $product->id)) {
                continue;
            }
            $tag->products()->attach($product->id);
            $tag->products->add($product);
        }
    }

    /**
     * @return Collection|Product[]
     */
    private function products(): Collection
    {
        if (empty($this->products)) {
            $this->products = Product::all();
        }
        return $this->products;
    }
}
