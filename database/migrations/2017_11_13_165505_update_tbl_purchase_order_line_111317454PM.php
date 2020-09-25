<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPurchaseOrderLine111317454PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->double('poline_orig_qty')->default(0)->after('poline_qty');
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
