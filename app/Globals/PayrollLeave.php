<?php

namespace App\Globals;
use Request;
use stdClass;
use Redirect;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Models\Tbl_payroll_group;
use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_shift_code;
use App\Models\Tbl_payroll_leave_schedulev2;
use App\Globals\Payroll2;
use App\Globals\Payroll;
use App\Models\Tbl_payroll_company;
use DB;

class PayrollLeave 
{
	public static function employee_leave_data($employee_id)
	{
		$employee_leave_data = Tbl_payroll_leave_schedule::getemployeeleavedata($employee_id)->first();
		return $employee_leave_data;
	}

	public static function employee_leave_date_data($employee_id, $date)
	{
		$employee_leave_date_data = Tbl_payroll_leave_schedule::getemployeeleavedatedata($employee_id,$date)->first();
		return $employee_leave_date_data;
	}

	public static function employee_leave_capacity($employee_id)
	{
		$employee_leave_data = Tbl_payroll_leave_schedule::getemployeeleavedata($employee_id)->first();
		return $employee_leave_data["payroll_leave_temp_days_cap"];
	}

	public static function employee_leave_consumed($payroll_leave_employee_id)
	{
		$employee_consumed_leave = Tbl_payroll_leave_schedule::getemployeeleaveconsumesumdata($payroll_leave_employee_id)->first();
		return $employee_consumed_leave->total_leave_consume;
	}

	public static function employee_leave_remaining($employee_id, $payroll_leave_employee_id)
	{
		$employee_consumed_leave = Tbl_payroll_leave_schedule::getemployeeleaveconsumesumdata($payroll_leave_employee_id)->first();
		$employee_leave_capacity = Tbl_payroll_leave_schedule::getemployeeleavedata($employee_id)->first();
		return $employee_consumed_leave->total_leave_consume-$employee_leave_capacity["payroll_leave_temp_days_cap"];
	}


	public static function employee_leave_capacity_consume_remaining($employee_id = 0)
	{
		$leave_data_all = Tbl_payroll_leave_schedule::getallemployeeleavedata($employee_id);
		return $leave_data_all;
	}
	

	//leave v2
	public static function employee_leave_date_datav2($employee_id, $date)
	{
		$employee_leave_date_data = Tbl_payroll_leave_schedulev2::getemployeeleavedatedata($employee_id,$date)->first();
		return $employee_leave_date_data;
	}

	public static function employee_leave_capacity_consume_remainingv2($employee_id = 0)
	{
		$leave_data_all = Tbl_payroll_leave_schedulev2::getallemployeeleavedata($employee_id);
		return $leave_data_all;
	}

}
