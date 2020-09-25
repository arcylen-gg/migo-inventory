<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_contact', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->integer('shop_id')->unsigned();
            $table->string('category',20);
            $table->string('contact');
            $table->tinyInteger('primary');
            $table->tinyInteger('archived');
            $table->dateTime('date_created');
        });
        Schema::table('tbl_contact', function($table) {
           $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_tbl_contact');
    }
}
