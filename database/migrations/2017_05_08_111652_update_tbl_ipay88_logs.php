<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblIpay88Logs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ipay88_logs', function (Blueprint $table) {
            $table->dropColumn('log_description');
            $table->string('log_merchant_code');
            $table->integer('log_payment_id');
            $table->string('log_currency');
            $table->string('log_remarks');
            $table->string('log_trans_id');
            $table->string('log_auth_code');
            $table->string('log_status');
            $table->string('log_error_desc');
            $table->string('log_signature');
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
