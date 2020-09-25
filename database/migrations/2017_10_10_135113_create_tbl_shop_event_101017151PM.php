<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblShopEvent101017151PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_shop_event', function (Blueprint $table) {
            $table->increments('event_id');
            $table->integer('event_shop_id');
            $table->string('event_title');
            $table->text('event_sub_title');
            $table->date('event_date');
            $table->longtext('event_description');
            $table->longtext('event_thumbnail_image');
            $table->longtext('event_banner_image');
            $table->string('event_attendee')->comment('all/members/guest - Event attendee');
            $table->tinyInteger('event_show')->default(1)->comment('Show in front or not');
            $table->tinyInteger('archived')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_shop_event');
    }
}
