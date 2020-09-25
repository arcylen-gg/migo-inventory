<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_audit_trail extends Model
{
	protected $table = 'tbl_audit_trail';
	protected $primaryKey = "audit_trail_id";
    public $timestamps = true;

    public function scopeUser($query)
    {
    	 return $query->join('tbl_user', 'tbl_user.user_id', '=', 'tbl_audit_trail.user_id');
    }

	public function scopeAuditTrail($query)
    {
		/*$query
			->join('tbl_user', 'tbl_user.user_id', '=', 'tbl_audit_trail.user_id')
			-//>select('tbl_user.*', DB::raw('CONCAT("tbl_user.user_first_name", " ", "tbl_user.user_last_name")));
			->where(DB::raw("CONCAT(`account_username`, ' ', `account_name`)"), 'LIKE', "%company Company Head%")->first()
		return $query;*/
    }    
}

