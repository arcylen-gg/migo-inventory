<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemLandingCost013118143PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_default_landing_cost', function (Blueprint $table) {
            $table->increments('default_cost_id');
            $table->integer('shop_id')->unsigned();
            $table->string('default_cost_name');
            $table->text('default_cost_description');
            $table->string('default_cost_type');
            $table->datetime('default_cost_created');
            $table->datetime('default_cost_updated');

            $table->foreign("shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
        });
        Schema::create('tbl_item_landing_cost', function (Blueprint $table) {
            $table->increments('landing_cost_id');
            $table->integer("landing_cost_item_id")->unsigned()->nullable();
            $table->integer("landing_cost_shop_id")->unsigned();
            $table->string("landing_cost_name");
            $table->string("landing_cost_type");
            $table->double("landing_cost_rate");
            $table->double("landing_cost_amount");
            $table->datetime("landing_cost_created");

            $table->foreign("landing_cost_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade"); 
            $table->foreign("landing_cost_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade"); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_landing_cost');
    }
}
