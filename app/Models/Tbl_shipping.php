<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_shipping extends Model
{
	protected $table = 'tbl_shipping';
	protected $primaryKey = "shipping_id";
    public $timestamps = false;
    
    public function scopesel($query, $shop_id = 0, $archived = 0)
    {
        $query->where('shop_id',$shop_id)->where('archived',$archived);
        return $query;
    }
}