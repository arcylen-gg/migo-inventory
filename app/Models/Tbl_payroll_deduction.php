<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_deduction extends Model
{
    protected $table = 'tbl_payroll_deduction';
	protected $primaryKey = "payroll_deduction_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_deduction_id
	// [INTEGER]		shop_id
	// [VARCHAR] 		payroll_deduction_name
	// [DOUBLE] 		payroll_deduction_amount
	// [DOUBLE] 		payroll_monthly_amortization
	// [DOUBLE] 		payroll_periodal_deduction
	// [DATE] 			payroll_deduction_date_filed
	// [DATE] 			payroll_deduction_date_start
	// [DATE]			payroll_deduction_date_end
	// [VARCHAR] 		payroll_deduction_period
	// [VARCHAR] 		payroll_deduction_category
	// [INTEGER] 		payroll_deduction_type
	// [TEXT] 			payroll_deduction_remarks
	// [TINY INTEGER] 	payroll_deduction_archived

	public function scopeseldeduction($query, $shop_id  = 0, $payroll_deduction_archived = 0)
	{
		$query->leftjoin('tbl_payroll_deduction_type','tbl_payroll_deduction_type.payroll_deduction_type_id','=','tbl_payroll_deduction.payroll_deduction_type')
			->where('tbl_payroll_deduction.shop_id', $shop_id)
			->where('tbl_payroll_deduction.payroll_deduction_archived', $payroll_deduction_archived);
		return $query;
	}
}
