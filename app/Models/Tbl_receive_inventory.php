<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_receive_inventory  extends Model
{
	protected $table = 'tbl_receive_inventory';
	protected $primaryKey = "ri_id";
    public $timestamps = false;

    public static function scopeVendor($query)
    {
    	return $query->leftjoin('tbl_vendor', 'vendor_id', '=', 'ri_vendor_id')
                     ->leftjoin("tbl_vendor_address","ven_addr_vendor_id","=","vendor_id")
                     ->leftjoin("tbl_vendor_other_info","ven_info_vendor_id","=","vendor_id");
    }
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","ri_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "receive_inventory");

        return $return;
    }
    public static function scopeTerms($query)
    {
        return $query->join("tbl_terms","tbl_terms.terms_id","=","tbl_receive_inventory.ri_terms_id");
    }
}