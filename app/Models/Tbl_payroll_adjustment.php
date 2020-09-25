<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_adjustment extends Model
{
    protected $table = 'tbl_payroll_adjustment';
	protected $primaryKey = "payroll_adjustment_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_adjustment_id
	// [INTEGER] payroll_employee_id
	// [INTEGER] payroll_period_company_id
	// [VARCHAR] payroll_adjustment_name
	// [VARCHAR] payroll_adjustment_category
	// [DOUBLE]  payroll_adjustment_amount

	public function scopegetadjustment($query, $payroll_employee_id = 0, $payroll_period_company_id = 0, $payroll_adjustment_category = 'positive')
	{
		$query->where('payroll_employee_id', $payroll_employee_id)
			 ->where('payroll_period_company_id', $payroll_period_company_id)
			 ->where('payroll_adjustment_category', $payroll_adjustment_category);
		return $query;
	}

	public function scopegetrecord($query, $payroll_employee_id = 0, $payroll_period_company_id = array(), $payroll_adjustment_category = '')
	{
		$query->where('payroll_employee_id', $payroll_employee_id)
			 ->where('payroll_adjustment_category', $payroll_adjustment_category)
			 ->whereIn('payroll_period_company_id', $payroll_period_company_id);

		return $query;
	} 	
}
