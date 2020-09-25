<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_service', function (Blueprint $table) 
        {
            $table->increments('service_id');
            $table->integer('service_shop')->unsigned();
            $table->string('service_name');
            $table->integer('service_parent_id');
            $table->tinyInteger('service_sublevel');
            $table->string('service_price');
            $table->string('service_description');
            $table->integer('service_income_account');
            $table->string('service_purchase_price');
            $table->string('service_purchase_description');
            $table->integer('service_expense_account');
            $table->string('service_pref_vendor');
            $table->integer('service_type')->unsigned();
            $table->string('service_search_keyword');
            $table->datetime('service_date_created');
            $table->datetime('service_date_visible');
            $table->tinyInteger('archived');
            
            $table->foreign('service_shop')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('service_type')->references('type_id')->on('tbl_category')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_service');
    }
}
