<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_13_month_compute extends Model
{
    protected $table = 'tbl_payroll_13_month_compute';
	protected $primaryKey = "payroll_13_month_compute_id";
    public $timestamps = false;


	// [PRIMARY KEY] payroll_13_month_compute_id
	// [INTEGER] shop_id
	// [INTEGER] payroll_employee_id
	// [INTEGER] payroll_period_company_id
	// [INTEGER] payroll_record_id

	public function scopeget13m($query, $payroll_employee_id = 0, $payroll_period_company_id = 0)
	{
		$query->join('tbl_payroll_record','tbl_payroll_record.payroll_record_id','=','tbl_payroll_13_month_compute.payroll_record_id')
			 ->where('tbl_payroll_13_month_compute.payroll_period_company_id', $payroll_period_company_id)
			 ->where('tbl_payroll_13_month_compute.payroll_employee_id', $payroll_employee_id);

		return $query;
	}
}
