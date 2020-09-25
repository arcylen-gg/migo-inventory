<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMlmPlanSettingsEnablePrivilege extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_mlm_plan_setting', function (Blueprint $table) 
        {
            $table->tinyInteger('enable_privilege_system')->default(0);
        });

        Schema::table('tbl_membership', function (Blueprint $table) 
        {
            $table->tinyInteger('membership_privilege')->default(0);
        });
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
