<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_search', function (Blueprint $table) {
            $table->engine = 'MyISAM'; // means you can't use foreign key constraints
            $table->increments('payroll_employee_search_id');
            $table->integer('payroll_search_employee_id');
            $table->text('body');
        });

        DB::statement('ALTER TABLE tbl_payroll_employee_search ADD FULLTEXT search(body)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_search');
    }
}
