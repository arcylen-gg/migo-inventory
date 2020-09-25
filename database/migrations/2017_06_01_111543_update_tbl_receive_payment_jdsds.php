<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblReceivePaymentJdsds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_payment', function (Blueprint $table) 
        {
            $table->string("rp_ref_name");
            $table->integer("rp_ref_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_receive_payment', function (Blueprint $table) {
            //
        });
    }
}
