<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventorySerialNumberV9887 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_serial_number', function (Blueprint $table) {
           $table->string("consume_source")->nullable();
           $table->integer("consume_source_id")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_inventory_serial_number', function (Blueprint $table) {
            //
        });
    }
}
