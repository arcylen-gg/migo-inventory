<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_salary extends Model
{
    protected $table = 'tbl_payroll_employee_salary';
	protected $primaryKey = "payroll_employee_salary_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_employee_salary_id
	// [INTEGER] 	 	payroll_employee_id
	// [DATE] 		 	payroll_employee_salary_effective_date
	// [TINYINTEGER] 	payroll_employee_salary_minimum_wage
	// [DOUBLE 18,2] 	payroll_employee_salary_monthly
	// [DOUBLE 18,2] 	payroll_employee_salary_daily
	// [DOUBLE 18,2] 	payroll_employee_salary_taxable
	// [DOUBLE 18,2] 	payroll_employee_salary_sss
	// [DOUBLE 18,2] 	payroll_employee_salary_pagibig
	// [DOUBLE 18,2] 	payroll_employee_salary_philhealth 
	// [TINY INTEGER] 	payroll_employee_salary_archived
	// [TINY INTEGER]	is_deduct_tax_default
	// [DOUBLE 18,2]	deduct_tax_custom
	// [TINY INTEGER]	is_deduct_sss_default
	// [DOUBLE 18,2]	deduct_sss_custom
	// [TINY INTEGER]	is_deduct_philhealth_default
	// [DOUBLE 18,2]	deduct_philhealth_custom
	// [TINY INTEGER]	is_deduct_pagibig_default
	// [DOUBLE 18,2]	deduct_pagibig_custom
	// [TINY INTEGER] 	dynamic_tax
	// [TINY INTEGER] 	dynamic_sss
	// [TINY INTEGER] 	dynamic_philhealth
	// [TINY INTEGER] 	dynamic_pagibig


	public function scopeselemployee($query, $payroll_employee_id = 0, $date = '0000-00-00')
	{
		if($date == '0000-00-00')
		{
			$date = date('Y-m-d');
		}

		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_employee_salary_effective_date','<=', $date)->orderBy('payroll_employee_salary_id', 'desc');
		return $query;
	}

	public function scopesalaylist($query, $payroll_employee_id = 0, $payroll_employee_salary_archived = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_employee_salary_archived',$payroll_employee_salary_archived);
		return $query;
	}
}
