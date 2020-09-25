<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_item_discount extends Model
{
	protected $table = 'tbl_mlm_item_discount';
	protected $primaryKey = "item_discount_d";
    public $timestamps = false;

    public static function scopeItem($query)
    {
    	return $query->join("tbl_item","tbl_item.item_id","=","tbl_mlm_item_discount.item_id");
    }
}