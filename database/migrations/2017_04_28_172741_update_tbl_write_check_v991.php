<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheckV991 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->string("wc_ref_name");
            $table->integer("wc_ref_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            //
        });
    }
}
