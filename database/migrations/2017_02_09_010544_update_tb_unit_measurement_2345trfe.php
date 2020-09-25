<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTbUnitMeasurement2345trfe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_unit_measurement', function (Blueprint $table) {
            $table->dropColumn("um_base_name");
            $table->dropColumn("um_base_abbrev");
        });

        Schema::table('tbl_unit_measurement_multi', function (Blueprint $table) {
            $table->tinyInteger("is_base");
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
