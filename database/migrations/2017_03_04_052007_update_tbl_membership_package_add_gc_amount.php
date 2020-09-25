<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMembershipPackageAddGcAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership_package', function (Blueprint $table) {
            $table->integer("membership_package_is_gc")->defaul(0);
            $table->double("membership_package_gc_amount");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_membership_package', function (Blueprint $table) {
            //
        });
    }
}
