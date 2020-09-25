<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Response;
use Excel;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Globals\Payroll;
use PDF2;
use App\Globals\Payroll2;
use DateTime;

use App\Models\Tbl_payroll_employee_basic;

use App\Http\Controllers\Member\PayrollDeductionController;


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



use App\Models\Tbl_payroll_leave_temp;
use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_leave_employee;

use App\Globals\AuditTrail;



class PayrollBranchTaggingReportController extends Member
{
	public function shop_id()
	{
		return $this->user_info->shop_id;
	}


	public function payroll_branch_tagging_report()
	{
		 $data["_company"] = Tbl_payroll_company::where("shop_id", $this->shop_id())->where('payroll_parent_company_id', 0)->get();
	     $data["_months"]  = Payroll::get_month();
	     $data["_years"]   = array("2017" => "2017","2018" => "2018","2019" => "2019");
	 	 
		 return view("member.payrollreport.payroll_branch_tagging_report", $data);
	}


	public function payroll_branch_tagging_report_table()
	{
		$period_date_start 	= Date('Y-m-d', strtotime(Request::input('period_date_start')));
		$period_date_end 	= Date('Y-m-d', strtotime(Request::input('period_date_end')));

		$data["_period_record"]    = Tbl_payroll_period::join('tbl_payroll_period_company', 'tbl_payroll_period.payroll_period_id', '=', 'tbl_payroll_period_company.payroll_period_id')
												->join('tbl_payroll_time_keeping_approved', 'tbl_payroll_period_company.payroll_period_company_id', '=', 'tbl_payroll_time_keeping_approved.payroll_period_company_id')
												->where('tbl_payroll_period.shop_id',$this->shop_id())
												->where('tbl_payroll_period.payroll_period_start', ">=", $period_date_start)
												->where('tbl_payroll_period.payroll_period_end', "<=" , $period_date_end)
												->where('tbl_payroll_period_company.payroll_period_status', "!=", 'pending')
												->get();

		$data['_branch_report'] 		= PayrollBranchTaggingReportController::get_company_sub_branch();
		$data 							= $this->compute_total_gross_pay_each_branch($data);
		$data["_company_parent_report"] = $this->compute_company_parent_tagging($data);
		
       	return view("member.payrollreport.payroll_branch_tagging_report_table", $data);
	}


	public function get_company_sub_branch()
	{
		$_company = Tbl_payroll_company::where('shop_id',$this->shop_id())->get();
		// ->where('payroll_parent_company_id','!=','0') /* branch only not the parent company*/
		$return = null;
		
		foreach ($_company as $key => $company) 
		{
			$return[$company->payroll_company_id]['company_name'] 		   = $company->payroll_company_name;
			$return[$company->payroll_company_id]['parent_company_id']     = $company->payroll_parent_company_id;
			$return[$company->payroll_company_id]['rotational_gross_pay']  = 0;
			$return[$company->payroll_company_id]['government_gross_pay']  = 0;
			$return[$company->payroll_company_id]['difference']			   = 0;
			
			$return[$company->payroll_company_id]['daily_branch_rotational']	   		   = array();
			$return[$company->payroll_company_id]['daily_employee_branch_rotational']	   = array();
			$return[$company->payroll_company_id]['daily_employee_branch_government']	   = array();

			$return[$company->payroll_company_id]['other_employee_branch_rotational']	   = array();
			$return[$company->payroll_company_id]['other_employee_branch_government']	   = array();

			$return[$company->payroll_company_id]['total_daily_branch_rotational']			   = 0;
			$return[$company->payroll_company_id]['total_daily_employee_branch_rotational']    = 0;
			$return[$company->payroll_company_id]['total_daily_employee_branch_government']	   = 0;

			$return[$company->payroll_company_id]['total_other_employee_branch_rotational']    = 0;
			$return[$company->payroll_company_id]['total_other_employee_branch_government']	   = 0;
		}

		return $return;
	}


	public function payroll_branch_tagging_report_period($period_company_id)
	{
		$data["period"] = tbl_payroll_period_company::sel($period_company_id)->get();
		return view('member.payrollreport.payroll_branch_tagging_report_period',$data);
	}


	public function compute_total_gross_pay_each_branch($data)
	{
		foreach ($data["_period_record"] as $key => $period_record) 
		{
			// unserialize
			$data["_period_record"][$key]['cutoff_compute'] 		= unserialize($period_record['cutoff_compute']);
			$data["_period_record"][$key]['cutoff_breakdown'] 		= unserialize($period_record['cutoff_breakdown']);
			$data["_period_record"][$key]['cutoff_input'] 			= unserialize($period_record['cutoff_input']);

			if ($period_record["salary_compute_type"] == "Daily Rate") 
			{
				foreach ($period_record['cutoff_input'] as $key_cutoff_input => $cutoff_input) 
				{
					$total_daily_rate_plus_cola = Payroll2::payroll_number_format($cutoff_input->compute->total_day_income + $cutoff_input->compute->cola, 2);
					
					/*if record does not have branch source company id or just manual input*/
					if ($cutoff_input->branch_source_company_id == 0) 
					{
						$data['_branch_report'][$period_record->employee_company_id]['rotational_gross_pay'] += $total_daily_rate_plus_cola;
						
						array_push($data['_branch_report'][$period_record->employee_company_id]['daily_employee_branch_rotational'], $total_daily_rate_plus_cola);
						$data['_branch_report'][$period_record->employee_company_id]['total_daily_employee_branch_rotational'] += $total_daily_rate_plus_cola;
					}
					/*have source company id*/
					else
					{
						$data['_branch_report'][$cutoff_input->branch_source_company_id]['rotational_gross_pay'] += $total_daily_rate_plus_cola;
						
						array_push($data['_branch_report'][$cutoff_input->branch_source_company_id]['daily_branch_rotational'], $total_daily_rate_plus_cola);
						$data['_branch_report'][$cutoff_input->branch_source_company_id]['total_daily_branch_rotational'] += $total_daily_rate_plus_cola;
					}

					//add rotational gross pay
					$data['_branch_report'][$period_record->employee_company_id]['government_gross_pay'] += $total_daily_rate_plus_cola; 
					array_push($data['_branch_report'][$period_record->employee_company_id]['daily_employee_branch_government'], $total_daily_rate_plus_cola);
					$data['_branch_report'][$period_record->employee_company_id]['total_daily_employee_branch_government'] += $total_daily_rate_plus_cola;
					
					//add salary 
					foreach ($period_record['cutoff_breakdown']->_breakdown as $key2 => $breakdown) 
					{
						
						if (isset($breakdown['type'])) 
						{
							if ($breakdown['add.gross_pay'] == true 
								&& ($breakdown['type'] == 'adjustment' || $breakdown['type'] == 'allowance')) 
							{
								// dd($breakdown);
								// $data['_branch_report'][$period_record->employee_company_id]['rotational_gross_pay'] += $breakdown['amount'];
								// $data['_branch_report'][$period_record->employee_company_id]['government_gross_pay'] += $breakdown['amount'];

								// $data['_branch_report'][$period_record->employee_company_id]['total_daily_employee_branch_rotational'] += $breakdown['amount'];
								// $data['_branch_report'][$period_record->employee_company_id]['total_daily_employee_branch_government'] += $breakdown['amount'];

								// array_push($data['_branch_report'][$period_record->employee_company_id]['daily_employee_branch_rotational'], $breakdown['amount']);
								// array_push($data['_branch_report'][$period_record->employee_company_id]['daily_employee_branch_government'], $breakdown['amount']);
							}
						}
					}
				}
			}
			else if ($period_record["salary_compute_type"] != "Monthly Rate" || $period_record["salary_compute_type"] == "Flat Rate") 
			{
				$gross_pay = Payroll2::payroll_number_format($period_record['gross_pay'], 2);
				$data['_branch_report'][$period_record->employee_company_id]['rotational_gross_pay'] += $gross_pay; 
				$data['_branch_report'][$period_record->employee_company_id]['government_gross_pay'] += $gross_pay;
				
				array_push($data['_branch_report'][$period_record->employee_company_id]['other_employee_branch_rotational'], $gross_pay);
				array_push($data['_branch_report'][$period_record->employee_company_id]['other_employee_branch_government'], $gross_pay);
				$data['_branch_report'][$period_record->employee_company_id]['total_other_employee_branch_rotational'] += $gross_pay;
				$data['_branch_report'][$period_record->employee_company_id]['total_other_employee_branch_government'] += $gross_pay;
			}
		}
	
		$data["total_rotational_report"] = 0;
		$data["total_government_report"] = 0;
		$data["total_difference"] 		 = 0;

		foreach ($data['_branch_report'] as $key => $branch_report) 
		{
			$diff = Payroll2::payroll_number_format(($branch_report["government_gross_pay"] - $branch_report["rotational_gross_pay"]),2);

			$data["total_rotational_report"] += $branch_report["rotational_gross_pay"];
			$data["total_government_report"] += $branch_report["government_gross_pay"];
			$data['_branch_report'][$key]['difference'] = $diff;
			$data["total_difference"] += $diff;
		}

		return $data;
	}


	public function compute_company_parent_tagging($data)
	{
		$_company_parent_report = array();

		foreach ($data['_branch_report'] as $key => $branch_report) 
		{
			if ($branch_report["parent_company_id"] == 0) 
			{
				$_company_parent_report[$key] = $branch_report;
			}
		}

		foreach ($data['_branch_report'] as $key => $branch_report) 
		{
			if ($branch_report["parent_company_id"] != 0) 
			{
				$_company_parent_report[$branch_report["parent_company_id"]]['rotational_gross_pay']	+= $branch_report['rotational_gross_pay'];
				$_company_parent_report[$branch_report["parent_company_id"]]['government_gross_pay']	+= $branch_report['government_gross_pay'];
				$_company_parent_report[$branch_report["parent_company_id"]]['difference'] 				+= $branch_report['difference'];
			}
		}

		return $_company_parent_report;
	}

}
