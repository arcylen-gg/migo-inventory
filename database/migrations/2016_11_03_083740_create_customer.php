<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('country_id');
            $table->string('title_name', 100);
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('suffix_name', 100);
            $table->string('email');
            $table->text('password');
            $table->string('company')->nullable();
            $table->date('b_day')->default('0000-00-00');
            
            $table->string('profile')->nullable();
            $table->tinyInteger('IsWalkin');
            $table->date('created_date')->nullable();
            $table->tinyInteger('archived');
        });
        Schema::table('tbl_customer', function($table) {
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
        Schema::drop('tbl_customer');
    }
}
