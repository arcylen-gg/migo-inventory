<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCreditMemoSdsda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) 
        {
            $table->string("cm_used_ref_name")->default("returns");
            $table->integer("cm_used_ref_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            //
        });
    }
}
