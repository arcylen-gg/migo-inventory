<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmMatchingAddGc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_matching', function (Blueprint $table) {
            //
            $table->integer('matching_settings_gc_count')->default(0);
            $table->double('matching_settings_gc_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_matching', function (Blueprint $table) {
            //
        });
    }
}
