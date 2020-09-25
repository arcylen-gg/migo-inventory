<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventorySlipV5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_slip', function (Blueprint $table) {
            $table->string('inventroy_source_reason');
            $table->integer('inventory_source_id');
            $table->string('inventory_source_name');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_inventory_slip', function (Blueprint $table) {
            //
        });
    }
}
