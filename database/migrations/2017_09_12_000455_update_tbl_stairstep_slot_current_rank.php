<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblStairstepSlotCurrentRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tbl_stairstep_distribute_slot', function (Blueprint $table) 
       {
            $table->integer('processed_current_rank');
            $table->double('processed_personal_pv');
            $table->double('processed_required_pv');
            $table->double('processed_multiplier');
            $table->double('processed_earned');
            $table->integer('processed_status');
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
