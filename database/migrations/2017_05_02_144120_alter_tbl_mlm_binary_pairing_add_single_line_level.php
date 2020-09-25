<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmBinaryPairingAddSingleLineLevel extends Migration
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
            $table->integer("pairing_point_single_line_bonus_level")->default(1);
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
