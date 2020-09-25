<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmGc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_gc', function (Blueprint $table) {
            $table->increments('mlm_gc_id');
            $table->string('mlm_gc_tag');
            $table->string('mlm_gc_code');
            $table->double('mlm_gc_amount');
            $table->integer('mlm_gc_member');
            $table->integer('mlm_gc_slot');
            $table->datetime('mlm_gc_date');
            $table->integer('mlm_gc_used')->default(0);
            $table->datetime('mlm_gc_used_date');
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
