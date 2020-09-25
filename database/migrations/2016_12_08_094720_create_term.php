<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_term', function (Blueprint $table) {
            $table->increments('term_id');
            $table->integer('shop_id')->unsigned();
            $table->string('term_name');
            $table->string('term_category');
            $table->integer('term_day');
            $table->integer('term_day_of_month');
            $table->integer('term_day_due_date');
            $table->tinyInteger('archived');
        });
        Schema::table('tbl_term', function($table) {
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
        Schema::drop('tbl_term');
    }
}
