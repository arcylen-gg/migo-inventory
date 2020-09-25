<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayollRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_requirements', function (Blueprint $table) {
            $table->increments('payroll_requirements_id');
            $table->integer('shop_id');
            $table->string('payroll_requirements_path');
            $table->string('payroll_requirements_original_name');
            $table->string('payroll_requirements_extension_name');
            $table->string('payroll_requirements_mime_type');
            $table->date('payroll_requirements_date_upload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_requirements');
    }
}
