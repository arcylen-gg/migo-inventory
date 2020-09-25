<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblAuditTrail2232323 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_audit_trail', function (Blueprint $table) {
            $table->increments('audit_trail_id');
            $table->integer("user_id")->unsigned();
            $table->text("logs");
            $table->text("old_data")->nullable();
            $table->text("new_data")->nullable();
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
        Schema::drop('tbl_audit_trail');
    }
}
