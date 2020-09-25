<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_multiple_price extends Model
{
	protected $table = 'tbl_item_multiple_price';
	protected $primaryKey = "multiprice_id";
    public $timestamps = true;

    public function scopeItem($query, $shop_id)
    {
    	$query->join("tbl_item","item_id","=","multiprice_item_id")
    	      ->where("shop_id", $shop_id);
    }
}