<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmPointLogSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_point_log_setting', function (Blueprint $table) {
            $table->increments("point_log_setting_id");
            $table->string("point_log_setting_plan_code");
            $table->string("point_log_setting_name");
            $table->text("point_log_notification");
            $table->string("point_log_setting_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_mlm_point_log_setting');
    }
}
