<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_shift_template extends Model
{
    protected $table = 'tbl_payroll_shift_template';
	protected $primaryKey = "shift_template_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		shift_template_id
	// [INTEGER] 		shift_code_id
	// [VARCHAR] 		day
	// [DOUBLE] 		target_hours
	// [TIME] 			work_start
	// [TIME] 			work_end
	// [TIME] 			break_start
	// [TIME] 			break_end
	// [TINY INTEGER] 	flexi
	// [TINY INTEGER] 	rest_day
	// [TINY INTEGER] 	extra_day
	// [TINY INTEGER]	night_shift


	public function scopegetshift($query, $shift_code_id = 0, $day = '')
	{
		return $query->where('shift_code_id', $shift_code_id)->where('day', $day);
	}	
}
