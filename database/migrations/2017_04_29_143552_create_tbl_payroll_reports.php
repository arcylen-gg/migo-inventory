<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_reports', function (Blueprint $table) {
            $table->increments('payroll_reports_id');
            $table->integer("shop_id")->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->string('payroll_reports_name');
            $table->tinyInteger('is_by_employee');
            $table->tinyInteger('is_by_department');
            $table->tinyInteger('is_by_company');
            $table->tinyInteger('payroll_reports_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_reports');
    }
}
