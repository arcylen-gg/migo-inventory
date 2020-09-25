<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_points', function (Blueprint $table) {
            $table->increments('membership_points_id');
            $table->integer('membership_id');
            $table->text('membership_points_binary');
            $table->text('membership_points_binary_max_pair');
            $table->text('membership_points_direct');
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
