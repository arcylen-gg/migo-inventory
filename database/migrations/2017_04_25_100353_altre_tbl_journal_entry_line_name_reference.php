<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltreTblJournalEntryLineNameReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_journal_entry_line', function (Blueprint $table) {
            $table->string("jline_name_reference")->after("jline_name_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_journal_entry_line', function (Blueprint $table) {
            //
        });
    }
}
