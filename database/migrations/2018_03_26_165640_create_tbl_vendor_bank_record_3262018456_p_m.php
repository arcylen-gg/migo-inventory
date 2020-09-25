<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVendorBankRecord3262018456PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor_bank_record', function (Blueprint $table) {
            $table->increments('bank_record_id');
            $table->integer("vendor_id")->unsigned();
            $table->string("vendor_account_name");
            $table->string("vendor_account_number");
            $table->string("vendor_account_type");
            $table->datetime("created_at");

            $table->foreign("vendor_id")->references("vendor_id")->on("tbl_vendor")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_vendor_bank_record');
    }
}
