<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmEncashmentProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_encashment_process', function (Blueprint $table) {
            $table->increments('encashment_process');
            $table->integer('shop_id')->unsigned();
            $table->datetime('enchasment_process_from');
            $table->datetime('enchasment_process_to');
            $table->datetime('enchasment_process_executed');
            $table->double('enchasment_process_tax');
            $table->integer('enchasment_process_tax_type');
            $table->double('enchasment_process_p_fee');
            $table->integer('enchasment_process_p_fee_type');
            $table->integer('encashment_process_sum');
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
