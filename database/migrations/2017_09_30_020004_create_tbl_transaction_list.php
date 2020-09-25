<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTransactionList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaction_list', function (Blueprint $table)
        {
            $table->increments('transaction_list_id');

            $table->integer('transaction_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->integer('transaction_price_level')->default(0)->comment('Only has value if PRICE LEVEL wasa applied.');
            $table->integer('transaction_reference_id')->default(0)->comment('Link to SELF (tbl_transaction) - E.G Estimate -> Sales Order -> Invoice -> Receipt');

            $table->date('transaction_date')->comment('DATE - Can be set by USER');
            $table->date('transaction_due_date')->comment('applicable on INVOICE and BILLS');
            $table->dateTime('transaction_date_created')->comment('REAL TIME - when transaction was created.');
            $table->dateTime('transaction_date_updated')->comment('REAL TIME - when transaction was last edited.');

            $table->string('transaction_type')->default('RECEIPT')->comment('receipt, invoice, sales order');
            $table->string('transaction_number')->default(0)->comment('NUMBER that is unique per SHOP (based on the transaction_type)');

            $table->text('transaction_remark')->nullable()->comment('REMARKS that are shown in the invoice, receipt, etc.');
            $table->text('transaction_remark_hidden')->nullable()->comment('REMARKS that can only be seen by administrators, cashiers, etc.');

            $table->double('transaction_posted')->default(0)->comment('USED to determine CUSTOMER BALANCE and TRANSACTION BALANCE');
            $table->double('transaction_subtotal')->default(0)->comment('Total of ITEM PRICE');
            $table->double('transaction_tax')->default(0)->comment('APPLIED TAX IF ANY - breakdown of tax must be in other table (tbl_transaction_tax)');
            $table->double('transaction_discount')->value()->comment('DOUBLE means percentage discount and INTEGER means fixed discount');
            $table->double('transaction_total')->default(0)->comment('Final total after tax and discount');
            $table->string('transaction_status')->default('open')->comment('applicable for invoice, purchase order (open invoice and open P.O)');
            
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transaction')->onDelete('cascade');
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
