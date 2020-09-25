<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_leave_tempv2 extends Model
{
    //
   	protected $table = 'tbl_payroll_leave_tempv2';
	protected $primaryKey = "payroll_leave_temp_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
    // [PRIMARY KEY]    tbl_payroll_leave_temp_id
    // [INTEGER]        shop_id
    // [VARCHAR]        payroll_leave_temp_name
    // [INTEGER]        payroll_leave_temp_days_cap
    // [TINY INTEGER]   payroll_leave_temp_with_pay
    // [TINYINTEGER]    payroll_leave_temp_is_cummulative
  
    public function scopesel($query, $shop_id = 0, $archived = 0)
	{
		return $query->where('shop_id', $shop_id)->where('payroll_leave_temp_archived', $archived);
	}
}
