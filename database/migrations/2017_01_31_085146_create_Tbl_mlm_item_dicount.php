<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmItemDicount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_item_discount', function (Blueprint $table) 
        {
            $table->increments('item_discount_d');
            $table->double('item_discount_price')->default(0);
            $table->double('item_discount_percentage')->default(0);
            $table->integer('membership_id')->unsigned();
            $table->integer('item_id')->unsigned();
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
