<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItem061420180454pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item', function (Blueprint $table) {
            $table->dropForeign('tbl_item_item_warehouse_id_foreign');
        });

        Schema::table('tbl_item', function (Blueprint $table) {
            $table->dropColumn('item_warehouse_id');
        });

        Schema::table('tbl_item', function (Blueprint $table) {
            $table->binary('item_warehouse_id')->nullable();
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
