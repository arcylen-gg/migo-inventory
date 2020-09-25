<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmbinaryReportAddLimit extends Migration
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

            if(!schema::hasColumn('tbl_mlm_binary_report','binary_report_point_limit'))
            {
                $table->double('binary_report_point_limit')->default(0);
            }
            if(!schema::hasColumn('tbl_mlm_binary_report','binary_report_point_deduction'))
            {
                $table->double('binary_report_point_deduction')->default(0);
            }
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
