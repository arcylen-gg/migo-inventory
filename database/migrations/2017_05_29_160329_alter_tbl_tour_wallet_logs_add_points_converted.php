<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblTourWalletLogsAddPointsConverted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_tour_wallet_logs', function (Blueprint $table) {
            //
            $table->double('tour_wallet_logs_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_tour_wallet_logs', function (Blueprint $table) {
            //
        });
    }
}
