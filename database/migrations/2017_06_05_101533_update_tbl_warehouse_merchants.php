<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('tbl_warehouse', 'merchant_warehouse')) 
        {
            Schema::table('tbl_warehouse', function (Blueprint $table) 
            {
                $table->tinyInteger('merchant_warehouse')->default(0);
                $table->double('default_repurchase_points_mulitplier')->default(0);
                $table->double('default_margin_per_product')->default(0);
                $table->string('merchant_logo')->default("");
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
