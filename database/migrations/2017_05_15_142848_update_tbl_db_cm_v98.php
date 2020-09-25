<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblDbCmV98 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->integer("cm_shop_id")->after("cm_customer_id");

        });
        Schema::table('tbl_debit_memo', function (Blueprint $table) {
            $table->integer("db_shop_id")->after("db_vendor_id");

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
