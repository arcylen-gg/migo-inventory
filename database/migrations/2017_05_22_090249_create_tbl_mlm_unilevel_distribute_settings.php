<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmUnilevelDistributeSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_unilevel_distribute_settings', function (Blueprint $table) {
            $table->increments('u_r_id');
            $table->double('u_r_personal')->default(0);
            $table->double('u_r_group')->default(0);
            $table->double('u_r_convertion')->default(0);
            $table->integer('u_r_shop_id')->nullable();
            $table->datetime('u_r_date');
            
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
