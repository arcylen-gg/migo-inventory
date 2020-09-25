<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_payslip extends Model
{
   	protected $table = 'tbl_payroll_payslip';
	protected $primaryKey = "payroll_payslip_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		payroll_payslip_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payslip_code
	// [INTEGER] 		payroll_paper_sizes_id
	// [DOUBLE] 		payslip_width
	// [INTEGER] 		payslip_copy
	// [TINY INTEGER] 	include_company_logo
	// [TINY INTEGER] 	include_department
	// [TINY INTEGER] 	include_job_title
	// [TINY INTEGER] 	include_time_summary
	// [VARCHAR] 		company_position
	// [TINY INTEGER] 	payroll_payslip_archived
	// [TINY INTEGER]	payslip_is_use


	public function scopegetpayslip($query, $shop_id = 0, $payroll_payslip_archived = 0)
	{
		$query->where('shop_id', $shop_id)
			  ->where('payroll_payslip_archived', $payroll_payslip_archived);

		return $query;
	}

	public function scopepayslip($query,$shop_id = 0, $payslip_is_use = 1)
	{
		$query->join('tbl_payroll_paper_sizes','tbl_payroll_paper_sizes.payroll_paper_sizes_id','=','tbl_payroll_payslip.payroll_paper_sizes_id')
			  ->where('tbl_payroll_payslip.shop_id',$shop_id)
			  ->where('tbl_payroll_payslip.payslip_is_use', $payslip_is_use);

		return $query;
	}
}
