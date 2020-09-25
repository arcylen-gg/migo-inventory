<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_project', function (Blueprint $table)
        {
            $table->increments("project_id");
            $table->string("project_name");
            $table->string("project_email");
            $table->string("project_contact");
            $table->integer("project_type")->unsigned();
        });
    }

    public function down()
    {
    }
}
