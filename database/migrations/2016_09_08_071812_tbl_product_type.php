<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblProductType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product_type', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('type_id');
            $table->string('type_name');
            $table->integer('type_shop')->unsigned();
            $table->dateTime('type_date_created');
            $table->foreign('type_shop')->references('shop_id')->on('tbl_shop');
            $table->tinyInteger('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_product_type');
    }
}
