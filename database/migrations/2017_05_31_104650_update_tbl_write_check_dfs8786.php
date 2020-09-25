<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWriteCheckDfs8786 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->renameColumn("wc_vendor_email","wc_customer_vendor_email");
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
