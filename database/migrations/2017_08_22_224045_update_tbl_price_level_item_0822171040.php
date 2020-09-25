<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPriceLevelItem0822171040 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_price_level', function (Blueprint $table)
        {
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
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
