<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblSirItem34s5d67 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_sir_item', function (Blueprint $table) {
            $table->integer("related_um_type");
            $table->integer("um_qty");
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
