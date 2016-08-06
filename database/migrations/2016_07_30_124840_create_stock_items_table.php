<?php

use ChingShop\Modules\Catalogue\Model\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockItemsTable extends Migration
{
    const TABLE_NAME = 'stock_items';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->increments('id');

                // A stock item is for a product option.
                $table->integer('product_option_id')
                    ->unsigned()
                    ->index();
                $table->foreign('product_option_id')
                    ->references('id')
                    ->on('product_options')
                    ->onDelete('cascade');

                // A stock item can be allocated to an order item.
                $table->integer('order_item_id')
                    ->unsigned()
                    ->index()
                    ->nullable();
                $table->foreign('order_item_id')
                    ->references('id')
                    ->on('order_items')
                    ->onDelete('cascade');

                $table->timestamps();
                $table->softDeletes();
            }
        );

        // Initialise stock items for existing product options.
        ProductOption::all()->each(
            function (ProductOption $productOption) {
                $productOption->stockItems()->save(new StockItem());
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
