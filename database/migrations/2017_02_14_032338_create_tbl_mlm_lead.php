<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmLead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_lead', function (Blueprint $table) {
            $table->increments('lead_id');
            $table->datetime('lead_join_date');
            $table->integer('lead_customer_id_sponsor');
            $table->integer('lead_customer_id_lead');
            $table->integer('lead_slot_id_sponsor');
            $table->integer('lead_sponsor_membership_code');
            $table->integer('lead_slot_id_lead');
            $table->integer('lead_used')->default(0);
            $table->datetime('lead_used_date')->nullable();
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
