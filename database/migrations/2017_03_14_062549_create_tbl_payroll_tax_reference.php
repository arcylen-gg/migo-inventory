<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTaxReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_tax_reference', function (Blueprint $table) {
            $table->increments('payroll_tax_reference_id');
            $table->integer('payroll_tax_status_id');
            $table->integer('shop_id');
            $table->string('tax_category');
            $table->double('tax_first_range', 18, 2);
            $table->double('tax_second_range', 18, 2);
            $table->double('tax_third_range', 18, 2);
            $table->double('tax_fourth_range', 18, 2);
            $table->double('tax_fifth_range', 18, 2);
            $table->double('taxt_sixth_range', 18, 2);
            $table->double('tax_seventh_range', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_tax_reference');
    }
}
