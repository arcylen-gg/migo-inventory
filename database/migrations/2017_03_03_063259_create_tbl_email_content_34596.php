<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEmailContent34596 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_email_content', function (Blueprint $table) {
            $table->increments('email_content_id');
            $table->string("email_content_key");
            $table->text("email_content");
            $table->integer("shop_id")->unsigned();
            $table->datetime("date_created");
            $table->datetime("date_updated");
            $table->tinyInteger("archived");

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
        Schema::drop('tbl_email_content');
    }
}
