<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Carbon\Carbon;

use DateTime;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Member\PayrollDeductionController;




use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Models\Tbl_payroll_time_keeping_approved_daily_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_performance;
use App\Models\Tbl_payroll_group;
use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_shift_code;
use App\Models\Tbl_payroll_shift_time;
use App\Models\Tbl_payroll_adjustment;
use App\Models\Tbl_payroll_period;
use App\Globals\Payroll2;
use App\Globals\Payroll;
use App\Globals\PayrollLeave;
use App\Globals\Utilities;
use App\Models\Tbl_payroll_company;



use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;


use DB;

class PayrollLedger extends Member
{

	public function shop_id()
	{
		return $this->user_info->shop_id;
	}

	public function index()
	{
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->shop_id();
		$data["_employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->get();
		// dd($data["_employee"]);
		return view("member.payrollreport.payroll_ledger",$data);
	}

	public function modal_ledger($employee_id)
	{
		$data["employee"] = Tbl_payroll_employee_basic::where("tbl_payroll_employee_basic.payroll_employee_id",$employee_id)->first();
		$data["_employee"]  = Tbl_payroll_period::GetEmployeeAllPeriodRecords($employee_id)
		->where("tbl_payroll_period_company.payroll_period_status","!=","pending")
		->get();

		$data = Payroll2::get_total_payroll_register($data);
		
		return view("member.payrollreport.payroll_employee_ledger",$data);
	}


}
