<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollJournalTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_journal_tag', function (Blueprint $table) {
            $table->increments('payroll_journal_tag_id');
            $table->integer("shop_id")->unsigned();
            $table->foreign("shop_id")->references("shop_id")->on("tbl_shop")->onDelete('cascade');
            $table->integer('account_id')->unsigned();
            $table->foreign("account_id")->references("account_id")->on("tbl_chart_of_account")->onDelete('cascade');
            $table->tinyInteger('journal_tag_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_journal_tag');
    }
}
