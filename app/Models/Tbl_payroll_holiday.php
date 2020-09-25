<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_holiday extends Model
{
    protected $table = 'tbl_payroll_holiday';
	protected $primaryKey = "payroll_holiday_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_holiday_id
	// [INTEGER] 		shop_id
	// [VARCHAR] 		payroll_holiday_name
	// [DATE] 			payroll_holiday_date
	// [VARCHAR] 		payroll_holiday_category
	// [TINY INTEGER] 	payroll_holiday_archived


    public function scopegetholiday($query, $shop_id = 0, $payroll_holiday_archived = 0)
    {
    	$query->where('shop_id', $shop_id)->where('payroll_holiday_archived', $payroll_holiday_archived);

    	return $query;
    }
}
