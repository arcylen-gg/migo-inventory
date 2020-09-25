<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelPostCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_post_category', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->integer('post_category_id')->unsigned();

            $table->foreign('post_id')
                  ->references('post_id')->on('tbl_post')
                  ->onDelete('cascade');

            $table->foreign('post_category_id')
                  ->references('post_category_id')->on('tbl_post_category')
                  ->onDelete('cascade');
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
