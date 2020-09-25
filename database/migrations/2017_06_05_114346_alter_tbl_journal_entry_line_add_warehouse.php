<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblJournalEntryLineAddWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_journal_entry_line', function (Blueprint $table) {
            //
            $table->integer('jline_warehouse_id')->default(0);
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
