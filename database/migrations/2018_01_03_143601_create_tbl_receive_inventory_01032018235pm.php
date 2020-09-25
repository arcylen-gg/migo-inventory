<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReceiveInventory01032018235pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receive_inventory', function (Blueprint $table) {
            $table->increments('ri_id');
            $table->string('transaction_refnum');
            $table->integer('ri_new_id');
            $table->integer('ri_shop_id')->unsigned();
            $table->integer('ri_vendor_id')->unsigned();
            $table->string('ri_vendor_email');
            $table->integer('ri_ap_account');
            $table->text('ri_mailing_address');
            $table->integer('ri_terms_id');
            $table->date('ri_date');
            $table->date('ri_due_date');
            $table->double('ri_total_amount');
            $table->integer('ri_payment_method');
            $table->string('ri_memo');
            $table->date('date_created');
            $table->tinyInteger('ri_is_paid');
            $table->double('ri_applied_payment');
            $table->tinyInteger('inventory_only');

            $table->foreign("ri_vendor_id")->references("vendor_id")->on("tbl_vendor")->onDelete("cascade");
            $table->foreign("ri_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
