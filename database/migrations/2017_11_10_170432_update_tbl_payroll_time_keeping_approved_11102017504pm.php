<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollTimeKeepingApproved11102017504pm extends Migration
{
     protected $fillable = ['company_id'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_time_keeping_approved', function (Blueprint $table) 
        {
           $table->integer('employee_company_id')->unsigned()->after('employee_id')->nullable();
           $table->foreign('employee_company_id')->references('payroll_company_id')->on('tbl_payroll_company')->onDelete('cascade');
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
