<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_user extends Model
{
	protected $table = 'tbl_user';
	protected $primaryKey = "user_id";
    public $timestamps = true;

    public function scopeShop($query)
    {
        return $query->leftJoin('tbl_shop','tbl_shop.shop_id','=','tbl_user.user_shop');
    }
    public function scopeActive($query)
    {
        return $query->where("tbl_user.archived", 0);
    }
    public function scopePosition($query)
    {
    	return $query->leftjoin('tbl_user_position', 'position_id', '=', 'user_level');
    }

    public function scopeAccess($query)
    {
    	return $query->leftjoin('tbl_user_access', 'access_position_id', '=', 'position_id');
    }
}