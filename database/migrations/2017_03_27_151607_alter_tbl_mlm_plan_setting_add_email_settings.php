<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMlmPlanSettingAddEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_plan_setting', function (Blueprint $table) {
            //
            $table->integer('plan_settings_email_membership_code')->default(0);
            $table->integer('plan_settings_email_product_code')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mlm_plan_setting', function (Blueprint $table) {
            //
        });
    }
}
