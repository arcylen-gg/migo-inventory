<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerWis122017112PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_wis_item_line', function (Blueprint $table) {
            $table->increments("itemline_id");
            $table->integer('itemline_item_id')->unsigned();
            $table->text('itemline_description');
            $table->integer('itemline_um')->nullable();
            $table->double('itemline_qty');
            $table->double('itemline_amount');

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
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            //
        });
    }
}
