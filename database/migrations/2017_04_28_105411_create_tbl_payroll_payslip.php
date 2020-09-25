<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollPayslip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_payslip', function (Blueprint $table) {
            $table->increments('payroll_payslip_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->string('payslip_code');
            $table->integer('payroll_paper_sizes_id')->unsigned();
            $table->foreign('payroll_paper_sizes_id')->references('payroll_paper_sizes_id')->on('tbl_payroll_paper_sizes')->onDelete('cascade');
            $table->double('payslip_width');
            $table->integer('payslip_copy');
            $table->tinyInteger('include_company_logo');
            $table->tinyInteger('include_department');
            $table->tinyInteger('include_job_title');
            $table->tinyInteger('include_time_summary');
            $table->string('company_position');
            $table->tinyInteger('payroll_payslip_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_payslip');
    }
}
