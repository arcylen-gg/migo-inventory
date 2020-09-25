<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblJournalEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_journal_entry_line', function (Blueprint $table) {
            $table->integer('jline_name_id')->after('jline_je_id');
            $table->integer('jline_item_id')->unsigned()->after('jline_name_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_journal_entry', function (Blueprint $table) {
            //
        });
    }
}
