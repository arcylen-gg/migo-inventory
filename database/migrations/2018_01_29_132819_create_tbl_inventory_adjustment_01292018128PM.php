<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInventoryAdjustment01292018128PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inventory_adjustment', function (Blueprint $table) {
            $table->increments('inventory_adjustment_id');
            $table->string("transaction_refnum");
            $table->integer("adj_warehouse_id")->unsigned();
            $table->text("adjustment_remarks");
            $table->text("adjustment_memo");
            $table->date("date_created");
            $table->datetime("created_at");
            $table->integer("adj_user_id")->unsigned()->nullable();
            $table->double("adjustment_amount");

            $table->foreign("adj_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
            $table->foreign("adj_user_id")->references("user_id")->on("tbl_user")->onDelete("cascade");
        });
        Schema::create('tbl_inventory_adjustment_line', function (Blueprint $table) {
            $table->increments('itemline_id');
            $table->integer("itemline_ia_id")->unsigned();
            $table->integer("itemline_item_id")->unsigned();
            $table->text("itemline_item_description");
            $table->integer("itemline_item_um");
            $table->double("itemline_actual_qty");
            $table->double("itemline_new_qty");
            $table->double("itemline_diff_qty");
            $table->double("itemline_rate");
            $table->double("itemline_amount");

            $table->foreign("itemline_ia_id")->references("inventory_adjustment_id")->on("tbl_inventory_adjustment")->onDelete("cascade");
            $table->foreign("itemline_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_inventory_adjustment');
    }
}
