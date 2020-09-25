<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollBiometricRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_biometric_record', function (Blueprint $table) 
        {
            $table->increments('payroll_biometric_record_id');
            $table->integer('shop_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_company_id');
            $table->date('payroll_time_date');
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
