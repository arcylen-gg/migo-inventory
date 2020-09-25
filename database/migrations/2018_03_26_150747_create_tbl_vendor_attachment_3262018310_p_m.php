<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVendorAttachment3262018310PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor_attachment', function (Blueprint $table) {
            $table->increments('vendor_attachment_id');
            $table->integer('vendor_id')->unsigned();
            $table->text('vendor_attachment_path');
            $table->string('vendor_attachment_name');
            $table->string('vendor_attachment_extension');
            $table->tinyInteger('archived');

            $table->foreign('vendor_id')->references('vendor_id')->on('tbl_vendor')->onDelete('cascade');
        });
         Schema::table('tbl_vendor_attachment', function (Blueprint $table) {
            $table->string('mime_type')->after('vendor_attachment_extension');
        });

        $statement = "ALTER TABLE tbl_vendor_attachment AUTO_INCREMENT =100;";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_vendor_attachment');
    }
}
