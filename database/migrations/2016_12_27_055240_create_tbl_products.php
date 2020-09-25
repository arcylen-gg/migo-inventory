<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item', function (Blueprint $table) 
        {
            $table->increments('item_id');
            $table->string('item_name');
            $table->string('item_sku');
            $table->string('item_sales_information');
            $table->string('item_purchasing_information');
            $table->string('item_img');
            
            $table->integer('item_quantity');
            $table->integer('item_reorder_point');
            
            $table->double('item_price');
            $table->double('item_cost');
            
            $table->tinyInteger('item_sale_to_customer');
            $table->tinyInteger('item_purchase_from_supplier');
            
            $table->integer('item_type_id')->unsigned();
            $table->integer('item_category_id')->unsigned();
            $table->integer('item_asset_account_id')->unsigned()->nullable();
            $table->integer('item_income_account_id')->unsigned()->nullable();
            $table->integer('item_expense_account_id')->unsigned()->nullable();

            $table->dateTime('item_date_tracked')->nullable();
            $table->dateTime('item_date_created');
            $table->dateTime('item_date_archived')->nullable();
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
        //
    }
}
