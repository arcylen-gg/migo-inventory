<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmPlanBinaryPromotionsAddArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_plan_binary_promotions', function (Blueprint $table) {
            //
            $table->integer("binary_promotions_archive")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_plan_binary_promotions', function (Blueprint $table) {
            //
        });
    }
}
