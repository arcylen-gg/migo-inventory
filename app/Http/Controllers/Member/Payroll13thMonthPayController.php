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

class Payroll13thMonthPayController extends Member
{

	public function shop_id($return = 'shop_id')
	{
	      switch ($return) {
	           case 'shop_id':
	                return $shop_id = $this->user_info->user_shop;
	                break;

	           case 'user_id':
	                return $shop_id = $this->user_info->user_id;
	                break;
	           
	           default:
	               
	                break;
	      }
	}

	public function index()
	{
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->shop_id();

		$data["_employee"] 					= Tbl_payroll_employee_basic::selemployee($parameter)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->get();
		$data['_company']    				= Payroll::company_heirarchy(Self::shop_id());

		return view("member.payrollreport.payroll_13th_month_pay_v2", $data );
	}

	public function employees_13th_month_pay_table()
	{

		$company_id = Request::input('company_id');
		
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->shop_id();

		$data["basis"] 	= unserialize(Request::input('employee_13_month_basis'));


		if ($company_id != 0 ) 
		{
			$data["_employee"] 	= Tbl_payroll_employee_basic::selemployee($parameter)->where("tbl_payroll_employee_basic.payroll_employee_company_id",$company_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->get();
		}
		else
		{
			$data["_employee"] 	= Tbl_payroll_employee_basic::selemployee($parameter)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->get();
		}

		
		
		/*unserialize(Request::input('employee_13_month_basis'));*/
		$this->employees_compute_13th_month_pay($data);

		return view('member.payrollreport.payroll_13th_month_pay_table_v2',$data);
	}

	public function employee_13_month_pay_report($employee_id)
	{
		$data["employee"] = Tbl_payroll_employee_basic::where("tbl_payroll_employee_basic.payroll_employee_id",$employee_id)->first();
		$data["_period"]  = Tbl_payroll_period::GetEmployeeAllPeriodRecords($employee_id)
		->where("tbl_payroll_period_company.payroll_period_status","!=","pending")
		->get();
		$data['basis']    =	unserialize(Request::input('basis'));
		$data = $this->compute_13th_month_pay($data);
		// dd($data);
		return view('member.payrollreport.payroll_13th_month_pay_report_v2',$data);
	}


	public function employee_13_month_pay_report_table()
	{
		$data["basis"] = unserialize(Request::input('employee_13_month_basis'));
		$data["basis_link"] = Request::input('employee_13_month_basis');
		// dd($data["basis_link"]);
		$data["employee"] = Tbl_payroll_employee_basic::where("tbl_payroll_employee_basic.payroll_employee_id",$data["basis"]['payroll_employee_id'])->first();
		$data["_period"]  = Tbl_payroll_period::GetEmployeeAllPeriodRecords($data["basis"]['payroll_employee_id'])
		->where("tbl_payroll_period_company.payroll_period_status","!=","pending")
		->get();
		
		$data = $this->compute_13th_month_pay($data);
		
		return view('member.payrollreport.payroll_13th_month_pay_report_table_v2',$data);
	}

	public function modal_employee_13_month_pay_report()
	{
		return view('member.modal.modal_employee_13_month_pay_report');
	}


	public function employee_13_month_pay_basis_submit()
	{

		$response["status"] 				 	= "success";
		$response["function"] 				 	= "load_13th_month_pay_table";
		$response["employee_id"]			 	= Request::input('employee_id');
		$response["employee_13_month_basis"]	= serialize(Request::input());
		$response["_token"]			 			= Request::input('_token');
		
		return json_encode($response);
	}

	public function compute_13th_month_pay($data)
	{
		$basis = $data["basis"];
		
		$grand_total_13th_month_pay = 0;

		foreach ($data["_period"] as $key_period => $period) 
		{
			$payroll_13th_month_basis = 0;


			if ($basis["payroll_13th_month_pay_basis"] == "net_pay") 
			{
				$payroll_13th_month_basis += $period["net_basic_pay"];
			}

			if ($basis["payroll_13th_month_pay_basis"] == "gross_pay") 
			{
				if (isset($period->cutoff_compute)) 
				{
					$gross_basic_pay = unserialize($period->cutoff_compute);
					
					if (isset($gross_basic_pay->cutoff_rate)) 
					{
						$payroll_13th_month_basis += $gross_basic_pay->cutoff_rate;
					}
				}
			}

			if (isset($period->cutoff_breakdown)) 
			{
				$period_cutoff_breakdown = unserialize($period->cutoff_breakdown);

				foreach ($period_cutoff_breakdown->_breakdown as $key_cutoff_breakdown => $breakdown) 
				{

					if (isset($data["basis"]["payroll_cola"])) 
					{
						if ($breakdown["label"] == "COLA") 
						{
							$payroll_13th_month_basis += $breakdown["amount"];
						}
					}

					if (isset($data["basis"]["payroll_allowance"]))
					{
						if (isset($breakdown["record_type"])) 
						{
							if ($breakdown["record_type"] == "allowance") 
							{
								$payroll_13th_month_basis += $breakdown["amount"];
							}
						}
					}

					if (isset($data["basis"]["late"])) 
					{
						if ($breakdown["label"] == "late") 
						{
							$payroll_13th_month_basis += $breakdown["amount"];
						}
					}

					if (isset($data["basis"]["absent"])) 
					{
						if ($breakdown["label"] == "absent") 
						{
							$payroll_13th_month_basis += $breakdown["amount"];
						}
					}

					if (isset($data["basis"]["undertime"])) 
					{
						if ($breakdown["label"] == "undertime") 
						{
							$payroll_13th_month_basis += $breakdown["amount"];
						}
					}
				}
			}



			if (isset($period->cutoff_input)) 
			{

				$_cutoff_input_breakdown = unserialize($period->cutoff_input);
				
				foreach ($_cutoff_input_breakdown as $value) 
				{

					if (isset($value->compute->_breakdown_addition)) 
					{
						foreach ($value->compute->_breakdown_addition as $lbl => $values) 
						{
							if (isset($basis['special_holiday'])) 
							{
								if ($lbl == 'Legal Holiday' || $lbl == 'Legal Holiday Rest Day') 
								{
									$payroll_13th_month_basis += $values['rate'];
								}
							}
							if (isset($basis['regular_holiday'])) 
							{
								if ($lbl == 'Special Holiday' || $lbl == 'Special Holiday Rest Day') 
								{
									$payroll_13th_month_basis += $values['rate'];
								}
							}
						}
					}
				}
			}

			$data['_period'][$key_period]->payroll_13th_month_basis 		= $payroll_13th_month_basis;
			$data['_period'][$key_period]->payroll_13th_month_contribution 	= @( $payroll_13th_month_basis / 12);
			$grand_total_13th_month_pay += @($payroll_13th_month_basis/12);
		}

		$data['grand_total_13th_month_pay'] = $grand_total_13th_month_pay;

		return $data;
	}

	public function employees_compute_13th_month_pay($data)
	{
		$basis = $data["basis"];

		foreach ($data["_employee"] as $key => $employee) 
		{
			$grand_total_13th_month_pay = 0;

			$_period  = Tbl_payroll_period::GetEmployeeAllPeriodRecords($employee->payroll_employee_id)
			->where("tbl_payroll_period_company.payroll_period_status","!=","pending")
			->where("tbl_payroll_period.year_contribution",$basis["payroll_13th_month_pay_year"])
			->get();

			$employee_info = Tbl_payroll_employee_contract::Group()->where('tbl_payroll_employee_contract.payroll_employee_id',$employee->payroll_employee_id)->first();

			foreach ($_period as $period_key => $period) 
			{
				$payroll_13th_month_basis = 0;

				if ($basis["payroll_13th_month_pay_basis"] == "net_pay") 
				{
					$payroll_13th_month_basis += $period["net_pay"];
				}


				if ($basis["payroll_13th_month_pay_basis"] == "gross_pay") 
				{
					if (isset($period->cutoff_compute)) 
					{
						$gross_basic_pay = unserialize($period->cutoff_compute);
						
						if (isset($gross_basic_pay->cutoff_rate)) 
						{
							$payroll_13th_month_basis += $gross_basic_pay->cutoff_rate;
						}
					}
				}



				if (isset($period->cutoff_breakdown)) 
				{
					$period_cutoff_breakdown = unserialize($period->cutoff_breakdown);

					foreach ($period_cutoff_breakdown->_breakdown as $key_cutoff_breakdown => $breakdown) 
					{

						if (isset($data["basis"]["payroll_cola"])) 
						{
							if ($breakdown["label"] == "COLA") 
							{
								$payroll_13th_month_basis += $breakdown["amount"];
							}
						}

						if (isset($data["basis"]["payroll_allowance"]))
						{
							if (isset($breakdown["record_type"])) 
							{
								if ($breakdown["record_type"] == "allowance") 
								{
									$payroll_13th_month_basis += $breakdown["amount"];
								}
							}
						}

						if (isset($data["basis"]["late"])) 
						{
							if ($breakdown["label"] == "late") 
							{
								$payroll_13th_month_basis += $breakdown["amount"];
							}
						}

						if (isset($data["basis"]["absent"])) 
						{
							if ($breakdown["label"] == "absent") 
							{
								$payroll_13th_month_basis += $breakdown["amount"];
							}
						}

						if (isset($data["basis"]["undertime"])) 
						{
							if ($breakdown["label"] == "undertime") 
							{
								$payroll_13th_month_basis += $breakdown["amount"];
							}
						}
					}
				}

				if (isset($period->cutoff_input)) 
				{

					$_cutoff_input_breakdown = unserialize($period->cutoff_input);
					
					foreach ($_cutoff_input_breakdown as $value) 
					{

						if (isset($value->compute->_breakdown_addition)) 
						{
							foreach ($value->compute->_breakdown_addition as $lbl => $values) 
							{
								if (isset($basis['special_holiday'])) 
								{
									if ($lbl == 'Legal Holiday' || $lbl == 'Legal Holiday Rest Day') 
									{
										$payroll_13th_month_basis += $values['rate'];
									}
								}
								if (isset($basis['regular_holiday'])) 
								{
									if ($lbl == 'Special Holiday' || $lbl == 'Special Holiday Rest Day') 
									{
										$payroll_13th_month_basis += $values['rate'];
									}
								}
							}
						}
					}
				}

				$grand_total_13th_month_pay += @($payroll_13th_month_basis/12);
			}

			$data["_employee"][$key]->grand_total_13th_month_pay = $grand_total_13th_month_pay;
		}
	}
}