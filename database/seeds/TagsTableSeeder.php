<?php

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TagsTableSeeder.
 */
class TagsTableSeeder extends Seed
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
        for ($i = 0; $i < 16; $i++) {
            $this->seedTag();
        }

        $this->seedTag('Christmas');
    }

    /**
     * Create a tag and attach to some products.
     *
     * @param string $name
     */
    private function seedTag(string $name = null)
    {
        /** @var Tag $tag */
        $tag = Tag::create(
            [
                'name' => $name ?? ucfirst($this->faker()->unique()->word),
            ]
        );
        for ($i = 0, $count = random_int(1, 8); $i < $count; $i++) {
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
        if ($this->products === null) {
            $this->products = Product::all();
        }

        return $this->products;
    }
}
