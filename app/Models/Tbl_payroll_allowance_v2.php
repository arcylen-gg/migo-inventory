<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_allowance_v2 extends Model
{
    protected $table = 'tbl_payroll_allowance_v2';
	protected $primaryKey = "payroll_allowance_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_allowance_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payroll_allowance_name
	// [DOUBLE] 		payroll_allowance_amount
	// [VARCHAR] 		payroll_allowance_category
	// [VARCHAR]		payroll_allowance_add_period
	// [TINY INTEGER] 	payroll_allowance_archived

	public function scopesel($query, $shop_id = 0, $payroll_allowance_archived = 0)
	{
		$query->where('shop_id', $shop_id)->where('payroll_allowance_archived',$payroll_allowance_archived);

		return $query;
	}
}
