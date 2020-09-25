<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTransactionPayment111417937AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaction_payment', function (Blueprint $table) {
            $table->increments('transaction_payment_id');
            $table->integer('transaction_id')->unsigned();
            $table->string('transaction_payment_type')->comment('cash/cheque/gc');
            $table->double('transaction_payment_amount');
            $table->datetime('transaction_payment_date');

            $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transaction')->onDelete('cascade');
        });
        Schema::table('tbl_transaction', function (Blueprint $table) {
            $table->tinyInteger('show_comment_on_receipt')->default(0);
            $table->text('comment_on_receipt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_transaction_payment');
    }
}
