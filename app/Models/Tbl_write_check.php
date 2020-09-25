<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_write_check extends Model
{
    protected $table = 'tbl_write_check';
	protected $primaryKey = "wc_id";
    public $timestamps = false;

    public function scopeVendor($query)
    {
        return $query->leftjoin('tbl_vendor', 'tbl_write_check.wc_reference_id', '=', 'tbl_vendor.vendor_id');
    }
    public function scopeWCMonitoringQty($query)
    {
        return $query->leftjoin('tbl_quantity_monitoring', 'tbl_write_check.wc_id', '=', 'tbl_quantity_monitoring.qty_transaction_id');
    }
    public function scopeCustomer($query)
    {
        return $query->leftjoin('tbl_customer', 'tbl_write_check.wc_reference_id', '=', 'tbl_customer.customer_id');
    }
    
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","wc_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "write_check");

        return $return;
    }
}
