<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReportFieldA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('tbl_report_field')) 
        {
            Schema::create('tbl_report_field', function (Blueprint $table) 
            {

                $table->increments('report_field_id');
                $table->integer('report_field_shop')->default(0);
                $table->string('report_field_module')->default(0);
                $table->string('report_field_label')->default(0);
                $table->string('report_field_type')->default(0);
                $table->string('report_field_archive')->default(0);
            });
        }
        
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
