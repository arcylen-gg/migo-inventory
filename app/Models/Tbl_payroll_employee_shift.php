<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_shift extends Model
{
    protected $table = 'tbl_payroll_employee_shift';
	protected $primaryKey = "employee_shift_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		employee_shift_id
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

	
}
