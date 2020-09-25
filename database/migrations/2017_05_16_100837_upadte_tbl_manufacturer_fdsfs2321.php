<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpadteTblManufacturerFdsfs2321 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_manufacturer', function (Blueprint $table) {
            $table->string("manufacturer_fname");
            $table->string("manufacturer_mname");
            $table->string("manufacturer_lname");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_manufacturer', function (Blueprint $table) {
            //
        });
    }
}
