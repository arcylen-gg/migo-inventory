<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipPackageHas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_package_has', function (Blueprint $table) {
                    $table->increments('membership_package_has_id');
                    $table->integer('membership_package_id');
                    $table->integer('variant_id');
                    $table->integer('product_id');
                    $table->tinyinteger('membership_package_has_archive');
                    $table->integer('membership_package_has_quantity');
                });
                // Schema::table('tbl_membership_package_has', function($table) {
                //     $table->foreign('membership_package_id')->references('membership_package_id')->on('tbl_membership_package');
                //     $table->foreign('variant_id')->references('variant_id')->on('tbl_variant');
                //     $table->foreign('product_id')->references('product_id')->on('tbl_product');
                // });
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
