<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_journal_entry_line extends Model
{
	protected $table = 'tbl_journal_entry_line';
	protected $primaryKey = "jline_id";
    public $timestamps = true;

    public function scopeAccount($query)
    {
    	return $query->leftjoin("tbl_chart_of_account", "account_id", "=", "jline_account_id")
    				 ->leftjoin("tbl_chart_account_type", "chart_type_id", "=", "account_type_id");
    }
    /* Dependent on Account() */
    public function scopeCustomerOnly($query)
    {
        return $query->selectRaw("(CASE normal_balance WHEN jline_type THEN jline_amount ELSE -jline_amount END) as 'amount'")
                     ->where("jline_name_reference", DB::raw("'customer'"));
    }
    /* Dependent on Account() */
    public function scopeVendorOnly($query)
    {
        return $query->selectRaw("(CASE normal_balance WHEN jline_type THEN jline_amount ELSE -jline_amount END) as 'amount'")
                     ->where("jline_name_reference", DB::raw("'vendor'"));
    }

    /* Add a column - Amount of each jounal accounting according to their normal balance */
    public function scopeAmount($query)
    {
        return $query->selectRaw("(CASE normal_balance WHEN jline_type THEN jline_amount ELSE -jline_amount END) as 'amount'");
    }

    /* Add a column - Amount of the total jounal accounting according to their normal balance */
    public function scopeTotalAmount($query)
    {
        return $query->selectRaw("*, sum(CASE normal_balance WHEN jline_type THEN jline_amount ELSE -jline_amount END) as 'amount'")
                     ->groupBy('jline_account_id');
    }

    public function scopeItem($query)
    {
    	return $query->leftjoin("tbl_item", "item_id", "=", "jline_item_id");
    }
    //RETURNS THE NAME OF THE CUSTOMER OR VENDOR INSIDE THE TRANSACTION
    public function scopeCustomerOrVendor($query)
    {
        return $query->selectRaw("*, CONCAT(IFNULL(first_name,''),IFNULL(vendor_first_name,''), ' ', IFNULL(middle_name,''),IFNULL(vendor_middle_name,''),' ', IFNULL(last_name,''),IFNULL(vendor_last_name,''),' ', IFNULL(company,''),IFNULL(vendor_company,'') ) as 'full_name'")
                     ->leftJoin("tbl_customer", function($on)
                        {
                            $on->on("customer_id", "=", "jline_name_id");
                            $on->on("jline_name_reference","=",DB::raw("'customer'"));
                        })
                      ->leftJoin("tbl_vendor", function($on)
                        {
                            $on->on("vendor_id", "=", "jline_name_id");
                            $on->on("jline_name_reference","=",DB::raw("'vendor'"));
                        });
    }
    public function scopeCustomerOrVendorv2($query)
    {
        return $query->selectRaw("*, concat(vendor_first_name,' ', vendor_middle_name, ' ',  vendor_last_name) as 'v_full_name', concat(first_name, ' ', middle_name,' ', last_name) as 'c_full_name', tbl_journal_entry_line.created_at as 'date_a'")
                        ->leftJoin("tbl_vendor", "vendor_id", "=", "jline_name_id")
                        ->leftJoin("tbl_customer", "customer_id", "=", "jline_name_id");
    }

    public function scopeSelectedLimit($query)
    {
        return $query->select("jline_item_id","jline_account_id","jline_type","jline_amount","jline_description");
    }

    public function scopeJournal($query)
    {
        return $query->join("tbl_journal_entry","je_id","=","jline_je_id");
    }
    public function scopeTransaction($query, $reference = null)
    {
        // $query->selectRaw("*, (CASE je_reference_module
        //                         WHEN 'invoice' THEN concat('/member/customer/invoice?id=', je_reference_id) 
        //                         WHEN 'sales-receipt' THEN concat('/member/customer/sales_receipt?id=', je_reference_id)
        //                         WHEN 'credit-memo' THEN concat('/member/customer/credit_memo?id=', je_reference_id)
        //                         WHEN 'receive-payment' THEN concat('/member/customer/receive_payment?id=', je_reference_id)
        //                         WHEN 'bill' THEN concat('/member/vendor/create_billt?id=', je_reference_id)
        //                         WHEN 'debit-memo' THEN concat('/member/vendor/debit_memo?id=', je_reference_id)
        //                         WHEN 'bill-payment' THEN concat('/member/vendor/paybill?id=', je_reference_id)
        //                         WHEN 'mlm-product-repurchase' THEN concat('/member/mlm/product_code/receipt?invoice_id=', je_reference_id)
        //                         WHEN 'product-order' THEN concat('/member/ecommerce/product_order/create_order?id=', je_reference_id)
        //                         WHEN 'journal-entry' THEN concat('/member/accounting/journal?id=', je_id)
        //                         END) as txn_link");

        $query->selectRaw("*, (CASE je_reference_module
                                WHEN 'invoice' THEN concat('/member/transaction/sales_invoice/print?id=', je_reference_id) 
                                WHEN 'sales-receipt' THEN concat('/member/transaction/sales_receipt/print?id=', je_reference_id)
                                WHEN 'credit-memo' THEN concat('/member/transaction/credit_memo/print?id=', je_reference_id)
                                WHEN 'receive-payment' THEN concat('/member/transaction/receive_payment/print?id=', je_reference_id)
                                WHEN 'bill' THEN concat('/member/transaction/enter_bills/print?id=', je_reference_id)
                                WHEN 'debit-memo' THEN concat('/member/transaction/debit_memo/print?id=', je_reference_id)
                                WHEN 'bill-payment' THEN concat('/member/transaction/pay_bills/print?id=', je_reference_id)
                                WHEN 'warehouse-issuance-slip' THEN concat('/member/transaction/wis/print?id=', je_reference_id)
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
    
    public function scopeSelectSales($query)
    {
        $sales_account[0] = 'Income';
        $sales_account[1] = 'Other Income';
        
        $query->whereIn('chart_type_name', $sales_account);

        return $query;
    }
    public function scopeJoinReciept($query)
    {
        $query->leftJoin('tbl_item_code_invoice', function($join){
            $join->on('item_code_invoice_id', '=', 'je_reference_id');
            $join->where('je_reference_module', '=', DB::raw('mlm-product-repurchase'));
        });
        return $query; 
    }
    public function scopeConcatUm($query)
    {
        $query->leftjoin('tbl_unit_measurement', 'tbl_unit_measurement.um_id','=', 'tbl_item.item_measurement_id')
        ->leftjoin("tbl_unit_measurement_multi","multi_um_id","=","um_id")
        ->selectRaw("concat(multi_name, ':', multi_abbrev) as 'z_um'");
        
        return $query;
    }
}