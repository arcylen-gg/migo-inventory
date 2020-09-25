<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_address', function (Blueprint $table) {
            $table->increments('customer_address_id');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer');
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('country_id')->on('tbl_country');
            $table->string('customer_state');
            $table->string('customer_city');
            $table->string('customer_zipcode');
            $table->text('customer_street');
            $table->string('purpose');
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
        Schema::drop('tbl_customer_address');
    }
}
