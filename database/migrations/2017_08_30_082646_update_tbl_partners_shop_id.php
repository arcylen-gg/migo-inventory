<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPartnersShopId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_partners', function (Blueprint $table) {
            $table->tinyInteger('archived')->default(0);
            $table->integer('shop_id')->unsigned()->nullable();

            $table->foreign('shop_id')
                  ->references('shop_id')->on('tbl_shop')
                  ->onDelete('cascade');
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
