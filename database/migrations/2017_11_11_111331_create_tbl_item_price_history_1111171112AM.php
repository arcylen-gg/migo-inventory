<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemPriceHistory1111171112AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_price_history', function (Blueprint $table) {
            $table->increments('item_price_history_id');
            $table->integer('item_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->string('price_type')->comment('sales_price or  cost_price');
            $table->double('price');
            $table->datetime('updated_at');

            $table->foreign('item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_price_history');
    }
}
