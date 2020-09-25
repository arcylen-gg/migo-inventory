<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuantityMonitoring081620180234pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_quantity_monitoring', function (Blueprint $table) {
            $table->increments('qty_monitoring_id');
            $table->integer("qty_item_id")->unsigned();
            $table->integer("qty_transaction_id");
            $table->string("qty_transaction_name");
            $table->integer("qty_transactionline_id");
            $table->integer("qty_ref_id");
            $table->string("qty_ref_name");
            $table->integer("qty_refline_id");
            $table->integer("qty_old")->default(0);
            $table->integer("qty_new")->default(0);
            $table->datetime("created_at");
            $table->datetime("updated_at");
            $table->foreign('qty_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_quantity_monitoring', function (Blueprint $table) {
            //
        });
    }
}
