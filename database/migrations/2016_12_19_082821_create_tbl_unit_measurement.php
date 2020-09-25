<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUnitMeasurement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_unit_measurement_type', function (Blueprint $table) {
            $table->increments('um_type_id');
            $table->string('um_type_name');
            $table->string('um_type_abbrev');
            $table->timestamps();
        });
        
        Schema::create('tbl_unit_measurement', function (Blueprint $table) {
            $table->increments('um_id');
            $table->integer('um_shop')->unsigned();
            $table->integer('um_type')->unsigned();
            $table->string('um_name');
            $table->string('um_base_name');
            $table->string('um_base_abbrev');
            $table->tinyInteger('is_multi');
            $table->datetime('um_date_created');
            $table->tinyInteger('um_archived');
            
            $table->foreign('um_shop')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('um_type')->references('um_type_id')->on('tbl_unit_measurement_type')->onDelete('cascade');
        });
        
        Schema::create('tbl_unit_measurement_multi', function (Blueprint $table) {
            $table->increments('multi_id');
            $table->integer('multi_um_id')->unsigned();
            $table->string('multi_name');
            $table->string('multi_ abbrev');
            $table->double('multi_conversion_ratio');
            $table->tinyInteger('multi_sequence');
            
            $table->foreign('multi_um_id')->references('um_id')->on('tbl_unit_measurement')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_unit_measurement_multi');
        Schema::drop('tbl_unit_measurement');
        Schema::drop('tbl_unit_measurement_type');
    }
}
