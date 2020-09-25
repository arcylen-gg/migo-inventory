<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_branch_location extends Model
{
    protected $table = 'tbl_payroll_branch_location';
	protected $primaryKey = "branch_location_id";
    public $timestamps = false;

    /* column reference name */
	// [INTEGER] 		branch_location_id
	// [INTEGER] 		shop_id
	// [VARCHAR 255] 	branch_location_name
	// [TINY INTEGER] 	branch_location_archived

	public function scopegetdata($query, $shop_id = 0, $branch_location_archived = 0)
	{
		$query->where('shop_id', $shop_id)->where('branch_location_archived', $branch_location_archived);
		return $query;
	}
}
