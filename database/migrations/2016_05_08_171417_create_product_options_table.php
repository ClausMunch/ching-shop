<?php

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductOptionsTable extends Migration
{
    const TABLE_NAME = 'product_options';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');

            $table->string('label', 127);
            $table->tinyInteger('position')->unsigned()->default(0);

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->unique(['product_id', 'label']);

            $table->timestamps();
            $table->softDeletes();
        });
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE `product_options` AUTO_INCREMENT=55;');
        }

        $this->initialiseProductOptions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE_NAME);
    }

    /**
     * Create a variant for each product that does not have any.
     */
    public function initialiseProductOptions()
    {
        /** @var Product $product */
        foreach (Product::has('options', '<', 1)->get() as $product) {
            echo "Creating option for product {$product->id}\n";
            $product->options()->save(
                new ProductOption([
                    'label' => 'Standard',
                ])
            );
        }
    }
}
