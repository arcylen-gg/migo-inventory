<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMerchantSchoolItemAddSId extends Migration
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
            $table->string('merchant_school_s_id')->nullable();
            $table->string('merchant_school_s_name')->nullable();
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
