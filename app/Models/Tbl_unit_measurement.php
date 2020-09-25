<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_unit_measurement extends Model
{
	protected $table = 'tbl_unit_measurement';
	protected $primaryKey = "um_id";
    public $timestamps = true;
    
    public function scopeType($query, $shop)
    {
        $query->join("tbl_unit_measurement_type","um_type_id","=","um_type")
              ->join("tbl_shop","shop_id","=","um_shop")
              ->where( function ($where) use($shop)
              {
                    $where->where("shop_id", $shop)
                          ->orWhere("shop_key", $shop);
              });
    }
    public function scopeMulti($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi","multi_um_id","=","um_id");
    }
}