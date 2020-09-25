<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPressiqDemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pressiq_demo', function (Blueprint $table)
        {
            $table->increments("demo_id");
            $table->string("demo_name");
            $table->string("demo_company");
            $table->string("demo_email")->unique();
            $table->string("demo_phone_number");
            $table->string("demo_messages");
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
