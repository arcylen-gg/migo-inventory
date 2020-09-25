<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_package', function (Blueprint $table) {
                    $table->increments('membership_package_id');
                    $table->integer('membership_id');
                    $table->string('membership_package_name');
                    $table->tinyinteger('membership_package_archive');
                    $table->datetime('membership_package_created');
                    $table->double('membership_package_weight');
                    $table->string('membership_package_weight_unit');
                    $table->double('membership_package_size_w');
                    $table->double('membership_package_size_h');
                    $table->double('membership_package_size_l');
                    $table->string('membership_package_size_unit');
                });
                // Schema::table('tbl_membership_package', function($table) {
                //     $table->foreign('membership_id')->references('membership_id')->on('tbl_membership');
                // });
                // Schema::table('tbl_membership', function($table) {
                //     $table->integer('shop_id');
                // });
                // Schema::table('tbl_membership', function($table) {
                //   $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
                // });
                // shop_id
                // tbl_shop
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
