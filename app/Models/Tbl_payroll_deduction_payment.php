<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_deduction_payment extends Model
{
    protected $table = 'tbl_payroll_deduction_payment';
	protected $primaryKey = "payroll_deduction_payment_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_deduction_payment_id
	// [INTEGER]		shop_id
	// [INTEGER] 		payroll_deduction_id
	// [INTEGER] 		payroll_employee_id
	// [INTEGER] 		payroll_record_id
	// [DOUBLE] 		payroll_payment_amount
	// [VARCHAR]		deduction_name
	// [VARCHAR]		deduction_category

	public function scopeselbyemployee($query, $payroll_deduction_id = 0, $payroll_employee_id = 0)
	{
		$query->where('payroll_deduction_id',$payroll_deduction_id)->where('payroll_employee_id', $payroll_employee_id);

		return $query;
	}

	public function scopegetpayment($query, $payroll_employee_id = 0, $payroll_record_id = array(), $payroll_deduction_id = 0)
	{
		$query->where('payroll_employee_id',$payroll_employee_id)
			  ->where('payroll_deduction_id', $payroll_deduction_id)
			  ->whereIn('payroll_record_id',$payroll_record_id);
		return $query;
	}

	public function scopegetrecord($query, $payroll_record_id = array(), $payroll_deduction_category = '')
	{
		$query->join('tbl_payroll_deduction','tbl_payroll_deduction.payroll_deduction_id','=','tbl_payroll_deduction_payment.payroll_deduction_id')
			 ->whereIn('tbl_payroll_deduction_payment.payroll_record_id', $payroll_record_id)
			 ->where('tbl_payroll_deduction.payroll_deduction_category', $payroll_deduction_category);

		return $query;
	}
}
