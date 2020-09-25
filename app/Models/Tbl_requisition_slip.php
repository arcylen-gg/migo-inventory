<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_requisition_slip extends Model
{
	protected $table = 'tbl_requisition_slip';
	protected $primaryKey = "requisition_slip_id";
    public $timestamps = false;

    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","requisition_slip_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "purchase_requisition");

        return $return;
    }
    public static function scopePerWarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","requisition_slip_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "purchase_requisition");
        return $return;
    }
    public static function scopeWarehouse($query)
    {
        return $query->leftjoin("tbl_warehouse","transaction_warehouse_id","=","warehouse_id");
    }
}