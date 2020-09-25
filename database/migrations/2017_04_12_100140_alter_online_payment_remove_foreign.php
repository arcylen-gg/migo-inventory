<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOnlinePaymentRemoveForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_online_pymnt_api', function (Blueprint $table) {
            $table->dropForeign("tbl_online_pymnt_api_api_gateway_id_foreign");
        });

        Schema::table('tbl_online_pymnt_link', function (Blueprint $table) {
            $table->dropForeign("tbl_online_pymnt_link_link_method_id_foreign");
        });

        Schema::table('tbl_online_pymnt_other', function (Blueprint $table) {
            $table->dropForeign("tbl_online_pymnt_other_other_gateway_id_foreign");
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_online_pymnt', function (Blueprint $table) {
            //
        });
    }
}
