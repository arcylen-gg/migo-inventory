<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_process_leave extends Model
{
    protected $table = 'tbl_payroll_process_leave';
	protected $primaryKey = "payroll_process_leave_id";
    public $timestamps = false;

	// [INTEGER] 		payroll_process_leave_id
	// [INTEGER] 		payroll_employee_id
	// [INTEGER] 		payroll_period_company_id
	// [INTEGER] 		payroll_leave_temp_id
	// [DOUBLE 18,2] 	process_leave_quantity
	// [VARCHAR 255]	payroll_leave_temp_name

	public function scopegetleave($query, $payroll_employee_id = 0, $payroll_period_company_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)
			  ->where('payroll_period_company_id', $payroll_period_company_id);
		return $query;
	}
}
