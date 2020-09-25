<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblCustomerAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('tbl_customer_attachment', function (Blueprint $table) {
            $table->string('mime_type')->after('customer_attachment_extension');
        });

        $statement = "ALTER TABLE tbl_customer_attachment AUTO_INCREMENT =100;";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
