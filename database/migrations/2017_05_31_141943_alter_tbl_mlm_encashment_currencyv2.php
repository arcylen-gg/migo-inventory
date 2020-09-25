<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmEncashmentCurrencyv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_encashment_currency', function (Blueprint $table) {
            //
            $table->string('en_cu_name')->default('Philippine Peso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_encashment_currency_add_name', function (Blueprint $table) {
            //
        });
    }
}
