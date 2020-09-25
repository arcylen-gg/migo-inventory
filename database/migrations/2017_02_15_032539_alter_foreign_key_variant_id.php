<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterForeignKeyVariantId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_variant_name', function (Blueprint $table) {
            $table->dropForeign("tbl_variant_name_variant_id_foreign");
            $table->foreign("variant_id")->references("evariant_id")->on("tbl_ec_variant")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_variant_name', function (Blueprint $table) {
            //
        });
    }
}
