<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblShopEvent101117134PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_shop_event', function (Blueprint $table) {
            $table->integer('event_number_attendee')->after('event_attendee');
        }); 
        Schema::table('tbl_shop_event_reserved', function (Blueprint $table) {
            $table->dropForeign('tbl_shop_event_reserved_customer_id_foreign');
            $table->dropColumn('reverse_date');
        });
        Schema::table('tbl_shop_event_reserved', function (Blueprint $table) {
            $table->integer('customer_id')->nullable()->default(0)->change();
            $table->datetime('reserve_date')->after('customer_id');
            $table->string('reservee_fname');
            $table->string('reservee_mname');
            $table->string('reservee_lname');
            $table->string('reservee_address');
            $table->string('reservee_contact');
            $table->string('reservee_enrollers_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_shop_event', function (Blueprint $table) {
            //
        });
    }
}
