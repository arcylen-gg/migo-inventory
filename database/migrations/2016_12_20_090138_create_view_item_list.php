<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewItemList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::Statement('CREATE VIEW view_item_list AS 
        SELECT  "product" AS type, 
                variant.variant_id AS item_id, 
                product_shop AS item_shop, 
                product_name AS item_name, 
                group_concat(o_value.option_value separator " • ") AS item_variations, 
                type_name AS item_category_name, 
                type_parent_id AS item_parent_id, 
                type_sub_level AS item_sublevel, 
                variant_price AS item_sale_price, 
                product_description AS 
                item_sale_description, 
                product_income_account AS item_income_account, 
                variant_purchase_price AS item_purchase_price, 
                product_purchase_description AS item_purchase_description, 
                product_cogs_exp_account AS item_cogs_exp_account, 
                product_asset_account AS item_asset_account
            FROM tbl_product join tbl_category on product_shop = type_id
            LEFT JOIN tbl_variant variant on variant_product_id = product_id
            LEFT JOIN tbl_variant_name v_name on v_name.variant_id = variant.variant_id
            LEFT JOIN tbl_option_value o_value on o_value.option_value_id = v_name.option_value_id
            GROUP BY variant.variant_id
        UNION
        SELECT  "service", 
                service_id, 
                service_shop, 
                service_name, 
                "null", 
                type_name, 
                type_parent_id, 
                type_sub_level, 
                service_price, 
                service_description,
                service_income_account, 
                service_purchase_price, 
                service_purchase_description, 
                service_expense_account, 
                "null"
            FROM tbl_service join tbl_category on service_shop = type_id');
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
