<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_department', function (Blueprint $table) {
            $table->increments('payroll_department_id');
            $table->integer('shop_id');
            $table->string('payroll_department_name');
            $table->tinyInteger('payroll_department_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_department');
    }
}
