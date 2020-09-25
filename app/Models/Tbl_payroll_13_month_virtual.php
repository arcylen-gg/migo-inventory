<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_13_month_virtual extends Model
{
    protected $table = 'tbl_payroll_13_month_virtual';
	protected $primaryKey = "payroll_13_month_virtual_id";
    public $timestamps = false;

	// [INTEGER] payroll_13_month_virtual_id
	// [INTEGER] payroll_employee_id
	// [INTEGER] payroll_period_company_id

	public function scopegetperiod($query, $payroll_employee_id = 0, $payroll_period_company_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_period_company_id', $payroll_period_company_id);

		return $query;
	}
}
