<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_wis extends Model
{
    protected $table = 'tbl_customer_wis';
	protected $primaryKey = "cust_wis_id";
    public $timestamps = true;

    public function scopeInventoryItem($query)
    {
        $query->selectRaw("*, count(cust_wis_item_id) as issued_qty")->leftjoin("tbl_customer_wis_item","tbl_customer_wis.cust_wis_id", "=", "tbl_customer_wis_item.cust_wis_id");

        return $query;
    }
    public function scopeItem($query)
    {
        $query->leftjoin("tbl_customer_wis_item_line","itemline_wis_id", "=", "cust_wis_id");
        return $query;        
    }

    public function scopeItemdetails($query)
    {
        return $query->leftjoin("tbl_item", "itemline_item_id", "=","tbl_item.item_id")
                    ->leftjoin('tbl_unit_measurement_multi','multi_um_id','=','item_measurement_id');
    }
    public function scopeCustomerInfo($query)
    {
        $query->join("tbl_customer","tbl_customer.customer_id", "=", "tbl_customer_wis.destination_customer_id");
        return $query;
    }
    public function scopeWarehouse($query)
    {
        $query->leftjoin("tbl_warehouse",'warehouse_id','=','cust_wis_from_warehouse');
    }

    public function scopeTruck($query)
    {
        $query->leftjoin("tbl_truck",'truck_id','=','cust_wis_truck_id');
    }

    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","cust_wis_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "warehouse_issuance_slip");

        return $return;
    }
}
