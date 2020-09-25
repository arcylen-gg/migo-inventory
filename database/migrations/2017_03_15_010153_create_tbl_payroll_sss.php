<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollSss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_sss', function (Blueprint $table) {
            $table->increments('payroll_sss_id');
            $table->integer('shop_id');
            $table->double('payroll_sss_min', 18, 2);
            $table->double('payroll_sss_max', 18, 2);
            $table->double('payroll_sss_monthly_salary', 18, 2);
            $table->double('payroll_sss_er', 18, 2);
            $table->double('payroll_sss_ee', 18, 2);
            $table->double('payroll_sss_total', 18, 2);
            $table->double('payroll_sss_eec', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_sss');
    }
}
