<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmBinaryReportAddLogPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_binary_report', function (Blueprint $table) {
            //
            $table->integer("binary_report_s_points_l")->default(0);
            $table->integer("binary_report_s_points_r")->default(0);
        });

        Schema::table('tbl_mlm_binary_pairing_log', function (Blueprint $table) {
            //
            $table->string("pairing_type")->default('earn');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_binary_report', function (Blueprint $table) {
            //
        });
    }
}
