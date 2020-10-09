<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_purchase_order extends Model
{   
	protected $table = 'tbl_purchase_order';
	protected $primaryKey = "po_id";
    public $timestamps = false;

    public static function scopeVendor($query)
    {
    	return $query->leftjoin("tbl_vendor","tbl_vendor.vendor_id","=","tbl_purchase_order.po_vendor_id")
                     ->leftjoin("tbl_vendor_address","ven_addr_vendor_id","=","vendor_id")
                     ->leftjoin("tbl_vendor_other_info","ven_info_vendor_id","=","vendor_id");
    }
    public static function scopeTerms($query)
    {
    	return $query->join("tbl_terms","tbl_terms.terms_id","=","tbl_purchase_order.po_terms_id");
    }
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","po_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "purchase_order");

        return $return;
    }

    public static function scopePurchase_item($query)
    {
        return $query->leftjoin("tbl_purchase_order_line","tbl_purchase_order_line.poline_po_id","=","tbl_purchase_order.po_id")
                    ->leftjoin("tbl_item","tbl_item.item_id","=","poline_item_id");
    }

    public static function scopeWarehouse($query)
    {
        return $query->leftjoin("tbl_warehouse","transaction_warehouse_id","=","warehouse_id");
    }
    public static function scopePerwarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","po_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "purchase_order");
        return $return;
    }
}
