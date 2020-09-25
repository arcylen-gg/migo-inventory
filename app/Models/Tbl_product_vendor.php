<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_product_vendor extends Model
{
	protected $table = 'tbl_product_vendor';
	protected $primaryKey = "vendor_id";
    public $timestamps = false;

    public function scopesel($query, $shop_id = 0, $archived = 0)
    {
    	$query->where('vendor_shop',$shop_id)->where('archived',$archived);
    }
}