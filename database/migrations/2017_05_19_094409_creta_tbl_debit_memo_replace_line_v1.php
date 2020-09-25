<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CretaTblDebitMemoReplaceLineV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_debit_memo_replace_line', function (Blueprint $table) 
        {
            $table->increments('dbline_replace_id');
            $table->integer('dbline_replace_db_id');
            $table->integer('dbline_replace_item_id');
            $table->integer('dbline_replace_qty');
            $table->double('dbline_replace_rate');
            $table->double('dbline_replace_amount');
            $table->tinyInteger('dbline_replace_status');
        });
        Schema::table('tbl_debit_memo_line', function (Blueprint $table) 
        {
            $table->tinyInteger('dbline_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_debit_memo_replace_line');
    }
}
