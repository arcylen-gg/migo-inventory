<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_remarks extends Model
{
    protected $table = 'tbl_payroll_remarks';
	protected $primaryKey = "payroll_remarks_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 	payroll_remarks_id
	// [INTEGER]	shop_id
	// [INTEGER] 	user_id
	// [INTEGER] 	payroll_period_company_id
	// [TEXT] 		payroll_remarks
	// [DATE TIME] 	payroll_remarks_date
	// [VARCHAR]	payroll_type
	// [VARCHAR]	file_name

	public function scopegetremarks($query, $shop_id = 0, $payroll_period_company_id = 0)
	{
		$query->join('tbl_shop','tbl_shop.shop_id','=','tbl_payroll_remarks.shop_id')
			  ->leftjoin('tbl_user','tbl_user.user_id','=','tbl_payroll_remarks.user_id')
			  ->where('tbl_payroll_remarks.shop_id', $shop_id)
			  ->where('tbl_payroll_remarks.payroll_period_company_id', $payroll_period_company_id);

		return $query;
	}
}
