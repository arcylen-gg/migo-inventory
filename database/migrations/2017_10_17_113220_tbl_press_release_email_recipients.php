<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblPressReleaseEmailRecipients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_press_release_recipients', function (Blueprint $table) {
            $table->increments('recipient_id');
            $table->string('research_email_address');
            $table->string('company_name');
            $table->string('name');
            $table->string('position');
            $table->string('title_of_journalist');
            $table->string('country');
            $table->string('industry_type');
            $table->timestamp('created_at');
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
