<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMerchantSchoolAddCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_merchant_school_wallet', function (Blueprint $table) {
            //
            $table->double('merchant_school_cash')->default(0);
            $table->double('merchant_school_total_cash')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_merchant_school', function (Blueprint $table) {
            //
        });
    }
}
