<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrllJobtitle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_jobtitle', function (Blueprint $table) {
            $table->increments('payroll_jobtitle_id');
            $table->integer('shop_id');
            $table->integer('payroll_jobtitle_department_id');
            $table->string('payroll_jobtitle_name');
            $table->tinyInteger('payroll_jobtitle_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_jobtitle');
    }
}
