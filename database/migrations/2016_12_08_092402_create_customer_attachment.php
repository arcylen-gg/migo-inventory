<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_attachment', function (Blueprint $table) {
            $table->increments('customer_attachment_id');
            $table->integer('customer_id')->unsigned();
            $table->text('customer_attachment_path');
            $table->string('customer_attachment_name');
            $table->string('customer_attachment_extension');
            $table->tinyInteger('archived');
        });
        Schema::table('tbl_customer_attachment', function($table) {
           $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_customer_attachment');
    }
}
