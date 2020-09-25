<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblBill5452454 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->integer("bill_new_id")->after("bill_id");
            $table->text("bill_mailing_address")->after("bill_ap_account");
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->integer("bill_terms_id")->after("bill_mailing_address");
            $table->string("bill_vendor_email")->after("bill_vendor_id");
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->date("bill_date")->after("bill_terms_id");
        });
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->date("bill_due_date")->after("bill_date");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            //
        });
    }
}
