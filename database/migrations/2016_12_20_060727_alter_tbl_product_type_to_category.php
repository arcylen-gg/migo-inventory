<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblProductTypeToCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('tbl_product_type', 'tbl_category');
        
        Schema::table('tbl_category', function (Blueprint $table) 
        {
            $table->integer('type_parent_id')->after('type_name');
            $table->tinyInteger('type_sub_level')->after('type_parent_id');
            $table->string('type_category')->default('inventory')->after('type_shop');
        });
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
