<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmEncashmentDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_encashment_process_details', function (Blueprint $table) {
            $table->increments('encashment_process_details_id');
            $table->integer('encashment_process')->unsigned();
            $table->integer('encashment_bank_deposit_id')->unsigned();
            $table->integer('encashment_type')->default(0);
            $table->string('bank_name');
            $table->string('bank_account_branch');
            $table->string('bank_account_name');
            $table->string('bank_account_number');
            $table->string('cheque_name');
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
