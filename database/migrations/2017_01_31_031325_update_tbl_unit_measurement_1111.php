<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblUnitMeasurement1111 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_unit_measurement', function (Blueprint $table) {
            $table->integer("um_type")->unsigned();
            $table->foreign("um_type")->references("um_type_id")->on('tbl_unit_measurement_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_unit_measurement', function (Blueprint $table) {
            //
        });
    }
}
