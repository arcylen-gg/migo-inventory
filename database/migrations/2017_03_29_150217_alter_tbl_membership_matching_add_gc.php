<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMembershipMatchingAddGc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_matching_log', function (Blueprint $table) {
            //
            $table->integer('matching_log_is_gc')->default(0);
            $table->double('matching_log_gc_amount')->default(0);
            $table->string('matching_log_gc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_matching_log', function (Blueprint $table) {
            //
        });
    }
}
