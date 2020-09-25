<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTrackingAcctgTransaction12222017428PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acctg_tracking', function (Blueprint $table) {
            $table->increments('acctg_tracking_id');
            $table->integer("current_using");
            $table->string("current_transaction_name");
            $table->string("attached_transaction_name");
            $table->integer("attached_transaction_id");
            $table->datetime("date_created");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_acctg_tracking');
    }
}
