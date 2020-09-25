<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblIpay88LogsOrderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ipay88_logs', function (Blueprint $table) {
            $table->integer('order_id')->unsigned()->nullable();
            
            $table->foreign('order_id')
                  ->references('ec_order_id')->on('tbl_ec_order')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
