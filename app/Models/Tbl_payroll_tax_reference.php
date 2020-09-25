<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_tax_reference extends Model
{
    protected $table = 'tbl_payroll_tax_reference';
	protected $primaryKey = "payroll_tax_reference_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */

	// [PRIMARY KEY] 	payroll_tax_reference_id
	// [INTEGER] 		payroll_tax_status_id
	// [INTEGER]		shop_id
	// [VARCHAR] 		tax_category
	// [DOUBLE] 		tax_first_range
	// [DOUBLE] 		tax_second_range
	// [DOUBLE] 		tax_third_range
	// [DOUBLE] 		tax_fourth_range
	// [DOUBLE] 		tax_fifth_range
	// [DOUBLE] 		taxt_sixth_range
	// [DOUBLE] 		tax_seventh_range

	public function scopesel($query, $shop_id = 0, $tax_category = '', $payroll_tax_period = '')
	{
		$query->join('tbl_payroll_tax_period','tbl_payroll_tax_period.payroll_tax_period_id','=','tbl_payroll_tax_reference.payroll_tax_status_id')
				->where('tbl_payroll_tax_reference.shop_id', $shop_id)
				->where('tbl_payroll_tax_reference.tax_category', $tax_category)
				->where('tbl_payroll_tax_period.payroll_tax_period', $payroll_tax_period);
		return $query;
	}
}
