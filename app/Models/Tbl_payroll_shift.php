<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_shift extends Model
{
    protected $table = 'tbl_payroll_shift';
	protected $primaryKey = "payroll_shift_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */

	// [INTEGER] 		payroll_shift_id
	// [INTEGER] 		payroll_group_id
	// [VARCHAR] 		day
	// [TIME] 			target_hours
	// [TIME] 			work_start
	// [TIME] 			work_end
	// [TIME] 			break_start
	// [TIME] 			break_end
	// [TINY INTEGER] 	flexi
	// [TINY INTEGER] 	rest_day
	// [TINY INTEGER] 	extra_day
	// [TINY INTEGER]	night_shift

	public function scopegetshift($query, $payroll_group_id = 0, $day = '')
	{
		$query->where('payroll_group_id', $payroll_group_id)->where('day',  $day);
		
		return $query;
	}
}
