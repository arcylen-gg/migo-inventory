<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblDebitMemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_debit_memo', function (Blueprint $table) {
            $table->increments('db_id');
            $table->integer('db_vendor_id');
            $table->integer('db_ap_acccount');
            $table->string('db_vendor_email');
            $table->date('db_date');
            $table->string('db_message');
            $table->string('db_memo');
            $table->double('db_amount');
            $table->datetime('date_created');
        });

        Schema::create('tbl_debit_memo_line', function (Blueprint $table) {
            $table->increments('dbline_id'); 
            $table->integer('dbline_db_id')->unsigned();
            $table->datetime('dbline_service_date');
            $table->integer('dbline_um');
            $table->integer('dbline_item_id');
            $table->string('dbline_description');
            $table->integer('dbline_qty');
            $table->double('dbline_rate');
            $table->double("dbline_amount");

            $table->foreign('dbline_db_id')->references('db_id')->on('tbl_debit_memo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_debit_memo');
    }
}
