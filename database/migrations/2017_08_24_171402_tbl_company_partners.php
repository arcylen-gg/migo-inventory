<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblCompanyPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tbl_company_partners', function (Blueprint $table) {
            $table->tinyInteger('archived')->default(0);
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')
                  ->references('shop_id')->on('tbl_shop')
                  ->onDelete('cascade');
            $table->increments('company_id');
            $table->string('company_name');
            $table->string('company_logo');
            $table->string('company_owner_name');
            $table->String('company_number');
            $table->string('company_address');
            $table->string('company_branch');
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
       Schema::drop('tbl_company_partners');
    }
}
