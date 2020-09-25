<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblAcctgTransaction02072018 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_acctg_transaction', function (Blueprint $table) {
            $table->integer("transaction_user_id")->unsigned()->nullable();
            $table->binary("acctg_transaction_history");
            $table->datetime("transaction_created_at");

            $table->foreign("transaction_user_id")->references("user_id")->on("tbl_user")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_acctg_transaction', function (Blueprint $table) {
            //
        });
    }
}
