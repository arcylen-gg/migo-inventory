<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblIpay88String extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ipay88_logs', function (Blueprint $table) 
        {
            $table->string('log_reference_number')->change();
        });

        Schema::table('tbl_ipay88_temp', function (Blueprint $table) 
        {
            $table->string('reference_number')->change();
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
