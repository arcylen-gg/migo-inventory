<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateSearchProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $statement = "create trigger trigger_product_search_update after update on tbl_variant
                    for each row
                    begin
                     update tbl_product_search set body = (select group_concat(product_name, ' ',  variant_name, ' ',variant_sku, ' ', variant_barcode) as product from view_product_variant where variant_id = new.variant_id) where variant_id = new.variant_id;
                    end ";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $drop = "DROP TRIGGER IF EXISTS trigger_product_search_update;";
        DB::unprepared($drop);
    }
}
