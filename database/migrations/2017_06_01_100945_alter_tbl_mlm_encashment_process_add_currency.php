<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmEncashmentProcessAddCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_encashment_process', function (Blueprint $table) {
            //
            $table->string('encashment_process_currency')->default('PHP');
            $table->double('encashment_process_currency_convertion')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_encashment_process', function (Blueprint $table) {
            //
        });
    }
}
