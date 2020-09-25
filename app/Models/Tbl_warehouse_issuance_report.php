<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_warehouse_issuance_report extends Model
{
	protected $table = 'tbl_warehouse_issuance_report';
	protected $primaryKey = "wis_id";
    public $timestamps = true;


    public function scopeInventory_item($query)
    {
        // $query->selectRaw("*, count(wis_item_id) as issued_qty, orig_wis_id as tbl_warehouse_issuance_report.wis_id")->leftjoin("tbl_warehouse_issuance_report_item","tbl_warehouse_issuance_report.wis_id", "=", "tbl_warehouse_issuance_report_item.wis_id");
        return $query;
    }
    public function scopeItemline($query)
    {
        return $query->leftjoin("tbl_warehouse_issuance_report_itemline", "wt_wis_id", "=","wis_id");
    }

    public function scopeItemdetails($query)
    {
        return $query->leftjoin("tbl_item", "wt_item_id", "=","tbl_item.item_id")
                    ->leftjoin('tbl_unit_measurement_multi','multi_um_id','=','item_measurement_id');
    }
    
    public function scopeDestinationWarehouse($query)
    {
    	return $query->leftjoin("tbl_warehouse","tbl_warehouse.warehouse_id",'=',"destination_warehouse_id");
    }   

    public function scopeTruck($query)
    {
        return $query->leftjoin("tbl_truck","truck_id",'=',"wis_truck_id");
    }   

    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","wis_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "warehouse_transfer");

        return $return;
    }
    public static function scopeUser($query)
    {
        return $query->leftjoin("tbl_user","tbl_user.user_id","=","tbl_warehouse_issuance_report.wis_issued_by");
    }
    public static function scopeReceivingreport($query)
    {
        return $query->leftjoin("tbl_warehouse_receiving_report","tbl_warehouse_receiving_report.wis_id","=","tbl_warehouse_issuance_report.wis_id");
    }
}