<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblSirItem3456 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_sir_item', function (Blueprint $table) {
            $table->integer("sold_qty");
            $table->integer("remaining_qty");
            $table->integer("physical_count");
            $table->string("status");
            $table->string("info");
            $table->decimal("loss_amount");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_sir_item', function (Blueprint $table) {
            //
        });
    }
}
