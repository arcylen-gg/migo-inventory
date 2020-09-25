<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_estimate_line extends Model
{
	protected $table = 'tbl_customer_estimate_line';
	protected $primaryKey = "estline_id";
    public $timestamps = false;

    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "estline_um");
    }
    public function scopeEstimate($query)
    {
        return $query->leftjoin("tbl_customer_estimate", "est_id", "=", "estline_est_id");
    }
    public function scopeEstimate_item($query)
    {
    	return $query->leftjoin("tbl_item","tbl_item.item_id","=","estline_item_id");
    }
}