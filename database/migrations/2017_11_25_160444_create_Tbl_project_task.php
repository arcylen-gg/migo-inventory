<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProjectTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_project_task', function (Blueprint $table)
        {
            $table->increments("project_task_id");
            $table->integer("project_id")->unsigned();
            $table->string("project_name");
            $table->string("assignee");
            $table->text("task");
            $table->string("hours");
            $table->integer("priority");
            $table->foreign("project_id")->references("project_id")->on("tbl_project")->onDelete("cascade");
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
