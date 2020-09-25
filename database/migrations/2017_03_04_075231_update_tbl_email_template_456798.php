<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblEmailTemplate456798 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_email_template', function (Blueprint $table) {            
            $table->string("footer_text_color");
            $table->string("header_text_color");
            $table->text("header_txt")->change();
            $table->text("footer_txt")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_email_template', function (Blueprint $table) {
            //
        });
    }
}
