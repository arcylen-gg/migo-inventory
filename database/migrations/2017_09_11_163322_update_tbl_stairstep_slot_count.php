<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblStairstepSlotCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tbl_stairstep_distribute', function (Blueprint $table) 
       {
            $table->integer('from_slot_id')->unsigned();
            $table->integer('to_slot_id')->unsigned();
            $table->integer('total_slot');
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
