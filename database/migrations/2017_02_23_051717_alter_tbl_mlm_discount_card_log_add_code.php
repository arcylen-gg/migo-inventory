<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmDiscountCardLogAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_discount_card_log', function (Blueprint $table) {
            
            if(!Schema::hasColumn('tbl_mlm_discount_card_log', 'discount_card_log_code'))
            {
                $table->string("discount_card_log_code")->nullable();
            }
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
