<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmBinarySettingsBinryType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_binary_setttings', function (Blueprint $table) {
            //
            $table->integer('binary_settings_type')->default(0);
            $table->double('binary_settings_matrix_income')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_binary_setttings', function (Blueprint $table) {
            //
        });
    }
}
