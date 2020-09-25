<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpadateTblManufacturer45678 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_manufacturer', function (Blueprint $table) {
            $table->integer("manufacturer_shop_id")->unsigned();

            $table->foreign("manufacturer_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
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
