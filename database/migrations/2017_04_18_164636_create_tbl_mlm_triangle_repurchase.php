<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmTriangleRepurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_triangle_repurchase', function (Blueprint $table) {
            $table->increments('triangle_repurchase_id');
            $table->integer('membership_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->double('triangle_repurchase_amount')->default(0);
            $table->double('triangle_repurchase_income')->default(0);
            $table->integer('triangle_repurchase_count')->default(0);
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
