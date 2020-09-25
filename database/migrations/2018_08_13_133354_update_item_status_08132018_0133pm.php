<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateItemStatus081320180133pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->tinyInteger('poline_item_status')->after('poline_orig_qty')->default(0);
        });
        Schema::table('tbl_purchase_order', function (Blueprint $table) {
            $table->integer('po_is_billed')->change();
        });
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table) {
            $table->tinyInteger('estline_status')->after('estline_orig_qty')->default(0);
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
