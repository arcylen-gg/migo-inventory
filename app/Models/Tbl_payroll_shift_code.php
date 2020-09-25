<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_shift_code extends Model
{
    protected $table = 'tbl_payroll_shift_code';
	protected $primaryKey = "shift_code_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] 		shift_code_id
	// [VARCHAR] 		shift_code_name
	// [TINY INTEGER] 	shift_archived
	// [INTEGER]		shop_id


	public function scopegetshift($query, $shop_id = 0, $shift_archived = 0)
	{
		$query->where('shop_id', $shop_id)->where('shift_archived', $shift_archived);
		return $query;
	}
    public function scopeDay($query)
    {
    	$query->join("tbl_payroll_shift_day", "tbl_payroll_shift_day.shift_code_id", "=", "tbl_payroll_shift_code.shift_code_id");
    }
    public function scopeTime($query)
    {
    	$query->join("tbl_payroll_shift_time", "tbl_payroll_shift_time.shift_day_id", "=", "tbl_payroll_shift_day.shift_day_id");
    }


}
