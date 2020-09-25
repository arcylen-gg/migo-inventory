<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblItemCodeItemAddDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code_item', function (Blueprint $table) {
            //
            $table->double('item_membership_discount')->default(0);
            $table->double('item_membership_discounted')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item_code_item', function (Blueprint $table) {
            //
        });
    }
}
