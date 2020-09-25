<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblEcVariantForeginKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_variant', function (Blueprint $table) {
            $table->integer('evariant_prod_id')->unsigned()->change();
            $table->integer('evariant_item_id')->unsigned()->change();

            $table->foreign('evariant_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_variant', function (Blueprint $table) {
            //
        });
    }
}
