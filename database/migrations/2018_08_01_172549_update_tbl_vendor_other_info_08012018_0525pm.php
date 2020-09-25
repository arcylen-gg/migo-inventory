<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblVendorOtherInfo080120180525pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_vendor_other_info', function (Blueprint $table) {
            $table->longtext('ven_info_notes')->nullable()->after('ven_info_billing');
            $table->string('ven_info_tin_no')->nullable()->after('ven_info_tax_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_vendor_other_info', function (Blueprint $table) {
            //
        });
    }
}
