<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblEcProductNameCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_product', function (Blueprint $table) {
            $table->integer('eprod_category_id')->nullable()->unsigned()->after('eprod_shop_id');
            $table->string('eprod_name')->after('eprod_is_single');
            
            $table->foreign('eprod_category_id')->references('type_id')->on('tbl_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_product', function (Blueprint $table) {
            //
        });
    }
}
