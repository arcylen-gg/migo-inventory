<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollManpowerReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_payroll_manpower_report', function (Blueprint $table) {
                   $table->increments('payroll_manpower_report_id');
                   $table->integer('shop_id');
                   $table->integer('payroll_employee_id');
                   $table->date('date_filed');
                   $table->text('employment_status');
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
