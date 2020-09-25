<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSalesRepresentative10112019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sales_representative', function (Blueprint $table) {
            $table->increments('sales_rep_id');
            $table->string('sales_rep_employee_number')->nullable();
            $table->string('sales_rep_first_name');
            $table->string('sales_rep_middle_name')->nullable();
            $table->string('sales_rep_last_name');
            $table->string('sales_rep_contact_no')->nullable();
            $table->longtext('sales_rep_address')->nullable();
            $table->integer('sales_rep_shop_id')->nullable()->unsigned();
            $table->foreign('sales_rep_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->integer('sales_rep_customer_id')->nullable()->unsigned();
            $table->foreign('sales_rep_customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->tinyInteger('sales_rep_archived')->default(0);
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_sales_representative');
    }
}
