<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCmsJobResume extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cms_job_resume', function (Blueprint $table) 
        {
            $table->increments('cms_job_resume_id')->unsigned();
            $table->string('job_apply');
            $table->string('job_resume');
            $table->text('job_introduction');
            $table->datetime('date_created');
            $table->tinyInteger('archived')->default(0);
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
