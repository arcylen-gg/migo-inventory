<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateTblBillItemLineAddOrigQuantity091120181105 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->integer("itemline_orig_qty")->after("itemline_qty")->default(0);
        });
        
        DB::statement('UPDATE tbl_bill_item_line SET itemline_orig_qty = itemline_qty');
    }

 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            //
        });
    }
}
