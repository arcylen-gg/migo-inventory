<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMlmBinarySettingsGcIncomeSettings extends Migration
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
            if(!schema::hasColumn('tbl_mlm_binary_setttings','binary_settings_gc_amount_type'))
            {
                $table->string('binary_settings_gc_amount_type')->default("fixed");
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
        //
    }
}
