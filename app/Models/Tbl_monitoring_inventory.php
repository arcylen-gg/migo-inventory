<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_monitoring_inventory extends Model
{
	protected $table = 'tbl_monitoring_inventory';
	protected $primaryKey = "invty_id";
    public $timestamps = false;

    
    public function scopeTransit($query)
    {
        return $query->leftjoin('tbl_customer_wis','tbl_customer_wis.cust_wis_id','=','tbl_monitoring_inventory.invty_transaction_id');
    }    
    public function scopeTransitTransfer($query)
    {
        return $query->leftjoin('tbl_warehouse_issuance_report','tbl_warehouse_issuance_report.wis_id','=','tbl_monitoring_inventory.invty_transaction_id');
    }
}	