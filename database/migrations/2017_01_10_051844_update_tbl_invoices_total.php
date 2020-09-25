<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInvoicesTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tbl_item_code_invoice', 'item_subtotal')) 
        {
            Schema::table('tbl_item_code_invoice', function (Blueprint $table) 
            {
                $table->double("item_subtotal");
                $table->double("item_discount");
                $table->double("item_total");
                $table->double("item_discount_percentage");
            });
        }

       
        if (!Schema::hasColumn('tbl_item_code', 'item_code_price_total')) 
        {
            Schema::table('tbl_item_code', function (Blueprint $table) 
            {
                $table->double("item_code_price_total");
            });       
        } 
        
        if (!Schema::hasColumn('tbl_membership_code_invoice', 'membership_subtotal')) 
        {
            Schema::table('tbl_membership_code_invoice', function (Blueprint $table) 
            {
                $table->double("membership_subtotal");
                $table->double("membership_discount");
                $table->double("membership_total");
                $table->double("membership_discount_percentage");
            });
        }
       
        if (!Schema::hasColumn('tbl_membership_code', 'membership_code_price_total')) 
        {
           Schema::table('tbl_membership_code', function (Blueprint $table) 
            {
                $table->double("membership_code_price_total");
            });   
        } 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
