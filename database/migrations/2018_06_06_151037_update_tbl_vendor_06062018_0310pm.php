<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblVendor060620180310pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_vendor', function (Blueprint $table) {
            $table->integer("vendor_warehouse_id")->unsigned()->nullable()->after("vendor_shop_id");
            $table->foreign("vendor_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_vendor', function (Blueprint $table) {
            //
        });
    }
}
