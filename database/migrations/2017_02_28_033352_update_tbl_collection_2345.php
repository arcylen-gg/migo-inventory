<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCollection2345 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_collection', function (Blueprint $table) {
            $table->dropColumn("status");
            $table->tinyInteger("collection_status");
            $table->dropColumn("hide");
            $table->renameColumn("note","collection_description");
        });

         Schema::table('tbl_collection_item', function (Blueprint $table) {
            $table->dropForeign("tbl_collection_item_variant_id_foreign");
            $table->renameColumn("variant_id","ec_product_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_collection', function (Blueprint $table) {
            //
        });
    }
}
