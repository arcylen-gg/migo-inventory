<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblEcOrderAddLoad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_order', function (Blueprint $table) {
            $table->integer('ec_order_load')->default(0);
            $table->string('ec_order_load_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_order', function (Blueprint $table) {
            //
        });
    }
}
