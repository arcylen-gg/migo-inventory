<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollBiometricTimeSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_biometric_time_sheet', function (Blueprint $table) 
        {
            $table->increments('payroll_biometric_time_sheet_id');
            $table->integer('payroll_biometric_record_id');
            $table->time('payroll_time_in')->default('00:00:00');
            $table->time('payroll_time_out')->default('00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
