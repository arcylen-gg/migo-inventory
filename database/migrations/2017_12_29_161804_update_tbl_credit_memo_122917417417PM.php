<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCreditMemo122917417417PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->tinyInteger("cm_status")->default(0)->after('cm_used_ref_id');
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
