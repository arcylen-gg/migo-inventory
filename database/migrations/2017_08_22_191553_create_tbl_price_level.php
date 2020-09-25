<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPriceLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_price_level', function (Blueprint $table)
        {
            $table->increments('price_level_id');
            $table->string('price_level_name');
            $table->string('price_level_type');
            $table->string('fixed_percentage_mode');
            $table->string('fixed_percentage_source');
            $table->double('fixed_percentage_value');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
