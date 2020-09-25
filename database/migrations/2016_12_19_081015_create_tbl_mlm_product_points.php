<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmProductPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_product_points', function (Blueprint $table) {
            $table->increments('product_points_id');
            $table->double('product_points_stairstep');
            $table->double('product_points_unilevel');
            $table->double('product_points_binary');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('tbl_product');
            $table->integer('variant_id')->unsigned();
            $table->foreign('variant_id')->references('variant_id')->on('tbl_variant');
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
