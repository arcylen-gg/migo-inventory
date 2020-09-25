<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayoutMethodCheque102817200PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot_money_remittance', function (Blueprint $table) {
            $table->increments('money_remittance_id');
            $table->string('money_remittance_type')->comments('Palawan Express, Cebuana & etc');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('contact_number');

            $table->integer('slot_id')->unsigned();
            $table->foreign('slot_id')->references('slot_id')->on('tbl_mlm_slot')->onDelete('cascade');
        });
        Schema::create('tbl_mlm_slot_coinsph', function (Blueprint $table) {
            $table->increments('coinsph_id');
            $table->text('wallet_address');
            $table->integer('slot_id')->unsigned();

            $table->foreign('slot_id')->references('slot_id')->on('tbl_mlm_slot')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_mlm_slot_cheque');
    }
}
