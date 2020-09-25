<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItem8242018105PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('tbl_item', function (Blueprint $table) {
            
        // });
         DB::statement('ALTER TABLE tbl_item ADD FULLTEXT fulltext_index (item_name, item_sku, item_sales_information, item_purchasing_information, item_barcode)');
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
