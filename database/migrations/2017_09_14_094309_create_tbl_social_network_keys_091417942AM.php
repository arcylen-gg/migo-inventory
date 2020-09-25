<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSocialNetworkKeys091417942AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_social_network_keys', function (Blueprint $table) {
            $table->increments('keys_id');
            $table->integer('shop_id')->unsigned();
            $table->string('social_network_name')->comment('Eg. Facebook, Google+ & etc.');
            $table->text('app_id');
            $table->text('app_secret');
            $table->datetime('app_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_social_network_keys');
    }
}
