<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_discount', function (Blueprint $table) 
        {
            $table->increments("item_discount_id");
            $table->integer("item_id")->unsigned();
            $table->double("item_discount_value");
            $table->string("item_discount_type")->default("fixed");
            $table->string("item_discount_remark");
            $table->dateTime("item_discount_date_start");
            $table->dateTime("item_discount_date_end");
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
