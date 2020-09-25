<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewEcommerceProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement('DROP VIEW view_product_variant');
        DB::Statement('DROP VIEW view_variant_option');
        DB::Statement('DROP VIEW view_item_list');

        DB::Statement('CREATE VIEW view_product_variant AS 
        SELECT eprod_id, evariant_id, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR " • ") AS variant_name, evariant_item_id, evariant_item_label, evariant_description
        FROM tbl_ec_product product INNER JOIN tbl_ec_variant variant ON eprod_id = evariant_prod_id
        LEFT JOIN tbl_variant_name var_name ON evariant_id = variant_id
        LEFT JOIN tbl_option_name op_name ON var_name.option_name_id = op_name.option_name_id
        LEFT JOIN tbl_option_value op_value ON var_name.option_value_id = op_value.option_value_id
        GROUP BY evariant_id');

        DB::Statement('CREATE VIEW view_variant_option AS 
        SELECT eprod_id, option_name, concat(eprod_id,"-",option_name) AS alias, GROUP_CONCAT(distinct(option_value) ORDER BY variant_name_order SEPARATOR ",") AS variant_value 
        FROM tbl_ec_product product INNER JOIN tbl_ec_variant variant ON eprod_id = evariant_prod_id
        JOIN tbl_variant_name var_name ON evariant_id = variant_id
        JOIN tbl_option_name op_name ON var_name.option_name_id = op_name.option_name_id
        JOIN tbl_option_value op_value ON var_name.option_value_id = op_value.option_value_id
        GROUP BY alias ORDER BY variant_name_order');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
