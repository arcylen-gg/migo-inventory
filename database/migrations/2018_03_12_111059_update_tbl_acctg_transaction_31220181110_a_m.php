<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblAcctgTransaction31220181110AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_acctg_transaction', function (Blueprint $table) {
            $table->integer("transaction_warehouse_id")->unsigned()->nullable()->after("transaction_user_id");

            $table->foreign("transaction_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_acctg_transaction', function (Blueprint $table) {
            //
        });
    }
}
