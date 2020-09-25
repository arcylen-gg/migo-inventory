<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLeaveEmployeeIdPayrollRequestLeave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('tbl_payroll_request_leave', function (Blueprint $table) {
            if(schema::hasColumn('tbl_payroll_request_leave','payroll_leave_employee_id'))
            {
                // $table->foreign("payroll_leave_employee_id")->references("payroll_leave_employee_id")->on("tbl_payroll_leave_employee_v2")->onDelete('cascade');
            }
            else
            {
                $table->integer('payroll_leave_employee_id')->unsigned();
                // $table->foreign("payroll_leave_employee_id")->references("payroll_leave_employee_id")->on("tbl_payroll_leave_employee_v2")->onDelete('cascade');
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
        //
    }
}
