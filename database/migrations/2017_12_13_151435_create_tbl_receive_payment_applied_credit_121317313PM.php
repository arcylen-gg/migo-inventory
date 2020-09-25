<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReceivePaymentAppliedCredit121317313PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_recvpyment_applied_credit', function (Blueprint $table) {
            $table->increments('applied_credit_id');
            $table->integer('rpline_rp_id')->unsigned();
            $table->string('ref_name');
            $table->integer('ref_id');
            $table->double('amount');

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
        Schema::dropIfExists('tbl_receive_payment_applied_credit');
    }
}
