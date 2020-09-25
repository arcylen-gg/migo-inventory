<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmEncashmentSettingsAddMinimum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_encashment_settings', function (Blueprint $table) {
            $table->double("enchasment_settings_minimum")->default(0);
        });
        Schema::table('tbl_mlm_encashment_process', function (Blueprint $table) {
            //
            $table->double("enchasment_process_minimum")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_encashment_settings', function (Blueprint $table) {
            //
        });
    }
}
