<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMerchantSchoolItemAddAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_merchant_school_item', function (Blueprint $table) {
            //
            $table->double('merchant_school_i_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_merchant_school_item', function (Blueprint $table) {
            //
        });
    }
}
