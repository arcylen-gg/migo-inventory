<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_shop extends Model
{
	protected $table = 'tbl_shop';
	protected $primaryKey = "shop_id";
    public $timestamps = true;

    public static function scopeActive($query)
    {
    	return $query->where("tbl_shop.archived", 0);
    }

    public static function scopeArchived($query)
    {
        return $query->where("tbl_shop.archived", 1);
    }

    public static function scopegetUser($query)
    {
    	return $query->leftjoin("tbl_user","tbl_user.user_shop","=","tbl_shop.shop_id");
    }
}