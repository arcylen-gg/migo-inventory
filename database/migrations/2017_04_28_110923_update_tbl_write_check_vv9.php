<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheckVv9 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->string("wc_vendor_email")->after("wc_vendor_id");
        });
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->text("wc_mailing_address")->after("wc_vendor_email");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            //
        });
    }
}
