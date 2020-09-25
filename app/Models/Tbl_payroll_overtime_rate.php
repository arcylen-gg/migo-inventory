<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_overtime_rate extends Model
{
    protected $table = 'tbl_payroll_overtime_rate';
	protected $primaryKey = "payroll_overtime_rate_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */

	// [PRIMARY KEY] 	payroll_overtime_rate_id
	// [INTEGER]		payroll_group_id
	// [VARCHAR] 		payroll_overtime_name
	// [DOUBLE] 		payroll_overtime_regular
	// [DOUBLE] 		payroll_overtime_overtime
	// [DOUBLE] 		payroll_overtime_nigth_diff
	// [DOUBLE] 		payroll_overtime_rest_day
	// [DOUBLE] 		payroll_overtime_rest_overtime
	// [DOUBLE] 		payroll_overtime_rest_night

	/* naming */
	/*
	• Regular
	• Legal Holiday
	• Special Holiday
	*/

	public function scopegetrate($query, $payroll_group_id = 0, $payroll_overtime_name = '')
	{
		$query->where('payroll_group_id', $payroll_group_id)->where('payroll_overtime_name', $payroll_overtime_name);
		return $query;
	}	
}
