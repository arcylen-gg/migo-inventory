<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblMlmSlotUpdateEonAccountNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot', function (Blueprint $table)
        {
            if(!schema::hasColumn('tbl_mlm_slot','slot_eon'))
            {
                $table->string('slot_eon');
                $table->string('slot_eon_account_no');
                $table->string('slot_eon_card_no');
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
        //
    }
}
