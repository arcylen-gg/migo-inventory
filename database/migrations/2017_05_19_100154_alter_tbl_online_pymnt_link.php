<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblOnlinePymntLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_online_pymnt_link', function (Blueprint $table) {
            $table->string('link_description')->after('link_reference_id');
            $table->float('link_discount_fixed')->after('link_description');
            $table->integer('link_discount_percentage')->after('link_discount_fixed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_online_pymnt_link', function (Blueprint $table) {
            //
        });
    }
}
