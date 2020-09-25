<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_shift_time extends Model
{
    protected $table = 'tbl_payroll_shift_time';
	protected $primaryKey = "shift_time_id";
    public $timestamps = false;

    public function scopeShiftTime($query, $shift_day_id)
    {
    	$query->join("tbl_payroll_shift_day", "tbl_payroll_shift_day.shift_day_id", "=", "tbl_payroll_shift_time.shift_day_id")
    	->where("shift_day_id", $shift_day_id);

 		return $query;
    }
}
