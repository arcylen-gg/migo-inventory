<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_time_sheet_record extends Model
{
    protected $table = 'tbl_payroll_time_sheet_record';
	protected $primaryKey = "payroll_time_sheet_record_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_time_sheet_record_id
	// [INTEGER] 		payroll_time_sheet_id
	// [INTEGER] 		payroll_company_id
	// [TIME] 			payroll_time_sheet_in
	// [TIME] 			payroll_time_sheet_out
	// [TIME]			payroll_time_sheet_approved_in
	// [TIME]			payroll_time_sheet_approved_out
	// [TEXT] 			payroll_time_shee_activity
	// [VARCHAR] 		payroll_time_sheet_origin
	// [VARCHAR] 		payroll_time_sheet_status
	// [TIME]			payroll_time_sheet_break
	// [TEXT]			time_sheet_record_remarks


	/* payroll_time_sheet_status [EXPLANATION] */
	/* 
		• pending 	= not computed
		• cancelled = time entry has been cancelled
		• processed = time has been used for computation and the computation has already approved.
	*/

	public function scopewherearray($query, $array = array())
	{
		foreach($array as $key => $value)
		{
			$query->where($key, $value);
		}
		return $query;
	}

	public function scopeTimesheet($query)
	{
		return $query->leftjoin('tbl_payroll_time_sheet',"tbl_payroll_time_sheet.payroll_time_sheet_id","=","tbl_payroll_time_sheet_record.payroll_time_sheet_id");
	}
	public function scopegetrecord($query, $payroll_time_sheet_id = 0)
	{
		$query->leftjoin('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_time_sheet_record.payroll_company_id')
				->where('tbl_payroll_time_sheet_record.payroll_time_sheet_id', $payroll_time_sheet_id);

		return $query;
	}
	public function scopeTimesheetRecord($query)
	{
		return $query->join('tbl_payroll_company',"tbl_payroll_company.payroll_company_id","=","tbl_payroll_time_sheet_record.payroll_company_id");
	}
}
