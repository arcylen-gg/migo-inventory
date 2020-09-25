<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_deduction_employee extends Model
{
    protected $table = 'tbl_payroll_deduction_employee';
	protected $primaryKey = "payroll_deduction_employee_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */

	// [PRIMARY KEY] 	payroll_deduction_employee_id
	// [INTEGER] 		payroll_deduction_id
	// [INTEGER] 		payroll_employee_id
	// [TINY INTEGER] 	payroll_deduction_employee_archived


	public function scopeselbyemployee($query, $payroll_deduction_id = 0, $payroll_deduction_employee_archived = 0)
	{
		
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_employee.payroll_employee_id')
			 ->where('tbl_payroll_deduction_employee.payroll_deduction_id',$payroll_deduction_id)
			 ->where('payroll_deduction_employee_archived',$payroll_deduction_employee_archived);

		return $query;
	}

	public function scopegetemployee($query, $payroll_deduction_employee_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_employee.payroll_employee_id')
			 ->where('payroll_deduction_employee_id', $payroll_deduction_employee_id);
		return $query;
	}

	public function scopegetdeduction($query, $payroll_employee_id = 0, $date = '0000-00-00', $period = '')
	{

		$query->join('tbl_payroll_deduction','tbl_payroll_deduction.payroll_deduction_id','=','tbl_payroll_deduction_employee.payroll_deduction_id')
			  ->leftjoin('tbl_payroll_deduction_payment','tbl_payroll_deduction_payment.payroll_deduction_id','=','tbl_payroll_deduction_employee.payroll_deduction_id')
			  ->where('tbl_payroll_deduction_employee.payroll_employee_id', $payroll_employee_id)
			  ->where('tbl_payroll_deduction.payroll_deduction_date_start', '<=', $date)
			  ->where('tbl_payroll_deduction.payroll_deduction_date_end','>=', $date)
			  ->where('tbl_payroll_deduction_employee.payroll_deduction_employee_archived', 0)
			  ->where(function($query2) use ($period){

			  		return $query2->where('tbl_payroll_deduction.payroll_deduction_period', $period)
			  					  ->orWhere('tbl_payroll_deduction.payroll_deduction_period','Every Period');
			  })
			  ->having('balance_deduction','>',0)
			  ->select(DB::raw('IFNULL(sum(tbl_payroll_deduction_payment.payroll_payment_amount),0) as total_payment, IFNULL(tbl_payroll_deduction.payroll_deduction_amount - IFNULL(sum(tbl_payroll_deduction_payment.payroll_payment_amount),0), 0) as balance_deduction, tbl_payroll_deduction.*'))
			  ->groupBy('tbl_payroll_deduction.payroll_deduction_id');

		return $query;
	}

	public function scopecheckdeduction($query, $payroll_employee_id = 0, $payroll_deduction_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_deduction_id', $payroll_deduction_id);

		return $query;
	}
}
