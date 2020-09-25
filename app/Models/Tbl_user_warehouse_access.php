<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_user_warehouse_access extends Model
{
	protected $table = 'tbl_user_warehouse_access';
	// protected $primaryKey = "user_warehouse_access_id";
    public $timestamps = false;

    public function scopeWarehouse($query)
    {
    	return $query->leftjoin("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_user_warehouse_access.warehouse_id");
    }
    public function scopeUser($query)
    {
    	return $query->leftjoin("tbl_user","tbl_user.user_id","=","tbl_user_warehouse_access.user_id");
    }
}