<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerSearchProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $statement = "create trigger trigger_product_search after insert on tbl_variant
                    for each row
                    begin
                     insert into tbl_product_search (variant_id, body) values (new.variant_id, (select group_concat(product_name, ' ',  variant_name, ' ',variant_sku, ' ', variant_barcode) as product from view_product_variant where variant_id = new.variant_id));
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
        $drop = "DROP TRIGGER IF EXISTS trigger_product_search;";
        DB::unprepared($drop);
        
    }
}
