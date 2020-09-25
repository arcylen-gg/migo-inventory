<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCreditMemoAppliedPayment122912953AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_credit_memo_applied_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("cm_id")->unsigned();
            $table->string("applied_ref_name");
            $table->integer("applied_ref_id");
            $table->double("applied_amount");
            $table->datetime("created_at");

            $table->foreign("cm_id")->references("cm_id")->on("tbl_credit_memo")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_credit_memo_applied_payment');
    }
}
