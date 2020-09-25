<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_type', function (Blueprint $table) 
        {
            $table->increments('item_type_id');
            $table->string('item_type_name');
            $table->tinyInteger('archived');
        });
        
        // $insert[0]["item_type_name"] = "Inventory";
        // $insert[1]["item_type_name"] = "Non-Inventory";
        // $insert[2]["item_type_name"] = "Service";
        // DB::table('tbl_item_type')->insert($insert);
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
