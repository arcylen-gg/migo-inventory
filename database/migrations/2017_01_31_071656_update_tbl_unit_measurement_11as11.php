<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblUnitMeasurement11as11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_unit_measurement', function (Blueprint $table) {           
            $table->dropForeign("tbl_unit_measurement_um_type_foreign");
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
