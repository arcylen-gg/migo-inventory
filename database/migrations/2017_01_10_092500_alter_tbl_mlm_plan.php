<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_plan', function (Blueprint $table) {
            //
            // marketing_plan
            $table->integer("marketing_plan_enable_encash")->default(0);
            $table->integer("marketing_plan_enable_product_repurchase")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_plan', function (Blueprint $table) {
            //
        });
    }
}
