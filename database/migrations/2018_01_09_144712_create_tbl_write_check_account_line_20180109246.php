<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWriteCheckAccountLine20180109246 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_write_check_account_line', function (Blueprint $table) {
            $table->increments('accline_id');
            $table->integer("accline_wc_id")->unsigned();
            $table->integer("accline_coa_id");
            $table->text("accline_description");
            $table->double("accline_amount");

            $table->foreign('accline_wc_id')->references('wc_id')->on('tbl_write_check')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_write_check_account_line');
    }
}
