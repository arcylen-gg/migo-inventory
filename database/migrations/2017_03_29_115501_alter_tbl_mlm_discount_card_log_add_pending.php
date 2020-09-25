<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmDiscountCardLogAddPending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_discount_card_log', function (Blueprint $table) {
            $table->integer('discount_card_log_issued')->default(0);
            $table->datetime('discount_card_log_issued_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_discount_card_log', function (Blueprint $table) {
            //
        });
    }
}
