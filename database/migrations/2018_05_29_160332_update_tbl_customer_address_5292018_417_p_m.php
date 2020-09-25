<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerAddress5292018417PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_address', function (Blueprint $table) {
            $table->dropForeign('tbl_customer_address_customer_id_foreign');
            $table->dropForeign('tbl_customer_address_country_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_address', function (Blueprint $table) {
            //
        });
    }
}
