<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemRangeSalesDiscount11052018121PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_range_sales_discount', function (Blueprint $table) {
            $table->increments("range_id");
            $table->integer("range_item_id")->unsigned();
            $table->double("range_qty");
            $table->double("range_new_price_per_piece");
            $table->datetime("range_date_created");
            $table->integer("range_user_id")->unsigned();

            $table->foreign("range_item_id")->references("item_id")->on("tbl_item")->onCascade("delete");
            $table->foreign("range_user_id")->references("user_id")->on("tbl_user")->onCascade("delete");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item_range_sales_discount', function (Blueprint $table) {
            //
        });
    }
}
