<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInventorySlip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inventory_slip', function (Blueprint $table) {
            $table->increments('inventory_slip_id');
            $table->integer('inventory_slip_id_sibling')->default(0);
            $table->string('inventory_reason');
            $table->integer('warehouse_id');
            $table->text('inventory_remarks');
            $table->dateTime('inventory_slip_date');
            $table->tinyInteger('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_inventory_slip');
    }
}
