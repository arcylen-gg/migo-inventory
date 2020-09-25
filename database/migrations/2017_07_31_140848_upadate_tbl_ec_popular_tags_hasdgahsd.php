<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpadateTblEcPopularTagsHasdgahsd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_ec_popular_tags', function (Blueprint $table) {
            $table->tinyInteger("tag_approved")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_ec_popular_tags', function (Blueprint $table) {
            //
        });
    }
}
