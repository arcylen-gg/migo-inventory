<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblTransactionRefNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaction_ref_number', function (Blueprint $table) {
            $table->increments('trans_ref_number_id');
            $table->string('shop_id');
            $table->string('key');
            $table->string('prefix');
            $table->date('other');
            $table->string('separator');
            $table->timestamps();

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
