<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_product_search', function (Blueprint $table) {
            $table->engine = 'MyISAM'; //  
            $table->increments('product_search_id');
            $table->integer('variant_id');
            $table->text('body');
            
        });
        DB::statement('ALTER TABLE tbl_product_search ADD FULLTEXT search(body)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_product_search');
    }
}
