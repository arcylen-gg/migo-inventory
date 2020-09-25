<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_group_rest_day extends Model
{
    protected $table = 'tbl_payroll_group_rest_day';
	protected $primaryKey = "payroll_group_rest_day_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_group_rest_day_id
	// [INTEGER] 		payroll_group_id
	// [VARCHAR] 		payroll_group_rest_day
	// [VARCHAR] 		payroll_group_rest_day_category

	public function scopeselcheck($query, $payroll_group_id = 0, $payroll_group_rest_day = '', $payroll_group_rest_day_category = 'rest day')
	{
		return $query->where('payroll_group_id', $payroll_group_id)->where('payroll_group_rest_day_category', $payroll_group_rest_day_category)->where('payroll_group_rest_day', $payroll_group_rest_day);
	}
}
