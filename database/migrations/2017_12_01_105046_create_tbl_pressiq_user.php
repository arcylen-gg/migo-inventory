<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPressiqUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pressiq_user', function (Blueprint $table)
        {
            $table->increments("user_id");
            $table->integer("user_level")->unsigned();
            $table->string("user_first_name");
            $table->string("user_last_name");
            $table->string("user_email");
            $table->string("user_password");
            $table->dateTime("user_date_created");
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
