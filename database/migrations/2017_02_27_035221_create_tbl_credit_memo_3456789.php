<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCreditMemo3456789 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_credit_memo', function (Blueprint $table) {
            $table->increments('cm_id');
            $table->integer('cm_customer_id');
            $table->integer('cm_ar_acccount');
            $table->string('cm_customer_email');
            $table->date('cm_date');
            $table->string('cm_message');
            $table->string('cm_memo');
            $table->double('cm_amount');
            $table->datetime('date_created');
        });

        Schema::create('tbl_credit_memo_line', function (Blueprint $table) {
            $table->increments('cmline_id'); 
            $table->integer('cmline_cm_id')->unsigned();
            $table->datetime('cmline_service_date');
            $table->integer('cmline_um');
            $table->integer('cmline_item_id');
            $table->string('cmline_description');
            $table->integer('cmline_qty');
            $table->double('cmline_rate');

            $table->foreign('cmline_cm_id')->references('cm_id')->on('tbl_credit_memo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_credit_memo');
    }
}
