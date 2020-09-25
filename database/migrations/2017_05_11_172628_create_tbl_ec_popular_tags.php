<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcPopularTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tbl_ec_popular_tags')) 
        {
            Schema::create('tbl_ec_popular_tags', function (Blueprint $table) 
            {
                $table->increments('tag_id');
                $table->integer('count')->default(0);
                $table->string('keyword')->default(0);
                $table->integer('shop_id')->unsigned();

                $table->foreign('shop_id')
                      ->references('shop_id')->on('tbl_shop')
                      ->onDelete('cascade');
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
