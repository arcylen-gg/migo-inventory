<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_requirements', function (Blueprint $table) {
            $table->increments('payroll_employee_requirements_id')->comment('tbl_payroll_requirements is reference table');
            $table->integer('payroll_employee_id');
            $table->tinyInteger('has_resume');
            $table->integer('resume_requirements_id');
            $table->tinyInteger('has_police_clearance');
            $table->integer('police_clearance_requirements_id');
            $table->tinyInteger('has_nbi');
            $table->integer('nbi_payroll_requirements_id');
            $table->tinyInteger('has_health_certificate');
            $table->integer('health_certificate_requirements_id');
            $table->tinyInteger('has_school_credentials');
            $table->integer('school_credentials_requirements_id');
            $table->tinyInteger('has_valid_id');
            $table->integer('valid_id_requirements_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_requirements');
    }
}
