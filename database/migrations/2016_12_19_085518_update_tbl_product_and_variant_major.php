<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblProductAndVariantMajor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_product', function (Blueprint $table) 
        {
            $table->integer('product_parent_id')->after('product_name');
            $table->tinyInteger('product_sub_level')->after('product_parent_id');
            $table->string('product_sale_description')->after('product_description');
            $table->integer('product_income_account')->unsigned()->after('product_description');
            $table->string('product_purchase_description')->after('product_income_account');
            $table->integer('product_cogs_exp_account')->unsigned()->after('product_purchase_description');
            $table->integer('product_pref_vendor_id')->after('product_cogs_exp_account');
            $table->integer('product_asset_account')->unsigned()->after('product_pref_vendor_id');
            
            // $table->foreign('product_income_account')->references('account_id')->on('tbl_chart_of_account')->onDelete('restrict');
            // $table->foreign('product_cogs_account')->references('account_id')->on('tbl_chart_of_account')->onDelete('restrict');
            // $table->foreign('product_asset_account')->references('account_id')->on('tbl_chart_of_account')->onDelete('restrict');
        });
        
        Schema::table('tbl_variant', function (Blueprint $table)
        {
            $table->double('variant_purchase_price')->after('variant_price');
            $table->datetime('variant_inventory_date')->after('variant_inventory_count');
            $table->integer('variant_reorder_min')->after('variant_fulfillment_service');
            $table->integer('variant_reorder_max')->after('variant_reorder_min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            //
        });
    }
}
