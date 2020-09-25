<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductPutArchived extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_product', function (Blueprint $table) {
            $table->tinyInteger('archived');
        });

        Schema::table('tbl_ec_variant', function (Blueprint $table) {
            $table->tinyInteger('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_product', function (Blueprint $table) {
            //
        });
    }
}
