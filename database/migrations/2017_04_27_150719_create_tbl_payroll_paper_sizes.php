<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollPaperSizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_paper_sizes', function (Blueprint $table) {
            $table->increments('payroll_paper_sizes_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelede('cascade');
            $table->string('paper_size_name');
            $table->double('paper_size_width', 18,2);
            $table->double('paper_size_height', 18,2);
            $table->tinyInteger('paper_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_paper_sizes');
    }
}
