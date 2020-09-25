<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemCodeStairstepgroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code', function (Blueprint $table) 
        {
            if(!schema::hasColumn('tbl_item_code','STAIRSTEP_GROUP'))
            {
                $table->double('STAIRSTEP_GROUP')->default(0);
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
