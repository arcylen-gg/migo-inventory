<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTransactionInprogress9101126AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acctg_transaction_inprogress', function (Blueprint $table) {
            $table->increments('inprogress_id');
            $table->integer("acctg_shop_id")->unsigned();
            $table->integer("acctg_warehouse_id")->unsigned();
            $table->integer("acctg_user_id")->unsigned();
            $table->string("transaction_name");
            $table->integer("transaction_id")->default(0);
            $table->string("transaction_ref_num");
            $table->datetime("date_created");

            $table->foreign("acctg_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
            $table->foreign("acctg_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
            $table->foreign("acctg_user_id")->references("user_id")->on("tbl_user")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_transaction_inprogress');
    }
}
