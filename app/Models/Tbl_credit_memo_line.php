<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_credit_memo_line extends Model
{
   protected $table = 'tbl_credit_memo_line';
	protected $primaryKey = "cmline_id";
    public $timestamps = true;

    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "cmline_um");
    }
    public function scopeCm_item($query)
    {
    	return $query->leftjoin("tbl_item","tbl_item.item_id","=","cmline_item_id");
    }
}
