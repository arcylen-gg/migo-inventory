<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblUnitMeasurementTypeXxxx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_unit_measurement_type', function (Blueprint $table) {
            $table->tinyInteger("archived")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_unit_measurement_type', function (Blueprint $table) {
            //
        });
    }
}
