<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory_adjustment extends Model
{
	protected $table = 'tbl_inventory_adjustment';
	protected $primaryKey = "inventory_adjustment_id";
    public $timestamps = false;

    public function scopeWarehouse($query)
    {
        return $query->leftjoin("tbl_warehouse", "adj_warehouse_id", "=", "warehouse_id");
    }
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","inventory_adjustment_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "inventory_adjustment");

        return $return;
    }
}