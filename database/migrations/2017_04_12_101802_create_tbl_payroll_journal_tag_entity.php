<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollJournalTagEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_journal_tag_entity', function (Blueprint $table) {
            $table->increments('payroll_journal_tag_entity_id');
            $table->integer('payroll_journal_tag_id')->unsigned();
            $table->foreign("payroll_journal_tag_id")->references("payroll_journal_tag_id")->on("tbl_payroll_journal_tag")->onDelete('cascade');
            $table->integer('payroll_entity_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_journal_tag_entity');
    }
}
