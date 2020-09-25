<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseInventoryRecordLog4192018959AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            $table->integer("record_bin_id")->nullable()->unsigned()->after("record_warehouse_id");

            $table->foreign("record_bin_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
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
