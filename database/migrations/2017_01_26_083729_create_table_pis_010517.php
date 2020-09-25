<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePis010517 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_position', function (Blueprint $table) {
            $table->increments('position_id');
            $table->string("position_name");
            $table->decimal("daily_rate");
            $table->datetime("position_created");
            $table->tinyInteger("archived")->default(0);
        });

        Schema::create('tbl_truck', function (Blueprint $table) {
            $table->increments('truck_id');
            $table->string("plate_number");
            $table->integer("warehouse_id")->unsigned();
            $table->datetime("created_at");
            $table->tinyInteger("archived")->default(0);

            $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
        });


        Schema::create('tbl_employee', function (Blueprint $table) {
            $table->increments('employee_id');
            $table->integer("shop_id")->unsigned();
            $table->integer("warehouse_id")->unsigned();
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->string("gender");
            $table->string("email");
            $table->string("username");
            $table->text("password");
            $table->date("b_day");
            $table->integer("position_id")->unsigned();
            $table->datetime("created_at");
            $table->tinyInteger("archived")->default(0);

            $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('position_id')->references('position_id')->on('tbl_position')->onDelete('cascade');
        });

        Schema::create('tbl_sir', function (Blueprint $table) {
            $table->increments('sir_id');
            $table->integer("truck_id")->unsigned();
            $table->integer("shop_id")->unsigned();
            $table->integer("sales_agent_id")->unsigned();
            $table->datetime("created_at");
            $table->tinyInteger("archived")->default(0);

            $table->foreign('truck_id')->references('truck_id')->on('tbl_truck')->onDelete('cascade');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('sales_agent_id')->references('employee_id')->on('tbl_employee')->onDelete('cascade');
        });
        

        Schema::create('tbl_sir_item', function (Blueprint $table) {
            $table->increments('sir_item_id');
            $table->integer("sir_id")->unsigned();
            $table->integer("item_id")->unsigned();
            $table->integer("item_qty");
            $table->tinyInteger("archived")->default(0);


            $table->foreign('sir_id')->references('sir_id')->on('tbl_sir')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_position');
    }
}
