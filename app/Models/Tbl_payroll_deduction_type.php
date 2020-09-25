<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_deduction_type extends Model
{
    protected $table = 'tbl_payroll_deduction_type';
	protected $primaryKey = "payroll_deduction_type_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_deduction_type_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payroll_deduction_category
	// [VARCHAR] 		payroll_deduction_type_name
	// [TINY INTEGER] 	payroll_deduction_archived

	public function scopeseltype($query, $shop_id = 0, $payroll_deduction_category = '', $payroll_deduction_archived = 0)
	{
		$query->where('shop_id', $shop_id)->where('payroll_deduction_category', $payroll_deduction_category)->where('payroll_deduction_archived', $payroll_deduction_archived);

		return $query;
	}
}
