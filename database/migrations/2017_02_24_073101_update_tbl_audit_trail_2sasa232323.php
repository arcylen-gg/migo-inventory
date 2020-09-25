<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblAuditTrail2sasa232323 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_audit_trail', function (Blueprint $table) {
           $table->renameColumn("logs","remarks");
           $table->string("source");
           $table->integer("source_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_audit_trail', function (Blueprint $table) {
            //
        });
    }
}
