<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItem060620180116pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item', function (Blueprint $table) {
            $table->integer("item_warehouse_id")->unsigned()->nullable()->after("item_id");
            $table->foreign("item_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->integer("customer_warehouse_id")->unsigned()->nullable()->after("customer_id");
            $table->foreign("customer_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item', function (Blueprint $table) {
            //
        });
    }
}
