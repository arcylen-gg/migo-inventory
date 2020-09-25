<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatCustomerSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_search', function (Blueprint $table) {
            $table->engine = 'MyISAM'; // means you can't use foreign key constraints
            $table->increments('id');
            $table->integer('customer_id');
            $table->text('body');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE tbl_customer_search ADD FULLTEXT search(body)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_customer_search');
    }
}
