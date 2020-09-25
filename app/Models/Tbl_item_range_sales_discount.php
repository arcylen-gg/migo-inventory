<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_range_sales_discount extends Model
{
   	protected $table = 'tbl_item_range_sales_discount';
	protected $primaryKey = "range_id";

	public static function scopeGetItemName($query)
	{
		return $query->join('tbl_item','tbl_item.item_id','=','tbl_item_range_sales_discount.range_item_id');
	}
}
