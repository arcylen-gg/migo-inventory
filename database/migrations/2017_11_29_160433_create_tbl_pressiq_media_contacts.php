<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPressiqMediaContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pressiq_media_contacts', function (Blueprint $table)
        {
            $table->increments("contact_id");
            $table->string("contact_name");
            $table->string("country");
            $table->string("contact_email")->unique();
            $table->string("contact_website");
            $table->string("contact_description");
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
