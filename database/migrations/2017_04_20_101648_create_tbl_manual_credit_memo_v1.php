<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblManualCreditMemoV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_manual_credit_memo', function (Blueprint $table) {
            $table->increments('manual_cm_id');
            $table->integer("sir_id")->unsigned();
            $table->integer("cm_id");
            $table->datetime("manual_cm_date");
            $table->tinyInteger("is_sync")->default(0);

            $table->foreign("sir_id")->references("sir_id")->on("tbl_sir")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_manual_credit_memo');
    }
}
