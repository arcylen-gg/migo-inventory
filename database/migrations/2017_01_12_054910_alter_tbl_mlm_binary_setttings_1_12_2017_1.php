<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmBinarySetttings11220171 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_binary_setttings', function (Blueprint $table) {
            $table->integer("binary_settings_placement")->default(0);
            $table->string("binary_settings_auto_placement")->default('left_to_right');
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
