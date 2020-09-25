<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_post', function (Blueprint $table) 
        {
            $table->increments('post_id');
            $table->integer('post_author')->unsigned();
            $table->dateTime('post_date')->default('0000-00-00 00:00:00');
            $table->dateTime('post_modified')->default('0000-00-00 00:00:00');
            $table->longText('post_content');
            $table->text('post_title');
            $table->text('post_excerpt');
            $table->string('post_status', 20)->default('publish');
            $table->string('comment_status', 20)->default('open');
            $table->integer('comment_count');
            $table->string('post_type', 20)->default('post');

            $table->foreign('post_author')
                  ->references('user_id')->on('tbl_user')
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
