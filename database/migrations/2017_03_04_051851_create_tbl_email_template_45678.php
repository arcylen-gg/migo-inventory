<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEmailTemplate45678 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_email_template', function (Blueprint $table) {
            $table->increments('email_template_id');
            $table->integer("shop_id")->unsigned();
            //header
            $table->text("header_image");
            $table->string("header_txt");
            $table->string("header_image_alignment");
            $table->string("header_background_color");
            //footer
            $table->string("footer_txt");
            $table->string("footer_image_alignment");
            $table->string("footer_background_color");

            $table->foreign("shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_email_template');
    }
}
