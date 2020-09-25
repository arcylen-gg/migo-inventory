<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollJournalTagEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_journal_tag_employee', function (Blueprint $table) {
            $table->increments('payroll_journal_tag_employee_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign("payroll_employee_id")->references("payroll_employee_id")->on("tbl_payroll_employee_basic")->onDelete('cascade');
            $table->integer('payroll_journal_tag_id')->unsigned();
            $table->foreign("payroll_journal_tag_id")->references("payroll_journal_tag_id")->on("tbl_payroll_journal_tag")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_journal_tag_employee');
    }
}
