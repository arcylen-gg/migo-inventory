<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblTruck23456 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_truck', function (Blueprint $table) 
        {
            if (!Schema::hasColumn('tbl_truck', 'truck_model'))
            {
                $table->string("truck_model");
            }
            if (!Schema::hasColumn('tbl_truck', 'truck_kilogram'))
            {
                $table->decimal("truck_kilogram");
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
        Schema::table('tbl_truck', function (Blueprint $table) {
            //
        });
    }
}
