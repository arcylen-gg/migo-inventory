<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePressiqPressReleases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pressiq_press_releases', function (Blueprint $table)
        {
            $table->increments("pr_id");
            $table->string("pr_headline");
            $table->string("pr_subheading");
            $table->string("pr_content");
            $table->string("pr_from");
            $table->string("pr_to");
            $table->string("pr_status");
            
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
