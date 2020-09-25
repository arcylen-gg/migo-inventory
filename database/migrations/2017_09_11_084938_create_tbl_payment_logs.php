<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPaymentLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payment_logs', function (Blueprint $table) 
        {
            $table->increments('payment_log_id');
            $table->string("payment_log_type");
            $table->string("payment_log_method");
            $table->dateTime("payment_log_created");
            $table->string("payment_log_url");  
            $table->binary("payment_log_data");
            $table->string("payment_log_ip_address");
            $table->integer("shop_id")->unsigned();

            $table->foreign('shop_id')
                  ->references('shop_id')->on('tbl_shop')
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
