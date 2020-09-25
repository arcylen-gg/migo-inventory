<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_journal_entry extends Model
{
	protected $table = 'tbl_journal_entry';
	protected $primaryKey = "je_id";
    public $timestamps = true;

    public function scopeLine($query)
    {
    	return $query->leftjoin("tbl_journal_entry_line","jline_je_id","=","je_id");
    }

    public function scopeTransaction($query, $reference = null)
    {
        $query->selectRaw("*, (CASE je_reference_module
                                
                                WHEN 'invoice' THEN concat('/member/customer/invoice?id=', je_reference_id) 
                                WHEN 'sales-receipt' THEN concat('/member/customer/sales_receipt?id=', je_reference_id)
                                WHEN 'credit-memo' THEN concat('/member/customer/credit_memo?id=', je_reference_id)
                                WHEN 'receive-payment' THEN concat('/member/customer/receive_payment?id=', je_reference_id)
                                WHEN 'bill' THEN concat('/member/transaction/enter_bills/create?id=', je_reference_id)
                                WHEN 'debit-memo' THEN concat('/member/vendor/debit_memo?id=', je_reference_id)
                                WHEN 'bill-payment' THEN concat('/member/vendor/paybill?id=', je_reference_id)
                                WHEN 'write-check' THEN concat('/member/transaction/write_check/create?id=', je_reference_id)
                                WHEN 'mlm-product-repurchase' THEN concat('/member/mlm/product_code/receipt?invoice_id=', je_reference_id)
                                WHEN 'product-order' THEN concat('/member/ecommerce/product_order/create_order?id=', je_reference_id)
                                WHEN 'journal-entry' THEN concat('/member/accounting/journal?id=', je_id)
                                END) as txn_link");
        if($reference)
        {
            $query->where("je_reference_module", $reference);
        }
        
        return $query;
    }
}