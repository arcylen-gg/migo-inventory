<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSoPoItemline100520180226pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table)
        {
            $table->integer('poline_received_qty')->after('poline_orig_qty')->default(0);
        });
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table)
        {
            $table->integer('estline_received_qty')->after('estline_orig_qty')->default(0);
        });
        Schema::table('tbl_quantity_monitoring', function (Blueprint $table)
        {
            $table->integer('qty_shop_id')->after('qty_monitoring_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            //
        });
    }
}
