<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_leave_employee extends Model
{
    protected $table = 'tbl_payroll_leave_employee';
	protected $primaryKey = "payroll_leave_employee_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_leave_employee_allowance
	// [INTEGER] 		payroll_leave_temp_id
	// [INTEGER] 		payroll_employee_id
	// [TINY INTEGER] 	payroll_leave_employee_is_archived

	public function scopegetperleave($query, $payroll_leave_temp_id = 0, $payroll_leave_employee_is_archived = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee.payroll_employee_id')
			 ->where('tbl_payroll_leave_employee.payroll_leave_temp_id',$payroll_leave_temp_id)
			 ->where('tbl_payroll_leave_employee.payroll_leave_employee_is_archived',$payroll_leave_employee_is_archived);
		
		return $query;
	}

	public function scopeemployee($query, $payroll_leave_employee_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee.payroll_employee_id')
			 ->where('tbl_payroll_leave_employee.payroll_leave_employee_id',$payroll_leave_employee_id);

		return $query;
	}

	public function scopecheckleave($query, $payroll_employee_id = 0, $payroll_leave_temp_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_leave_temp_id', $payroll_leave_temp_id);

		return $query;
	}

	public function scopegetpayable_leave($query, $payroll_employee_id = 0)
	{
		$query->join('tbl_payroll_leave_temp','tbl_payroll_leave_temp.payroll_leave_temp_id','=','tbl_payroll_leave_employee.payroll_leave_temp_id')
			  ->where('tbl_payroll_leave_temp.payroll_leave_temp_with_pay',1)
			  ->where('tbl_payroll_leave_employee.payroll_employee_id', $payroll_employee_id)
			  ->orderBy('tbl_payroll_leave_schedule.payroll_leave_schedule_id', 'desc');
		return $query;
	}

}
