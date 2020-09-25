<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_schedule extends Model
{
    protected $table = 'tbl_payroll_employee_schedule';
	protected $primaryKey = "employee_schedule_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		employee_schedule_id
	// [INTEGER] 		payroll_employee_id
	// [DATE] 			schedule_date
	// [DOUBLE] 		target_hours
	// [TIME] 			work_start
	// [TIME] 			work_end
	// [TIME] 			break_start
	// [TIME] 			break_end
	// [TINY INTEGER] 	flexi
	// [TINY INTEGER] 	rest_day
	// [TINY INTEGER] 	extra_day
	// [TINY INTEGER]	night_shift


	public function scopegetschedule($query, $payroll_employee_id = 0, $schedule_date = '0000-00-00')
	{
		return $query->where('payroll_employee_id', $payroll_employee_id)->where('schedule_date', $schedule_date);
	}
}
