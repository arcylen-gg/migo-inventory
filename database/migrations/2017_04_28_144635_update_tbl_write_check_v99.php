<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheckV99 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->renameColumn("wc_ap_account","wc_cash_account");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            //
        });
    }
}
