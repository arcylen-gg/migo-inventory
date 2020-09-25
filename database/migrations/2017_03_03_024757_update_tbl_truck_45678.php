<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblTruck45678 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->integer("truck_shop_id")->unsigned();

            $table->foreign("truck_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            //
        });
    }
}
