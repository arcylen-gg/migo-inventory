<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblManualReceivePayment5678 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_manual_receive_payment', function (Blueprint $table) {
            $table->increments('manual_receive_payment_id');
            $table->integer("agent_id");
            $table->integer("rp_id");
            $table->integer("sir_id");
            $table->datetime("rp_date");
            $table->tinyInteger("is_sync")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_manual_receive_payment');
    }
}
