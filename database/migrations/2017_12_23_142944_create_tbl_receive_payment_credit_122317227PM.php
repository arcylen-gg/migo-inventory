<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReceivePaymentCredit122317227PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receive_payment_credit', function (Blueprint $table) {
            $table->increments('rp_credit_id');
            $table->integer("rp_id")->unsigned();
            $table->string("credit_reference_name");
            $table->integer("credit_reference_id");
            $table->double("credit_amount");
            $table->datetime("date_created");

            $table->foreign("rp_id")->references("rp_id")->on("tbl_receive_payment")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receive_payment_credit');
    }
}
