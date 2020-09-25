<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSirSalesReportV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sir_sales_report', function (Blueprint $table) {
            $table->increments('sir_sales_report_id');
            $table->integer("sir_id");
            $table->longtext("report_data");
            $table->datetime("report_created");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sir_sales_report');
    }
}
