<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_allowance_record extends Model
{
    protected $table = 'tbl_payroll_allowance_record';
	protected $primaryKey = "payroll_allowance_record_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// payroll_allowance_record_id
	// payroll_record_id
	// payroll_employee_id
	// payroll_employee_allowance_id
	// payroll_record_allowance_amount

	public function scopegetbyrecord($query, $payroll_record_id = array())
	{
		$query->whereIn('payroll_record_id', $payroll_record_id);
		return $query;
	}
}
