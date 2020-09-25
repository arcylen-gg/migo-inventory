<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRemarksColumn021320180514pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            $table->text('ri_remarks')->after('ri_memo');
        });

        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->text('bill_remarks')->after('bill_memo');
        });

        Schema::table('tbl_write_check', function (Blueprint $table) {
            $table->text('wc_remarks')->after('wc_memo');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_receive_inventory', function (Blueprint $table) {
            //
        });
    }
}
