<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item', function (Blueprint $table) 
        {
            // $table->string('item_discounted')->default("no_discount");
            // $table->double('item_discounted_value');
            // $table->string('item_discounted_remark')->default("no_discount");
            // $table->dateTime('item_discounted_remark_start_date');
            // $table->dateTime('item_discounted_remark_end_date');
            // $table->double('item_discounted_remark_value');
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
