<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPosition8764 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_position', function (Blueprint $table) {
            $table->integer("position_shop_id")->unsigned();
            $table->foreign("position_shop_id")->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_position', function (Blueprint $table) {
            //
        });
    }
}
