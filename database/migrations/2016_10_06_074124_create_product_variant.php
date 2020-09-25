<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_option_name', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('option_name_id');
            $table->string('option_name');
        });
        
        Schema::create('tbl_option_value', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('option_value_id');
            $table->string('option_value');
        });
        
        Schema::create('tbl_variant', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('variant_id');
            $table->integer('variant_product_id')->unsigned();
            $table->tinyInteger('variant_single')->unsigned();
            $table->double('variant_price',18,2)->default(0);
            $table->double('variant_compare_price',18,2)->default(0);
            $table->tinyInteger('variant_charge_taxes');
            $table->string('variant_sku');
            $table->string('variant_barcode');
            $table->tinyInteger('variant_track_inventory');
            $table->tinyInteger('variant_allow_oos_purchase');
            $table->integer('variant_inventory_count');
            $table->double('variant_weight');
            $table->string('variant_weight_lbl');
            $table->tinyInteger('variant_require_shipping');
            $table->integer('popularity');
            $table->string('variant_fulfillment_service')->nullable();
            $table->integer('variant_main_image')->unsigned();
            $table->dateTime('variant_date_created');
            $table->dateTime('variant_date_visible');
            $table->tinyInteger('archived');
            $table->foreign('variant_product_id')->references('product_id')->on('tbl_product')->onDelete('cascade');
        });
        
        Schema::create('tbl_variant_name', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('variant_name_id');
            $table->integer('variant_name_order')->unsigned();
            $table->integer('variant_id')->unsigned();
            $table->integer('option_name_id')->unsigned();
            $table->integer('option_value_id')->unsigned();

            $table->foreign('variant_id')->references('variant_id')->on('tbl_variant')->onDelete('cascade');
            $table->foreign('option_name_id')->references('option_name_id')->on('tbl_option_name')->onDelete('cascade');
            $table->foreign('option_value_id')->references('option_value_id')->on('tbl_option_value')->onDelete('cascade');
        });
        
        DB::Statement('CREATE VIEW view_product_variant AS 
        SELECT product_id, product_name, variant.variant_id, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR " • ") AS variant_name, variant_price, variant_sku, variant_barcode,variant_allow_oos_purchase, variant_track_inventory, variant_inventory_count
        FROM tbl_product product INNER JOIN tbl_variant variant ON product.product_id = variant.variant_product_id
        LEFT JOIN tbl_variant_name var_name ON variant.variant_id = var_name.variant_id
        LEFT JOIN tbl_option_name op_name ON var_name.option_name_id = op_name.option_name_id
        LEFT JOIN tbl_option_value op_value ON var_name.option_value_id = op_value.option_value_id
        WHERE variant.archived = 0
        GROUP BY variant.variant_id');

        DB::Statement('CREATE VIEW view_variant_option AS 
        SELECT product_id, option_name, concat(product_id,"-",option_name) AS alias, GROUP_CONCAT(distinct(option_value) ORDER BY variant_name_order SEPARATOR ",") AS variant_value 
        FROM tbl_product product INNER JOIN tbl_variant variant ON product.product_id = variant.variant_product_id
        JOIN tbl_variant_name var_name ON variant.variant_id = var_name.variant_id
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

    }
}
