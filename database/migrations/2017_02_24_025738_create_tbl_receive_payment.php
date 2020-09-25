<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReceivePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receive_payment', function (Blueprint $table) {
            $table->increments('rp_id');
            $table->integer('rp_customer_id');
            $table->integer('rp_ar_account');
            $table->date('rp_date');
            $table->float('rp_total_amount');
            $table->string('rp_payment_method');
            $table->text('rp_memo');
            $table->datetime('date_created');
        });

        Schema::create('tbl_receive_payment_line', function (Blueprint $table) {
            $table->increments('rpline_id');
            $table->integer('rpline_rp_id')->unsigned();
            $table->integer('rpline_txn_type');
            $table->integer('rpline_txn_id');    
            $table->float('rpline_amount');

            $table->foreign('rpline_rp_id')->references('rp_id')->on('tbl_receive_payment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_recieve_payment');
    }
}
