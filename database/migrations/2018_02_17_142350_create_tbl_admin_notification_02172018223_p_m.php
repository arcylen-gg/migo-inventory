<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblAdminNotification02172018223PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_admin_notification', function (Blueprint $table) {
            $table->increments('notification_id');
            $table->integer('notification_shop_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->text('notification_description');
            $table->string('transaction_refname');
            $table->integer('transaction_refid');
            $table->datetime('transaction_date');
            $table->string('transaction_status')->comment("pending || done");
            $table->datetime('created_date');

            $table->foreign("notification_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
            $table->foreign("user_id")->references("user_id")->on("tbl_user")->onDelete("cascade");
            $table->foreign("warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_admin_notification');
    }
}
