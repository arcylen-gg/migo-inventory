<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSmsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sms_logs', function (Blueprint $table) {
            $table->increments('sms_logs_id');
            $table->integer('sms_logs_shop_id');
            $table->string('sms_logs_key'); 
            $table->string('sms_logs_status')->comments("success, pending, failed");
            $table->string('sms_logs_recipient');  
            $table->string('sms_logs_remarks');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sms_logs');
    }
}
