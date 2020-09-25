<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemPointsRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_item_points', function (Blueprint $table) 
        {
            $table->double('RANK')->default(0);
            $table->double('RANK_GROUP')->default(0);
        });

        Schema::table('tbl_item_code', function (Blueprint $table) 
        {
            $table->double('RANK')->default(0);
            $table->double('RANK_GROUP')->default(0);
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
