<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseInventoryRecordLog112917257PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            $table->double('record_count_inventory')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            //
        });
    }
}
