<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMlmSlotWalletLogAddTriangleMatrixColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            //
            // $table->integer('wallet_log_matrix_triangle')->default(0);
            if(!schema::hasColumn('tbl_mlm_slot_wallet_log','wallet_log_matrix_triangle'))
            {
                $table->integer('wallet_log_matrix_triangle')->default(0);
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
        Schema::table('tbl_mlm_slot_wallet_log', function (Blueprint $table) {
            //
        });
    }
}
