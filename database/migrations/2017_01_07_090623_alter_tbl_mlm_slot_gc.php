<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmSlotGC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_slot', function (Blueprint $table) {
            //
            
            if(!Schema::hasColumn('tbl_mlm_slot', 'slot_pairs_gc'))
            {
                $table->double('slot_pairs_gc')->default(0);
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
        Schema::table('tbl_mlm_slot', function (Blueprint $table) {
            //
        });
    }
}
