<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventorySlipV6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_slip', function (Blueprint $table) {
            $table->string('inventory_slip_consume_refill');
            $table->integer('inventory_slip_customer_id');
            $table->string('inventory_slip_consume_cause');
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
