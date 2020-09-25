<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollTimeSheetV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('tbl_payroll_time_sheet','payroll_time_in'))
        {
            Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
                $table->dropColumn('payroll_time_in');
            });
        }

        if(Schema::hasColumn('tbl_payroll_time_sheet','payroll_time_out'))
        {
            Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
                $table->dropColumn('payroll_time_out');
            });
        }

        if(Schema::hasColumn('tbl_payroll_time_sheet','payroll_time_sheet_activities'))
        {
            Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
                $table->dropColumn('payroll_time_sheet_activities');
            });
        }

        if(Schema::hasColumn('tbl_payroll_time_sheet','payroll_time_sheet_origin'))
        {
            Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
                $table->dropColumn('payroll_time_sheet_origin');
            });
        }


        Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
            $table->time('payroll_time_approve_regular_overtime');
            $table->time('payroll_time_approve_early_overtime');
            $table->time('payroll_time_approve_extra_day');
            $table->time('payroll_time_approve_rest_day');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
            //
        });
    }
}
