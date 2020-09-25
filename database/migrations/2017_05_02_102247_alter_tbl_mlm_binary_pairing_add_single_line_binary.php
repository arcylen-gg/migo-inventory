<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmBinaryPairingAddSingleLineBinary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_binary_pairing', function (Blueprint $table) {
            //

            $table->double("pairing_point_single_line_bonus")->default(0);
            $table->integer("pairing_point_single_line_bonus_percentage")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_binary_pairing', function (Blueprint $table) {
            //
        });
    }
}
