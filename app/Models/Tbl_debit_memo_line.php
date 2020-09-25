<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_debit_memo_line extends Model
{
   protected $table = 'tbl_debit_memo_line';
   protected $primaryKey = "dbline_id";
   public $timestamps = false;

   public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "dbline_um");
    }
    public function scopeDb_item($query)
    {
    	return $query->leftjoin("tbl_item","tbl_item.item_id","=","dbline_item_id");
    }
    public function scopeReplace_dbitem($query)
    {
      return $query->leftjoin("tbl_debit_memo_replace_line","tbl_debit_memo_replace_line.dbline_replace_dbline_id","=","tbl_debit_memo_line.dbline_id");
    }
    public function scopeBinLocation($query)
    {
        return $query->leftjoin("tbl_warehouse", "warehouse_id", "=", "dbline_sub_wh_id");        
    }
}
