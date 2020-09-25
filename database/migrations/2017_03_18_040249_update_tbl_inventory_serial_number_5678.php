<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventorySerialNumber5678 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_serial_number', function (Blueprint $table) {
            $table->dropForeign("tbl_inventory_serial_number_inventory_id_foreign");
            $table->renameColumn("inventory_id", "serial_inventory_id");
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
