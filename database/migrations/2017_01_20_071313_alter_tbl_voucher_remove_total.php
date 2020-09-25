<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblVoucherRemoveTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_voucher', function (Blueprint $table) {
            $table->dropColumn('voucher_sub_total');
            $table->dropColumn('voucher_discount');
            $table->dropColumn('voucher_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_voucher', function (Blueprint $table) {
            //
        });
    }
}
