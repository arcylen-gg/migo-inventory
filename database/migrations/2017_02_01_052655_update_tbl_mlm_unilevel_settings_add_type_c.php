<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMlmUnilevelSettingsAddTypeC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_unilevel_settings', function (Blueprint $table) {
            //
            $table->tinyinteger('unilevel_settings_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_unilevel_settings', function (Blueprint $table) {
            //
        });
    }
}
