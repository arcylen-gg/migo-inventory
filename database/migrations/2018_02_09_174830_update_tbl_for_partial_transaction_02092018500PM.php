<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblForPartialTransaction02092018500PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table) {
            $table->double("estline_orig_qty")->after("estline_qty");
        });
        Schema::table('tbl_customer_invoice_line', function (Blueprint $table) {
            $table->double("invline_orig_qty")->after("invline_qty"); 
        });
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            $table->double("itemline_orig_qty")->after("itemline_qty");            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_estimate_line', function (Blueprint $table) {
            //
        });
    }
}
