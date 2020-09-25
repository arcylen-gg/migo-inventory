<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMembershipPointsAddSingleLineBinary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership_points', function (Blueprint $table) {
            //
            $table->double("membership_points_binary_single_line")->default(0);
            $table->double("membership_points_binary_single_line_limit")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_membership_points', function (Blueprint $table) {
            //
        });
    }
}
