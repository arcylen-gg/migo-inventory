<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblReceivePaymentLineReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_payment_line', function (Blueprint $table) {
            $table->renameColumn('rpline_txn_type', 'rpline_reference_name');
            $table->renameColumn('rpline_txn_id', 'rpline_reference_id');
            
        });

        Schema::table('tbl_receive_payment_line', function (Blueprint $table) {
            $table->string('rpline_reference_name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_recive_payment_line', function (Blueprint $table) {
            //
        });
    }
}