<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUserWarehouseAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_warehouse_access', function (Blueprint $table) 
        {
            // $table->increments('user_warehouse_access_id');
            $table->integer('user_id')->unsigned();
            $table->integer('warehouse_id')->unsigned();
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
