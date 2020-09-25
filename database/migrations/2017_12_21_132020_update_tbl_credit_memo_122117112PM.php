<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCreditMemo122117112PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->text("cm_customer_billing_address")->after("cm_customer_email");
        });
        Schema::table('tbl_receive_payment', function (Blueprint $table) {
            $table->string("rp_customer_email")->after("rp_customer_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            //
        });
    }
}
