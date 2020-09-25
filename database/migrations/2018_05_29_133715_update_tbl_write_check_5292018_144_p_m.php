<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheck5292018144PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->integer("wc_cash_account_id")->nullable()->after("wc_shop_id")->unsigned();

            $table->foreign("wc_cash_account_id")->references("account_id")->on("tbl_chart_of_account")->onDelete("cascade");
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
