<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmPlanBinaryPromotionsAddDirect extends Migration
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
            $table->double('binary_promotions_repurchase_points')->default(0);
            $table->double('binary_promotions_direct')->default(0);
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
