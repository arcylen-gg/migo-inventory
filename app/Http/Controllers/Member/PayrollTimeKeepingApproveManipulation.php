<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Excel;
use DB;
use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_employment_status;
use App\Models\Tbl_payroll_employee_salary;

class PayrollTimeKeepingApproveManipulation extends Member
{
	public function time_breakdown($period_company_id, $employee_id)
	{
		
		
		if ($employee_id != 0) {
			$timekeeping_approve = Tbl_payroll_time_keeping_approved::where('payroll_period_company_id',$period_company_id)->where("employee_id", $employee_id)->get();
			$time_keeping_approve_id = $timekeeping_approve->time_keeping_approve_id;

			$cutoff_breakdown = unserialize($timekeeping_approve->cutoff_breakdown);
			$cutoff_breakdown->_time_breakdown["absent"]["float"] = 0;
			$cutoff_breakdown->_time_breakdown["absent"]["time"]  = "No Absent";
			
			$update["cutoff_breakdown"] = serialize($cutoff_breakdown);

			Tbl_payroll_time_keeping_approved::where('time_keeping_approve_id', $time_keeping_approve_id)->update($update);
		}
		else
		{
			$_timekeeping_approve = Tbl_payroll_time_keeping_approved::where('payroll_period_company_id',$period_company_id)->get();
			foreach ($_timekeeping_approve as $key => $timekeeping_approve) 
			{
				
				$time_keeping_approve_id = $timekeeping_approve->time_keeping_approve_id;

				$cutoff_breakdown = unserialize($timekeeping_approve->cutoff_breakdown);
				$cutoff_breakdown->_time_breakdown["absent"]["float"] = 0;
				$cutoff_breakdown->_time_breakdown["absent"]["time"]  = "No Absent";
				
				$update["cutoff_breakdown"] = serialize($cutoff_breakdown);

				Tbl_payroll_time_keeping_approved::where('time_keeping_approve_id', $time_keeping_approve_id)->update($update);
			}
			dd(unserialize($_timekeeping_approve[0]->cutoff_breakdown));
		}
		
		
	}
		
}