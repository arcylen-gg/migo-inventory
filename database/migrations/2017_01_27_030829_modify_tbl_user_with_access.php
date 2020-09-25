<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTblUserWithAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tbl_user_position'))
        {
            Schema::create('tbl_user_position', function (Blueprint $table) {
                $table->increments('position_id');
                $table->integer('position_shop_id');
                $table->string('position_name');
                $table->integer('position_rank');
                $table->tinyInteger('archived');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tbl_user_access'))
        {  
            Schema::create('tbl_user_access', function (Blueprint $table) {
                $table->increments('access_id');
                $table->integer('access_position_id');
                $table->string('access_page_code');
                $table->string('access_name');
                $table->integer('access_permission');
                $table->timestamps();
            });
        }

        Schema::table('tbl_user', function (Blueprint $table){
            $table->integer('user_level')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_user_position');
    }
}
