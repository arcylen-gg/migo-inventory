<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Carbon\Carbon;
use PDF2;

use App\Globals\AuditTrail;
use App\Globals\Payroll2;
use App\Http\Controllers\Controller;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Models\Tbl_payroll_time_keeping_approved_performance;
use App\Models\Tbl_payroll_journal_tag;


class PayrollProcessController extends Member
{
	public function index($period_company_id)
	{
		$data["period_company_id"] = $period_company_id;
		$data["step"] = Request::input("step");

		if(Request::isMethod("post"))
		{
			$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();
			$data = $this->get_total($data);
			extract($data);
			
			$record = Tbl_payroll_period_company::where('payroll_period_company_id', $period_company_id)->first();

			switch ($data["step"])
			{
				case 'process':
					$step = "processed";
				break;
				case 'register':
					$step = "registered";
				break;
				case 'post':
					$step = "posted";
				break;

				case 'approve':
					$step = "approved";
				break;

				default:
					$step = "generated";
				break;
			}

			if ($data["step"] == "post") 
			{
				Payroll2::insert_journal_entry_per_period($period_company_id ,$this->user_info->shop_id);
			}
			
			$update["payroll_period_status"] = $step;
			$update["payroll_period_total_basic"] = $total_basic;
			$update["payroll_period_total_gross"] = $total_gross;
			$update["payroll_period_total_net"] = $total_net;
			$update["payroll_period_total_sss_ee"] = $total_sss_ee;
			$update["payroll_period_total_sss_er"] = $total_sss_er;
			$update["payroll_period_total_sss_ec"] = $total_sss_ec;
			$update["payroll_period_total_philhealth_ee"] = $total_philhealth_ee;
			$update["payroll_period_total_philhealth_er"] = $total_philhealth_er;
			$update["payroll_period_total_pagibig_ee"] = $total_pagibig_ee;
			$update["payroll_period_total_pagibig_er"] = $total_pagibig_er;
			$update["payroll_period_total_grand"] = $total_grand;

			Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->update($update);

			$new_record = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->first();
			AuditTrail::record_logs("Process Payroll Period","Payroll Process Period id # " . $period_company_id, $period_company_id,$record,$new_record);
			
			return Redirect::to("/member/payroll/payroll_process_module");
		}
		else
		{
			$data["company"] = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->company()->companyperiod()->first();
			return view("member.payroll2.payroll_process", $data);
		}
	}
	public function index_table($period_company_id)
	{
		$data["company"] = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->company()->companyperiod()->first();
		$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();
		$data = $this->get_total($data);
		return view("member.payroll2.payroll_process_table", $data);
	}
	public function modal_view_summary($period_company_id)
	{
		$data["company"] = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->company()->companyperiod()->first();
		$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();
		
		$data["period_info"] = $company_period = Tbl_payroll_period_company::sel($period_company_id)->first();
		$data["show_period_start"]	= date("F d, Y", strtotime($data["period_info"]->payroll_period_start));
		$data["show_period_end"]	= date("F d, Y", strtotime($data["period_info"]->payroll_period_end));

		$data = $this->get_total($data);
		
		return view("member.payroll2.payroll_process_modal_view_summary", $data);
	}

	public function modal_approved_summary($period_company_id)
	{
		$data["period_info"] = $company_period = Tbl_payroll_period_company::sel($period_company_id)->first();
		$data["show_period_start"]	= date("F d, Y", strtotime($data["period_info"]->payroll_period_start));
		$data["show_period_end"]	= date("F d, Y", strtotime($data["period_info"]->payroll_period_end));
		$data["_employee"] 			= Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();
		$data["payroll_summary"] 	= "payroll_summary";
		foreach($data["_employee"] as $key => $employee)
		{
			$data["_employee"][$key] = $employee;
			$data["_employee"][$key]->cutoff_compute = unserialize($employee->cutoff_compute);
			$data["_employee"][$key]->cutoff_input =  unserialize($employee->cutoff_input);
			$data["_employee"][$key]->cutoff_breakdown =  unserialize($employee->cutoff_breakdown);

			$other_deductions = 0;

			foreach($data["_employee"][$key]->cutoff_breakdown->_breakdown as $breakdown)
			{
				if($breakdown["deduct.net_pay"] == true)
				{
					$other_deductions += $breakdown["amount"];
				}
			}

			$data["_employee"][$key]->other_deduction = $other_deductions;
			$data["_employee"][$key]->total_deduction = $employee->philhealth_ee + $employee->sss_ee + $employee->pagibig_ee + $employee->tax_ee + $other_deductions;
		}

		$data = $this->get_total($data);

		return view("member.payroll2.payroll_process_modal_approved_summary", $data);
	}
	public function unprocess($period_company_id)
	{
		$old_record = Tbl_payroll_period_company::where('payroll_period_company_id', $period_company_id)->first();
        
		/* TODO: SECURE UNPROCESS */
		$update["payroll_period_status"] = "generated";
		Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->update($update);

		$new_record = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->first();
		AuditTrail::record_logs("Unprocess Payroll Period","Unprocess Payroll Period id # ". $period_company_id,$period_company_id,$old_record,$new_record);

		return Redirect::to("/member/payroll/time_keeping");
	}

	public function get_total($data)
	{

		$total_basic = 0;
		$total_gross = 0;
		$total_net = 0;
		$total_tax = 0;

		$g_total_er = 0;
		$g_total_ee = 0;
		$g_total_ec = 0;

		$total_sss_ee = 0;
		$total_sss_er = 0;
		$total_sss_ec = 0;
		$total_philhealth_ee = 0;
		$total_philhealth_er = 0;
		$total_pagibig_ee = 0;
		$total_pagibig_er = 0;
		$total_deduction = 0;

		$total_deduction_employee=0;

		$_other_deduction = null;
		$_addition = null;
		$_deduction = null;

			
		foreach($data["_employee"] as $key => $employee)
		{
			
			$deduction = 0;
			$total_basic += $employee->net_basic_pay;
			$total_gross += $employee->gross_pay;
			$total_net += $employee->net_pay;
			$total_tax += $employee->tax_ee;

			$total_er = $employee->sss_er + $employee->philhealth_er +  $employee->pagibig_er;
			$total_ee = $employee->sss_ee + $employee->philhealth_ee +  $employee->pagibig_ee;
			$total_ec = $employee->sss_ec;

			$total_sss_ee += $employee->sss_ee;
			$total_sss_er += $employee->sss_er;
			$total_sss_ec += $employee->sss_ec;
			$total_philhealth_ee += $employee->philhealth_er;
			$total_philhealth_er += $employee->philhealth_er;
			$total_pagibig_ee += $employee->pagibig_ee;
			$total_pagibig_er += $employee->pagibig_er;

			// $total_deduction_employee += $employee["total_deduction"];
			
			
			$data["_employee"][$key] = $employee;
			$data["_employee"][$key]->total_er = $total_er;
			$data["_employee"][$key]->total_ee = $total_ee;
			$data["_employee"][$key]->total_ec = $total_ec;

			$g_total_ec += $total_ec;
			$g_total_er += $total_er;
			$g_total_ee += $total_ee;

			$total_deduction += ($total_ee);

			if(isset($employee->cutoff_breakdown))
			{
				if (isset($data["payroll_summary"])) 
				{
					$_duction_break_down = $employee->cutoff_breakdown->_breakdown;
				}
				else
				{
					$_duction_break_down = unserialize($employee->cutoff_breakdown)->_breakdown;
				}

				foreach($_duction_break_down as $breakdown)
				{
					if($breakdown["deduct.net_pay"] == true)
					{
						$total_deduction_employee += $breakdown["amount"];
						$deduction += $breakdown["amount"];
					}

					if($breakdown["deduct.gross_pay"] == true)
					{
						$total_deduction_employee += $breakdown["amount"];
						$deduction += $breakdown["amount"];
					}

					if ($breakdown["label"] == "SSS EE" || $breakdown["label"] == "PHILHEALTH EE" || $breakdown["label"] == "PAGIBIG EE" ) 
					{
						$total_deduction_employee += $breakdown["amount"];
						$deduction += $breakdown["amount"];
					}
				}

				$data["_employee"][$key]->total_deduction_employee = $deduction;
					
			}

			if (isset($employee["cutoff_breakdown"]->_breakdown )) 
			{
				# code...
				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{
					if($breakdown["deduct.net_pay"] == true)
					{
						if(isset($_other_deduction[$breakdown["label"]]))
						{
							$_other_deduction[$breakdown["label"]] += $breakdown["amount"];
							$total_deduction += $breakdown["amount"];
						}
						else
						{
							$_other_deduction[$breakdown["label"]] = $breakdown["amount"];
							$total_deduction += $breakdown["amount"];
						}
						
					}
				}

				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{
					if($breakdown["add.gross_pay"] == true)
					{
						if(isset($_addition[$breakdown["label"]]))
						{
							$_addition[$breakdown["label"]] += $breakdown["amount"];
						}
						else
						{
							$_addition[$breakdown["label"]] = $breakdown["amount"];
						}
					}
				}

				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{

					if($breakdown["type"] == "deductions")
					{
						if(isset($_deduction[$breakdown["label"]]))
						{
							$_deduction[$breakdown["label"]] += $breakdown["amount"];
						}
						else
						{
							$_deduction[$breakdown["label"]] = $breakdown["amount"];
						}
					}
				}
			}
		}

		$data["total_basic"] = $total_basic;
		$data["total_gross"] = $total_gross;
		$data["total_net"] = $total_net;
		$data["total_er"] = $g_total_er;
		$data["total_ee"] = $g_total_ee;
		$data["total_ec"] = $g_total_ec;
		$data["total_tax"] = $total_tax;
		$data["total_grand"] = $total_net + $g_total_er + $g_total_ee + $g_total_ec + $total_tax;
		$data["total_sss_ee"] = $total_sss_ee;
		$data["total_sss_er"] = $total_sss_er;
		$data["total_sss_ec"] = $total_sss_ec;
		$data["total_philhealth_ee"] = $total_philhealth_ee;
		$data["total_philhealth_er"] = $total_philhealth_er;
		$data["total_pagibig_ee"] = $total_pagibig_ee;
		$data["total_pagibig_er"] = $total_pagibig_er;
		$data["_other_deduction"] = $_other_deduction;
		$data["_addition"] = $_addition;
		$data["_deduction"] = $_deduction;
		$data["total_deduction"] = $total_deduction;
		$data["total_deduction_of_all_employee"] = $total_deduction_employee;
		
		// dd($data["total_deduction_of_all_employee"]);
		return $data;
	}


	public function income_summary_timesheet($period_company_id,$employee_id)
	{
		$data["period_company"] = $period_company = Tbl_payroll_period_company::where("payroll_period_company_id",$period_company_id)->first();
		$data["period"] 		= $period         = Tbl_payroll_period::where("payroll_period_id",$period_company->payroll_period_id)->first();
		$data["approved"] 		= $approved       = Tbl_payroll_time_keeping_approved::where("employee_id",$employee_id)->where("payroll_period_company_id",$period_company_id)->first();
		
		$data["employee"]   	= Tbl_payroll_employee_basic::where("payroll_employee_id",$employee_id)->first();
		$data["_timesheet"]     = null;
		$ctr                    = 0;
		$days_worked            = 0;
		$breakdown       		= unserialize($approved->cutoff_breakdown)->_time_breakdown;   
		$input	       			= unserialize($approved->cutoff_input);   
		$compute        		= unserialize($approved->cutoff_compute);   
		
		$data["total_absent"]	= $total_absent	  = $breakdown["absent"]["time"]; 
		$data["total_late"]		= $total_late	  = $breakdown["late"]["time"]; 
		$data["overtime"]		= $overtime		  = $breakdown["overtime"]["time"]; 
		$data["undertime"]		= $undertime	  = $breakdown["undertime"]["time"]; 

		// dd($input);
		foreach($input as $key => $timesheet)
		{

			if($timesheet->_time != null)
			{	

				$count_time  = count($timesheet->_time);
				$data["_timesheet"][$ctr]["covered_date"] = date('M d, Y', strtotime($key));
				$data["_timesheet"][$ctr]["converted_time_in"]  = date('h:i:s A', strtotime($timesheet->_time[0]->time_in));
				$data["_timesheet"][$ctr]["converted_time_out"] = date('h:i:s A', strtotime($timesheet->_time[$count_time - 1]->time_out));
				$data["_timesheet"][$ctr]["remarks"] 			= $timesheet->default_remarks;
				$ctr++;
				$days_worked = $days_worked + 1;
			}
		}	

		$data["days_worked"] = $days_worked;

		// dd($input);

		return view("member.payroll2.payroll_process_modal_view_timesheet", $data);
	}
	public function income_summary_timesheet_v2($period_company_id,$employee_id)
	{		
		//arcy
		$data["period_company"] = $period_company = Tbl_payroll_period_company::where("payroll_period_company_id",$period_company_id)->first();

		$data["employee"]   	= Tbl_payroll_employee_basic::where("payroll_employee_id",$employee_id)->first();

		$period 				= Tbl_payroll_period::where('payroll_period_id',$data["period_company"]->payroll_period_id)->first();

		
		$data['_timesheet'] = [];
		$data["days_worked"]	= 0;
		$data["days_absent"]	= 0;
		$data['total_late'] = 0;
		$data['total_undertime'] = 0;
		$data['total_overtime'] = 0;
		$days_worked = 0;
		$days_absent = 0;
		$total_late = 0;                                                                                                                                                                                                                                                                                                                                                         
		$total_undertime = 0;
		$total_overtime = 0;


		if($period)
		{
			$date_start = $period->payroll_period_start;
			$date_end = $period->payroll_period_end;

			$get_timesheet = Tbl_payroll_time_sheet::get_timesheet()->selectRaw('*, tbl_payroll_time_sheet_record.payroll_time_sheet_in as actual_in, tbl_payroll_time_sheet_record.payroll_time_sheet_out as actual_out, tbl_payroll_time_sheet_record_approved.payroll_time_sheet_in as approved_in, tbl_payroll_time_sheet_record_approved.payroll_time_sheet_out as approved_out ')->whereBetween('payroll_time_date', array($date_start, $date_end))->where('payroll_employee_id',$employee_id)->groupBy('payroll_time_date')->get();
			
			foreach ($get_timesheet as $key => $value) 
			{

				$data['_timesheet'][$key] = [];
				$data['_timesheet'][$key]["covered_date"] = date('M d, Y', strtotime($value->payroll_time_date));
				if($value->payroll_time_sheet_record_id)
				{
					$days_worked++;
					$data['_timesheet'][$key]["actual_in"] = date('h:i:s A', strtotime($value->actual_in));
					$data['_timesheet'][$key]["actual_out"] = date('h:i:s A', strtotime($value->actual_out));
					$data['_timesheet'][$key]["approved_in"] = date('h:i:s A', strtotime($value->approved_in));
					$data['_timesheet'][$key]["approved_out"] = date('h:i:s A', strtotime($value->approved_out));
				}
				
				else
				{		
					$days_absent++;
					$data['_timesheet'][$key]["actual_in"] = 'NO TIME';
					$data['_timesheet'][$key]["actual_out"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_in"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_out"] = 'NO TIME';
				}

				$time_serialize = unserialize($value->payroll_time_serialize);

				if($time_serialize)
				{
					$total_late += $time_serialize->late;
					$total_undertime += $time_serialize->undertime;
					$total_overtime += $time_serialize->overtime;
				}

				$data['_timesheet'][$key]["remarks"] = $value->payroll_time_shee_activity;
				$data['_timesheet'][$key]["branch"] = $value->payroll_company_name;

			}
		}

		$data["days_worked"]	= $days_worked;
		$data["days_absent"]	= $days_absent;
		$data['total_late'] = date('i',strtotime($total_late));
		$data['total_undertime'] = date('i',strtotime($total_undertime));
		$data['total_overtime'] = date('i',strtotime($total_overtime));

		return view("member.payroll2.payroll_process_modal_view_timesheet", $data);
	}
	public function income_summary_timesheet_v3($period_company_id,$employee_id)
	{
		//arcy
		$data["period_company"] = $period_company = Tbl_payroll_period_company::where("payroll_period_company_id",$period_company_id)->first();

		$data["employee"]   	= Tbl_payroll_employee_basic::where("payroll_employee_id",$employee_id)->first();

		$period 				= Tbl_payroll_period::where('payroll_period_id',$data["period_company"]->payroll_period_id)->first();

		
		$data['_timesheet'] = [];
		$data["days_worked"]	= 0;
		$data["days_absent"]	= 0;
		$data['total_late'] = 0;
		$data['total_undertime'] = 0;
		$data['total_overtime'] = 0;
		$days_worked = 0;
		$days_absent = 0;
		$total_late = 0;
		$total_undertime = 0;
		$total_overtime = 0;
		if($period)
		{
			$date_start = $period->payroll_period_start;
			$date_end = $period->payroll_period_end;

			$get_timesheet = Tbl_payroll_time_sheet::whereBetween('payroll_time_date', array($date_start, $date_end))->where('payroll_employee_id',$employee_id)->groupBy('payroll_time_date')->get();
			
			foreach ($get_timesheet as $key => $value) 
			{

				$data['_timesheet'][$key] = [];
				$data['_timesheet'][$key]["covered_date"] = date('M d, Y', strtotime($value->payroll_time_date));
				
				$_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $value->payroll_time_sheet_id)->join("tbl_payroll_company", "tbl_payroll_company.payroll_company_id", "=", "tbl_payroll_time_sheet_record.payroll_company_id")->get();
				$_record_approved = Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $value->payroll_time_sheet_id)->get();

				$data['_timesheet'][$key]["actual_time"] = $_record;
				$data['_timesheet'][$key]["approve_time"] = $_record_approved;



				if(count($_record) > 0)
				{
					$days_worked++;
					$data['_timesheet'][$key]["actual_in"] = date('h:i:s A', strtotime($value->actual_in));
					$data['_timesheet'][$key]["actual_out"] = date('h:i:s A', strtotime($value->actual_out));
					$data['_timesheet'][$key]["approved_in"] = date('h:i:s A', strtotime($value->approved_in));
					$data['_timesheet'][$key]["approved_out"] = date('h:i:s A', strtotime($value->approved_out));
				}
				else
				{		
					$days_absent++;
					$data['_timesheet'][$key]["actual_in"] = 'NO TIME';
					$data['_timesheet'][$key]["actual_out"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_in"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_out"] = 'NO TIME';
				}

				$time_serialize = unserialize($value->payroll_time_serialize);

				if($time_serialize)
				{
					$total_late += $time_serialize->late;
					$total_undertime += $time_serialize->undertime;
					$total_overtime += $time_serialize->overtime;
				}
				$data['_timesheet'][$key]["remarks"] = $value->payroll_time_shee_activity;
				$data['_timesheet'][$key]["branch"] = $value->payroll_company_name;

			}
		}

		$time_keeping_approved = Tbl_payroll_time_keeping_approved::where("employee_id",$employee_id)->where("payroll_period_company_id",$period_company_id)->first();


		$_time_breakdown = unserialize($time_keeping_approved->cutoff_breakdown)->_time_breakdown;

		$data["days_worked"]		= isset($_time_breakdown["day_spent"]["time"]) ? $_time_breakdown["day_spent"]["time"] : "no data" ;
		$data["days_absent"]		= $_time_breakdown["absent"]["time"];
		$data['total_late'] 		= $_time_breakdown["late"]["time"];
		$data['total_undertime'] 	= $_time_breakdown["undertime"]["time"];
		$data['total_overtime'] 	= $_time_breakdown["overtime"]["time"];

		//additional by james omosora
		$data['employee_id_pdf'] = $employee_id;
		$data['period_company_id_pdf'] = $period_company_id;
		
		return view("member.payroll2.payroll_process_modal_view_timesheet2", $data);
	}

	public function income_summary_timesheet_v3_view_pdf($period_company_id,$employee_id)
	{
		//arcy
		$data["period_company"] = $period_company = Tbl_payroll_period_company::where("payroll_period_company_id",$period_company_id)->first();

		$data["employee"]   	= Tbl_payroll_employee_basic::where("payroll_employee_id",$employee_id)->first();

		$period 				= Tbl_payroll_period::where('payroll_period_id',$data["period_company"]->payroll_period_id)->first();

		
		$data['_timesheet'] = [];
		$data["days_worked"]	= 0;
		$data["days_absent"]	= 0;
		$data['total_late'] = 0;
		$data['total_undertime'] = 0;
		$data['total_overtime'] = 0;
		$days_worked = 0;
		$days_absent = 0;
		$total_late = 0;
		$total_undertime = 0;
		$total_overtime = 0;
		if($period)
		{
			$date_start = $period->payroll_period_start;
			$date_end = $period->payroll_period_end;

			$get_timesheet = Tbl_payroll_time_sheet::whereBetween('payroll_time_date', array($date_start, $date_end))->where('payroll_employee_id',$employee_id)->groupBy('payroll_time_date')->get();

			foreach ($get_timesheet as $key => $value) 
			{

				$data['_timesheet'][$key] = [];
				$data['_timesheet'][$key]["covered_date"] = date('M d, Y', strtotime($value->payroll_time_date));
				
				$_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $value->payroll_time_sheet_id)->join("tbl_payroll_company", "tbl_payroll_company.payroll_company_id", "=", "tbl_payroll_time_sheet_record.payroll_company_id")->get();
				$_record_approved = Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $value->payroll_time_sheet_id)->get();

				$data['_timesheet'][$key]["actual_time"] = $_record;
				$data['_timesheet'][$key]["approve_time"] = $_record_approved;



				if(count($_record) > 0)
				{
					$days_worked++;
					$data['_timesheet'][$key]["actual_in"] = date('h:i:s A', strtotime($value->actual_in));
					$data['_timesheet'][$key]["actual_out"] = date('h:i:s A', strtotime($value->actual_out));
					$data['_timesheet'][$key]["approved_in"] = date('h:i:s A', strtotime($value->approved_in));
					$data['_timesheet'][$key]["approved_out"] = date('h:i:s A', strtotime($value->approved_out));
				}
				else
				{		
					$days_absent++;
					$data['_timesheet'][$key]["actual_in"] = 'NO TIME';
					$data['_timesheet'][$key]["actual_out"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_in"] = 'NO TIME';
					$data['_timesheet'][$key]["approved_out"] = 'NO TIME';
				}

				$time_serialize = unserialize($value->payroll_time_serialize);

				if($time_serialize)
				{
					$total_late += $time_serialize->late;
					$total_undertime += $time_serialize->undertime;
					$total_overtime += $time_serialize->overtime;
				}
				$data['_timesheet'][$key]["remarks"] = $value->payroll_time_shee_activity;
				$data['_timesheet'][$key]["branch"] = $value->payroll_company_name;

			}
		}

		$time_keeping_approved = Tbl_payroll_time_keeping_approved::where("employee_id",$employee_id)->where("payroll_period_company_id",$period_company_id)->first();


		$_time_breakdown = unserialize($time_keeping_approved->cutoff_breakdown)->_time_breakdown;

		$data["days_worked"]		= isset($_time_breakdown["day_spent"]["time"]) ? $_time_breakdown["day_spent"]["time"] : "no data" ;
		$data["days_absent"]		= $_time_breakdown["absent"]["time"];
		$data['total_late'] 		= $_time_breakdown["late"]["time"];
		$data['total_undertime'] 	= $_time_breakdown["undertime"]["time"];
		$data['total_overtime'] 	= $_time_breakdown["overtime"]["time"];

		$format["title"] = $data['employee']->payroll_employee_first_name." timesheet";
		$format["format"] = "A4";
		$format["default_font"] = "sans-serif";
		$pdf = PDF2::loadView('member.payroll2.payroll_process_modal_view_timesheet2_view_pdf', $data, [], $format);
		return $pdf->stream('document.pdf');
		
		// return view("member.payroll2.payroll_process_modal_view_timesheet2_view_pdf", $data);
	}
}
