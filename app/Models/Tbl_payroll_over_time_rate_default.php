<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_over_time_rate_default extends Model
{
    protected $table = 'tbl_payroll_over_time_rate_default';
	protected $primaryKey = "payroll_over_time_rate_default_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */

	// [PRIMARY KEY] 	over_time_rate_default_id
	// [VARCHAR] 		payroll_overtime_name
	// [DOUBLE] 		payroll_overtime_regular
	// [DOUBLE] 		payroll_overtime_overtime
	// [DOUBLE] 		payroll_overtime_nigth_diff
	// [DOUBLE] 		payroll_overtime_rest_day
	// [DOUBLE] 		payroll_overtime_rest_overtime
	// [DOUBLE] 		payroll_overtime_rest_night
}
