<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmPlanBinaryPromotionsRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_plan_binary_promotions_log', function (Blueprint $table) {

            $table->increments('promotions_request_id');
            $table->integer('promotions_request_slot')->default(0);
            $table->integer('promotions_request_binary_promotions_id')->default(0);
            $table->string('promotions_request_item_name')->nullable();
            $table->date('promotions_request_date');
            $table->integer('promotions_request_consume_l')->default(0);
            $table->integer('promotions_request_consume_r')->default(0);

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
