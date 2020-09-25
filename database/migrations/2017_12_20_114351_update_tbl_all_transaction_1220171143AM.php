<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblAllTransaction1220171143AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->string("transaction_refnum")->after("inv_id");
        });
        Schema::table('tbl_customer_estimate', function (Blueprint $table) {
            $table->string("transaction_refnum")->after("est_id");
        });
        Schema::table('tbl_receive_payment', function (Blueprint $table) {
            $table->string("transaction_refnum")->after("rp_id");
        });
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->string("transaction_refnum")->after("cm_id");
        });
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            $table->renameColumn("cust_wis_number","transaction_refnum");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            //
        });
    }
}
