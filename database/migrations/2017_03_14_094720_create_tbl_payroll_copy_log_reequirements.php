<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollCopyLogReequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_copy_log_requirements', function (Blueprint $table) {
            $table->increments('payroll_copy_log_requirements_id');
            $table->integer('shop_id');
            $table->string('requirements_category');
            $table->dateTime('requirements_copy_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_copy_log_requirements');
    }
}
