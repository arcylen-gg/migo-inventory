<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_sss extends Model
{
    protected $table = 'tbl_payroll_sss';
	protected $primaryKey = "payroll_sss_id";
    public $timestamps = true;

    /* COLUMN NAME REFERENCE */

	// [PRIMARY KEY] 	payroll_sss_id
	// [INTEGER]		shop_id
	// [DOUBLE] 		payroll_sss_min
	// [DOUBLE] 		payroll_sss_max
	// [DOUBLE] 		payroll_sss_monthly_salary
	// [DOUBLE] 		payroll_sss_er
	// [DOUBLE] 		payroll_sss_ee
	// [DOUBLE] 		payroll_sss_total
	// [DOUBLE] 		payroll_sss_eec

	// public function scopegetsss($shop_id = 0, $rate)
	// {
		
	// }
}
