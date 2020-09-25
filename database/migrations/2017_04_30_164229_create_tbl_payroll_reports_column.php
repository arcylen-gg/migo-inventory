<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollReportsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_reports_column', function (Blueprint $table) {
            $table->increments('payroll_reports_column_id');
            $table->integer('payroll_reports_id')->unsigned();
            $table->foreign('payroll_reports_id')->references('payroll_reports_id')->on('tbl_payroll_reports')->onDelete('cascade');
            $table->integer('column_entity_id');
            $table->string('column_origin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_reports_column');
    }
}
