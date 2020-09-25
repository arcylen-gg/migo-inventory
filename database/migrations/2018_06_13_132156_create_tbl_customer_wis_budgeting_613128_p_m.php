<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerWisBudgeting613128PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_wis_budget', function (Blueprint $table) {
            $table->increments('wis_budget_id');
            $table->integer('budget_shop_id')->nullable()->unsigned();
            $table->integer('budget_wis_id')->nullable()->unsigned();
            
            $table->double('budget_adjusted')->default(0)->nullable();
            
            $table->string('current_budget_month')->nullable();
            $table->double('current_budget_month_amount')->default(0)->nullable();

            $table->string('prev_budget_month')->nullable();
            $table->double('prev_budget_month_amount')->default(0)->nullable();

            $table->string('adj_budget_month')->nullable();
            $table->double('adj_budget_month_amount')->default(0)->nullable();

            $table->double('total_item_less_amount')->default(0)->nullable();

            $table->string('total_budget_month')->nullable();
            $table->double('total_budget_month_amount')->default(0)->nullable();

            $table->foreign("budget_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
            $table->foreign("budget_wis_id")->references("cust_wis_id")->on("tbl_customer_wis")->onDelete("cascade");
        });

        Schema::create('tbl_customer_wis_budgetline', function (Blueprint $table) {
            $table->increments('wis_budgetline_id');
            $table->integer('budgetline_id')->nullable()->unsigned();
            $table->integer("budgetline_item_id")->nullable()->unsigned();
            $table->double("budgetline_item_qty")->nullable();
            $table->double("budgetline_item_amount")->nullable();

            $table->foreign("budgetline_id")->references("wis_budget_id")->on("tbl_customer_wis_budget")->onDelete("cascade");
            $table->foreign("budgetline_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customer_wis_budgeting');
    }
}
