<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_image', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('image_id');
            $table->string('image_path')->unique();
            $table->string('image_key')->unique();
            $table->integer('image_shop')->unsigned();
            $table->string('image_reason')->default('product');
            $table->integer('image_reason_id');
            $table->dateTime('image_date_created');
            $table->foreign('image_shop')->references('shop_id')->on('tbl_shop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_image');
    }
}
