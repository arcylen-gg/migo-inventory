<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblDebitMemoReplaceLineV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_debit_memo_replace_line', function (Blueprint $table) 
        {
            $table->renameColumn("dbline_replace_db_id","dbline_replace_dbline_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_debit_memo_replace_line', function (Blueprint $table) {
            //
        });
    }
}
