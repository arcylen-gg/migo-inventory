<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblBinaryReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_binary_pairing_log', function (Blueprint $table) {
            $table->increments('pairing_id');
            $table->integer('pairing_point_l')->default(0);
            $table->integer('pairing_point_r')->default(0);
            $table->integer('pairing_income')->default(0);
            $table->integer('pairing_slot')->default(0);
            $table->integer('pairing_slot_entry')->default(0);
            $table->date('pairing_date');
        });

        Schema::create('tbl_mlm_binary_report', function (Blueprint $table) {
            $table->increments('binary_report_id');
            $table->integer('binary_report_slot')->default(0);
            $table->integer('binary_report_slot_g')->default(0);
            $table->integer('binary_report_s_points')->default(0);
            $table->integer('binary_report_s_left')->default(0);
            $table->integer('binary_report_s_right')->default(0);
            $table->integer('binary_report_e_left')->default(0);
            $table->integer('binary_report_e_right')->default(0);
            $table->integer('binary_report_tree_level')->default(0);
            $table->date('binary_report_date');
            $table->string('binary_report_reason')->default('Slot Creation');
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
