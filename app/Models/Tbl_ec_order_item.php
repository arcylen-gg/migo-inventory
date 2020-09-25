<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_ec_order_item extends Model
{
	protected $table = 'tbl_ec_order_item';
	protected $primaryKey = "ec_order_item_id";
    public $timestamps = false;

	public static function scopeEcorder($query, $status = null)
    {
    	$query->join("tbl_ec_order","tbl_ec_order.ec_order_id","=","tbl_ec_order_item.ec_order_id");
    	if($status != null) $query->whereIn("order_status", $status);
    	return $query;
    }

    public static function scopeItem($query)
    {
    	$query->join("tbl_ec_variant", "tbl_ec_variant.evariant_id", "=", "tbl_ec_order_item.item_id")->leftjoin("tbl_ec_variant_image", "tbl_ec_variant.evariant_id", "=", "tbl_ec_variant_image.eimg_variant_id")->leftjoin("tbl_image", "tbl_image.image_id", "=", "tbl_ec_variant_image.eimg_image_id");

    	return $query;
    }
}