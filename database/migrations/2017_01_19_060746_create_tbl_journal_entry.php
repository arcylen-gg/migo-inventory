<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblJournalEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_journal_entry', function (Blueprint $table) {
            $table->increments('je_id');
            $table->integer('je_shop_id')->unsigned();
            $table->string('je_reference_module');
            $table->integer('je_reference_id');
            $table->datetime('je_entry_date');
            $table->text('je_remarks');
            $table->timestamps();

            $table->foreign('je_shop_id')->references('shop_id')->on("tbl_shop")->onDelete('cascade');
        });

        Schema::create('tbl_journal_entry_line', function (Blueprint $table) {
            $table->increments('jline_id');
            $table->integer('jline_je_id')->unsigned();
            $table->integer('jline_account_id')->unsigned();
            $table->string('jline_type');
            $table->double('jline_amount');
            $table->text('jline_description');
            $table->timestamps();

            $table->foreign('jline_je_id')->references('je_id')->on('tbl_journal_entry')->onDelete('cascade');
            $table->foreign('jline_account_id')->references('account_id')->on('tbl_chart_of_account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_journal_entry_line');
        Schema::drop('tbl_journal_entry');
    }
}
