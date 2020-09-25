<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouse120617254PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse', function (Blueprint $table) {
            $table->integer('warehouse_parent_id')->default(0)->after('warehouse_name');
        });
        Schema::table('tbl_warehouse', function (Blueprint $table) {
            $table->integer('warehouse_level')->default(0)->after('warehouse_parent_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse', function (Blueprint $table) {
            //
        });
    }
}
