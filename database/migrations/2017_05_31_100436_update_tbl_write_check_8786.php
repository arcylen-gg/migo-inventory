<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheck8786 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) 
        {
            $table->renameColumn("wc_vendor_id","wc_reference_id");
        });
        Schema::table('tbl_write_check', function (Blueprint $table) 
        {
            $table->string("wc_reference_name")->default("vendor")->after("wc_reference_id");
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
