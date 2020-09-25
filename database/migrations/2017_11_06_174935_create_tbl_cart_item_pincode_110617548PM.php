<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCartItemPincode110617548PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cart_item_pincode', function (Blueprint $table) {
            $table->increments('item_pincode_id');
            $table->string('unique_id_per_pc');
            $table->integer('shop_id');
            $table->integer('product_id');
            $table->string('pincode');
            $table->string('status')->default('Not Processes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_cart_item_pincode', function (Blueprint $table) {
            //
        });
    }
}
