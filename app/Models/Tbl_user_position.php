<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_user_position extends Model
{
	protected $table = 'tbl_user_position';
	protected $primaryKey = "position_id";
    public $timestamps = false;

    public function scopeShop($query)
    {
    	return $query->join("tbl_shop","shop_id","=","position_shop_id");
    }
}