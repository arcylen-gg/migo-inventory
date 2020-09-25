<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblShopAddMerchantSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_shop', function (Blueprint $table) {
            //
            if(!schema::hasColumn('tbl_shop','shop_merchant_school'))
            {
                $table->integer('shop_merchant_school')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_shop', function (Blueprint $table) {
            //
        });
    }
}
