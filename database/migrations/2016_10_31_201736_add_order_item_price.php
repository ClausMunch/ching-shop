<?php

use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderItemPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            CreateOrderItemsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->double('price')->default(0.0);
            }
        );
        OrderItem::with('basketItem.productOption.product.prices')->get()->each(
            function (OrderItem $orderItem) {
                $orderItem->price = $orderItem->basketItem->priceAsFloat();
                $orderItem->save();
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
        Schema::table(
            CreateOrderItemsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropColumn('price');
            }
        );
    }
}
