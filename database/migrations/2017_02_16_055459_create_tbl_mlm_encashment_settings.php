<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmEncashmentSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_encashment_settings', function (Blueprint $table) {
            $table->increments('enchasment_settings_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('enchasment_settings_auto');
            $table->double('enchasment_settings_tax');
            $table->integer('enchasment_settings_tax_type');
            $table->double('enchasment_settings_p_fee');
            $table->integer('enchasment_settings_p_fee_type');
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
