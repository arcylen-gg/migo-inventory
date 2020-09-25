<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblManufacturer3456 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_manufacturer', function (Blueprint $table) {
            $table->increments('manufacturer_id');
            $table->string("manufacturer_name");
            $table->string("manufacturer_address");
            $table->string("phone_number");
            $table->string("email_address");
            $table->text("website");
            $table->datetime("date_created");
            $table->datetime("date_updated");
            $table->tinyInteger("archived")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_manufacturer');
    }
}
