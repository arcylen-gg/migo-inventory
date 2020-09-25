<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_allowance_v2 extends Model
{
    protected $table = 'tbl_payroll_employee_allowance_v2';
	protected $primaryKey = "payroll_employee_allowance_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_employee_allowance_id
	// [INTEGER] 		payroll_allowance_id
	// [INTEGER] 		payroll_employee_id
	// [TINY INTEGER] 	payroll_employee_allowance_archived

	public function scopeJoinAllowance($query)
	{
		$query->join('tbl_payroll_allowance_v2','tbl_payroll_allowance_v2.payroll_allowance_id','=','tbl_payroll_employee_allowance_v2.payroll_allowance_id');
	}

	public function scopegetperallowance($query, $payroll_allowance_id = 0, $payroll_employee_allowance_archived = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_allowance_v2.payroll_employee_id')
			 ->where('tbl_payroll_employee_allowance_v2.payroll_allowance_id',$payroll_allowance_id)
			 ->where('tbl_payroll_employee_allowance_v2.payroll_employee_allowance_archived',$payroll_employee_allowance_archived);
		
		return $query;
	}

	public function scopeemployee($query, $payroll_employee_allowance_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_allowance_v2.payroll_employee_id')
			 ->where('tbl_payroll_employee_allowance_v2.payroll_employee_allowance_id',$payroll_employee_allowance_id);

		return $query;
	}

	public function scopeemployee_allowance($query, $payroll_employee_id = 0, $payroll_allowance_category = 'fixed')
	{
		$query->join('tbl_payroll_allowance_v2','tbl_payroll_allowance_v2.payroll_allowance_id','=','tbl_payroll_employee_allowance_v2.payroll_allowance_id')
			 ->where('tbl_payroll_employee_allowance_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_allowance_v2.payroll_allowance_category', $payroll_allowance_category)
			 ->where('tbl_payroll_employee_allowance_v2.payroll_employee_allowance_archived', 0)
			 ->where('tbl_payroll_allowance_v2.payroll_allowance_archived',0);

		return $query;
	}

	public function scopecheckallowance($query, $payroll_employee_id = 0, $payroll_allowance_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_allowance_id', $payroll_allowance_id);
		return $query;
	}
}
