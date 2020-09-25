<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_shop', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('shop_id');
            $table->string('shop_key')->unique(); //FOR SUBDOMAIN ADDRESS
            $table->dateTime('shop_date_created')->default('1000-01-01 00:00:00');
            $table->dateTime('shop_date_expiration')->default('1000-01-01 00:00:00');
            $table->dateTime('shop_last_active_date')->default('1000-01-01 00:00:00');
            $table->string('shop_status', 50)->default('trial');
            $table->integer('shop_country')->unsigned();
            $table->string('shop_city');
            $table->string('shop_zip');
            $table->string('shop_street_address');
            $table->string('shop_contact');
            $table->foreign('shop_country')->references('country_id')->on('tbl_country');
            $table->text('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_shop');
    }
}
