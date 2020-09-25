<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_time_sheet extends Model
{
    protected $table = 'tbl_payroll_time_sheet';
	protected $primaryKey = "payroll_time_sheet_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCES */

    // ['PRIMARY KEY']					payroll_time_sheet_id
	// [INTEGER] 						payroll_employee_id
	// [VARCHAR (DEFAULT : Regular) ] 	payroll_time_sheet_type
	// [DATE]							payroll_time_date

	/* REASONING */
	/* [payroll_company_id]  
		• REASON: THERE ARE SOME CASE WHERE EMPLOYEES NEED TO LOG IN DIFFERENT COMPANY/BRANCH AND THE CLIENT WANT TO SEE THE BREAK DOWN OF COMPUTATION PER COMPANY/BRANCH THEY VISITED
	*/

	/* [payroll_time_sheet_origin] 
		• REASON : DETEMINE IF THE TIME SHEET CAME FROM BIO METRICS OR IN ANY OTHER SOURCE
	*/

	public function scopecheckdata($query, $payroll_employee_id = 0, $payroll_time_date = '0000-00-00')
	{
		return $query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_time_date',$payroll_time_date);
	}
	public function scopeUpdateCompute($query, $_cutoff_input)
	{
    	foreach($_cutoff_input as $key => $input)
    	{
			$update_compute["time_sheet_daily_rate"] = $input->compute->daily_rate;
			$update_compute["time_sheet_daily_basic"] = $input->compute->total_day_basic;
			$update_compute["time_sheet_daily_cola"] = $input->compute->total_day_cola;
			$update_compute["time_sheet_daily_total_addition"] = $input->compute->breakdown_addition;
			$update_compute["time_sheet_daily_total_deduction"] = $input->compute->breakdown_deduction;
			$query->where("payroll_time_sheet_id", $input->payroll_time_sheet_id)->update($update_compute);
    	}
	}
	public function scopegetpercompany($query, $payroll_period_company_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_time_sheet.payroll_employee_id')
			 ->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
			 ->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			 ->where('tbl_payroll_period_company.payroll_period_company_id', $payroll_period_company_id)
			 ->where('tbl_payroll_time_sheet.payroll_time_date' ,'>=',DB::raw('tbl_payroll_period.payroll_period_start'))
			 ->where('tbl_payroll_time_sheet.payroll_time_date' ,'<=',DB::raw('tbl_payroll_period.payroll_period_end'));
		
		return $query;
	}
	public function scopeget_timesheet_approved($query)
	{
		return $query->leftjoin('tbl_payroll_time_sheet_record_approved','tbl_payroll_time_sheet.payroll_time_sheet_id','=','tbl_payroll_time_sheet_record_approved.payroll_time_sheet_id')->leftjoin('tbl_payroll_time_sheet_record_approved','tbl_payroll_time_sheet.payroll_time_sheet_id','=','tbl_payroll_time_sheet_record_approved.payroll_time_sheet_id')->leftjoin('tbl_payroll_company','tbl_payroll_time_sheet_record_approved.payroll_company_id',"=","tbl_payroll_company.payroll_company_id");
	}
	public function scopeget_timesheet($query)
	{
		return $query->leftjoin('tbl_payroll_time_sheet_record','tbl_payroll_time_sheet.payroll_time_sheet_id','=','tbl_payroll_time_sheet_record.payroll_time_sheet_id')
		->leftjoin('tbl_payroll_time_sheet_record_approved','tbl_payroll_time_sheet.payroll_time_sheet_id','=','tbl_payroll_time_sheet_record_approved.payroll_time_sheet_id')
		->leftjoin('tbl_payroll_company','tbl_payroll_time_sheet_record.payroll_company_id',"=","tbl_payroll_company.payroll_company_id");
	}

}
