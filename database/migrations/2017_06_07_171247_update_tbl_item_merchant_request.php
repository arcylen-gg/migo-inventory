<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemMerchantRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tbl_item_merchant_request')) 
        {
            Schema::create('tbl_item_merchant_request', function (Blueprint $table) 
            {
                $table->increments('item_merchant_request_id');
                $table->string('item_merchant_request_status');
                $table->integer('item_merchant_requested_by')->unsigned();
                $table->integer('item_merchant_accepted_by')->nullable()->unsigned();
                $table->integer('merchant_warehouse_id')->unsigned();
                $table->integer('merchant_item_id')->unsigned();
                $table->dateTime('item_merchant_accepted_date')->nullable();
                $table->dateTime('date_created');
            });
        }
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
