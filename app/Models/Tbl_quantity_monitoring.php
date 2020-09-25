<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_quantity_monitoring extends Model
{
    protected $table = 'tbl_quantity_monitoring';
	protected $primaryKey = "quantity_monitoring_id";
    public $timestamps = true;

    public function scopeWCMonitoringQty($query)
    {
        return $query->leftjoin('tbl_write_check', 'tbl_write_check.wc_id', '=', 'tbl_quantity_monitoring.qty_transaction_id');
    }
}
