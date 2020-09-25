<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_pricing_history extends Model
{
	protected $table = 'tbl_item_pricing_history';
	protected $primaryKey = "pricing_history_id";
    public $timestamps = false;

    public static function scopeUser($query)
    {
    	return $query->leftjoin("tbl_user","user_id","=","pricing_user_id");
    }
}