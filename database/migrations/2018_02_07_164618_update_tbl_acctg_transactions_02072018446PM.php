<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblAcctgTransactions02072018446PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table) {
            $table->string("estline_refname")->nullable();
            $table->integer("estline_refid")->default(0);
        });
        Schema::table('tbl_customer_invoice_line', function (Blueprint $table) {
            $table->string("invline_refname")->nullable();
            $table->integer("invline_refid")->default(0);
        });
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            $table->string("itemline_refname")->nullable();
            $table->integer("itemline_refid")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table) {
            //
        });
    }
}
