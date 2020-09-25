<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_reports extends Model
{
    protected $table = 'tbl_payroll_reports';
	protected $primaryKey = "payroll_reports_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
 	// [INTEGER] 		payroll_reports_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payroll_reports_name
	// [TINY INTEGER] 	is_by_employee
	// [TINY INTEGER] 	is_by_department
	// [TINY INTEGER] 	is_by_company
	// [TINY INTEGER] 	payroll_reports_archived


	public function scopegetdata($query, $shop_id = 0, $payroll_reports_archived = 0)
	{
		$query->where('shop_id', $shop_id)
			  ->where('payroll_reports_archived', $payroll_reports_archived);

		return $query;
	}
}
