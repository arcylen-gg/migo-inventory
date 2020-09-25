<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('tbl_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->string('user_pic');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('mobile_number');
            $table->string('password');
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
    }
}
