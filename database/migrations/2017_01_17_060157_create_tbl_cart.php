<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cart', function (Blueprint $table) 
        {
            $table->increments("cart_id");
            $table->integer("product_id")->unsigned();
            $table->integer("quantity");
            $table->integer("shop_id")->unsigned();
            $table->string("unique_id_per_pc");
            $table->string("status")->default("Not Processed");
            $table->dateTime("date_added");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
