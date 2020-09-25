<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblBill010820180558PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->integer("bill_ri_id")->unsigned()->nullable()->after('bill_new_id');

            $table->foreign("bill_ri_id")->references("ri_id")->on("tbl_receive_inventory")->onDelete("cascade");
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            //
        });
    }
}
