<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventorySlipV7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_slip', function (Blueprint $table) {
            $table->dropColumn('inventory_slip_customer_id');
            $table->integer('inventory_slip_consumer_id');
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
