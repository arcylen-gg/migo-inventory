<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_bundle extends Model
{
	protected $table = 'tbl_item_bundle';
	protected $primaryKey = "bundle_id";
    public $timestamps = true;

    public function scopeItem($query)
    {
    	return $query->join("tbl_item","item_id","=","bundle_item_id");
    }

    public function scopeItemBundle($query)
    {
        return $query->join("tbl_item","item_id","=","bundle_bundle_id");
    }

    /* Dependent on scopeItem() */
    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "bundle_um_id");
    }
}