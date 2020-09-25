<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblEcOrderPaymentUpload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_order', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->after('order_status')->default(0);
            $table->string('payment_upload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_order', function (Blueprint $table) {
            //
        });
    }
}
