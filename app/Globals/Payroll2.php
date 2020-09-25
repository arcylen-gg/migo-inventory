<?php
namespace App\Globals;
use stdClass;
use App\Globals\Payroll;
use App\Globals\PayrollAccounting;
use App\Globals\Utilities;
use DB;
use Carbon\Carbon;

use App\Models\Tbl_payroll_overtime_rate;
use App\Models\Tbl_payroll_group;
use App\Models\Tbl_payroll_adjustment;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_13_month_virtual;
use App\Models\Tbl_payroll_record;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_employee_allowance;
use App\Models\Tbl_payroll_employee_allowance_v2;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_pagibig;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_holiday_employee;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Models\Tbl_payroll_shift_code;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_13th_month_basis;

use App\Globals\PayrollLeave;

use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_journal_tag;


use DateTime;

class Payroll2
{
	public static function get_contribution_information_for_a_month($shop_id, $month, $year)
	{
		$month_number = $month;

		$month = DateTime::createFromFormat('!m', $month)->format('F');
		

		$data["_employee"] = Tbl_payroll_period::getContributions($shop_id, $month, $year)->get();
		$_contribution 					= null;
		$count 							= 0;

        $total_pagibig_er               = 0;
		$total_pagibig_ee               = 0;
		$grand_total_pagibig_ee 		= 0;
		$grand_total_pagibig_er 		= 0;
		$grand_total_pagibig_ee_er 		= 0;

		$grand_total_sss_ee 			= 0;
		$grand_total_sss_er 			= 0;
		$grand_total_sss_ec 			= 0;
		$grand_total_sss_ee_er 			= 0;

		$grand_total_philhealth_ee 		= 0;
		$grand_total_philhealth_er 		= 0;
		$grand_total_philhealth_ee_er 	= 0;
		$checking 						= array();
		$employee_ 						= collect($data["_employee"])->where('employee_id',1072);
		$employeecheck 					= array();

		foreach($data["_employee"] as $key => $employee)
		{
				if(count($employeecheck) == 0)
				{
					$count++;
					$period_count_contribution = 1;

					$total_pagibig_ee = $employee->pagibig_ee;
					$total_pagibig_er = $employee->pagibig_er;

					$total_sss_ee = $employee->sss_ee;
					$total_sss_er = $employee->sss_er;
					$total_sss_ec = $employee->sss_ec;

					$total_philhealth_ee = $employee->philhealth_ee;
					$total_philhealth_er = $employee->philhealth_er;

					$temp['employee_id'] = $employee->employee_id;
					array_push($employeecheck,$temp);
					
				}
				else
				{

					if(in_array($employee->employee_id, $employeecheck))
					{
						$period_count_contribution = $_contribution[$employee->employee_id]->period_count_contribution + 1;
						$total_pagibig_ee += $employee->pagibig_ee;
						$total_pagibig_er += $employee->pagibig_er;

						$total_sss_ee += $employee->sss_ee;
						$total_sss_er += $employee->sss_er;
						$total_sss_ec += $employee->sss_ec;

						$total_philhealth_ee += $employee->philhealth_ee;
						$total_philhealth_er += $employee->philhealth_er;
					}
					else
					{
						$total_pagibig_ee = $employee->pagibig_ee;
						$total_pagibig_er = $employee->pagibig_er;

						$total_sss_ee = $employee->sss_ee;
						$total_sss_er = $employee->sss_er;
						$total_sss_ec = $employee->sss_ec;

						$total_philhealth_ee = $employee->philhealth_ee;
						$total_philhealth_er = $employee->philhealth_er;
					}
				}

				if(count($employeecheck) != 0)
				{
					$total_pagibig_ee_er = $total_pagibig_ee + $total_pagibig_er;
					$total_sss_ee_er = $total_sss_ee + $total_sss_er + $total_sss_ec;
					$total_philhealth_ee_er = $total_philhealth_ee + $total_philhealth_er;

					/* INFORMATION EMPLOYEE CONTRIBUTION */
					$_contribution[$employee->employee_id] = new stdClass();
					$_contribution[$employee->employee_id]->count = $count;
					$_contribution[$employee->employee_id]->period_count_contribution = $period_count_contribution;
					$_contribution[$employee->employee_id]->employee_id = $employee->employee_id;
					$temp['employee_id'] = $employee->employee_id;
					array_push($employeecheck,$temp);
					$_contribution[$employee->employee_id]->payroll_employee_pagibig = ($employee->payroll_employee_pagibig == "" ? "N/A" : $employee->payroll_employee_pagibig);
					$_contribution[$employee->employee_id]->payroll_employee_sss = ($employee->payroll_employee_sss == "" ? "N/A" : $employee->payroll_employee_sss);
					$_contribution[$employee->employee_id]->payroll_employee_philhealth = ($employee->payroll_employee_philhealth == "" ? "N/A" : $employee->payroll_employee_philhealth);


					$_contribution[$employee->employee_id]->account_number = $employee->employee_id;
					$_contribution[$employee->employee_id]->membership_program = $employee->employee_id;
					$_contribution[$employee->employee_id]->payroll_employee_last_name = strtoupper($employee->payroll_employee_last_name);
					$_contribution[$employee->employee_id]->payroll_employee_first_name = strtoupper($employee->payroll_employee_first_name);
					$_contribution[$employee->employee_id]->payroll_employee_suffix_name = $employee->payroll_employee_suffix_name == "" ? "N/A" : strtoupper($employee->payroll_employee_suffix_name);
					$_contribution[$employee->employee_id]->payroll_employee_middle_name = ($employee->payroll_employee_middle_name == "" ? "N/A" : strtoupper($employee->payroll_employee_middle_name));
					$_contribution[$employee->employee_id]->period_covered 	= $month_number . "/" . $year;
					$_contribution[$employee->employee_id]->monthly_compensation = 0;
					
					$_contribution[$employee->employee_id]->total_pagibig_ee = $total_pagibig_ee;
					$_contribution[$employee->employee_id]->total_pagibig_er = $total_pagibig_er;
					$_contribution[$employee->employee_id]->total_pagibig_ee_er = $total_pagibig_ee_er;

					if($total_pagibig_ee == 0)
					{
						$data["pagibig"] = Tbl_payroll_period::GetContributions_employee($shop_id, $month, $year,$employee->employee_id)->get();
						foreach($data["pagibig"] as $key => $pagibig)
						{
							if($pagibig->pagibig_ee != 0)
							{
								$_contribution[$employee->employee_id]->total_pagibig_ee = $pagibig->pagibig_ee;
								$_contribution[$employee->employee_id]->total_pagibig_er = $pagibig->pagibig_er;
								$_contribution[$employee->employee_id]->total_pagibig_ee_er = $pagibig->pagibig_ee + $pagibig->pagibig_er;
							}
							
						}
					}

					$_contribution[$employee->employee_id]->total_sss_ee = $total_sss_ee;
					$_contribution[$employee->employee_id]->total_sss_er = $total_sss_er;
					$_contribution[$employee->employee_id]->total_sss_ec = $total_sss_ec;
					$_contribution[$employee->employee_id]->total_sss_ee_er = $total_sss_ee_er;

					$_contribution[$employee->employee_id]->total_philhealth_ee = $total_philhealth_ee;
					$_contribution[$employee->employee_id]->total_philhealth_er = $total_philhealth_er;
					$_contribution[$employee->employee_id]->total_philhealth_ee_er = $total_philhealth_ee_er;
				
					$grand_total_pagibig_ee 	+= $employee->pagibig_ee;
					$grand_total_pagibig_er 	+= $employee->pagibig_er;
					$grand_total_pagibig_ee_er 	+= $employee->pagibig_ee + $employee->pagibig_er;

					$grand_total_sss_ee 	+= $employee->sss_ee;
					$grand_total_sss_er 	+= $employee->sss_er;
					$grand_total_sss_ec 	+= $employee->sss_ec;
					$grand_total_sss_ee_er 	+= $employee->sss_ee + $employee->sss_er + $employee->sss_ec;

					$grand_total_philhealth_ee 		+= $employee->philhealth_ee;
					$grand_total_philhealth_er 		+= $employee->philhealth_er;
					$grand_total_philhealth_ee_er 	+= $employee->philhealth_ee + $employee->philhealth_er;
				}
			
		}

		$return["_employee_contribution"] 	 = $_contribution;
		$return["grand_total_pagibig_ee"] 	 = $grand_total_pagibig_ee;
		$return["grand_total_pagibig_er"] 	 = $grand_total_pagibig_er;
		$return["grand_total_pagibig_ee_er"] = $grand_total_pagibig_ee_er;

		$return["grand_total_sss_ee"]		= $grand_total_sss_ee;
		$return["grand_total_sss_er"] 		= $grand_total_sss_er;
		$return["grand_total_sss_ec"] 		= $grand_total_sss_ec;
		$return["grand_total_sss_ee_er"] 	= $grand_total_sss_ee_er;

		$return["grand_total_philhealth_ee"] 	= $grand_total_philhealth_ee;
		$return["grand_total_philhealth_er"] 	= $grand_total_philhealth_er;
		$return["grand_total_philhealth_ee_er"] = $grand_total_philhealth_ee_er;

		return $return;
	}
	public static function get_contribution_information_for_a_month_filter($shop_id, $month, $year,$company_id)
	{
		$month_number = $month;
		$month = DateTime::createFromFormat('!m', $month)->format('F');

		$checkcompany = Tbl_payroll_company::where('payroll_company_id',$company_id)->first();

		$branchcompany = array();
		if($checkcompany['payroll_parent_company_id'] == 0)
		{
			$tempbranchcompany = Tbl_payroll_company::where('payroll_parent_company_id',$checkcompany['payroll_company_id'])->get();

			foreach($tempbranchcompany as $branch)
			{
				$temp['payroll_company_id'] = $branch['payroll_company_id'];
				array_push($branchcompany,$temp);
			}
		}

		$data["_employee"] = Tbl_payroll_period::getContributions_filter($shop_id, $month, $year,$company_id,$branchcompany)->get();


		$_contribution = null;
		$count = 1;

		$grand_total_pagibig_ee 	= 0; 
		$grand_total_pagibig_er 	= 0;
		$grand_total_pagibig_ee_er 	= 0;

		$grand_total_sss_ee 	= 0;
		$grand_total_sss_er 	= 0;
		$grand_total_sss_ec 	= 0;
		$grand_total_sss_ee_er 	= 0;

		$grand_total_philhealth_ee 	  	= 0;
		$grand_total_philhealth_er 		= 0;
		$grand_total_philhealth_ee_er 	= 0;
		$employeecheck 					= array();

		foreach($data["_employee"] as $key => $employee)
		{
				if(count($employeecheck) == 0)
				{
					$period_count_contribution = 1;

					$total_pagibig_ee = $employee->pagibig_ee;
					$total_pagibig_er = $employee->pagibig_er;

					$total_sss_ee = $employee->sss_ee;
					$total_sss_er = $employee->sss_er;
					$total_sss_ec = $employee->sss_ec;

					$total_philhealth_ee = $employee->philhealth_ee;
					$total_philhealth_er = $employee->philhealth_er;

					$temp['employee_id'] = $employee->employee_id;
					array_push($employeecheck,$temp);
					
				}
				else
				{

					if(in_array($employee->employee_id, $employeecheck))
					{
						$period_count_contribution = $_contribution[$employee->employee_id]->period_count_contribution + 1;
						$total_pagibig_ee += $employee->pagibig_ee;
						$total_pagibig_er += $employee->pagibig_er;

						$total_sss_ee += $employee->sss_ee;
						$total_sss_er += $employee->sss_er;
						$total_sss_ec += $employee->sss_ec;

						$total_philhealth_ee += $employee->philhealth_ee;
						$total_philhealth_er += $employee->philhealth_er;
					}
					else
					{
						$total_pagibig_ee = $employee->pagibig_ee;
						$total_pagibig_er = $employee->pagibig_er;

						$total_sss_ee = $employee->sss_ee;
						$total_sss_er = $employee->sss_er;
						$total_sss_ec = $employee->sss_ec;

						$total_philhealth_ee = $employee->philhealth_ee;
						$total_philhealth_er = $employee->philhealth_er;
					}
				}

				if(count($employeecheck) != 0)
				{
					$total_pagibig_ee_er = $total_pagibig_ee + $total_pagibig_er;
					$total_sss_ee_er = $total_sss_ee + $total_sss_er + $total_sss_ec;
					$total_philhealth_ee_er = $total_philhealth_ee + $total_philhealth_er;

					/* INFORMATION EMPLOYEE CONTRIBUTION */
					$_contribution[$employee->employee_id] = new stdClass();
					$_contribution[$employee->employee_id]->count = $count;
					$_contribution[$employee->employee_id]->period_count_contribution = $period_count_contribution;
					$_contribution[$employee->employee_id]->employee_id = $employee->employee_id;
					$temp['employee_id'] = $employee->employee_id;
					array_push($employeecheck,$temp);
					$_contribution[$employee->employee_id]->payroll_employee_pagibig = ($employee->payroll_employee_pagibig == "" ? "N/A" : $employee->payroll_employee_pagibig);
					$_contribution[$employee->employee_id]->payroll_employee_sss = ($employee->payroll_employee_sss == "" ? "N/A" : $employee->payroll_employee_sss);
					$_contribution[$employee->employee_id]->payroll_employee_philhealth = ($employee->payroll_employee_philhealth == "" ? "N/A" : $employee->payroll_employee_philhealth);


					$_contribution[$employee->employee_id]->account_number = $employee->employee_id;
					$_contribution[$employee->employee_id]->membership_program = $employee->employee_id;
					$_contribution[$employee->employee_id]->payroll_employee_last_name = strtoupper($employee->payroll_employee_last_name);
					$_contribution[$employee->employee_id]->payroll_employee_first_name = strtoupper($employee->payroll_employee_first_name);
					$_contribution[$employee->employee_id]->payroll_employee_suffix_name = $employee->payroll_employee_suffix_name == "" ? "N/A" : strtoupper($employee->payroll_employee_suffix_name);
					$_contribution[$employee->employee_id]->payroll_employee_middle_name = ($employee->payroll_employee_middle_name == "" ? "N/A" : strtoupper($employee->payroll_employee_middle_name));
					$_contribution[$employee->employee_id]->period_covered 	= $month_number . "/" . $year;
					$_contribution[$employee->employee_id]->monthly_compensation = 0;
					
					$_contribution[$employee->employee_id]->total_pagibig_ee = $total_pagibig_ee;
					$_contribution[$employee->employee_id]->total_pagibig_er = $total_pagibig_er;
					$_contribution[$employee->employee_id]->total_pagibig_ee_er = $total_pagibig_ee_er;

					if($total_pagibig_ee == 0)
					{
						$data["pagibig"] = Tbl_payroll_period::GetContributions_employee($shop_id, $month, $year,$employee->employee_id)->get();
						foreach($data["pagibig"] as $key => $pagibig)
						{
							if($pagibig->pagibig_ee != 0)
							{
								$_contribution[$employee->employee_id]->total_pagibig_ee = $pagibig->pagibig_ee;
								$_contribution[$employee->employee_id]->total_pagibig_er = $pagibig->pagibig_er;
								$_contribution[$employee->employee_id]->total_pagibig_ee_er = $pagibig->pagibig_ee + $pagibig->pagibig_er;
							}
							
						}
					}

					$_contribution[$employee->employee_id]->total_sss_ee = $total_sss_ee;
					$_contribution[$employee->employee_id]->total_sss_er = $total_sss_er;
					$_contribution[$employee->employee_id]->total_sss_ec = $total_sss_ec;
					$_contribution[$employee->employee_id]->total_sss_ee_er = $total_sss_ee_er;

					$_contribution[$employee->employee_id]->total_philhealth_ee = $total_philhealth_ee;
					$_contribution[$employee->employee_id]->total_philhealth_er = $total_philhealth_er;
					$_contribution[$employee->employee_id]->total_philhealth_ee_er = $total_philhealth_ee_er;
				
					$grand_total_pagibig_ee 	+= $employee->pagibig_ee;
					$grand_total_pagibig_er 	+= $employee->pagibig_er;
					$grand_total_pagibig_ee_er 	+= $employee->pagibig_ee + $employee->pagibig_er;

					$grand_total_sss_ee 	+= $employee->sss_ee;
					$grand_total_sss_er 	+= $employee->sss_er;
					$grand_total_sss_ec 	+= $employee->sss_ec;
					$grand_total_sss_ee_er 	+= $employee->sss_ee + $employee->sss_er + $employee->sss_ec;

					$grand_total_philhealth_ee 		+= $employee->philhealth_ee;
					$grand_total_philhealth_er 		+= $employee->philhealth_er;
					$grand_total_philhealth_ee_er 	+= $employee->philhealth_ee + $employee->philhealth_er;
				}
			
		}
		
		$return["_employee_contribution"] 		= $_contribution;
		$return["grand_total_pagibig_ee"] 		= $grand_total_pagibig_ee;
		$return["grand_total_pagibig_er"] 		= $grand_total_pagibig_er;
		$return["grand_total_pagibig_ee_er"] 	= $grand_total_pagibig_ee_er;

		$return["grand_total_sss_ee"] 			= $grand_total_sss_ee;
		$return["grand_total_sss_er"] 			= $grand_total_sss_er;
		$return["grand_total_sss_ec"] 			= $grand_total_sss_ec;
		$return["grand_total_sss_ee_er"] 		= $grand_total_sss_ee_er;

		$return["grand_total_philhealth_ee"] 	= $grand_total_philhealth_ee;
		$return["grand_total_philhealth_er"] 	= $grand_total_philhealth_er;
		$return["grand_total_philhealth_ee_er"] = $grand_total_philhealth_ee_er;

		return $return;
	}

	public static function get_number_of_period_per_month($shop_id, $year, $company = 0)
	{
		$_month = array();

		for($ctr = 1; $ctr <= 12; $ctr++)
		{
			$_month[$ctr]["month_name"] = DateTime::createFromFormat('!m', $ctr)->format('F');

			$payroll_period = Tbl_payroll_period::where("shop_id", $shop_id);
			$payroll_period->joinCompany();
			$payroll_period->where("month_contribution", $_month[$ctr]["month_name"]);
			$payroll_period->where("tbl_payroll_period_company.payroll_period_status", "!=", "generated");
			$payroll_period->where("year_contribution", $year);

			$_month[$ctr]["period_count"] = $payroll_period->count();
		}

		return $_month;
	}

	public static function timesheet_info_db($employee_id, $date)
	{
		return Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($date)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->first();
	}

	public static function timesheet_info($company_period, $employee_id) 
	{
		$_timesheet = null;
		$from 		= $data["start_date"] 	= $company_period->payroll_period_start;
		$to 		= $data["end_date"] 	= $company_period->payroll_period_end;
		$payroll_period_company_id = $company_period->payroll_period_company_id;
		$shift_code_id = Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->value("shift_code_id");
	
		while($from <= $to)
		{
			$timesheet_db = Payroll2::timesheet_info_db($employee_id, $from);
			
			/* CREATE TIMESHEET DB IF EMPTY */
			if(!$timesheet_db)
			{
				$_shift_real 	=  Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $from);
				$_shift 		=  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $from));
				
				$insert = null;
				$insert["payroll_employee_id"] 		= $employee_id;
				$insert["payroll_time_date"] 		= $from;
				$insert["payroll_time_shift_raw"] 	= serialize($_shift);
				Tbl_payroll_time_sheet::insert($insert);
				$timesheet_db =Payroll2::timesheet_info_db($employee_id, $from);
				$insert = null;
			}

			if($timesheet_db->custom_shift == 1)
			{
				$_shift =  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from));
			}
			else
			{
				$_shift =  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $from));
			}

			$timesheet_db = Payroll2::timesheet_info_db($employee_id, $from);

			if($timesheet_db->custom_shift == 1)
			{
				$_shift_real =  Payroll2::db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from);
				$_shift 	 =  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from));
			}
			else
			{
				$_shift_real =  Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $from);
				$_shift 	 =  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $from));
			}

			/* CLEAR APPROVED RECORD IF SHIFT CHANGED */

			if($timesheet_db->payroll_time_shift_raw != serialize($_shift))
			{
				$update = null;
				$update["payroll_time_shift_raw"] = serialize($_shift);
				Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->update($update);
				Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->delete();
				$update = null;
			}

			// $holiday = Payroll2::timesheet_get_is_holiday($employee_id, $from);
			
			$_timesheet[$from] = new stdClass();
			$_timesheet[$from]->payroll_time_sheet_id 		= $timesheet_db->payroll_time_sheet_id;
			$_timesheet[$from]->custom_shift 				= $timesheet_db->custom_shift;
			$_timesheet[$from]->custom_shift_id 			= $timesheet_db->custom_shift_id;
			$_timesheet[$from]->date 						= Carbon::parse($from)->format("Y-m-d");
			$_timesheet[$from]->day_number 					= Carbon::parse($from)->format("d");
			$_timesheet[$from]->day_word 					= Carbon::parse($from)->format("D");
			$_timesheet[$from]->record 						= Payroll2::timesheet_process_in_out($timesheet_db);
			$_timesheet[$from]->is_holiday 					= Payroll2::timesheet_get_is_holiday($employee_id, $from); //$holiday["holiday_day_type"];
			$_timesheet[$from]->is_leave					= Payroll2::timesheet_get_is_leave($employee_id, $from);
			$_timesheet[$from]->branch_source_company_id 	= 0;

			/*Start Get Timesheet Company Source of the day*/
			if ($_timesheet[$from]->record != null) 
			{
				foreach ($_timesheet[$from]->record as $key => $rec) 
				{
					$temp_time_float = 0;

					if (Payroll::time_float(Payroll::time_diff($rec->time_sheet_in,$rec->time_sheet_out)) >= $temp_time_float) 
					{
						$temp_time_float = Payroll::time_float(Payroll::time_diff($rec->time_sheet_in,$rec->time_sheet_out));
						
						$_timesheet[$from]->branch_source_company_id = $rec->branch_id;
					}
				}
			}
			/*End*/

			// $_timesheet[$from]->holiday_name = $holiday["holiday_name"];
			if(isset($_shift_real[0]))
			{
				$_timesheet[$from]->day_type = $day_type = Payroll2::timesheet_get_day_type($_shift_real[0]->shift_rest_day, $_shift_real[0]->shift_extra_day);
			}
			else
			{
				$_timesheet[$from]->day_type = "regular";
			}
			
			$_timesheet[$from]->default_remarks = Payroll2::timesheet_default_remarks($_timesheet[$from]);
			$_timesheet[$from]->daily_info 		= Payroll2::timesheet_process_daily_info($employee_id, $from, $timesheet_db, $payroll_period_company_id);
			$from = Carbon::parse($from)->addDay()->format("Y-m-d");

		}

		return $_timesheet;

	}

	public static function timesheet_process_daily_info($employee_id, $date, $timesheet_db, $payroll_period_company_id)
	{
		$return = new stdClass();

		if($timesheet_db)
		{
			$approved_record = Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->get();
			$approved = false;
			
			if(count($approved_record) > 0)
			{
				$_record = $approved_record;
				$approved = true;
			}
			else
			{
				$_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->get();
			}

			$return = Payroll2::timesheet_process_daily_info_record($employee_id, $date, $approved, $_record, $timesheet_db->payroll_time_sheet_id, $payroll_period_company_id, $timesheet_db->time_keeping_approved,  $timesheet_db->custom_shift,  $timesheet_db->custom_shift_id);
			
			$return->source = "";
			$return->branch = "";
			$return->payroll_time_sheet_id = 0;

		}
		else
		{
			$return->for_approval = 0;
			$return->daily_salary = 0;
		}

		return $return;
	}
	public static function save_clean_shift_to_approved_table($time_sheet_id, $_clean_shift, $_shift_raw)
	{
		if($_clean_shift)
		{
			foreach($_clean_shift as $key => $clean_shift)
			{

				$insert[$key]["payroll_time_sheet_id"] 				= $time_sheet_id;
				$insert[$key]["payroll_time_sheet_in"] 				= $clean_shift->time_in;
				$insert[$key]["payroll_time_sheet_out"]	 			= $clean_shift->time_out;
				$insert[$key]["payroll_time_shee_activity"] 		= "";
				$insert[$key]["payroll_time_sheet_origin"] 			= "";
				$insert[$key]["payroll_time_sheet_auto_approved"] 	= $clean_shift->auto_approved;
				$insert[$key]["payroll_time_serialize"] 			= serialize($clean_shift);
			}
			Tbl_payroll_time_sheet_record_approved::insert($insert);
		}
	}
	public static function timesheet_process_daily_info_record($employee_id, $date, $approved, $_time, $payroll_time_sheet_id, $payroll_period_company_id, $time_keeping_approved, $custom_shift, $custom_shift_id)
	{
		$return 					= new stdClass();
		$return->for_approval		= ($approved == true ? 0 : 1);
		$return->daily_salary		= 0;
		$employee_contract			= Tbl_payroll_employee_contract::selemployee($employee_id, $date)->group()->first();
		$shift_code_id 				= Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->value("shift_code_id");
		
		if($custom_shift == 1) //CUSTOM SHIFT
		{
			$_shift 				= Payroll2::db_get_shift_of_employee_by_code($custom_shift_id, $date);
			
		}
		else //DEFAULT SHIFT
		{
			$_shift 				= Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $date);
		}

		$_shift_raw 				= Payroll2::shift_raw($_shift);
		$_time_raw					= Payroll2::time_raw($_time);
		
		$mode						= "daily";

		$return->_time				= $_time_raw;
		$return->_shift 			= $_shift_raw;
		$return->time_compute_mode	= "regular";

		if(count($_shift) > 0)
		{
			if($_shift[0]->shift_flexi_time == 1)
			{
				$return->time_compute_mode = "flexi";
			}
		}
		
		if(count($_shift) > 0)
		{
			$return->shift_target_hours = $_shift[0]->shift_target_hours;
		}
		else
		{
			$return->shift_target_hours = 0;
		}

		if(isset($_shift_raw))
		{
			if ($_shift[0]->shift_break_hours != null) 
			{
				$return->shift_break_hours = $_shift[0]->shift_break_hours;
			}
			else
			{
				$return->shift_break_hours = "0.00";
			}
		}
		
		$return->time_keeping_approved = $time_keeping_approved;

		if($return->for_approval == 1) //PENDING
		{
			$return->status = "PENDING";
			if($return->time_compute_mode == "flexi")
			{
				$return->clean_shift = Payroll2::clean_shift_flexi($_time_raw, $return->shift_break_hours,	$return->shift_target_hours);
			}
			else
			{
				$return->clean_shift = Payroll2::clean_shift($_time_raw, $_shift_raw);
			}
		
			Payroll2::save_clean_shift_to_approved_table($payroll_time_sheet_id, $return->clean_shift, $_shift_raw);
			$return->compute_shift = $return->clean_shift;
		}
		else //APPROVED
		{
			$return->status = "APPROVED";
			//$return->clean_shift	= $this->convert_to_serialize_row_from_approved_clean_shift($_time);
			//dd($_time_raw);

			if($return->time_compute_mode == "flexi")
			{
				$return->clean_shift = Payroll2::clean_shift_flexi($_time_raw, $return->shift_break_hours, $return->shift_target_hours);
			}
			else
			{
				$return->clean_shift = Payroll2::clean_shift($_time_raw, $_shift_raw);
			}
			
			$return->shift_approved = true;
			$return->compute_shift = $return->clean_shift;
		}

		$return->shift_approved = Payroll2::check_if_shift_approved($return->clean_shift);
		$return->compute_shift = Payroll2::remove_not_auto_approve($return->clean_shift);

		if(!$employee_contract)
		{
			dd("This employee doesn't have contract at this point of time.");
		}


		$rate = Payroll2::getdaily_rate($employee_id, $date, $employee_contract->payroll_group_working_day_month);
		
		$daily_rate  = $rate['daily'];
		$cola		 = $rate['cola'];
		$hourly_rate = $rate['hourly'];
		
		/* CHECK IF LATE GRACT TIME WORKING */

		$return->late_grace_time = $late_grace_time = $employee_contract->late_grace_time;
		$return->grace_time_rule_late = $grace_time_rule_late = $employee_contract->grace_time_rule_late;
		$return->overtime_grace_time = $overtime_grace_time = $employee_contract->overtime_grace_time;
		$return->grace_time_rule_overtime = $grace_time_rule_overtime = $employee_contract->grace_time_rule_overtime;
		

		if(isset($_shift[0]))
		{
			$return->day_type = $day_type = Payroll2::timesheet_get_day_type($_shift[0]->shift_rest_day, $_shift[0]->shift_extra_day);
		}
		else
		{
			$return->day_type = $day_type = "regular";
		}

		$return->is_holiday = $is_holiday = Payroll2::timesheet_get_is_holiday($employee_id, $date);
		//$return->leave = $leave = $this->timesheet_get_leave_hours($employee_id, $date, $_shift_raw);
		
		/*START leave function*/
		// $leave_data_all = PayrollLeave::employee_leave_data($employee_id);
  		// $leave_cap_data = PayrollLeave::employee_leave_capacity($employee_id);
        $leave_info_v1 =  Tbl_payroll_leave_schedule::getemployeeleavedatedata($employee_id, $date)->first();
        $use_leave = false;
        
        $leave = "00:00:00";
        $data_this = PayrollLeave::employee_leave_capacity_consume_remainingv2($employee_id)->get();
        $leavepay = 0;
        // dd($leave_info_v1);
        if ($leave_info_v1) 
        {
        	$use_leave 	= true;
        	$leave 		= $leave_info_v1["leave_hours"];
        	$leavepay 	= 1;
        }
        else
        {
        	$leave_date_data = PayrollLeave::employee_leave_date_datav2($employee_id,$date);
        	
        	if (count($leave_date_data) > 0) 
        	{
        		// $used_leave_data = PayrollLeave::employee_leave_consumed($leave_date_data["payroll_leave_employee_id"]);
        		// $remaining_leave_data = PayrollLeave::employee_leave_remaining($employee_id, $leave_data_all["payroll_leave_employee_id"]);

        		$use_leave = true;
        		$leave=$leave_date_data["leave_hours"];
        		$leavepay=$leave_date_data["payroll_leave_temp_with_pay"];
        	}
        }
        

    	$return->use_leave = $use_leave;             
		$return->leave = $leave;
		$return->leavepay = $leavepay;
		$return->leave_fill_date = $leave_fill_late = 1;
		$return->leave_fill_undertime = $leave_fill_undertime = 1;
		$return->default_remarks = Payroll2::timesheet_default_remarks($return);

		/*End leave function*/
		
		$compute_type = Payroll2::convert_period_cat($employee_contract->payroll_group_salary_computation);

		if($return->time_compute_mode == "flexi")
		{
			$return->time_output =  Payroll2::compute_time_mode_flexi($return->compute_shift, $return->shift_target_hours, $return->shift_break_hours, $overtime_grace_time, $grace_time_rule_overtime, $day_type, $is_holiday, $leave, $leave_fill_undertime, $use_leave, $compute_type, $leavepay, $testing = false);
		}
		else
		{
			$return->time_output = Payroll2::compute_time_mode_regular($return->compute_shift, $_shift_raw, $late_grace_time, $grace_time_rule_late, $overtime_grace_time, $grace_time_rule_overtime, $day_type, $is_holiday , $leave, $leave_fill_late, $leave_fill_undertime, $return->shift_target_hours, $use_leave, $compute_type, $leavepay, false);

		}
		
		$return->compute_type = $compute_type = Payroll2::convert_period_cat($employee_contract->payroll_group_salary_computation);

		$return->compute = Payroll2::compute_income_day_pay($return->time_output, $daily_rate, $hourly_rate, $employee_contract->payroll_group_id, $cola, $compute_type, $return->time_compute_mode);

		if($payroll_period_company_id != 0)
		{
			$access = Utilities::checkAccess('payroll-timekeeping','salary_rates');
			
			//DISPLAY TOTAL AMOUNT IN TIMESHEET 
			try 
			{
				if($access == 1) 
				{
					$return->value_html = Payroll2::timesheet_daily_income_to_string($return->compute_type, $payroll_time_sheet_id, $return->compute, $return->shift_approved, $payroll_period_company_id, $time_keeping_approved);
				}
				else
				{
					$return->value_html = Payroll2::timesheet_daily_target_hours_to_string($return->compute_type, $payroll_time_sheet_id, $return->compute, $return->time_output, $return->shift_approved, $payroll_period_company_id, $time_keeping_approved);
				}
			} 
			catch (\Exception $e) 
			{
				$return->value_html = Payroll2::timesheet_daily_target_hours_to_string($return->compute_type, $payroll_time_sheet_id, $return->compute, $return->time_output, $return->shift_approved, $payroll_period_company_id, $time_keeping_approved);
			}
		}

		return $return;

	}
	public static function timesheet_daily_income_to_string($compute_type, $timesheet_id, $compute, $approved, $period_company_id, $time_keeping_approved = 0)
	{
		if($compute_type == "daily")
		{
			$income = $compute->total_day_income;
		}
		elseif($compute_type == "hourly")
		{
			$income = $compute->total_day_income;
		}
		else
		{
			$income = $compute->total_day_income - $compute->daily_rate;
		}
		
		if($time_keeping_approved == 1)
		{
			$string = '<a style="color: green;" onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '">PHP ' . number_format($income, 2) . '</a>';	
		}
		elseif($approved == true)
		{
			$string = '<a onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '">PHP ' . number_format($income, 2) . '</a>';	
		}
		else
		{
			$string = '<a style="color: red;" onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '">PHP ' . number_format($income, 2) . '</a>';
		}
		
		return $string;
	}


	public static function timesheet_daily_target_hours_to_string($compute_type, $timesheet_id, $compute, $time_output, $approved, $period_company_id, $time_keeping_approved = 0)
	{

		if($compute_type == "daily")
		{
			$income = $compute->total_day_income;
		}
		elseif($compute_type == "hourly")
		{
			$income = $compute->total_day_income;
		}
		else
		{
			$income = $compute->total_day_income - $compute->daily_rate;
		}
		
		$target_hours = $time_output['time_spent'];
		
		
		if($time_keeping_approved == 1)
		{
			$string = '<a style="color: green;" onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '"> ' . $target_hours . '</a>';	
		}
		elseif($approved == true)
		{
			$string = '<a onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '"> ' . $target_hours . '</a>';	
		}
		else
		{
			$string = '<a style="color: red;" onclick="action_load_link_to_modal(\'/member/payroll/company_timesheet_day_summary/' . $timesheet_id . '?period_company_id=' . $period_company_id . '\', \'lg\')" href="javascript:" class="daily-salary" amount="' . $income . '"> ' . $target_hours . '</a>';
		}
		
		return $string;
	}
	public static function shift_raw($_shift)
	{
		$_shift_raw = null;

		foreach($_shift as $key => $shift)
		{
			if($shift->shift_work_start != null && $shift->shift_work_end != null)
			{
				$_shift_raw[$key] = new stdClass();
				$_shift_raw[$key]->shift_in = $shift->shift_work_start;
				$_shift_raw[$key]->shift_out = $shift->shift_work_end;
			}
		}

		return $_shift_raw;
	}
	
	/* GLOBAL FUNCTION FOR THIS CONTROLLER */
	public static function remove_not_auto_approve($_time)
	{
		if($_time)
		{
			$return = null;
			
			foreach($_time as $key => $time)
			{
				if($time->auto_approved != 0)
				{
					$return[$key] = $time;
				}
			}
			
			return $return;
		}
		else
		{
			$return = null;
		}
		
		return $return;

	}
	public static function time_raw($_time)
	{
		$_time_raw = null;

		foreach($_time as $key => $time)
		{
			if($time->payroll_time_sheet_in)
			{
				if($time->payroll_time_sheet_in != $time->payroll_time_sheet_out)
				{
					$ignore = false;

					if(strtotime($time->payroll_time_sheet_in) < strtotime($time->payroll_time_sheet_out))
					{
						if(isset($time->payroll_time_sheet_record_id))
						{
							if($time->payroll_time_sheet_auto_approved == 2)
							{
								$ignore = true;
							}
						}

						if($ignore == false)
						{
							$_time_raw[$key] = new stdClass();
							$_time_raw[$key]->time_in = $time->payroll_time_sheet_in;
							$_time_raw[$key]->time_out = $time->payroll_time_sheet_out;

							if(isset($time->payroll_time_sheet_record_id))
							{
								$_time_raw[$key]->payroll_time_sheet_record_id = $time->payroll_time_sheet_record_id;
								$_time_raw[$key]->payroll_time_sheet_auto_approved = $time->payroll_time_sheet_auto_approved;
							}
						}
					}

				}
			}
		}

		return $_time_raw;
	}
	
	public static function db_get_shift_of_employee_by_code($shift_code_id, $date)
	{
		return Tbl_payroll_shift_code::where('tbl_payroll_shift_code.shift_code_id', $shift_code_id)->day()->time()->where("shift_day", date("D", strtotime($date)))->get();
	}
	/* GLOBAL FUNCTION FOR THIS CONTROLLER */
	public static function c_24_hour_format($time)
	{
	    return date("H:i:s", strtotime($time));
	}
	public static function convert_to_serialize_row_from_approved_clean_shift($_time)
	{
		$return = null;
		
		if($_time)
		{
			foreach($_time as $key => $time)
			{
				$return[$key] = unserialize($time->payroll_time_serialize);
				$return[$key]->payroll_time_sheet_record_id = $time->payroll_time_sheet_record_id;
			}
		}
		
		return $return;
	}

	public static function check_if_shift_approved($_time)
	{
		$auto_approve = true;

		if($_time == null)
		{
			$auto_approve = true;
		}
		else
		{
			foreach($_time as $time)
			{
				if($time->auto_approved == 0)
				{
					$auto_approve = false;
				}
			}
		}

		return $auto_approve;
	}

	public static function timesheet_get_day_type($shift_rest_day, $shift_extra_day)
	{
		$day_type = "regular";

		if($shift_rest_day == 1)
		{
			$day_type	= 'rest_day';
		}
		
		if($shift_extra_day == 1)
		{
			$day_type	= 'extra_day';
		}
		return $day_type;
	}
	public static function timesheet_get_is_holiday($employee_id, $date)
	{
		$day_type	= 'not_holiday';
		// $day_type["holiday_name"]	= '';
		$company_id	= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->value('payroll_employee_company_id');

		// $holiday	= Tbl_payroll_holiday_company::getholiday($company_id, $date)->first();
		$holiday	= Tbl_payroll_holiday_employee::getholidayv2($employee_id, $date)->first();
		
		if($holiday != null)
		{
			$day_type = strtolower($holiday->payroll_holiday_category);
			// $day_type["holiday_name"] = strtolower($holiday->payroll_holiday_name);
		}
		return $day_type;
	}

	public static function timesheet_get_is_leave($employee_id, $date)
	{
		$leave_schedule = Tbl_payroll_leave_schedule::checkemployee($employee_id, $date)->get();

		if (count($leave_schedule) > 0) 
		{
			return true;
		}

		return false;
	}
	public static function timesheet_default_remarks($data)
	{
		$remarks = null;

		if($data->day_type == "rest_day")
		{
			$remarks[] = "REST DAY";
		}
		if($data->day_type == "extra_day")
		{
			$remarks[] = "EXTRA DAY";
		}
		if($data->is_holiday != "not_holiday")
		{
			$remarks[] = "HOLIDAY";
		}
		if (isset($data->is_leave)) 
		{
			if($data->is_leave)
			{
				$remarks[] = "LEAVE";
			}
		}

		// if (isset($data->holiday_name)) 
		// {
		// 	$remarks[] = $data->holiday_name;
		// }
		if($remarks)
		{
			return implode(",", $remarks);
		}
		else
		{
			return "";
		}
	}
	public static function timesheet_process_in_out($timesheet_db)
	{
		$_timesheet_record = null;

		if($timesheet_db)
		{
			$_timesheet_record_db = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->get();
			$_timesheet_record = Payroll2::timesheet_process_in_out_record($_timesheet_record_db);
			
		}
		else
		{
			$_timesheet_record = Payroll2::timesheet_process_in_out_default();

		}

		return $_timesheet_record;
	}
	
	public static function timesheet_process_in_out_default()
	{
		$_timesheet_record[0] = new stdClass();
		$_timesheet_record[0]->time_sheet_in = "";
		$_timesheet_record[0]->time_sheet_out = "";
		$_timesheet_record[0]->time_sheet_activity = "";
		return $_timesheet_record;
	}
	public static function timesheet_get_leave_hours($employee_id, $date, $shift)
	{
		$target = Payroll2::target_hours($shift);
		$schedule = Tbl_payroll_leave_schedule::where('payroll_leave_employee_id', $employee_id)->where('payroll_schedule_leave', $date)->first();
		
		if($schedule != null)
		{
			if($schedule->leave_whole_day == 0)
			{
				$target = $schedule->leave_hours;
			}
		}
		
		
		return $target;
	}
	public static function getdaily_rate($employee_id, $date, $target_days)
	{
		$salary = Tbl_payroll_employee_salary::selemployee($employee_id, $date)->first();
	
		if(!$salary)
		{
			dd("This employee doesn't have salary as of this date (" .  payroll_date_format($date) . "). Please check effectivity date of salary.");
		}
	
		$return['daily']	=  $salary->payroll_employee_salary_daily;
		$return['cola'] 	=  $salary->payroll_employee_salary_cola;
		$return['hourly']	=  $salary->payroll_employee_salary_hourly;
		
		return $return;
	}	
	public static function timesheet_process_in_out_record($_timesheet_record_db)
	{
		$_timesheet_record = null;

		foreach($_timesheet_record_db as $key => $record)
		{
			$_timesheet_record[$key] = new stdClass();
			$_timesheet_record[$key]->time_sheet_in = gb_convert_time_from_db_to_timesheet($record->payroll_time_sheet_in);
			$_timesheet_record[$key]->time_sheet_out = gb_convert_time_from_db_to_timesheet($record->payroll_time_sheet_out);
			$_timesheet_record[$key]->time_sheet_activity = $record->payroll_time_shee_activity;
			$_timesheet_record[$key]->branch 	= Payroll2::timesheet_get_branch($record->payroll_company_id)->name;
			$_timesheet_record[$key]->branch_id = $record->payroll_company_id;
			$_timesheet_record[$key]->source = $record->payroll_time_sheet_origin;
			$_timesheet_record[$key]->payroll_time_sheet_id = $record->payroll_time_sheet_id;
			$_timesheet_record[$key]->payroll_time_sheet_record_id = $record->payroll_time_sheet_record_id;
		}

		return $_timesheet_record;
	}
	public static function timesheet_get_branch($payroll_company_id)
	{
		$return = new stdClass();

		$company_information = Tbl_payroll_company::where("payroll_company_id", $payroll_company_id)->first();

		if($company_information)
		{
			$return->name = $company_information->payroll_company_name;
			$return->id = $company_information->pyaroll_company_id;
		}
		else
		{
			$return->name = "No Branch";
			$return->id = 0;
		}

		return $return;
	}

	public static function sort_by_time($_time)
	{
		//sir kim code problem causing undefined offset when 1 time in is null 

		// $count = 0;


		// $n = count($_time);
  //       for ($i = 0; $i < $n-1; $i++)
  //       {
  //           for ($j = 0; $j < $n-$i-1; $j++)
  //           {
  //               if (Payroll2::convert_time_in_minutes($_time[$j]->time_in) > Payroll2::convert_time_in_minutes($_time[$j+1]->time_in))
  //               {

  //                   // swap temp and arr[i]
  //                   $temp = $_time[$j];
  //                   $_time[$j] = $_time[$j+1];
  //                   $_time[$j+1] = $temp;
                   
  //               }
  //           }
  //       }
		
		// return $_time;

		//kenneth
		 usort($_time,function($firsttime,$secondtime)
		 {
		        $firsttime = Payroll2::convert_time_in_minutes($firsttime->time_in);
		        $secondtime = Payroll2::convert_time_in_minutes($secondtime->time_in);

		        if ($firsttime == $secondtime) {
		           return 0;
		        }

		        return $firsttime < $secondtime ? -1 : 1;
		 });

   		 return $_time;

	}
	



    /*
     * TITLE: CLEAN SHIFT
     * 
     * Returns a clean TIME IN and TIME OUT when cross reference with SHIFTING SCHEDULES.
     *
     * @param
     *    $_time (array)
     *		- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *
     *	  $_shift (array)
     *		- shift_in (time 00:00:00)
     *		- shift_out (time 00:00:00)
     *
     * @return (array)
     *    	- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *		- auto_approve (integer 0 or 1)
     *		- reason (string 00:00:00)
     *		- status_time_sched (time 00:00:00)
     *		- late (time 00:00:00)
     *		- undertime (time 00:00:00)
     *		- overtime (time 00:00:00)
     *		- extra time (time 00:00:00)
     *
     * @author (Kim Briel Oraya)
     *
     */

	public static function clean_shift($_time, $_shift, $testing = false)
	{
		$output_ctr = 0;
		$_output = null;

		
		if ($_time != null) 
		{
			$_time = Payroll2::sort_by_time($_time);
		}
		
		if ($_time == null) 
		{
			
		}
		else if ($_shift == null) 
		{
			foreach ($_time as $time) 
			{

				$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $time->time_out, 0,"NO SCHEDULE","00:00:00","00:00:00","00:00:");
			}
		}
		else
		{
			$count_time=0;
			$output_array=array();
			$one_time=true;
			$last_overtime = true;

			foreach ($_time as $time)
			{
				$one_time=true;
				$count_shift=0;
				$time_in_minutes = explode(":", $time->time_in);
				$time_out_minutes = explode(":", $time->time_out);
				$time_in_minutes = ($time_in_minutes[0]*60) + ($time_in_minutes[1]);
				$time_out_minutes = ($time_out_minutes[0]*60) + ($time_out_minutes[1]);
				$missed_shift=true;

				foreach($_shift as $shift)
				{
					echo $testing == true ?  "<hr><br><br>compare: Time (" . date("h:i A", strtotime($time->time_in)) . " - " . date("h:i A", strtotime($time->time_out)) . ") vs Shift (" . date("h:i A", strtotime($shift->shift_in)) . "-" . date("h:i A", strtotime($shift->shift_out)) . ")<br>" : "";
					//explode(":", $shift->shift_in)
					$shift_in_minutes 	= explode(":", $shift->shift_in);
					$shift_out_minutes 	= explode(":", $shift->shift_out);
					$shift_in_minutes 	= ($shift_in_minutes[0]*60) + ($shift_in_minutes[1]);
					$shift_out_minutes 	= ($shift_out_minutes[0]*60) + ($shift_out_minutes[1]);


					/*early overtime*/
					if ($count_shift == 0 && ($time_out_minutes < $shift_in_minutes)) 
					{
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($time->time_in)." to ".Payroll2::convert_to_12_hour($time->time_out)."(0) - <span style='color: green; text-transform: uppercase'> Early OVERTIME time in and time out 1<span><br></b>";
						echo $testing == true ? $reason : "";
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $time->time_out, 0, $reason, "OVERTIME", "00:00:00", "00:00:00", Payroll::time_diff($time->time_in,$time->time_out));
						break;
					}


					//check if there is next last shift that has blank time in and time out
					if ($count_time==(sizeof($_time)-1) && ($time_in_minutes>=Payroll2::convert_time_in_minutes($_shift[sizeof($_shift)-1]->shift_out)))
					{
						// dd($time->time_in. ' '. $time->time_out);
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($time->time_in)." to ".Payroll2::convert_to_12_hour($time->time_out)." (0) - <span style='color: green; text-transform: uppercase'>OVERTIME time in and time out 2<span><br></b>";
						echo $testing == true ? $reason : "";
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $time->time_out, 0, $reason, "OVERTIME", "00:00:00", "00:00:00", Payroll::time_diff($time->time_in,$time->time_out));
						break;
					}

					//no time in first shift
					if (($count_time == 0) && ($count_shift==0)) 
					{
						//error : 
						//solve : by adding equal sign in first condition
						if ($time_in_minutes>=$shift_out_minutes) 
						{
							$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_in) ." to ". Payroll2::convert_to_12_hour($shift->shift_out)." (2) - <span style='color: green; text-transform: uppercase'>LATE FOR FIRST SHIFT<span><br></b>";
							echo $testing == true ? $reason : ""; 
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_in, $shift->shift_out, 2, $reason, "LATE", Payroll::time_diff($shift->shift_in, $shift->shift_out),"00:00:00","00:00:00");						
						}
					}

					//the next shift has no time in or time out
					if ($count_time==(sizeof($_time)-1)&&(!(($time_in_minutes>=$shift_in_minutes)&&($time_in_minutes<=$shift_out_minutes))))
					{
						if (($time_out_minutes<=$shift_in_minutes)) 
						{
							$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_in)." to ". Payroll2::convert_to_12_hour($shift->shift_out)." (2) - <span style='color: green; text-transform: uppercase'>SHIFT HAS BEEN SKIPPED<span><br></b>";
							echo $testing == true ? $reason : ""; 
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_in, $shift->shift_out, 2, $reason, "UNDERTIME", "00:00:00", Payroll::time_diff($shift->shift_in, $shift->shift_out), "00:00:00");	
						}
					}



					/*START first and between early time in*/
					if (($time_in_minutes<$shift_in_minutes) && ($time_out_minutes>=$shift_in_minutes)) 
					{
						if ($one_time) 
						{
							$reason = "<b>answer: ". Payroll2::convert_to_12_hour($time->time_in)." to ". Payroll2::convert_to_12_hour($shift->shift_in)." (0)- <span style='color: green; text-transform: uppercase'>early over time<span><br></b>";
							echo $testing == true ? $reason : ""; 
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $shift->shift_in, 0, $reason, "OVERTIME","00:00:00","00:00:00",Payroll::time_diff($time->time_in,$shift->shift_in));					
						}
						$one_time=false;
					}
					else if(($time_in_minutes>=$shift_in_minutes) && ($time_in_minutes<=$shift_out_minutes))
					{
						$one_time=false;
					}
					/*END first and between early time in */


					/*START all approve timeshift*/
					//if sandwich
					if (($time_in_minutes<=$shift_in_minutes) && ($time_out_minutes>=$shift_out_minutes)) 
					{
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_in)." to ". Payroll2::convert_to_12_hour($shift->shift_out)." (1) - <span style='color: green; text-transform: uppercase'>sandwich between time in and time out</span><br></b>";
						echo $testing == true ? $reason : ""; 
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_in, $shift->shift_out, 1,$reason,"APPROVED","00:00:00","00:00:00","00:00:00");
					}
					//late time in but not undertime
					//error: solve by adding equal sign in 2nd line condition
					else if ((($time_in_minutes>$shift_in_minutes) && ($time_in_minutes<$shift_out_minutes))
						&&($time_out_minutes>=$shift_out_minutes)) 
					{
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($time->time_in)." to ".Payroll2::convert_to_12_hour($shift->shift_out)." (1)- <span style='color: green; text-transform: uppercase'>LATE TIME IN BUT NOT UNDERTIME<span><br></b>";
						echo $testing == true ? $reason : "";
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $shift->shift_out, 1,$reason,"LATE",Payroll::time_diff($shift->shift_in,$time->time_in),"00:00:00","00:00:00");
					}
					//not late time in but undertime time out
					//error: solve by adding equal sign in first condition
					else if(($time_in_minutes<=$shift_in_minutes) &&
						(($time_out_minutes>$shift_in_minutes) && ($time_out_minutes<$shift_out_minutes)))
					{
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_in)." to ".Payroll2::convert_to_12_hour($time->time_out)." (1) - <span style='color: green; text-transform: uppercase'>ONTIME BUT UNDERTIME<span><br></b>".Payroll::time_diff($time->time_out,$shift->shift_out);
						 
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_in, $time->time_out, 1,$reason,"UNDERTIME","00:00:00",Payroll::time_diff($time->time_out,$shift->shift_out),"00:00:00");

					}
					//late and undertime
					else if (($time_in_minutes>$shift_in_minutes) && ($time_out_minutes<$shift_out_minutes)) 
					{
						$reason = "<b>answer: ". Payroll2::convert_to_12_hour($time->time_in)." to ".Payroll2::convert_to_12_hour($time->time_out)." (1)- <span style='color: green; text-transform: uppercase'>LATE AND UNDERTIME<span><br></b>";
						echo $testing == true ? $reason : "";
						$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $time->time_out, 1,$reason,"LATE AND UNDERTIME",Payroll::time_diff($shift->shift_in,$time->time_in),Payroll::time_diff($time->time_out,$shift->shift_out),"00:00:00");
						//echo $shift->shift_in."  ".$time->time_in;
					}
					/*END all approve shift*/

					/*START last overtime*/
					$count_shift++;
					//last overtime out
					if ((($count_time+1)==sizeof($_time)) && ($count_shift==sizeof($_shift))) 
					{
						if ($time_out_minutes>$shift_out_minutes) 
						{
							$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_out)." to ". Payroll2::convert_to_12_hour($time->time_out)." (0)- <span style='color: green; text-transform: uppercase'>LAST OVERTIME<span><br></b>";
							echo $testing == true ? $reason : "";
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_out, $time->time_out, 0,$reason,"OVERTIME","00:00:00","00:00:00",Payroll::time_diff($shift->shift_out,$time->time_out));
						}
						$last_overtime = false;
					}



					//Last Overtime with next overtime that has no shift
					if ($count_shift == sizeof($_shift) && $last_overtime) 
					{
						if ($time_out_minutes>$shift_out_minutes) 
						{
							$reason = "<b>answer: ". Payroll2::convert_to_12_hour($shift->shift_out)." to ". Payroll2::convert_to_12_hour($time->time_out)." (0)- <span style='color: green; text-transform: uppercase'>LAST OVERTIME 2<span><br></b>";
							echo $testing == true ? $reason : "";
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_out, $time->time_out, 0,$reason,"OVERTIME","00:00:00","00:00:00",Payroll::time_diff($shift->shift_out,$time->time_out));
						}
					}
					/*END last overtime*/

					/*START between overtime Sandwich*/
					//check if there is next shift
					if (isset($_shift[$count_shift])) 
					{
						$next_shift_in_minutes = explode(":", $_shift[$count_shift]->shift_in);
						$next_shift_out_minutes = explode(":", $_shift[$count_shift]->shift_out);
						$next_shift_in_minutes = ($next_shift_in_minutes[0]*60) + ($next_shift_in_minutes[1]);
						$next_shift_out_minutes = ($next_shift_out_minutes[0]*60) + ($next_shift_out_minutes[1]);

						// if overtime
						if (($time_out_minutes>$shift_out_minutes)&&
							($time_out_minutes<$next_shift_in_minutes)) 
						{
							$reason = "<b>answer: ".$shift->shift_out." to ".$time->time_out." (0) - <span style='color: green; text-transform: uppercase'>TIME OUT IS IN BETWEEN SHIFT OUT AND NEXT SHIFT IN OVERTIME<span><br></b>";
							echo $testing == true ? $reason : "";
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_out, $time->time_out, 0, $reason,"OVERTIME","00:00:00","00:00:00",Payroll::time_diff($shift->shift_out,$time->time_out));
						}

						// sandwich overtime between shift out and next shift in
						if (($time_in_minutes>$shift_out_minutes)&&($time_out_minutes<$next_shift_in_minutes)) 
						{
							$reason = "<b>answer: ".Payroll2::convert_to_12_hour($time->time_in)." to ".Payroll2::convert_to_12_hour($time->time_out)." (0) - <span style='color: green; text-transform: uppercase'>SANDWICH BETWEEN SHIFT OUT AND NEXT SHIFT IN<span><br></b>";
							echo $testing == true ? $reason : "";
							$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $time->time_in, $time->time_out, 0, $reason,"OVERTIME","00:00:00","00:00:00",Payroll::time_diff($time->time_in,$time->time_out));
						}
						
						//sandwich overtime, time out is between next shift in and out
						//error: exact time_in in shift_out , solve: added equal sign in first condition
						if (($time_in_minutes<=$shift_out_minutes)&&
							($time_out_minutes>=$next_shift_in_minutes)) 
						{ 
							//sometimes it records with the same time
							if ($shift_out_minutes != $next_shift_in_minutes) 
							{
								$reason = "<b>answer: ".Payroll2::convert_to_12_hour($shift->shift_out)." to ".Payroll2::convert_to_12_hour($_shift[$count_shift]->shift_in)." (0)- <span style='color: green; text-transform: uppercase'>sandwich overtime, time in is between next shift in and out<span><br></b>";
								echo $testing == true ? $reason : "";
								$_output = Payroll2::time_shift_output($time, $_output, $output_ctr++, $shift->shift_out, $_shift[$count_shift]->shift_in, 0, $reason,"OVERTIME","00:00:00","00:00:00",Payroll::time_diff($shift->shift_out, $_shift[$count_shift]->shift_in));
							}
						}
					}
					/*END between overtime Sandwich*/
				}
				$one_time=true;
				$count_time++;
			}
		}


		return $_output;
	}

    /*
     * TITLE: CLEAN SHIFT FOR FLEXITIME
     * 
     * Returns a clean TIME IN and TIME OUT when cross reference with SHIFTING SCHEDULES.
     *
     * @param
     *    $_time (array)
     *		- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *
     *	  $target_hours (time 00:00:00)
     *
     * @ret (array)
     *    	- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *		- auto_approve (integer 0 or 1)
     *		- reason (string 00:00:00)
     *
     * @author (Kim Briel Oraya)
     *
     */

	public static function clean_shift_flexi($_time, $break_hours = 0 ,$target_hours = 0 , $testing = false)
	{
	//	dd($target_hours);
		$index		      = 0;
		$_output	      = null;
		
		if($_time==null)
		{
			
		}
		else
		{
			if($target_hours!=0)
			{
				//$target_hours += $break_hours;
				$target_hours = $target_hours+$break_hours;
				$target_hours = Payroll2::convert_time_in_minutes($target_hours);
			}
			else
			{
				$target_hours = "00:00:00";
				$target_hours = 0;
			}

			$sum_target_hours = 0;
			//($_output, $index, $time_in, $time_out, $auto_approved, $reason = "",$status_time_sched = "", $over_time="00:00:00", $undertime = "00:00:00")
			//$_output = Payroll2::time_shift_output($_output, $output_ctr++, $time->time_in, $time->time_out, 0,"NO SCHEDULE","00:00:00","00:00:00","00:00:");
			foreach ($_time as $time) 
			{
	
				echo $testing == true ? "<hr><br><br> TIME IN - ".$time->time_in." vs TIME OUT - ".$time->time_out."<br><br>":"";
				$time_in_minutes = Payroll2::convert_time_in_minutes(Payroll::time_diff($time->time_in, $time->time_out));
		
				//sum is not yet over or equal to target hours

				if ($sum_target_hours < $target_hours) 
				{
					if (($time_in_minutes+$sum_target_hours)>$target_hours) 
					{
						echo $testing == true ? "<b>answer</b>: TIME IN = ".$time->time_in." TIME OUT = ". $time->time_out." OVER TARGET TIME<br>":"";
						$minus_minutes = ($time_in_minutes+$sum_target_hours)-$target_hours;
						//"APPROVED 1 minutes -> ".$minus_minutes." = (".$time_in_minutes." + ".$sum_target_hours.") - ".$target_hours
						$_output = Payroll2::flexi_time_shift_output($time, $_output,$index++, $time->time_in, Payroll2::minus_time($minus_minutes,$time->time_out),1 , "APPROVED 1", "APPROVED");
						$ot = Payroll::time_diff(Payroll2::minus_time($minus_minutes,$time->time_out), $time->time_out);
						$_output = Payroll2::flexi_time_shift_output($time, $_output,$index++, Payroll2::minus_time($minus_minutes,$time->time_out), $time->time_out,0 , "OVERTIME 1 ".Payroll2::minus_time($minus_minutes,$time->time_out), "OVERTIME", $ot);
					}
					else
					{
						$_output = Payroll2::flexi_time_shift_output($time, $_output,$index++, $time->time_in, $time->time_out, 1, "APPROVED 2 ", "APPROVED");
						echo $testing == true ? "<b>answer</b>: TIME IN = ".$time->time_in." TIME OUT = ". $time->time_out." LESS THAN TARGET TIME<br>":"";
					}
					$sum_target_hours = $sum_target_hours + $time_in_minutes;
				}
				else
				{
					$_output = Payroll2::flexi_time_shift_output($time, $_output,$index++, $time->time_in, $time->time_out, 0, "OVERTIME 2 ", "OVERTIME",Payroll::time_diff($time->time_in, $time->time_out));
					echo $testing == true ? "<b>answer</b>: TIME IN = ".$time->time_in." TIME OUT = ". $time->time_out." OVERTIME<br>":"";
				}
			}
		}
		
		return $_output;
	}


    /*
     * TITLE: COMPUTE TIME MODE FOR REGULAR
     * 
     * Returns breakdown of hours depending on the shift schedule.
     *
     * @param
     *    $_time (array)
     *		- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *		- overtime (time 00:00:00)
     *		- late (time 00:00:00)
     *		- undertime (time 00:00:00)
     *		- overtime_approved (boolean true/false)
     *	  $_shift (array)
     *		- shift_in (time 00:00:00)
     *		- shift_out (time 00:00:00)
     *	  $late_grace_time (time 00:00:00)
     *	  $day_type (string "regular", "_day", "extra_day")
     *    $is_holiday (string "not_holiday", "special", "regular")
     *    $leave (time 00:00:00)
     * @return (array)
     *    	- time_spent (time 00:00:00)
     *		- is_absent (boolean true/false)
     *		- late (time 00:00:00)
     *		- undertime (time 00:00:00)
     *		- overtime (time 00:00:00)
     *		- target_hours (time 00:00:00)
     *		- regular_hours (time 00:00:00) (overtime+night_diff)-time_spent
     *		- rest_day_hours (time 00:00:00)
     *		- extra_day_hours (time 00:00:00)
     *		- regular_holiday_hours (time 00:00:00)
     *		- special_holiday_hours (time 00:00:00)
     *		- leave_hours (time 00:00:00)
     *		- excess_leave_hours (time 00:00:00) //for undefined time (regular_hours+leave_hours)-target_hours
     *		- night_differential (time 00:00:00)
     *		- is_half_day (boolean true/false)
     *
     * @author (Kim Briel Oraya)
     *
     */
	public static function compute_time_mode_regular($_time, $_shift, $late_grace_time = "00:00:00", $grace_time_rule_late="per_shift", $overtime_grace_time = "00:00:00", $grace_time_rule_overtime = "per_shift", $day_type = "regular", $is_holiday = "not_holiday", $leave = "00:00:00", $leave_fill_late = 0, $leave_fill_undertime = 0, $target_hours = 0, $use_leave = false , $compute_type , $leavepay = 0, $testing = false)
	{
		$leave_fill_undertime	= 1;
		$leave_fill_late		= 1;
		$time_spent				= "00:00:00";
		$late_hours 			= "00:00:00";
		$under_time 			= "00:00:00";
		$over_time				= "00:00:00";
		$night_differential		= "00:00:00";
		$regular_hours			= "00:00:00";
		$rest_day_hours 		= "00:00:00";
		$extra_day_hours		= "00:00:00";
		$regular_holiday_hours	= "00:00:00";
		$special_holiday_hours	= "00:00:00";
		$leave_hours_consumed	= "00:00:00";
		$leave_minutes 			= Self::time_float($leave);
		$leave_hours			= $leave; //$leave;
		$leavepay 				= $leavepay;
		$excess_leave_hours 	= $leave;
		$is_half_day			= false;
		$is_absent				= true;

		$overtime_grace_time = "00:00:00"; // remove if there is a client that use gracetime overtime
	
		//target time is the same from shift hours
		if($target_hours == 0)
		{
			$target_hours = Payroll2::target_hours($_shift);
		}
		//target time is different from shift hours
		else
		{
			$target_hours = Payroll2::float_time($target_hours);
		}
		
		if ($_time == null) 
		{
			if (!(($day_type == "rest_day")||($day_type == "extra")||($is_holiday == "regular")))
			{
				$is_absent =true;
			}

			/*Start trigger if leave*/
			if ($is_absent==true && $leave_minutes != 0) 
			{
				$target_minutes = Payroll2::convert_time_in_minutes($target_hours);
				$leave_minutes = Payroll2::convert_time_in_minutes($leave);
				if ($target_minutes > $leave_minutes ) 
				{
					$under_time 			= Payroll::time_diff($leave_hours,$target_hours);
					$leave_hours_consumed   = $leave_hours;
					$time_spent             = $leave_hours;
					$is_absent 				= false;
				}
				else
				{
					$leave_hours_consumed   = $target_hours;
					$is_absent 				= false;
					$time_spent 			= $target_hours;
				}
			}
			/*End trigger if leave*/
		}
		else
		{
			if($_shift != null)
			{
				/*START check if there is multimple time in and time out in a single day*/
				foreach ($_shift as $shift) 
				{
					$shift_in_minutes  = explode(":", $shift->shift_in);
					$shift_out_minutes = explode(":", $shift->shift_out);
					$shift_in_minutes  = ($shift_in_minutes[0]*60) + ($shift_in_minutes[1]);
					$shift_out_minutes = ($shift_out_minutes[0]*60) + ($shift_out_minutes[1]);
					$count_output=0;
					if ($_time != null ) 
					{
						foreach ($_time as $output) 
						{
							//check if there is approved time in and out if not is_absent is equal to true
							if($output->auto_approved == 1)
							{
								$is_absent = false;
							}
						
							if (isset($_time[$count_output])) 
							{
									
								$output_in_minutes  = explode(":", $_time[$count_output]->time_in);
								$output_out_minutes = explode(":", $_time[$count_output]->time_out);
								$output_in_minutes  = ($output_in_minutes[0]*60) + ($output_in_minutes[1]);
								$output_out_minutes = ($output_out_minutes[0]*60) + ($output_out_minutes[1]);
								if (isset($_time[$count_output+1])) 
								{
									$next_output_in_minutes  = explode(":", $_time[$count_output+1]->time_in);
									$next_output_out_minutes = explode(":", $_time[$count_output+1]->time_out);
									$next_output_in_minutes  = ($next_output_in_minutes[0]*60) + ($next_output_in_minutes[1]);
									$next_output_out_minutes = ($next_output_out_minutes[0]*60) + ($next_output_out_minutes[1]);
									if ((($output_in_minutes>=$shift_in_minutes)&&($output_out_minutes<$shift_out_minutes))&&
										($next_output_in_minutes>$shift_in_minutes))
									{
										if(	$_time[$count_output]->auto_approved==1 && $_time[$count_output+1]->auto_approved==1)
										{
											$_time[$count_output]->undertime  = Payroll::time_diff($_time[$count_output]->time_out,$_time[$count_output+1]->time_in);
											$_time[$count_output+1]->late 	= "00:00";
										}
									}
								}
							}
							$count_output++;
						}
					}
				}
				/*END check if there is multimple time in and time out in a single day*/
			}


			$count = 0;

			/*Start compute night differential*/
			$night_differential = Payroll2::night_differential_computation($_time,false);
			/*End compute night differential*/

			foreach ($_time as $time) 
			{
				echo $testing == true ? "<hr><br><br> TIME IN - ".$time->time_in." vs TIME OUT - ".$time->time_out."<br><br>":"";                                                                                                
				//undertime computation
	
				$under_time = Payroll::sum_time($under_time,$time->undertime);
				
				//late hours computation and depends to time_grace_rule
				if ($grace_time_rule_late=="per_shift") 
				{

					$late_minutes = Payroll2::convert_time_in_minutes($time->late);
					//record late if late is greater than grace time
					if ($late_minutes>Payroll2::convert_time_in_minutes($late_grace_time)) 
					{
						$late_hours = Payroll::sum_time($late_hours,$time->late);
					}
					//remove late and add it to time spent
					else
					{
						$time_spent = Payroll::sum_time($time_spent,$time->late);
					}
				}	
				else if ($grace_time_rule_late=="accumulative") 
				{
					$late_hours = Payroll::sum_time($time->late,$late_hours);
				}
				else if ($grace_time_rule_late=="first") 
				{
					//first approve time shift if late record it
					$late_minutes = Payroll2::convert_time_in_minutes($time->late);
					//record late if late is greater than grace time
					if (($count==0)) 
					{
						if($late_minutes>Payroll2::convert_time_in_minutes($late_grace_time))
						{
							$late_hours = Payroll::sum_time($late_hours,$time->late);
						}
						else
						{
							$time_spent = Payroll::sum_time($time_spent,$time->late);
						}
					}
					else
					{
						$late_hours = Payroll::sum_time($late_hours,$time->late);
					}
				}
				else if ($grace_time_rule_late=="first-deducted") 
				{
					//first approve time shift if late record it
					$late_minutes = Payroll2::convert_time_in_minutes($time->late);
					//record late if late is greater than grace time
					if (($count==0)) 
					{
						if($late_minutes>Payroll2::convert_time_in_minutes($late_grace_time))
						{
							$late_hours = Payroll::sum_time($late_hours,$time->late);
							$late_hours = Payroll::time_diff($late_grace_time, $late_hours);
						}
						else
						{
							$time_spent = Payroll::sum_time($time_spent,$time->late);
						}
					}
					else
					{
						$late_hours = Payroll::sum_time($late_hours,$time->late);
					}
				}
				else if ($grace_time_rule_late=="last") 
				{
					$late_minutes = Payroll2::convert_time_in_minutes($time->late);
					if ($count==(sizeof($_time)-1)) 
					{
						if($late_minutes>Payroll2::convert_time_in_minutes($late_grace_time))
						{
							$late_hours = Payroll::sum_time($late_hours,$time->late);
						}
						else
						{
							$time_spent = Payroll::sum_time($time_spent,$time->late);
						}
					}
					else
					{
						$late_hours = Payroll::sum_time($late_hours,$time->late);
					}
				}
								
				//check if underime or late auto approved
				if (!($time->auto_approved==2)) 
				{
					//time spent computation
					$time_spent = Payroll::sum_time($time_spent,Payroll::time_diff($time->time_in,$time->time_out));
				}
				
				//over time computation per shift
				if($grace_time_rule_overtime=="per_shift")
				{
					if (Payroll2::convert_time_in_minutes($time->overtime)>Payroll2::convert_time_in_minutes($overtime_grace_time)) 
					{
						$over_time = Payroll::sum_time($over_time,$time->overtime);
					}
					else if (Payroll2::convert_time_in_minutes($time->overtime)<=Payroll2::convert_time_in_minutes($overtime_grace_time)) 
					{
						$time_spent = Payroll2::minus_time(Payroll2::convert_time_in_minutes($time->overtime),$time_spent);
					}
				}
				else if($grace_time_rule_overtime=="accumulative")
				{
					$over_time = Payroll::sum_time($over_time,$time->overtime);
				}

				$count++;
			}


			//compute regular hours
			$regular_hours = Payroll2::time_difference($time_spent,(Payroll::sum_time($over_time,$night_differential))).":00"; //(overtime+night_diff)-time_spent

			//grace time for late - accumulative.
			$late_minutes = Payroll2::convert_time_in_minutes($late_hours);
			if (($late_minutes<=Payroll2::convert_time_in_minutes($late_grace_time))&&($grace_time_rule_late=="accumulative")) 
			{
				$time_spent = Payroll::sum_time($time_spent,$late_hours);
				$late_hours="00:00";
			}

			//grace time for overtime - accumulative.
			$over_time_minutes = Payroll2::convert_time_in_minutes($over_time);
			if (($over_time_minutes<=Payroll2::convert_time_in_minutes($overtime_grace_time)) && ($grace_time_rule_overtime=="accumulative")) 
			{
				$time_spent = Payroll2::minus_time($over_time_minutes,$time_spent);
				$over_time="00:00";
			}


			

			/*START sum time_spent late and undertime of all auto approved sched*/
			//purpose for checking if there is missed shift in and shift out in the middle
			$late_hour_temp="00:00";
			$under_time_temp="00:00";
			$time_spent_temp="00:00";
		
			foreach ($_time as $time) 
			{
				if ($time->auto_approved==1 || ($time->auto_approved==2) && ($time->status_time_sched!='OVERTIME')) 
				{
					$time_spent_temp = Payroll::sum_time($time_spent_temp,$time->undertime);
					$time_spent_temp = Payroll::sum_time($time_spent_temp,$time->late);
					$time_spent_temp =  Payroll::sum_time($time_spent_temp,Payroll::time_diff($time->time_in,$time->time_out));					
				}
			}

			//record if undertime
			//not yet sure
			$target_minutes 		= Payroll2::convert_time_in_minutes($target_hours);
			$time_spent_in_minutes 	= Payroll2::convert_time_in_minutes($time_spent_temp);
			$under_time_in_minutes 	= Payroll2::convert_time_in_minutes($time->undertime);

			if ($time_spent_in_minutes<$target_minutes) 
			{ 
				$under_time = Payroll::sum_time($under_time,Payroll::time_diff($time_spent_temp,$target_hours));
				$under_time = Payroll2::minus_time($under_time_in_minutes,$under_time);
			}
			/*END sum time_spent late and undertime of all auto approved sched*/
	
			/*Start leave trigger*/
			if ($use_leave == true) 
			{
				//if absent
				if ($is_absent==true) 
				{
					$target_minutes = Payroll2::convert_time_in_minutes($target_hours);
					$leave_minutes = Payroll2::convert_time_in_minutes($leave);
					if ($target_minutes > $leave_minutes) 
					{
						$under_time 			= Payroll::time_diff($leave_hours,$target_hours);
						$leave_hours_consumed   = $leave_hours;
						$time_spent             = $leave_hours;	
						$is_absent 				= false;
					}
					else
					{
						$leave_hours_consumed   = $target_hours;
						$is_absent 				= false;
						$time_spent 			= $target_hours;
					}
				}

				//if not absent and there was a late or undertime
				else
				{
					//fill undertime with leave hours
					if ($leave_fill_undertime == 1) 
					{

					 	$undertime_minutes = Payroll2::convert_time_in_minutes($under_time);
						$excess_leave_minutes = Payroll2::convert_time_in_minutes($excess_leave_hours);

						//has undertime record and have leave hours
						if (($undertime_minutes > 0) && ($excess_leave_minutes > 0)) 
						{
							//leave hours can fill the undertime record
							if ($undertime_minutes <= $excess_leave_minutes) 
							{
								$leave_hours_consumed	= Payroll::sum_time($leave_hours_consumed,$under_time);
								$excess_leave_hours 	= Payroll2::minus_time($undertime_minutes,$excess_leave_hours);
								$time_spent 			= Payroll::sum_time($time_spent,$under_time);
								$under_time 			= "00:00";
								//dd(Payroll2::minus_time(60,$excess_leave_hours));
							}
							//leave hours can't fill the undertime record
							else if ($undertime_minutes>$excess_leave_minutes) 
							{
								$leave_hours_consumed	= Payroll::sum_time($leave_hours_consumed, $leave_hours);
								$under_time 			= Payroll2::minus_time($excess_leave_minutes, $under_time);
								$time_spent 			= Payroll::sum_time($time_spent, $excess_leave_hours);
								$excess_leave_hours		= "00:00";
							}
						}
					}

					//fill late with leave hours
					if ($leave_fill_late == 1) 
					{
						$late_minutes = Payroll2::convert_time_in_minutes($late_hours);
						$excess_leave_minutes = Payroll2::convert_time_in_minutes($excess_leave_hours);
						//has late record and have leave hours
						if (($late_minutes>0)&&($excess_leave_minutes>0)) 
						{
							//leave hours can fill the undertime record
							if ($late_minutes<=$excess_leave_minutes) 
							{
								$leave_hours_consumed	= Payroll::sum_time($leave_hours_consumed, $late_hours);
								$excess_leave_hours = Payroll2::minus_time($late_minutes,$excess_leave_hours);
								$time_spent = Payroll::sum_time($time_spent,$late_hours);
								$late_hours = "00:00";
							}
							//leave hours can't fill the undertime record
							else if ($late_minutes>$excess_leave_minutes && $excess_leave_minutes!=0) 
							{
								$leave_hours_consumed	= Payroll::sum_time($leave_hours_consumed, $leave_hours);
								$late_hours = Payroll2::minus_time($excess_leave_minutes,$late_hours);
								$time_spent = Payroll::sum_time($time_spent,$excess_leave_hours);
								$excess_leave_hours="00:00";
							}
						}
					}

					//excess leave hour if not use
					if (($leave_fill_undertime==0) && ($leave_fill_late==0)) 
					{
						$excess_leave_hours=$leave;
					}
				}
			}
			/*End leave trigger*/

			//check if time spent is only half day
			if ((Payroll2::divide_time_in_half($target_hours.":00"))==$time_spent.":00") 
			{
				$is_half_day=true;
			}
		}

		/*START day type and holiday type*/
		if (Payroll2::convert_to_24_hour($time_spent)=="00:00:00") 
		{
			$is_absent=true;
		}
		if ($day_type=="rest_day") 
		{
			$rest_day_hours = $time_spent;
			$is_absent = false;
		}
		if ($day_type=="extra_day") 
		{
			$extra_day_hours = $regular_hours;	
			$is_absent = false;
		
		}
		if ($is_holiday=="regular") 
		{
			$regular_holiday_hours = $regular_hours;
			$is_absent = false;
		}
		if ($is_holiday=="special") 
		{
			$special_holiday_hours = $regular_hours;
			
			if ($compute_type == "monthly") 
			{
				$is_absent = false;
			}
		}
		/*END day type and holiday type*/ 

		$return["target_hours"] 		 = Payroll2::convert_to_24_hour($target_hours);
		$return["time_spent"] 			 = Payroll2::convert_to_24_hour($time_spent);
		$return["is_absent"] 			 = $is_absent;
		$return["late"]  				 = Payroll2::convert_to_24_hour($late_hours);
		$return["undertime"] 			 = Payroll2::convert_to_24_hour($under_time);
		$return["overtime"] 			 = Payroll2::convert_to_24_hour($over_time);
		$return["regular_hours"] 		 = Payroll2::convert_to_24_hour($regular_hours);
		$return["rest_day_hours"] 		 = Payroll2::convert_to_24_hour($rest_day_hours);
		$return["extra_day_hours"] 		 = Payroll2::convert_to_24_hour($extra_day_hours);
		$return["regular_holiday_hours"] = Payroll2::convert_to_24_hour($regular_holiday_hours);
		$return["special_holiday_hours"] = Payroll2::convert_to_24_hour($special_holiday_hours);
		$return["total_hours"] 		     = Payroll2::convert_to_24_hour($time_spent);
		$return["break_hours"] 			 = Payroll2::convert_to_24_hour("00:00:00");
		$return["night_differential"] 	 = Payroll2::convert_to_24_hour($night_differential);
		$return["leave_hours"] 			 = $leave;
		$return["leave_pay"]			 = $leavepay;
		$return["is_half_day"] 			 = $is_half_day;
		$return["is_holiday"] 			 = $is_holiday;
		$return["day_type"] 			 = $day_type;
		$return["excess_leave_hours"] 	 = $excess_leave_hours;
		$return["leave_hours_consumed"]  = $leave_hours_consumed;
	
		return $return;

	}

	/*
     * TITLE: COMPUTE TIME MODE FOR FLEXI
     * 
     * Returns breakdown of hours depending on the shift schedule.
     *
     * @param
     *    $_time (array)
     *		- time_in (time 00:00:00)
     *		- time_out (time 00:00:00)
     *		- overtime (time 00:00:00)
     *		- late (time 00:00:00)
     *		- undertime (time 00:00:00)
     *		- overtime_approved (boolean true/false)
     *	  $_shift (array)
     *		- shift_in (time 00:00:00)
     *		- shift_out (time 00:00:00)
     *	  $late_grace_time (time 00:00:00)
     *	  $day_type (string "regular", "rest_day", "extra_day")
     *    $is_holiday (string "not_holiday", "special", "regular")
     *    $leave (time 00:00:00)
     * @return (array)
     *    	- time_spent (time 00:00:00)
     *		- is_absent (boolean true/false)
     *		- late (time 00:00:00)
     *		- undertime (time 00:00:00)
     *		- overtime (time 00:00:00)
     *		- target_hours (time 00:00:00)
     *		- regular_hours (time 00:00:00) (overtime+night_diff)-time_spent
     *		- rest_day_hours (time 00:00:00)
     *		- extra_day_hours (time 00:00:00)
     *		- regular_holiday_hours (time 00:00:00)
     *		- special_holiday_hours (time 00:00:00)
     *		- leave_hours (time 00:00:00)
     *		- excess_leave_hours (time 00:00:00) //for undefined time (regular_hours+leave_hours)-target_hours
     *		- night_differential (time 00:00:00)
     *		- is_half_day (boolean true/false)
     *		- is_holiday ('not_holiday' / 'regular' / 'special')
     *
     * @author (Kim Briel Oraya)
     *
     */


	public static function compute_time_mode_flexi($_time, $target_hours="08:00:00", $break_hours=0, $overtime_grace_time = "00:00:00", $grace_time_rule_overtime="per_shift", $day_type = "regular", $is_holiday = "not_holiday", $leave = "00:00:00", $leave_fill_undertime=0, $use_leave = false, $compute_type , $leavepay = 0, $testing = false)

	{

		$leave_fill_undertime	= 1;
		$time_spent 			= "00:00:00";
		$late_hours 			= "00:00:00";
		$under_time 			= "00:00:00";
		$over_time 				= "00:00:00";
		$night_differential 	= Payroll2::night_differential_computation($_time,false);
		$break_hours 			= Payroll::float_time($break_hours);
		$working_hours 			= Payroll::float_time($target_hours);
		$target_hours 			= Payroll::sum_time(Payroll::float_time($target_hours),Payroll::float_time($break_hours));
		$regular_hours 			= "00:00:00";
		$rest_day_hours 		= "00:00:00";
		$extra_day_hours 		= "00:00:00";
		$regular_holiday_hours 	= "00:00:00";
		$special_holiday_hours 	= "00:00:00";
		$leave_hours_consumed 	= "00:00:00";
		$leave_hours 			= $leave;
		$leavepay               = $leavepay;
		$leave_minutes 			= Self::time_float($leave);
		$excess_leave_hours 	= $leave_hours;
		$is_half_day 			= false;
		$is_absent 				= false;

		$overtime_grace_time = "00:00:00"; // remove if there is a client that use gracetime overtime

		// if ($use_leave) 
		// {
		// 	$is_absent = true;
		// }
	
		if ($_time == null) 
		{
			if (!(($day_type == "rest")||($day_type == "extra")||($is_holiday == "regular")))
			{
				$is_absent =true;
			}
			/*Start leave trigger*/
			if ($is_absent==true && $leave_minutes !=0) 
			{
				$target_minutes = Payroll2::convert_time_in_minutes($target_hours);
				$leave_minutes = Payroll2::convert_time_in_minutes($leave);
				if ($target_minutes > $leave_minutes) 
				{
					$under_time 			= Payroll::time_diff($leave_hours,$target_hours);
					$leave_hours_consumed   = $leave_hours;
					$time_spent             = $leave_hours;	
					$is_absent 				= false;
				}
				else
				{
					$leave_hours_consumed   = $target_hours;
					$is_absent 				= false;
					$time_spent 			= $target_hours;
				}
			}
			/*End leave trigger*/
		}

		else
		{
			foreach ($_time as $time) 
			{
				//time spent computation
				$time_spent = Payroll::sum_time($time_spent,Payroll::time_diff($time->time_in,$time->time_out));
				//over time computation per shift
				//remove
				// if($grace_time_rule_overtime=="per_shift")
				// {
				// 	if (Payroll2::convert_time_in_minutes($time->overtime)>Payroll2::convert_time_in_minutes($overtime_grace_time)) 
				// 	{
				// 		$over_time = Payroll::sum_time($over_time,$time->overtime);
				// 	}
				// 	else if (Payroll2::convert_time_in_minutes($time->overtime)<=Payroll2::convert_time_in_minutes($overtime_grace_time)) 
				// 	{
				// 		$time_spent = Payroll2::minus_time(Payroll2::convert_time_in_minutes($time->overtime),$time_spent);
				// 	}
				// }
				// else if($grace_time_rule_overtime=="accumulative")
				// {
				// 	$over_time = Payroll::sum_time($over_time,$time->overtime);
				// }
			}
		}

		//record if undertime
		$target_minutes = Payroll2::convert_time_in_minutes($target_hours);
		$time_spent_in_minutes = Payroll2::convert_time_in_minutes($time_spent);
		
		if ($time_spent_in_minutes!=0) 
		{
			if ($time_spent_in_minutes<$target_minutes) 
			{
				$under_time = Payroll::sum_time($under_time,Payroll::time_diff($time_spent, $target_hours));
			}
		}

		
		if ($time_spent_in_minutes>$target_minutes) 
		{
			$over_time = Payroll2::minus_time($target_minutes,$time_spent);
		}
		//dd($_time);


		/*trigger leave*/
		if ($use_leave == true) 
		{
			
		//fill undertime with leave hours
			if ($is_absent==true) 
			{
				$target_minutes = Payroll2::convert_time_in_minutes($target_hours);
				$leave_minutes = Payroll2::convert_time_in_minutes($leave);
				if ($target_minutes > $leave_minutes) 
				{
					$under_time 			= Payroll::time_diff($leave_hours,$target_hours);
					$leave_hours_consumed   = $leave_hours;
					$time_spent             = $leave_hours;	
					$is_absent 				= false;
				}
				else
				{
					$leave_hours_consumed   = $target_hours;
					$is_absent 				= false;
					$time_spent 			= $target_hours;
				}
			}
			else
			{
				if ($leave_fill_undertime==1) 
				{
				 	$undertime_minutes = Payroll2::convert_time_in_minutes($under_time);
					$excess_leave_minutes = Payroll2::convert_time_in_minutes($excess_leave_hours);
					//has undertime record and have leave hours
					if (($undertime_minutes>0)&&($excess_leave_minutes>0)) 
					{
						//leave hours can fill the undertime record
						if ($undertime_minutes<=$excess_leave_minutes) 
						{
							$leave_hours_consumed = $under_time;
							$excess_leave_hours = Payroll2::minus_time($undertime_minutes,$excess_leave_hours);
							$time_spent = Payroll::sum_time($time_spent,$under_time);
							$under_time = "00:00";
						}
						//leave hours can't fill the undertime record
						else if ($undertime_minutes>$excess_leave_minutes) 
						{
							$leave_hours_consumed = $excess_leave_hours;
							$under_time = Payroll2::minus_time($excess_leave_minutes,$under_time);
							$time_spent = Payroll::sum_time($time_spent,$excess_leave_hours);
							$excess_leave_hours="00:00";
						}
					}
				}
				else
				{
					$excess_leave_minutes = $leave;
				}
			}
		}
		/*End leave trigger*/


		
		//grace time for overtime - accumulative.
		$over_time_minutes = Payroll2::convert_time_in_minutes($over_time);
		if (($over_time_minutes<=Payroll2::convert_time_in_minutes($overtime_grace_time))&&($grace_time_rule_overtime=="accumulative")) 
		{
				$time_spent = Payroll2::minus_time($over_time_minutes,$time_spent);
				$over_time="00:00";
		}

		//check if time spent is only half day
		if ((Payroll2::divide_time_in_half($target_hours.":00"))==$time_spent.":00") 
		{
			$is_half_day=true;
		}


		//compute regular hours
		$regular_hours = Payroll2::time_difference($time_spent,(Payroll::sum_time($over_time,$night_differential))).":00"; //(overtime+night_diff)-time_spent



		/*START day type and holiday type*/
		if ($day_type=='regular') 
		{
			if (Self::time_float($time_spent)==0) 
			{

			}
		}
		if ($day_type=="rest_day") 
		{
			$rest_day_hours = $time_spent;
			$is_absent=false;
		}
		if ($day_type=="extra_day") 
		{
			$extra_day_hours = $regular_hours;	
			$is_absent=false;
		}
		if ($is_holiday=="regular") 
		{
			$regular_holiday_hours = $regular_hours;
		}
		if ($is_holiday=="special") 
		{
			$special_holiday_hours = $regular_hours;
			if ($compute_type == "monthly") 
			{
				$is_absent = false;
			}
		}
		/*END day type and holiday type*/

		$return["time_spent"] 			 = Payroll2::convert_to_24_hour($time_spent);
		$return["target_hours"] 		 = Payroll2::convert_to_24_hour($working_hours);
		$return["break_hours"] 			 = Payroll2::convert_to_24_hour($break_hours);
		$return["is_absent"] 			 = $is_absent;
		$return["late"] 				 = Payroll2::convert_to_24_hour($late_hours);
		$return["undertime"] 			 = Payroll2::convert_to_24_hour($under_time);
		$return["overtime"] 			 = Payroll2::convert_to_24_hour($over_time);
		$return["regular_hours"] 		 = Payroll2::convert_to_24_hour($regular_hours);
		$return["rest_day_hours"] 		 = Payroll2::convert_to_24_hour($rest_day_hours);
		$return["extra_day_hours"] 		 = Payroll2::convert_to_24_hour($extra_day_hours);
		$return["regular_holiday_hours"] = Payroll2::convert_to_24_hour($regular_holiday_hours);
		$return["special_holiday_hours"] = Payroll2::convert_to_24_hour($special_holiday_hours);
		$return["total_hours"] 			 = Payroll2::convert_to_24_hour($time_spent);
		$return["night_differential"] 	 = Payroll2::convert_to_24_hour($night_differential);
		$return["leave_hours"] 			 = Payroll2::convert_to_24_hour($leave);
		$return["leave_pay"]             = $leavepay;
		$return["is_half_day"] 			 = $is_half_day;
		$return["is_holiday"] 			 = $is_holiday;
		$return["day_type"] 			 = $day_type;
		$return["excess_leave_hours"] 	 = Payroll2::convert_to_24_hour($excess_leave_hours);
		$return["leave_hours_consumed"]  = $leave_hours_consumed;
		
		return $return;
	}


	public static function compute_income_daily()
	{

	}



    /*
     * TITLE: COMPUTE INCOME FOR EMPLOYEE DAILY
     * 
     * Returns breakdown of hours depending on the shift schedule.
     *
     * @param
     * 	  $_time (array)
	     *    => $time_spent (time 00:00:00)
	     *    => $is_absent (time 00:00:00)
	     *    => $late (time 00:00:00)
	     *    => $undertime (time 00:00:00)
	     *    => $overtime (time 00:00:00)
	     *    => $target_hours (time 00:00:00)
	     *    => $excess_leave_hours (time 00:00:00)
	     *    => $regular_hours (time 00:00:00)
	     *    => $rest_day_hours (time 00:00:00)
	     *    => $regular_holiday_hours (time 00:00:00)
	     *    => $special_holiday_hours (t
	     ime 00:00:00)
	     *    => $leave_hours (time 00:00:00)
	     *    => $total_hours (time 00:00:00)
	     *    => $night_differential (time 00:00:00)
	     *    => $is_half_day (time 00:00:00)
	     * 	  => $is_holiday (''/'regular'/'special')
     *    
     * @return (array)
     *   
	 *	regular_salary
	 *		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	extra_salary
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	leave_salary
	 *		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	special_holiday_salary
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	regular_holiday_salary
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 * 	overtime_salary
	 *		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	night_diff_salary
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	late_deduction
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
	 *	undertime_deduction
	 * 		=> hours (00:00:00)
	 *		=> income (double)
	 *		=> formula ('plus')
     *
     * @author Kim Briel Oraya
     *
     */
 
	public static function compute_income_day_pay($_time = array(), $daily_rate = 0, $hourly_rate_employee_salary = 0, $group_id = 0, $cola = 0, $compute_type="", $time_compute_mode="regular")
	{
		$return = new stdClass();

		$time_spent = Self::time_float($_time['time_spent']);
		
		/* START leave pay computation */
		if (Self::time_float($_time['leave_hours']) != 0) 
		{
			$target_float 						 			  = Self::time_float($_time['target_hours']);
			$return->_breakdown_addition['Leave Pay']['time'] = $_time['leave_hours'];

			if($_time['leave_pay'] == 1)
			{
				$return->_breakdown_addition['Leave Pay']['rate'] = Self::time_float($_time['leave_hours']) * @($daily_rate/$target_float);
			}
			else
			{
				$return->_breakdown_addition['Leave Pay']['rate'] = 0;
			}

			$return->_breakdown_addition['Leave Pay']['hour'] = $_time['leave_hours'];
		}
		/* END leave pay computation */

		/* START determine the daily rate by compute type, day type and holidays */
		$daily_true_rate = $daily_rate;
		
		if($compute_type == "daily")
		{
			if( $_time['day_type'] == 'rest_day' || $_time["is_holiday"] == "regular" || $_time['day_type'] == 'extra_day' || $_time["is_holiday"] == "special") //|| $_time["is_holiday"] == "special"
			{
				$daily_rate = 0;
			}
		}

		if($compute_type == "hourly")
		{
			if ($_time['day_type'] == 'regular' && $_time["is_holiday"] == "special") 
			{
				$daily_rate = 0;
			}
			if ($_time['day_type'] == 'rest_day' && $_time["is_holiday"] == "special") 
			{
				$daily_rate = 0;
			}
		}

		if($compute_type == "monthly")
		{
			if( $_time['day_type'] == 'rest_day' || $_time["is_holiday"] == "regular" || $_time['day_type'] == 'extra_day') 
			{
				$daily_rate = 0;
			}
		}

		if ((  $_time['day_type'] == 'extra_day'  || $_time["is_holiday"] == "regular" ) && $time_spent!=0) 
		{
			$return->daily_rate = $daily_true_rate;
		}
		else
		{
			$return->daily_rate = $daily_rate;
		}

		if($time_spent!= 0)
		{
			$daily_rate = $daily_true_rate;
		}

		
		$total_day_income 				= $daily_rate;
		$target_float 					= Self::time_float($_time['target_hours']);
		$daily_rate_plus_cola			= $daily_rate + $cola;

		// $hourly_rate 			= $return->hourly_rate = divide($daily_rate, $target_float);

		/* END determine the daily rate by compute type*/


		/*START get hourly rate*/
		if($compute_type != "hourly")
		{
			$hourly_rate = $return->hourly_rate = divide($daily_rate, $target_float);
		}
		else
		{
			$hourly_rate = $return->hourly_rate = $hourly_rate_employee_salary;
		}
		
		/*END get hourly rate*/
		
		/* GET INITIAL DATA */
		$param_rate 		= Tbl_payroll_overtime_rate::where('payroll_group_id', $group_id)->get()->toArray();
		$collection 		= collect($param_rate);
		$regular_param 		= $collection->where('payroll_overtime_name', 'Regular')->first();
		$legal_param 		= $collection->where('payroll_overtime_name', 'Legal Holiday')->first();
		$special_param 		= $collection->where('payroll_overtime_name', 'Special Holiday')->first();
		$group 				= Tbl_payroll_group::where('payroll_group_id', $group_id)->first();

		/* BREAKDOWN ADDITIONS */
		$_time['time_spent']   = Payroll2::minus_time(Payroll2::convert_time_in_minutes($_time['break_hours']),$_time['time_spent']);

		$time_spent 		   = Self::time_float($_time['time_spent']);
		$regular_float 		   = Self::time_float($_time['regular_hours']);
		$rest_float 		   = Self::time_float($_time['rest_day_hours']);
		$extra_float 		   = Self::time_float($_time['extra_day_hours']);
		$legal_float 		   = Self::time_float($_time['regular_holiday_hours']);
		$special_float 		   = Self::time_float($_time['special_holiday_hours']);
		$leave_float 		   = Self::time_float($_time['leave_hours']);
		$overtime_float 	   = Self::time_float($_time['overtime']);
		$night_diff_float 	   = Self::time_float($_time['night_differential']);
		$extra_float 		   = Self::time_float($_time['extra_day_hours']);
		$leave_float		   = Self::time_float($_time["leave_hours"]);
		$overtime 			   = 0;
		$nightdiff 			   = 0;
		$breakdown_deduction   = 0;
		$breakdown_addition    = 0;
		$additional_rate 	   = 1;
		$cola_true_rate 	   = $cola;
		$payroll_late_category = $group->payroll_late_category;
		$payroll_late_category = $group->payroll_under_time_category;
		
		$overtime 				= 0;
		$nightdiff 				= 0;
		$breakdown_deduction 	= 0;
		$breakdown_addition 	= 0;
		$additional_percentage 	= 1;
		$cola_true_rate 		= $cola;
		$payroll_late_category 	= $group->payroll_late_category;
		$payroll_late_category 	= $group->payroll_under_time_category;

		if ($_time['is_holiday'] == 'not_holiday') 
		{
			if ($rest_float != 0) 
			{
					//Rest Day
					if($compute_type == "daily")
					{
						$total_day_income = 0;
						$return->_breakdown_addition["Rest Day"]["time"] = ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($regular_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Rest Day"]["rate"] = (( ( $time_spent >= $target_float ? $target_float:$time_spent ) * $hourly_rate ) * ($regular_param['payroll_overtime_rest_day']));
						$return->_breakdown_addition["Rest Day"]["hour"] = ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]);
						
						$total_day_income 		= $total_day_income + $return->_breakdown_addition["Rest Day"]["rate"]; 			
						$additional_percentage 	= ($regular_param['payroll_overtime_rest_day']);
						$breakdown_addition     += $return->_breakdown_addition["Rest Day"]["rate"];
					}
					else if($compute_type == "hourly")
					{
						$total_day_income   = 0;
						$return->daily_rate = 0;
						$return->_breakdown_addition["Rest Day"]["time"] = ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($regular_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Rest Day"]["rate"] = (($target_float * $hourly_rate ) * ($regular_param['payroll_overtime_rest_day']));
						$return->_breakdown_addition["Rest Day"]["hour"] = ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]);
						
						$total_day_income 		= $total_day_income + $return->_breakdown_addition["Rest Day"]["rate"]; 			
						$additional_percentage 	= ($regular_param['payroll_overtime_rest_day']);
						$breakdown_addition     += $return->_breakdown_addition["Rest Day"]["rate"];
					}
					else if($compute_type == "monthly")
					{
						$return->_breakdown_addition["Rest Day"]["time"] =  ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($regular_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Rest Day"]["rate"] = ((($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) * $hourly_rate) * ($regular_param['payroll_overtime_rest_day']));
						$return->_breakdown_addition["Rest Day"]["hour"] =  ($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]);
						
						$total_day_income 		 = ($return->_breakdown_addition["Rest Day"]["rate"]); 
						$additional_percentage 	 = ($regular_param['payroll_overtime_rest_day']);
						$breakdown_addition 	 += $return->_breakdown_addition["Rest Day"]["rate"];
					}
				

				//Rest Day Over Time
				if ($overtime_float != 0) 
				{
					$return->_breakdown_addition["Rest Day OT"]["time"] = $_time["overtime"] ." (".ctopercent($regular_param['payroll_overtime_rest_overtime']).")"; 
					$return->_breakdown_addition["Rest Day OT"]["rate"] = ($hourly_rate * $overtime_float) * ($regular_param['payroll_overtime_rest_overtime']);
					$return->_breakdown_addition["Rest Day OT"]["hour"] = $_time["overtime"];
					
					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Rest Day OT"]["rate"]; 
					$overtime 			 = $return->_breakdown_addition["Rest Day OT"]["rate"];
					$breakdown_addition  += $overtime;
				}
				//Rest Day Night Differential
				if ($night_diff_float != 0) 
				{
					$return->_breakdown_addition["Rest Day ND"]["time"] = $_time["night_differential"] ." (".ctopercent($regular_param['payroll_overtime_rest_night']).")"; 
					$return->_breakdown_addition["Rest Day ND"]["rate"] = ($hourly_rate * $night_diff_float) * ($regular_param['payroll_overtime_rest_night']);
					$return->_breakdown_addition["Rest Day ND"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Rest Day ND"]["rate"]; 
					$nightdiff 			 = $return->_breakdown_addition["Rest Day ND"]["rate"];
					$breakdown_addition  += $nightdiff;
				}
			}
			else
			{

				if ($compute_type == "hourly") 
				{
					$return->daily_rate = $hourly_rate * $target_float;
					$total_day_income   = $return->daily_rate;
				}
		  		if ($overtime_float != 0) 
				{
					$return->_breakdown_addition["Over Time"]["time"] = $_time["overtime"] ." (".ctopercent($regular_param['payroll_overtime_overtime']).")"; 
					$return->_breakdown_addition["Over Time"]["rate"] = ($hourly_rate * $overtime_float) * ($regular_param['payroll_overtime_overtime']);
					$return->_breakdown_addition["Over Time"]["hour"] = $_time["overtime"];
					
					$total_day_income    = $total_day_income + $return->_breakdown_addition["Over Time"]["rate"]; 
					$overtime 			 = $return->_breakdown_addition["Over Time"]["rate"];
					$breakdown_addition  += $return->_breakdown_addition["Over Time"]["rate"];
				}
				//Regular Night Differential
				if ($night_diff_float != 0) 
				{
					$return->_breakdown_addition["Night Differential"]["time"] = $_time["night_differential"] ." (".ctopercent($regular_param['payroll_overtime_nigth_diff']).")"; 
					$return->_breakdown_addition["Night Differential"]["rate"] = ($hourly_rate * $night_diff_float) * ($regular_param['payroll_overtime_nigth_diff']);
					$return->_breakdown_addition["Night Differential"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Night Differential"]["rate"]; 
					$nightdiff 			 = $return->_breakdown_addition["Night Differential"]["rate"];
					$breakdown_addition  += $return->_breakdown_addition["Night Differential"]["rate"];	
				}
			}
		}
		if($_time['is_holiday'] == 'regular')
		{
			//Legal Holiday with rest day
			if($rest_float > 0)
			{

					if ($compute_type == "daily") 
					{
						$return->daily_rate = 0;
						$return->_breakdown_addition["Legal Holiday Rest Day"]["time"] = "";  //.($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($legal_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Legal Holiday Rest Day"]["rate"] = ( $daily_rate) * ($legal_param['payroll_overtime_rest_day']);
						$return->_breakdown_addition["Legal Holiday Rest Day"]["hour"] = "";

						$total_day_income 	   =  $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"]; 
						$additional_percentage =  ($legal_param['payroll_overtime_rest_day']);
						$breakdown_addition    += $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"];
					}
					else if ($compute_type == "hourly") 
					{
						$return->daily_rate = 0;
						$return->_breakdown_addition["Legal Holiday Rest Day"]["time"] = "";  //.($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($legal_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Legal Holiday Rest Day"]["rate"] = ($target_float) * ($hourly_rate * $legal_param['payroll_overtime_rest_day']);
						$return->_breakdown_addition["Legal Holiday Rest Day"]["hour"] = "";

						$total_day_income 	   =  $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"]; 
						$additional_percentage = ($legal_param['payroll_overtime_rest_day']);
						$breakdown_addition    += $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"];
					}
					else if($compute_type == "monthly")
					{
						$return->_breakdown_addition["Legal Holiday Rest Day"]["time"] = "";  //.($time_spent>=$target_float ? $_time["target_hours"]:$_time["time_spent"]) ." (".ctopercent($legal_param['payroll_overtime_rest_day']).")"; 
						$return->_breakdown_addition["Legal Holiday Rest Day"]["rate"] = $daily_rate * ($legal_param['payroll_overtime_rest_day']);
						$return->_breakdown_addition["Legal Holiday Rest Day"]["hour"] = "";
						
						$total_day_income 	   = $total_day_income + $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"]; 
						$additional_percentage = ($legal_param['payroll_overtime_rest_day']);
						$breakdown_addition    += $return->_breakdown_addition["Legal Holiday Rest Day"]["rate"];
					}
				

				//Legal Holiday Rest Day Over Time
				if ($overtime_float!=0) 
				{
					$return->_breakdown_addition["Legal Holiday Rest Day OT"]["time"] = $_time["overtime"] ." (".ctopercent($legal_param['payroll_overtime_rest_overtime']).")"; 
					$return->_breakdown_addition["Legal Holiday Rest Day OT"]["rate"] = ($hourly_rate * $overtime_float) * ($legal_param['payroll_overtime_rest_overtime']);
					$return->_breakdown_addition["Legal Holiday Rest Day OT"]["hour"] = $_time["overtime"];

					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Legal Holiday Rest Day OT"]["rate"]; 
					$overtime 			 = $return->_breakdown_addition["Legal Holiday Rest Day OT"]["rate"];
					$breakdown_addition  += $overtime;
				}

				//Legal Holiday Rest Day Night Differential
				if ($night_diff_float!=0) 
				{
					$return->_breakdown_addition["Legal Holiday Rest Day ND"]["time"] = $_time["night_differential"] ." (".ctopercent($legal_param['payroll_overtime_rest_night']).")"; 
					$return->_breakdown_addition["Legal Holiday Rest Day ND"]["rate"] = ($hourly_rate * $night_diff_float) * ($legal_param['payroll_overtime_rest_night']);
					$return->_breakdown_addition["Legal Holiday Rest Day ND"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Legal Holiday Rest Day ND"]["rate"]; 
					$nightdiff 			 = $return->_breakdown_addition["Legal Holiday Rest Day ND"]["rate"];
					$breakdown_addition  += $nightdiff;
				}
			}
			else
			{
				//Legal Holiday
					if ($compute_type=="daily" ) 
					{
						if(($legal_param['payroll_overtime_regular']) == 0)
						{
							$return->_breakdown_addition["Legal Holiday"]["time"] = "";
							$return->_breakdown_addition["Legal Holiday"]["rate"] = $daily_rate * ($legal_param['payroll_overtime_regular']);
							$return->_breakdown_addition["Legal Holiday"]["hour"] = "";
							
							$total_day_income 	    = $total_day_income + $return->_breakdown_addition["Legal Holiday"]["rate"];
							$additional_percentage  = ($legal_param['payroll_overtime_regular']);
							$breakdown_addition 	+= $return->_breakdown_addition["Legal Holiday"]["rate"];
						}
						else
						{
							$daily_rate = $daily_true_rate;
							$return->_breakdown_addition["Legal Holiday"]["time"] = ""; 
							$return->_breakdown_addition["Legal Holiday"]["rate"] = $daily_rate * ($legal_param['payroll_overtime_regular']);
							$return->_breakdown_addition["Legal Holiday"]["hour"] = "";

							$total_day_income 		= $total_day_income + $return->_breakdown_addition["Legal Holiday"]["rate"];
							$additional_percentage  = ($legal_param['payroll_overtime_regular']);
							$breakdown_addition 	+= $return->_breakdown_addition["Legal Holiday"]["rate"];
						}
					}
					else if ($compute_type == "hourly")
					{
						$total_day_income = 0;

						if($time_spent != 0)
						{
							$return->daily_rate = $hourly_rate * $target_float;
						}
						else
						{
							$return->daily_rate = 0;
						}

						$return->_breakdown_addition["Legal Holiday"]["time"] = ""; 
						$return->_breakdown_addition["Legal Holiday"]["rate"] = $target_float * ($hourly_rate * $legal_param['payroll_overtime_regular']);
						$return->_breakdown_addition["Legal Holiday"]["hour"] = "";

						$total_day_income 		= $return->daily_rate + $total_day_income + $return->_breakdown_addition["Legal Holiday"]["rate"];
						$additional_percentage  = ($legal_param['payroll_overtime_regular']);
						$breakdown_addition 	+= $return->_breakdown_addition["Legal Holiday"]["rate"];
					}
					else if ($compute_type == "monthly") 
					{
						if(($legal_param['payroll_overtime_regular']) == 0)
						{
							$return->_breakdown_addition["Legal Holiday"]["time"] = "";
							$return->_breakdown_addition["Legal Holiday"]["rate"] = $daily_rate * ($legal_param['payroll_overtime_regular']);
							$return->_breakdown_addition["Legal Holiday"]["hour"] = "";

							$total_day_income 		= $total_day_income + $return->_breakdown_addition["Legal Holiday"]["rate"];
							$additional_percentage 	= ($legal_param['payroll_overtime_regular']);
							$breakdown_addition 	+= $return->_breakdown_addition["Legal Holiday"]["rate"];
						}
						else
						{
							$return->_breakdown_addition["Legal Holiday"]["time"] = ""; 
							$return->_breakdown_addition["Legal Holiday"]["rate"] = $daily_rate * ($legal_param['payroll_overtime_regular']);
							$return->_breakdown_addition["Legal Holiday"]["hour"] = "";

							$total_day_income 	   = $total_day_income + $return->_breakdown_addition["Legal Holiday"]["rate"];
							$additional_percentage = ($legal_param['payroll_overtime_regular']);
							$breakdown_addition    += $return->_breakdown_addition["Legal Holiday"]["rate"];
						}
					}
			    


				if ($overtime_float!=0) 
				{
					//Legal Holiday Over Time
					$return->_breakdown_addition["Legal OT"]["time"] = $_time["overtime"] ." (".ctopercent($legal_param['payroll_overtime_overtime']).")"; 
					$return->_breakdown_addition["Legal OT"]["rate"] = ($hourly_rate * $overtime_float) * ($legal_param['payroll_overtime_overtime']);
					$return->_breakdown_addition["Legal OT"]["hour"] = $_time["overtime"];

					$total_day_income 	= $total_day_income + $return->_breakdown_addition["Legal OT"]["rate"]; 
					$overtime 		    = $return->_breakdown_addition["Legal OT"]["rate"];
					$breakdown_addition += $overtime;
				}
				if ($night_diff_float!=0) 
				{
					//Legal Holiday Night Differential
					$return->_breakdown_addition["Legal Holiday ND"]["time"] = $_time["night_differential"] ." (".ctopercent($legal_param['payroll_overtime_nigth_diff']).")"; 
					$return->_breakdown_addition["Legal Holiday ND"]["rate"] = ($hourly_rate * $night_diff_float) * ($legal_param['payroll_overtime_nigth_diff']);
					$return->_breakdown_addition["Legal Holiday ND"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	= $total_day_income + $return->_breakdown_addition["Legal Holiday ND"]["rate"]; 
					$nightdiff 			= $return->_breakdown_addition["Legal Holiday ND"]["rate"];
					$breakdown_addition += $nightdiff;
				}
			}
		}
		if ($_time['is_holiday'] == 'special')
		{
			//special Holiday with rest day
			if($rest_float > 0)
			{
				if ($time_spent != 0) 
				{					
					if ($compute_type == "daily") 
					{
						$total_day_income = 0;
						$return->_breakdown_addition["Special Holiday Rest Day"]["time"] = ""; 
						$return->_breakdown_addition["Special Holiday Rest Day"]["rate"] = $daily_rate + ($daily_rate * (($special_param['payroll_overtime_rest_day'])-1));
						$return->_breakdown_addition["Special Holiday Rest Day"]["hour"] = "";
						
						$total_day_income 	= $total_day_income + $return->_breakdown_addition["Special Holiday Rest Day"]["rate"]; 
						$breakdown_addition += $return->_breakdown_addition["Special Holiday Rest Day"]["rate"];
						// $additional_percentage = ($special_param['payroll_overtime_rest_day']);
					}
					else if ($compute_type == "hourly") 
					{
						$return->daily_rate = 0;
						$total_day_income = $target_float * $hourly_rate;
						$return->_breakdown_addition["Special Holiday Rest Day"]["time"] = ""; 
						$return->_breakdown_addition["Special Holiday Rest Day"]["rate"] = $total_day_income + ($target_float * ($hourly_rate * (($special_param['payroll_overtime_rest_day'])-1)));
						$return->_breakdown_addition["Special Holiday Rest Day"]["hour"] = "";
						
						$total_day_income 	=  $return->daily_rate + $return->_breakdown_addition["Special Holiday Rest Day"]["rate"]; 
						$breakdown_addition += $return->_breakdown_addition["Special Holiday Rest Day"]["rate"];
						// $additional_percentage = ($special_param['payroll_overtime_rest_day']);
					}
					else if ($compute_type == "monthly") 
					{
						$return->_breakdown_addition["Special Holiday Rest Day"]["time"] = "" ; 
						$return->_breakdown_addition["Special Holiday Rest Day"]["rate"] = $daily_rate * ($special_param['payroll_overtime_rest_day']);
						$return->_breakdown_addition["Special Holiday Rest Day"]["hour"] = "";

						$total_day_income 		= $total_day_income + $return->_breakdown_addition["Special Holiday Rest Day"]["rate"]; 
						$additional_percentage 	= ($special_param['payroll_overtime_rest_day']);
						$breakdown_addition 	+= $return->_breakdown_addition["Special Holiday Rest Day"]["rate"];
					}
				}

				//Special Holiday Rest Day Over Time
				if ($overtime_float != 0) 
				{
					$return->_breakdown_addition["Special Holiday Rest Day OT"]["time"] = $_time["overtime"] ." (".ctopercent($special_param['payroll_overtime_rest_overtime']).")"; 
					$return->_breakdown_addition["Special Holiday Rest Day OT"]["rate"] = ($hourly_rate * $overtime_float) * ($special_param['payroll_overtime_rest_overtime']);
					$return->_breakdown_addition["Special Holiday Rest Day OT"]["hour"] = $_time["overtime"];
					
					$total_day_income 	 = $total_day_income + $return->_breakdown_addition["Special Holiday Rest Day OT"]["rate"]; 
					$overtime            = ($hourly_rate * $overtime_float) * ($special_param['payroll_overtime_rest_overtime']);
					$breakdown_addition  += $overtime;
				}

				//Special Holiday Rest Day Night Differential
				if ($night_diff_float != 0) 
				{
					$return->_breakdown_addition["Special Holiday Rest Day ND"]["time"] = $_time["night_differential"] ." (".ctopercent($special_param['payroll_overtime_rest_night']).")"; 
					$return->_breakdown_addition["Special Holiday Rest Day ND"]["rate"] = ($hourly_rate * $night_diff_float) * ($special_param['payroll_overtime_rest_night']);
					$return->_breakdown_addition["Special Holiday Rest Day ND"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	= $total_day_income + $return->_breakdown_addition["Special Holiday Rest Day ND"]["rate"]; 
					$nightdiff 			= ($hourly_rate * $night_diff_float) * ($special_param['payroll_overtime_rest_night']);
					$breakdown_addition += $nightdiff;
				}
			}
			//Special Holiday
			else
			{
				if ($time_spent != 0) 
				{
					if ($compute_type == "daily")
					{
						// $total_day_income = 0;
						$return->_breakdown_addition["Special Holiday"]["time"] = ""; 
						$return->_breakdown_addition["Special Holiday"]["rate"] = $daily_rate * (($special_param['payroll_overtime_regular']) - 1);
						$return->_breakdown_addition["Special Holiday"]["hour"] = "";
						
						$total_day_income 	= $total_day_income + $return->_breakdown_addition["Special Holiday"]["rate"];
						$breakdown_addition += $return->_breakdown_addition["Special Holiday"]["rate"];
						// $additional_percentage =  ($special_param['payroll_overtime_regular']);
					}
					else if ($compute_type == "hourly")
					{
						$return->daily_rate = $target_float * $hourly_rate;
						$return->_breakdown_addition["Special Holiday"]["time"] = ""; 
						$return->_breakdown_addition["Special Holiday"]["rate"] = $target_float * ($hourly_rate * (($special_param['payroll_overtime_regular']) - 1));
						$return->_breakdown_addition["Special Holiday"]["hour"] = "";
						
						$total_day_income 	= $return->daily_rate + $return->_breakdown_addition["Special Holiday"]["rate"];
						$breakdown_addition += $return->_breakdown_addition["Special Holiday"]["rate"];
						// $additional_percentage =  ($special_param['payroll_overtime_regular']);
					}
					else if ($compute_type=="monthly") 
					{
						$return->_breakdown_addition["Special Holiday"]["time"] = ""; 
						$return->_breakdown_addition["Special Holiday"]["rate"] = $daily_rate * (($special_param['payroll_overtime_regular'])-1);
						$return->_breakdown_addition["Special Holiday"]["hour"] = "";
						
						$total_day_income 		= $total_day_income + $return->_breakdown_addition["Special Holiday"]["rate"];
						//-1 because if deducted by late,undertime it will be base in employee's hourly rate
						$additional_percentage =  (($special_param['payroll_overtime_regular']) -1 );
						$breakdown_addition    += $return->_breakdown_addition["Special Holiday"]["rate"];
					}
				}
				//Special Holiday Over Time
				if ($overtime_float != 0) 
				{
					$return->_breakdown_addition["Special Holiday OT"]["time"] = $_time["overtime"] ." (".ctopercent($special_param['payroll_overtime_overtime']).")"; 
					$return->_breakdown_addition["Special Holiday OT"]["rate"] = ($hourly_rate * $overtime_float) * ($special_param['payroll_overtime_overtime']);
					$return->_breakdown_addition["Special Holiday OT"]["hour"] = $_time["overtime"];
					$total_day_income 	= $total_day_income + $return->_breakdown_addition["Special Holiday OT"]["rate"]; 
					$overtime 			=  $return->_breakdown_addition["Special Holiday OT"]["rate"];
					$breakdown_addition += $overtime;
				}
				//Special Holiday Night Differential
				if ($night_diff_float != 0) 
				{
					$return->_breakdown_addition["Special Holiday ND"]["time"] = $_time["night_differential"] ." (".ctopercent($special_param['payroll_overtime_nigth_diff']).")"; 
					$return->_breakdown_addition["Special Holiday ND"]["rate"] = ($hourly_rate * $night_diff_float) * ($special_param['payroll_overtime_nigth_diff']);
					$return->_breakdown_addition["Special Holiday ND"]["hour"] = $_time["night_differential"];
					
					$total_day_income 	= $total_day_income + $return->_breakdown_addition["Special Holiday ND"]["rate"]; 
					$nightdiff 			= $return->_breakdown_addition["Special Holiday ND"]["rate"];
					$breakdown_addition += $nightdiff; 
				}
			}

		} 

		$subtotal_after_addition = $total_day_income;
		//compute cola
		$cola = Payroll2::compute_income_day_pay_cola($_time , $daily_rate, $group_id , $cola , $compute_type, $leave_float);


		/*START BREAKDOWN DEDUCTIONS */

		$late_float			= Self::time_float($_time['late']);
		$undertime_float	= Self::time_float($_time['undertime']);
		$absent_float		= 0;

	
		//no time in monthly
		if($time_spent == 0 && $compute_type=="monthly")
		{

			if($_time['is_holiday'] == 'special')
			{
				$_time["is_absent"] = false;
			}
		}
		
		//no time in daily
		if($time_spent == 0 && $compute_type == "daily")
		{
			if($_time['is_holiday'] == 'special' || $_time['is_holiday'] == 'regular' || $_time['day_type'] == 'extra_day' || $_time['day_type'] == 'rest_day')
			{
				$_time["is_absent"] = false;
			}
		}

		if($time_spent == 0 && $compute_type=="hourly")
		{

			if($_time['is_holiday'] == 'special')
			{
				$_time["is_absent"] = false;
			}
		}
		
		if(($_time['day_type'] == 'rest_day') && $time_spent == 0 || ($_time['day_type'] == 'extra_day') && $time_spent == 0)
		{
			$_time["is_absent"] = false;
			$return->daily_rate = 0;
			$total_day_income = 0;
			$subtotal_after_addition = 0;

		}

		//deducted if absent
		if($_time["is_absent"] == true)
		{
			$absent_deduction = $daily_rate;

			if($compute_type == "hourly")
			{
				$absent_deduction = $hourly_rate * $target_float;
			}

			$total_day_income 	  = $total_day_income - $absent_deduction;
			$absent_float 		  = 1;
			$absent 			  = $absent_deduction;
			$breakdown_deduction  += $absent_deduction;

			$return->_breakdown_deduction["absent"]["time"] = ""; 
			$return->_breakdown_deduction["absent"]["rate"] = $absent_deduction; 
			$return->_breakdown_deduction["absent"]["hour"] = "";	
		}
		elseif($_time["is_absent"] == false && ($_time['day_type'] != 'rest_day'))
		{
			/*Start Undertime Deduction Computation*/
			if ($undertime_float != 0) 
			{
				$undertime_rate = Self::compute_undertime_deduction($_time['undertime'], $group->payroll_under_time_category, $undertime_float, $daily_rate, $hourly_rate, $hourly_rate_employee_salary, $additional_percentage, $group);
				
				// if ($compute_type == "hourly") 
				// {
				// 	$undertime_rate = 0;
				// }

				$return->_breakdown_deduction["undertime"]["rate"] = $undertime_rate;
				$return->_breakdown_deduction["undertime"]["time"] = $_time['undertime'];
				$return->_breakdown_deduction["undertime"]["hour"] = $_time['undertime'];

				$total_day_income 	 = $total_day_income - $undertime_rate;
				$breakdown_deduction += $undertime_rate;
			}
			/*End Undertime Deduction Computation*/
			
			/*Start late Deduction Computation*/
			if ($late_float != 0)
			{
				$late_rate = Self::compute_late_deduction($_time['late'], $group->payroll_late_category, $late_float, $daily_rate, $hourly_rate, $hourly_rate_employee_salary, $additional_percentage, $group);
				
				// if ($compute_type == "hourly") 
				// {
				// 	$late_rate = 0;
				// }

				$return->_breakdown_deduction["late"]["rate"] = $late_rate;
				$return->_breakdown_deduction["late"]["time"] = $_time['late']; 
				$return->_breakdown_deduction["late"]["hour"] = $_time['late']; 

				$total_day_income 		 = $total_day_income - $late_rate;
				$breakdown_deduction 	 += $late_rate;
			}
			/*End late Deduction Computation*/
		}
		/*END BREAKDOWN DEDUCTIONS*/

		$return->subtotal_after_addition	= $subtotal_after_addition;
		if($time_spent > $target_float)
		{
			$return->rendered_days 		    = 1;
		}
		else
		{
			$return->rendered_days 		    = @($time_spent/$target_float);
		}
		$return->cola						= $cola->cola_day_pay;
		$return->cola_daily 				= $cola->cola_daily;
		$return->total_day_income_plus_cola = $cola->cola_plus_daily_rate;
		$return->total_day_income			= $total_day_income;
		$return->total_day_cola 			= 0;
		$return->total_day_cola_deduction 	= $cola->cola_daily_deduction;
		$return->total_day_cola_addition 	= $cola->cola_daily_addition;
		$return->cola_percentile			= $cola->cola_percentile;
		$return->total_day_basic			= $subtotal_after_addition - ($breakdown_deduction + $breakdown_addition);
		$return->breakdown_addition 		= $breakdown_addition;
		$return->breakdown_deduction		= $breakdown_deduction; 
		$return->rendered_tardiness			= @(($late_float + $undertime_float) / $target_float) + $absent_float;

		return $return;
	}


	public static function compute_undertime_deduction($undertime_hours = "00:00:00", $undertime_category = "Base on Salary", $undertime_float = 0, $daily_rate = 0, $hourly_rate = 0, $hourly_rate_employee_salary = 0, $additional_percentage = 0, $group)
	{
		$undertime_rate = 0;

		if ($undertime_category == 'Base on Salary') 
		{
			//Normal late rate
			$undertime_rate = ($undertime_float * $hourly_rate) * $additional_percentage;

			//Monthly Rate hourly deduction if hourly_rate_employee_salary is not equal to zero
			if ($group->payroll_group_salary_computation == "Monthly Rate" && $hourly_rate_employee_salary != 0) 
			{
				$undertime_rate = ($undertime_float * $hourly_rate_employee_salary)  * $additional_percentage;
			}
		}
		else if ($undertime_category == 'Custom')
		{
			$undertime_interval  = $group->payroll_under_time_interval;
			$undertime_deduction = $group->payroll_under_time_deduction;
			$undertime_minutes   = Self::convert_time_in_minutes($undertime_hours);
			
			if ($group->payroll_under_time_parameter == "Hour") 
			{
				$undertime_interval = $undertime_interval * 60;
			}
			if ($undertime_minutes >= $undertime_interval) 
			{
				$undertime_multiplier = (int) @($undertime_minutes / $undertime_interval);
				$undertime_percentage_deduction = ($undertime_multiplier * $undertime_deduction);
				$undertime_rate = $daily_rate * $undertime_percentage_deduction;
			}
		}

		return $undertime_rate;
	}


	public static function compute_late_deduction($late_hours = "00:00:00", $late_category = "Base on Salary", $late_float = 0, $daily_rate = 0, $hourly_rate = 0, $hourly_rate_employee_salary = 0, $additional_percentage = 0, $group)
	{
		$late_rate = 0;

		if ($late_category == 'Base on Salary') 
		{
			if($late_float != 0)
			{
				//Normal late rate
				$late_rate = ($late_float * $hourly_rate)  * $additional_percentage;

				//Monthly Rate hourly deduction if hourly_rate_employee_salary is not equal to zero
				if ($group->payroll_group_salary_computation == "Monthly Rate" && $hourly_rate_employee_salary != 0) 
				{
					$late_rate = ($late_float * $hourly_rate_employee_salary)  * $additional_percentage;
				}
			}
		}
		else if ($late_category == 'Custom')
		{

			$late_interval  = $group->payroll_late_interval;
			$late_deduction = $group->payroll_late_deduction;
			$late_minutes 	= Self::convert_time_in_minutes($late_hours);
			
			if ($group->payroll_late_parameter == "Hour") 
			{
				$late_interval = $late_interval * 60;
			}

			if ($late_minutes >= $late_interval) 
			{
				$late_multiplier = (int) @($late_minutes / $late_interval);
				$late_percentage_deduction = ($late_multiplier * $late_deduction);
				$late_rate = $daily_rate * $late_percentage_deduction;
			}
		}

		return $late_rate;
	}
	
	public static function cutoff_compute_gross_pay($compute_type, $cutoff_rate, $cutoff_cola, $cutoff_target_days = 0,  $_date_compute)
	{
		$return = new stdClass();
		$return->cutoff_income_plus_cola = 0;
		if($compute_type == "daily")
		{ 
			$cutoff_income_plus_cola = 0;
			$cutoff_income			 = 0;
			$cutoff_cola			 = 0;
			$cutoff_basic			 = 0;
			$render_days			 = 0;
			
			foreach($_date_compute as $date => $date_compute)
			{
				if(!isset($date_compute->compute))
				{
					dd("Kindly enter data on timesheet before opening the summary.");
				}

				$cutoff_income_plus_cola += $date_compute->compute->total_day_income_plus_cola;
				$cutoff_income			 += $date_compute->compute->total_day_income;
				$cutoff_cola			 += $date_compute->compute->cola;
				$cutoff_basic			 += $date_compute->compute->total_day_basic;
				$render_days			 += $date_compute->compute->rendered_days;
			}

			// $return->cutoff_rate     		  = $cutoff_rate;
			$return->cutoff_income_plus_cola  = $cutoff_income_plus_cola;
			$return->cutoff_income 			  = $cutoff_income;
			$return->cutoff_cola			  = $cutoff_cola;
			$return->cutoff_basic			  = $cutoff_basic;
			$return->render_days			  = $render_days;
		}
		else if($compute_type == "hourly")
		{
			$cutoff_income_plus_cola = 0;
			$cutoff_income			 = 0;
			$cutoff_cola			 = 0;
			$cutoff_basic			 = 0;
			$render_days			 = 0;
			
			foreach($_date_compute as $date => $date_compute)
			{
				if(!isset($date_compute->compute))
				{
					dd("Kindly enter data on timesheet before opening the summary.");
				}

				$cutoff_income_plus_cola += $date_compute->compute->total_day_income_plus_cola;
				$cutoff_income			 += $date_compute->compute->total_day_income;
				$cutoff_cola			 += $date_compute->compute->cola;
				$cutoff_basic			 += $date_compute->compute->total_day_basic;
				$render_days			 += $date_compute->compute->rendered_days;
			}

			// $return->cutoff_rate     		  = $cutoff_rate;
			$return->cutoff_income_plus_cola  = $cutoff_income_plus_cola;
			$return->cutoff_income 			  = $cutoff_income;
			$return->cutoff_cola			  = $cutoff_cola;
			$return->cutoff_basic			  = $cutoff_basic;
			$return->render_days			  = $render_days;
		}
		else if ($compute_type=="monthly") 
		{
			$breakdown_deduction		 = 0;
			$breakdown_addition 		 = 0;
			$breakdown_subtotal 		 = 0;
			$rendered_tardiness 		 = 0;

			foreach($_date_compute as $date => $date_compute)
			{
				if(!isset($date_compute->compute))
				{
					dd("Kindly enter data on timesheet before opening the summary.");
				}
				$breakdown_deduction += $date_compute->compute->breakdown_deduction;
				$breakdown_addition  += $date_compute->compute->breakdown_addition;
				$rendered_tardiness  += $date_compute->compute->rendered_tardiness;
				$breakdown_subtotal  += ($date_compute->compute->breakdown_addition - $date_compute->compute->breakdown_deduction);
			}
			
			$return->total_deduction = $breakdown_deduction;
			$return->total_addition  = $breakdown_addition;
			$return->total_subtotal  = $breakdown_subtotal;
			$return->cutoff_rate     = $cutoff_rate;
			
			//
			$cutoff_income_plus_cola = $cutoff_rate + $cutoff_cola + $breakdown_addition;
			$cutoff_income_plus_cola = $cutoff_income_plus_cola - $breakdown_deduction;
			

			//COMPUTE CUTOFF INCOME AND CUTOFF COLA
			$cola_percentile	= @($cutoff_cola / ($cutoff_rate + $cutoff_cola));
			$cutoff_income		= $cutoff_rate + $breakdown_addition; //$cutoff_income_plus_cola * (1 - $cola_percentile);
			/*$cutoff_cola		= $cutoff_income_plus_cola * $cola_percentile;*/
			
			//COMPUTE CUTOFF BASIC

			$deduction	  = $breakdown_deduction; //error(commented) * (1 - $cola_percentile);

			$cutoff_basic = $cutoff_rate - $deduction;
			
			$cutoff_target_days -= $rendered_tardiness;
			
			$return->deduction_cola 		  = 0;
			$return->cutoff_income_plus_cola  = $cutoff_income_plus_cola;
			$return->cutoff_income 			  = $cutoff_income;
			$return->cutoff_cola			  = $cutoff_cola;
			$return->cutoff_basic			  = $cutoff_basic;
			$return->render_days			  = $cutoff_target_days;
	
		}
		else if($compute_type=="fix") 
		{
			
		}
		//get breakdown addition summary and break down deduction summary
		foreach($_date_compute as $date => $date_compute)
		{

			if(isset($date_compute->compute->_breakdown_addition))
			{
				foreach($date_compute->compute->_breakdown_addition as $lbl => $badd)
				{
					if(isset($return->_breakdown_addition_summary[$lbl]))
					{
						$return->_breakdown_addition_summary[$lbl] += $badd["rate"];
						$return->_breakdown_addition_summary_time[$lbl] .= "<br>". "<b>" . payroll_date_format($date) . "</b> " . ($badd["time"] != "" ?  " ~ " .$badd["time"] : "")  . " ~ " . payroll_currency($badd["rate"]);	

					}
					else
					{
						$return->_breakdown_addition_summary[$lbl] = $badd["rate"];
						$return->_breakdown_addition_summary_time[$lbl] = "<b>" . payroll_date_format($date) . "</b> " .  ($badd["time"] != "" ?  " ~ " .$badd["time"] : "")  . " ~ " . payroll_currency($badd["rate"]);
						
					}
				}
			}
			
			if(isset($date_compute->compute->_breakdown_deduction))
			{
				foreach($date_compute->compute->_breakdown_deduction as $lbl => $bded)
				{
					if(isset($return->_breakdown_deduction_summary[$lbl]))
					{
						$return->_breakdown_deduction_summary[$lbl] += $bded["rate"];
					}
					else
					{
						$return->_breakdown_deduction_summary[$lbl] = $bded["rate"];
					}
				}
			}
		}
	
		return $return;
	}

	public static function compute_income_month_pay()
	{
		
	}
	
	public static function compute_income_day_pay_cola ($_time = array(), $daily_rate = 0, $group_id = 0, $cola = 0, $compute_type="", $leave_float = 0)
	{
		$return = new stdClass();
		$total_day_income 		= $daily_rate ;
		$target_float 			= Self::time_float($_time['target_hours']);
		$daily_rate_plus_cola	= $daily_rate + $cola;
		$cola_rate_per_hour 	= @($cola/$target_float);
		$daily_cola 			= $cola;
		
		/* GET INITIAL DATA */
		$param_rate 			= Tbl_payroll_overtime_rate::where('payroll_group_id', $group_id)->get()->toArray();
		$collection 			= collect($param_rate);
		$regular_param 			= $collection->where('payroll_overtime_name','Regular')->first();
		$legal_param 			= $collection->where('payroll_overtime_name','Legal Holiday')->first();
		$special_param 			= $collection->where('payroll_overtime_name','Special Holiday')->first();
		$group 					= Tbl_payroll_group::where('payroll_group_id', $group_id)->first();
		$cola_daily_deduction	= 0;
		$cola_daily_addition	= 0;
		
		/* BREAKDOWN ADDITIONS */
		$time_spent		 		= Self::time_float($_time['time_spent']);
		
		if($_time['is_holiday'] == 'regular')
		{
			if($time_spent != 0)
			{
				$cola_daily_addition = $cola;
				$cola =	$cola * 2;
			}
		}
		if($time_spent==0 && $_time["day_type"] != "rest_day" && $_time["day_type"] != "extra_day" && $leave_float == 0)
		{
			$cola_daily_deduction = $cola;		
		}

		if ($leave_float != 0) 
		{
			$cola_daily_deduction = $cola;
			$cola = $cola - $cola;
			$daily_cola = $daily_cola - $daily_cola;
		}
		
		//for daily fixed cola
		if ($time_spent==0) 
		{
			$daily_cola = $daily_cola - $daily_cola;
			//debugging report: from else if in top to here
			$cola = $cola - $cola;
			//debugging report: from else if in top to here
		}
		
		/*breakdown deduction*/
		$late_float			= Self::time_float($_time['late']);
		$undertime_float	= Self::time_float($_time['undertime']);

		if ($late_float != 0) 
		{
			$cola_daily_deduction = ($late_float * $cola_rate_per_hour);
			$cola = $cola - ($late_float * $cola_rate_per_hour);
		}

		if ($undertime_float!=0) 
		{
			$cola_daily_deduction = ($undertime_float * $cola_rate_per_hour);
			$cola = $cola - ($undertime_float * $cola_rate_per_hour);
		}

		if ($cola < 0) 
		{
			$cola = 0;
		}

		$return->cola_daily 		  = $daily_cola;
		$return->cola_day_pay 		  = $cola;
		$return->cola_plus_daily_rate = $daily_rate+$cola;
		$return->cola_daily_deduction = $cola_daily_deduction;
		$return->cola_daily_addition  = $cola_daily_addition;
		$return->cola_percentile 	  = @($cola / ($daily_rate+$cola));
		
		return $return;
	}


	public static function compute_income_day_pay_monthly_fixed_cola($_time = array(), $daily_rate = 0, $group_id = 0, $cola = 0, $compute_type="")
	{
		$return = new stdClass();
		$total_day_income 		= $daily_rate ;
		$target_float 			= Self::time_float($_time['target_hours']);
		$daily_rate_plus_cola	= $daily_rate + $cola;
		$cola_rate_per_hour 	= @($cola/$target_float);
		
		/* GET INITIAL DATA */
		$param_rate 			= Tbl_payroll_overtime_rate::where('payroll_group_id', $group_id)->get()->toArray();
		$collection 			= collect($param_rate);
		$regular_param 			= $collection->where('payroll_overtime_name','Regular')->first();
		$legal_param 			= $collection->where('payroll_overtime_name','Legal Holiday')->first();
		$special_param 			= $collection->where('payroll_overtime_name','Special Holiday')->first();
		$group 					= Tbl_payroll_group::where('payroll_group_id', $group_id)->first();
		
		/* BREAKDOWN ADDITIONS */
		$time_spent		 		= Self::time_float($_time['time_spent']);
	
		$return->cola_day_pay = $cola;
		$return->cola_plus_daily_rate = $daily_rate+$cola;
		$return->cola_percentile = @($cola/ ($daily_rate+$cola));
		
		return $return;
		
	}


	/* GLOBALS */
	public static function time_check_if_exist_between($check_exist, $between_in, $between_out)
	{
		$if_check_exist=false;
		$time_exist_array = explode(":", $check_exist);
		$time_in_array = explode(":", $between_in);
		$time_out_array = explode(":",$between_out);

		$check_exist =($time_exist_array[0]*3600) + ($time_exist_array[1]*60) + $time_exist_array[2];
		$between_in = ($time_in_array[0]*3600) + ($time_in_array[1]*60) + $time_in_array[2];
		$between_out = ($time_out_array[0]*3600) + ($time_out_array[1]*60) + $time_out_array[2];

		if (($check_exist>=$between_in)&&($check_exist<=$between_out)) 
		{
			$if_check_exist=true;
		}

		return $if_check_exist;
	}
	
		//compute target time
	public static function target_hours($_shift)
	{
		$target_hours = "00:00";
		if($_shift)
		{
			foreach ($_shift as $shift) 
			{
				//target time computation
				$target_hours=Payroll::sum_time($target_hours,Payroll::time_diff($shift->shift_in,$shift->shift_out));
			}
		}

		return $target_hours;
	} 


	public static function use_leave()
	{
		
	}

	//night differential 10pm to 6am militiary time
	public static function night_differential_computation($_time,$testing=false)
	{
		$night_differential = "00:00";
		
		if(!$_time)
		{
		
		}
		else
		{
			foreach ($_time as $time) 
			{
				echo $testing == true ? "<hr><br><br> TIME IN - ".$time->time_in." vs TIME OUT - ".$time->time_out."<br><br>":"";
				$time_in_integer = explode(":", $time->time_in);
				$time_out_integer = explode(":", $time->time_out);
				
				$time_in_integer = (int)$time_in_integer[0]."".$time_in_integer[1];
				$time_out_integer = (int)$time_out_integer[0]."".$time_out_integer[1];
				
				if ($time->auto_approved==2) 
				{
					continue;
				}
				
				/*START night differential computation*/
				if((2200<=$time_in_integer)&&(2400>=$time_out_integer))
				{
					echo $testing == true ? "<b>NIGTH DIFFERENTIAL</b> answer: <b>".Payroll2::time_difference($time->time_out,$time->time_in)."</b> hour night differential reason time in and out is in between 10:00pm to 12:00nn":"";
					$night_differential = Payroll::sum_time($night_differential,Payroll2::time_difference($time->time_out,$time->time_in));
				}

				//if time out was after 10:00pm
				else if (2200<$time_out_integer) 
				{
		
					echo $testing == true ? "<b>NIGTH DIFFERENTIAL</b> answer: <b>".Payroll2::time_difference($time->time_out,"22:00")."</b> hour night differential reason time out was after 10:00pm":"";
					$night_differential = Payroll::sum_time($night_differential,Payroll2::time_difference($time->time_out,"22:00"));
				}
		
				//if time in and timeout is in between 12:00nn to 6:00am
				if(((600>$time_in_integer)&&(0000<$time_out_integer))&&(!(600<$time_out_integer)))
				{
					echo $testing == true ? "<b>NIGTH DIFFERENTIAL</b> answer: <b>".Payroll2::time_difference($time->time_out,$time->time_in)."</b> hour night differential reason time in and out is in between 12:00nn to 6:00am":"";
					$night_differential = Payroll::sum_time($night_differential,Payroll2::time_difference($time->time_out,$time->time_in));
				}

				//time in start before 6:00am and time out is after 6am
				else if ((600>$time_in_integer)&&(600<=$time_out_integer)) 
				{
					echo $testing == true ? "<b>NIGTH DIFFERENTIAL</b> answer: <b>".Payroll2::time_difference("06:00",$time->time_in)."</b> hour night differential reason time in start before 06:00 am":"";
					$night_differential = Payroll::sum_time($night_differential,Payroll2::time_difference("06:00",$time->time_in));
				}
			}
		}

		return Payroll2::convert_to_24_hour($night_differential);
	}

	public static function convert_to_12_hour($strDate)
	{
		return date("h:i A", strtotime($strDate));
	}
	

	public static function convert_to_24_hour($strDate)
	{
		return date("H:i:s", strtotime($strDate));
	}


	public static function time_shift_output($time, $_output, $index, $time_in, $time_out, $auto_approved, $reason = "",$status_time_sched = "",$late="00:00:00",$undertime="00:00:00",$overtime="00:00:00")
	{
		
		$_output[$index] = new stdClass();
		$_output[$index]->time_in = $time_in;
		$_output[$index]->time_out = $time_out;
		$_output[$index]->auto_approved = $auto_approved;
		$_output[$index]->reason=$reason;
		$_output[$index]->status_time_sched = $status_time_sched;
		$_output[$index]->late = $late;
		$_output[$index]->undertime = $undertime;
		$_output[$index]->overtime = $overtime;
		$_output[$index]->payroll_time_sheet_record_id = 0;

		if(isset($time->payroll_time_sheet_record_id))
		{
			$_output[$index]->payroll_time_sheet_record_id = $time->payroll_time_sheet_record_id;
		}

		if(isset($time->payroll_time_sheet_auto_approved))
		{
			if ($auto_approved != 2) 
			{
				$_output[$index]->auto_approved = $time->payroll_time_sheet_auto_approved;
			}
		}

		return $_output;
	}

	public static function flexi_time_shift_output($time, $_output, $index, $time_in, $time_out, $auto_approved, $reason = "", $status_time_sched = "", $over_time="00:00:00", $undertime = "00:00:00")
	{

		$_output[$index] = new stdClass();
		$_output[$index]->time_in = $time_in;
		$_output[$index]->time_out = $time_out;
		$_output[$index]->auto_approved = $auto_approved;
		$_output[$index]->reason=$reason;
		$_output[$index]->status_time_sched = $status_time_sched;
		$_output[$index]->overtime = $over_time;
		$_output[$index]->undertime = $undertime;
		$_output[$index]->late = "00:00:00";

		$_output[$index]->payroll_time_sheet_record_id = 0;

		if(isset($time->payroll_time_sheet_record_id))
		{
			$_output[$index]->payroll_time_sheet_record_id = $time->payroll_time_sheet_record_id;
		}

		if(isset($time->payroll_time_sheet_auto_approved))
		{
			$_output[$index]->auto_approved = $time->payroll_time_sheet_auto_approved;
		}

		return $_output;
	}

	public static function sum_time($time_1 = '00:00', $time_2 = '00:00')
	{
		$extime1 = explode(':', $time_1);
		$extime2 = explode(':', $time_2);
	
		$hour = $extime1[0] + $extime2[0];
		$min = 0;
		if(isset($extime1[1]) && isset($extime2[1]))
		{
			$min = $extime1[1] + $extime2[1];
		}

		return Payroll::return_time($hour, $min);
	}

	public static function sum_two_time($time_one,$time_two)
	{
		$time_one = explode(":", $time_one);
		$time_two = explode(":", $time_two);
		$time_one = ($time_one[0]*60) + $time_one[1];
		$time_two = ($time_two[0]*60) + $time_two[1];

		$sum_time = $time_one+$time_two;
		$sum_time = ($sum_time/60) .":".($sum_time%60);
		return $sum_time;
	}


	public static function time_difference($time_1 = '00:00', $time_2 = '00:00')
	{
		$extime1 = explode(':', $time_1);
		$extime2 = explode(':', $time_2);

		$hour = $extime1[0] - $extime2[0];
		$min  = 0;
		
		if(isset($extime1[1]) && isset($extime2[1]))
		{
			$min = $extime1[1] - $extime2[1];
		}
		
		return Payroll::return_time($hour, $min);
	}

	public static function divide_time_in_half($time_1 = '00:00:00')
	{
		$extime1 = explode(':', $time_1);
		$hour;
		$min = 0;
		if (($extime1[0] %2)==1) 
		{
			$hour = (int)($extime1[0] /2) ;
			$min += 30;
		}
		else
		{
			$hour = ($extime1[0] /2);
		}

		if(isset($extime1[1]))
		{
			$min = $min+((int)$extime1[1] / 2);
		}

		return Payroll::return_time($hour, $min).":00";
	}

	public static function minus_time($minus_time_minutes,$time)
	{
		$time_in_minutes="00:00:00";
		if ($minus_time_minutes<=Payroll2::convert_time_in_minutes($time)) 
		{
			$time_in_minutes = date("H:i:s", strtotime(-($minus_time_minutes)." minutes", strtotime($time)));
		}
		return $time_in_minutes;
	}

	public static function diff_time_in_minutes($time_start,$time_end)
	{
		$start_date = new DateTime('$time_start');
		$since_start = $start_date->diff(new DateTime('$time_end'));
		$minutes="";
		$minutes += $since_start->h * 60;
		$minutes += $since_start->i;
		return $minutes;
	}

	public static function time_float($time = '00:00')
	{
		$extime = explode(':', $time);

		if(!isset($extime[1]))
		{
			return 0;
		}
		else
		{
			$hour = $extime[0];
			$min = $extime[1] / 60;
			return $hour + $min;
		}
	}

	public static function convert_time_in_minutes($time)
	{
		$time = explode(":", $time);
		if(!isset($time[1]))
		{
			$time[1] = 0;
		}
		$time = ($time[0] * 60.0) + ($time[1] * 1.0);
		return $time;
	}

	
	public static function float_time($float = 0)
	{
		$hour = intval($float);
		$min = round(($float - $hour) * 60);
		return Payroll::return_time($hour, $min);
	}

	/*  get net salary */
	// tbl_payroll_gross
	// - payroll_gross_id
	// - payroll_period_company_id
	// - employee_id
	// - basic_pay
	// - gross_pay

	public static function cutoff_compute_net_pay($payroll_period_company_id, $employee_id, $basic_pay = 0, $gross_pay = 0, $rendered_days = 0)
	{
		$return 		 = array();
		$return['obj']	 = array();
		$total_deminimis = 0;
		$total_deduction = 0;

		/* get period details */
		$date_query 		= Tbl_payroll_period_company::sel($payroll_period_company_id)->first();
		$start_date 		= $date_query->payroll_period_start;
		$end_date 			= $date_query->payroll_period_end;
		$period_category 	= $date_query->payroll_period_category;

		/* get employee contract */
		$group 	= Tbl_payroll_employee_contract::selemployee($employee_id, $start_date)
												->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
		                                        ->first();
		$shop_id = $group->shop_id;

		/* GET EMPLOYEE SALARY */
		$salary 			= Tbl_payroll_employee_salary::selemployee($employee_id, $start_date)->first();
		if($salary == null)
		{
			$salary = new stdClass();
			$salary->payroll_employee_salary_minimum_wage 	= 0;
			$salary->payroll_employee_salary_monthly 		= 0;
			$salary->payroll_employee_salary_daily 			= 0;
			$salary->payroll_employee_salary_taxable 		= 0;
			$salary->payroll_employee_salary_sss 			= 0;
			$salary->payroll_employee_salary_pagibig 		= 0;
			$salary->payroll_employee_salary_philhealth 	= 0;
			$salary->is_deduct_tax_default 					= 0;
			$salary->deduct_tax_custom 						= 0;
			$salary->is_deduct_sss_default 					= 0;
			$salary->deduct_sss_custom 						= 0;
			$salary->is_deduct_philhealth_default 			= 0;
			$salary->deduct_philhealth_custom 				= 0;
			$salary->is_deduct_pagibig_default 				= 0;
			$salary->deduct_pagibig_custom 					= 0;
		}

		/* GET EMPLOYEE DATA */
		$employee 			= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->first();
		
		/* ALLOWANCES */
		$allowances 		= Payroll2::get_allowance($employee_id, $rendered_days);
		$total_deminimis	+= $allowances['total'];
		$temp['name'] 		= 'allowances';
		$temp['obj']  		= $allowances['obj'];
		array_push($return['obj'], $temp);
		
		/* allowances */
		$adjustment_allowance 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Allowance')->get();
		$total_deminimis 			+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Allowance')->sum('payroll_adjustment_amount');
		
		$temp['name'] 				= 'adjustment_allowance';
		$temp['obj']  				= Payroll2::adjustment_breakdown($adjustment_allowance, 'add');
		array_push($return['obj'], $temp);
	
		/* bonus */
		$adjustment_bonus 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Bonus')->get();
		$total_deminimis 			+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Bonus')->sum('payroll_adjustment_amount');
		$temp['name'] 				= 'adjustment_bonus';
		$temp['obj']  				= Payroll2::adjustment_breakdown($adjustment_bonus, 'add');
		array_push($return['obj'], $temp);
		
		/* commission */
		$adjustment_commission 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Commissions')->get();
		$total_deminimis 				+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Commissions')->sum('payroll_adjustment_amount');
		$temp['name'] 					= 'adjustment_commission';
		$temp['obj']  					= Payroll2::adjustment_breakdown($adjustment_commission, 'add');
		array_push($return['obj'], $temp);
		
		/* incentives */
		$adjustment_incentives 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Incentives')->get();
		$total_deminimis 				+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Incentives')->sum('payroll_adjustment_amount');
		$temp['name'] 					= 'adjustment_incentives';
		$temp['obj']  					= Payroll2::adjustment_breakdown($adjustment_incentives, 'add');
		array_push($return['obj'], $temp);
		
		/* 13 month pay */
		$adjustment_13_month 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, '13 month pay')->get();
		$total_deminimis 				+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , '13 month pay')->sum('payroll_adjustment_amount');
		$temp['name'] 					= 'adjustment_13_month';
		$temp['obj']  					= Payroll2::adjustment_breakdown($adjustment_13_month, 'add');
		array_push($return['obj'], $temp);
		
		/* deductions */
		$adjustment_deductions 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Deductions')->get();
		$total_deduction 				+= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Deductions')->sum('payroll_adjustment_amount');
		$temp['name'] 					= 'adjustment_deductions';
		$temp['obj']  					= Payroll2::adjustment_breakdown($adjustment_deductions, 'minus');
		array_push($return['obj'], $temp);
		
		/* GET 13 MONTH PAY */
		$n13_month = 0;
		if(isset($group->payroll_group_13month_basis))
		{
			$n13_month = Payroll2::get_13_month($group->payroll_group_13month_basis, $basic_pay, $employee_id, $payroll_period_company_id);
		}

		$total_deminimis += $n13_month;
		
		/* GET PREVIOUS RECORD OF GOVERNMENT CONTRIBUTION */
		$date_period[0] = date('Y-m-01', strtotime($end_date));
		$date_period[1] = date('Y-m-t', strtotime($end_date));
		
		
		$pevious_record = Tbl_payroll_record::getdate($shop_id, $date_period)
											->where('tbl_payroll_period.payroll_period_category', $period_category)
											->where('payroll_employee_id',$employee_id)
											->get()
											->toArray();

		$previous_tax 			= collect($pevious_record)->sum('tax_contribution');
		$previous_sss 			= collect($pevious_record)->sum('sss_contribution_ee');
		$previous_pagibig 		= collect($pevious_record)->sum('pagibig_contribution');
		$previous_philhealth	= collect($pevious_record)->sum('philhealth_contribution_ee');
		$period_category_arr	 	= Payroll::getperiodcount($shop_id, $end_date, $period_category, $start_date);

		/* GET SSS CONTRIBUTION */
		$sss_contribution 		= Payroll2::get_sss($shop_id, $salary->payroll_employee_salary_sss, $period_category_arr, $group->payroll_group_sss, $salary->is_deduct_sss_default, $salary->deduct_sss_custom, $previous_sss);
		$sss_ee					= $sss_contribution['sss_ee'];
		$sss_er					= $sss_contribution['sss_er'];
		$sss_ec					= $sss_contribution['sss_ec'];

		$total_deduction 		+= $sss_ee;
		
		$temp['name']	= 'sss_ee';
		$temp['obj']	= Payroll2::computation_array('SSS EE', $sss_ee, 'minus');
		array_push($return['obj'], $temp);

		$temp['name']	= 'sss_er';
		$temp['obj']	= Payroll2::computation_array('SSS ER', $sss_er, 'employer');
		array_push($return['obj'], $temp);

		$temp['name']	= 'sss_ec';
		$temp['obj']	= Payroll2::computation_array('SSS EC', $sss_ec, 'employer');
		array_push($return['obj'], $temp);

		/* GET PHILHEALTH CONTRIBUTION */
		$philhealth_contribution  	= Payroll2:: get_philhealth($shop_id, $group->payroll_group_philhealth ,$salary->payroll_employee_salary_philhealth, $period_category_arr, $salary->is_deduct_philhealth_default, $salary->deduct_philhealth_custom, $previous_philhealth);
		$philhealth_ee 				= $philhealth_contribution['philhealth_ee'];
		$philhealth_er 				= $philhealth_contribution['philhealth_er'];

		$total_deduction			+= $philhealth_ee;
		
		$temp['name']	= 'philhealth_ee';
		$temp['obj']	= Payroll2::computation_array('PhilHealth EE', $philhealth_ee, 'minus');
		array_push($return['obj'], $temp);

		$temp['name']	= 'philhealth_er';
		$temp['obj']	= Payroll2::computation_array('PhilHealth ER', $philhealth_ee, 'employer');
		array_push($return['obj'], $temp);

		// GET PAGIBIG CONTRIBUTION
		$pagibig_contribution 		= Payroll2::get_pagibig($shop_id, $salary->payroll_employee_salary_pagibig, $group->payroll_group_pagibig, $period_category_arr, $salary->is_deduct_pagibig_default, $period_category, $salary->deduct_pagibig_custom);
		$pagibig_ee = $pagibig_contribution['pagibig_ee'];
		$pagibig_er = $pagibig_contribution['pagibig_er'];
		$total_deduction			+= $pagibig_ee;
		
		$temp['name']	= 'pagibig_ee';
		$temp['obj']	= Payroll2::computation_array('PAIGBIG EE', $pagibig_ee, 'minus');
		array_push($return['obj'], $temp);

		$temp['name']	= 'pagibig_er';
		$temp['obj']	= Payroll2::computation_array('PAIGBIG ER', $pagibig_er, 'employer');
		array_push($return['obj'], $temp);

		/* GET TAX CONTRIBUTION */
		$tax_contribution = Payroll2::get_tax($shop_id, $salary->payroll_employee_salary_minimum_wage, $group->payroll_group_tax, $period_category, $group->payroll_group_before_tax, $salary->payroll_employee_salary_taxable, $sss_ee, $philhealth_ee, $pagibig_ee, $employee->payroll_employee_tax_status, $period_category_arr);
		$total_deduction			+= $tax_contribution;
		$temp['name']	= 'tax';
		$temp['obj']	= Payroll2::computation_array('TAX', $tax_contribution, 'minus');
		array_push($return['obj'], $temp);
		
		/* AGENCY DEDUCTION */
		$agency_deduction = 0;
		if($group->payroll_group_agency == Payroll::return_ave($period_category))
		{
			$agency_deduction = $group->payroll_group_agency_fee;
		}
		$total_deduction			+= $agency_deduction;


		$temp['name']	= 'agency_deduction';
		$temp['obj']	= Payroll2::computation_array('AGENCY DEDUCTION', $agency_deduction, 'minus');
		array_push($return['obj'], $temp);

		$deduction = Payroll::getdeduction($employee_id, $start_date, $period_category_arr['period_category'], $period_category, $shop_id);
		$total_deduction += $deduction['total_deduction'];

		$temp['name'] = 'deduction';
		$temp['obj'] = Payroll2::deducton_breakdown($deduction['deduction']);
		array_push($return['obj'], $temp);
		
		$net = ($gross_pay + $total_deminimis) - $total_deduction;
		// dd($total_deduction);
		$return['net_pay']	=	$net;
		
		return 	$return;

	}
	
	/* COMPUTE CUTOFF BREAKDOWN - BY GUILLERMO TABLIGAN */
	public static function cutoff_breakdown($payroll_period_company_id, $employee_id, $cutoff_compute, $data)
	{
		$return = new stdClass();

		$data["employee_id"] 		= $employee_id;
		$data["employee"]			= Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->first();


		/* GET PERIOD DETAILS */
		$data["date_query"] 			= $date_query 			= Tbl_payroll_period_company::sel($payroll_period_company_id)->first();
		$data["start_date"] 			= $start_date 			= $date_query->payroll_period_start;
		$data["end_date"] 				= $end_date 			= $date_query->payroll_period_end;
		$data["period_category"] 		= $period_category 		= $date_query->payroll_period_category;

		/* GET PAYROLL GROUP INFORMATION */
		$data["group"] 					= $group 				= Tbl_payroll_employee_contract::selemployee($employee_id, $start_date)->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')->first();
		$data["salary"] 				= $salary 				= Tbl_payroll_employee_salary::selemployee($employee_id, $start_date)->first();
		$data["shop_id"]				= $shop_id				= $group->shop_id;

		/* GET PERIOD CATEGORY ARR */
		$data["period_category_arr"] 	= $period_category_arr	= Payroll::getperiodcount($shop_id, $end_date, $period_category, $start_date);

		/* BREAKDOWN MODE */
		$return->_breakdown = array();

		/* FLAT RATE DECLARED BASIC PAY */
		if($group->payroll_group_salary_computation == "Flat Rate")
		{
			$payroll_period_category = $data["date_query"]->payroll_period_category; //Semi-monthly, Monthly, Weekly, Daily

			if($payroll_period_category == "Semi-monthly")
			{
				$divisor = 2;
			}
			elseif($payroll_period_category == "Weekly")
			{				
				$divisor = 4;
			}
			else
			{
				$divisor = 1;
			}

			$cutoff_compute->cutoff_basic = $salary->payroll_employee_salary_monthly / $divisor;
		}

		$return = Payroll2::cutoff_breakdown_additions($return, $data);
		
		if ($group->payroll_group_cola_basis == "") 
		{
			if($group->payroll_group_salary_computation == "Flat Rate")
			{
				$return = Payroll2::cutoff_fixed_montly_cola($return, $data);
			}
			else if($group->payroll_group_salary_computation == "Daily Rate")
			{
				$return = Payroll2::cutoff_breakdown_cola($return, $data);
			}
			else if ($group->payroll_group_salary_computation == "Monthly Rate") 
			{
				$return = Payroll2::cutoff_fixed_montly_cola($return, $data);
			}
		}
		else
		{
			if ($group->payroll_group_cola_basis == "Daily Computation") 
			{
				$return = Payroll2::cutoff_breakdown_cola($return, $data);
			}
			else if($group->payroll_group_cola_basis == "Daily Fixed") 
			{
				$return = Payroll2::cutoff_daily_fixed_cola($return, $data);
			}
			else if($group->payroll_group_cola_basis == "Monthly Fixed")
			{
				$return = Payroll2::cutoff_fixed_montly_cola($return, $data);
			}
			else if($group->payroll_group_cola_basis == "Pro Rated Monthly")
			{
				$return = Payroll2::cutoff_pro_rated_montly_cola($return, $data);
			}
			if($group->payroll_group_salary_computation == "Flat Rate")
			{
				$return = Payroll2::cutoff_fixed_montly_cola($return, $data);
			}
		}
		
		
		$return = Payroll2::cutoff_breakdown_compute_time($return, $data);

		if ($return->_time_breakdown["time_spent"]["float"] != 0 || $group->payroll_group_salary_computation == "Flat Rate")
		{
			$return = Payroll2::cutoff_breakdown_deductions($return, $data); //meron bang non-taxable deduction?? lol
			$return = Payroll2::cutoff_breakdown_adjustments($return, $data);
			$return = Payroll2::cutoff_breakdown_allowance_v2($return, $data);
			$return = Payroll2::cutoff_breakdown_taxable_allowances($return, $data);
			$return = Payroll2::cutoff_breakdown_non_taxable_allowances($return, $data);
			$return = Payroll2::cutoff_breakdown_hidden_allowances($return, $data);
		}
		
		$return = Payroll2::cutoff_breakdown_compute_gross_basic_pay($return, $data);
		$return = Payroll2::cutoff_breakdown_compute_gross_pay($return, $data);
		$return = Payroll2::cutoff_breakdown_government_contributions($return, $data);
		$return = Payroll2::cutoff_breakdown_compute_taxable_salary($return, $data);
		$return = Payroll2::cutoff_breakdown_compute_tax($return, $data);	
		$return = Payroll2::cutoff_breakdown_compute_net($return, $data);
		
		return $return;
	}

	public static function cutoff_compute_13th_month_pay($employee_id, $data)
	{
		extract($data);
		$group = Tbl_payroll_employee_contract::Group()->where('tbl_payroll_employee_contract.payroll_employee_id', $employee_id)->first();
		$payroll_13th_month_basis = Tbl_payroll_13th_month_basis::where('payroll_group_id',$group['payroll_group_id'])->first();
		$payroll_13th_month_pay = 0;

		if ($payroll_13th_month_basis != null) 
		{
			if ($payroll_13th_month_basis['payroll_group_13month_basis'] == "Net Basic Pay") 
			{
				$payroll_13th_month_pay += $cutoff_breakdown->basic_pay_total;
			}
			else if($payroll_13th_month_basis['payroll_group_13month_basis'] == "Gross Basic Pay")
			{
				$payroll_13th_month_pay += $cutoff_breakdown->gross_basic_pay;
			}
			foreach ($cutoff_breakdown->_breakdown as $key => $breakdown) 
			{
				if ($breakdown['label'] == 'COLA' && $payroll_13th_month_basis["payroll_group_13th_month_addition_cola"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'late' && $payroll_13th_month_basis["payroll_group_13th_month_addition_late"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'undertime' && $payroll_13th_month_basis["payroll_group_13th_month_addition_undertime"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'absent' && $payroll_13th_month_basis["payroll_group_13th_month_addition_absent"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'Special Holiday' && $payroll_13th_month_basis["payroll_group_13th_month_addition_special_holiday"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'Legal Holiday' && $payroll_13th_month_basis["payroll_group_13th_month_addition_regular_holiday"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'Legal Holiday' && $payroll_13th_month_basis["payroll_group_13th_month_addition_regular_holiday"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'allowance' && $payroll_13th_month_basis["payroll_group_13th_month_addition_allowance"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
				if ($breakdown['label'] == 'allowance_de_minimis' && $payroll_13th_month_basis["payroll_group_13th_month_addition_de_minimis_benefit"] == 1) 
				{
					$payroll_13th_month_pay += $breakdown['amount'];
				}
			}
		}
		
		return $payroll_13th_month_pay;
	}

	public static function cutoff_breakdown_compute_time($return, $data)
	{
		$show_time_breakdown = array('target_hours','time_spent', 'undertime', 'overtime','late','night_differential','leave_hours');

		$return->_time_breakdown['day_spent']["float"] = 0;
		$return->_time_breakdown['day_spent']["time"] = "No Day Spent";

		$return->_time_breakdown['absent']["float"] = 0;
		$return->_time_breakdown['absent']["time"] = "No Absent";

		//dd($data["cutoff_input"]);
		foreach($data["cutoff_input"] as $cutoff_input)
		{
			foreach($cutoff_input->time_output as $key => $time_output)
			{
				if(in_array($key, $show_time_breakdown))
				{
					if(!isset($return->_time_breakdown[$key]))
					{
						$return->_time_breakdown[$key]["float"] = Payroll2::time_float($time_output);
					}
					else
					{
						$return->_time_breakdown[$key]["float"] += Payroll2::time_float($time_output);
					}
					$return->_time_breakdown[$key]["time"] = Payroll2::float_time($return->_time_breakdown[$key]["float"]);
				}

				if($key == "is_absent")
				{
					if($time_output == true)
					{
						$return->_time_breakdown['absent']["float"] += 1;
						$return->_time_breakdown['absent']["time"] = $return->_time_breakdown['absent']["float"] . " Absent(s)";
					}
				}

				if ($key == 'time_spent' && $time_output != '00:00:00') 
				{
					$return->_time_breakdown['day_spent']["float"] += 1;
					$return->_time_breakdown['day_spent']["time"]  = $return->_time_breakdown['day_spent']["float"] . " Day(s) Spent";
				}

				if ($key == 'is_holiday') 
				{
					if ($time_output == 'regular') 
					{
						if (!isset($return->_time_breakdown['regular_holiday']["float"])) 
						{
							$return->_time_breakdown['regular_holiday']["float"] = 1;
							$return->_time_breakdown['regular_holiday']["time"] = $return->_time_breakdown['regular_holiday']["float"] . " Regular Holiday(s)";
						}
						else
						{
							$return->_time_breakdown['regular_holiday']["float"] += 1;
							$return->_time_breakdown['regular_holiday']["time"] = $return->_time_breakdown['regular_holiday']["float"] . " Regular Holiday(s)";
						}
					}

					if ($time_output == 'special') 
					{
						if (!isset($return->_time_breakdown['sepcial_holiday']["float"])) 
						{
							$return->_time_breakdown['special_holiday']["float"] = 1;
							$return->_time_breakdown['special_holiday']["time"] = $return->_time_breakdown['special_holiday']["float"] . " Special Holiday(s)";
						}
						else
						{
							$return->_time_breakdown['special_holiday']["float"] += 1;
							$return->_time_breakdown['special_holiday']["time"] = $return->_time_breakdown['special_holiday']["float"] . " Special Holiday(s)";
						}
					}
				}
			}
		}

		if (!isset($return->_time_breakdown['regular_holiday']["float"])) 
		{
			$return->_time_breakdown['regular_holiday']["float"] = 0;
			$return->_time_breakdown['regular_holiday']["time"] = $return->_time_breakdown['regular_holiday']["float"] . " No Regular Holiday";
		}

		if (!isset($return->_time_breakdown['special_holiday']["float"])) 
		{
			$return->_time_breakdown['special_holiday']["float"] = 0;
			$return->_time_breakdown['special_holiday']["time"] = $return->_time_breakdown['special_holiday']["float"] . " No Special Holiday";
		}
		
		return $return;
	}
	public static function cutoff_breakdown_to_tr($breakdown)
	{
		if($breakdown["mode"] == "plus")
		{
			$return = '<tr>
                            <td colspan="7" class="text-right" style="opacity: 0.5">
                                ADD: <span class="text-bold">' . strtoupper($breakdown["label"]) . "</span>" . (isset($breakdown["description"]) ? "<br>" . $breakdown["description"] : "") . '
                            </td>
                            
                            <td class="text-right">
                                ' . payroll_currency($breakdown["amount"]) . ' 
                            </td>
                        </tr>';
		}
		else
		{
			$return = '<tr>
                            <td colspan="7" class="text-right" style="opacity: 0.5">
                                LESS: <span class="text-bold">' . strtoupper($breakdown["label"]) . "</span>" . (isset($breakdown["description"]) ? "<br>" . $breakdown["description"] : "") . '
                            </td>
                            
                            
                            <td class="text-right" style="opacity: 0.5; color: red;">
                                ' . payroll_currency($breakdown["amount"]) . ' 
                            </td>
                        </tr>';	
        }

		return $return;
	}


	public static function cutoff_breakdown_compute_net($return, $data)
	{
		extract($data);

		$return->net_pay_total = $return->taxable_salary_total;
		$return->_net_pay_breakdown = array();
		
		foreach($return->_breakdown as $breakdown)
		{
			if($breakdown["add.net_pay"] == true)
			{
				$return->net_pay_total += $breakdown["amount"];
				$breakdown["mode"] = "plus";
				$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
				array_push($return->_net_pay_breakdown, $breakdown);	
			}

			if($breakdown["deduct.net_pay"] == true)
			{
				$return->net_pay_total -= $breakdown["amount"];
				$breakdown["mode"] = "minus";
				$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
				array_push($return->_net_pay_breakdown, $breakdown);	
			}
			
		}		

		return $return;
	}
	public static function cutoff_breakdown_compute_tax($return, $data)
	{
		extract($data);

		/* GET INITIAL SETTINGS */
		$payroll_period_category = $date_query->payroll_period_category; //Semi-monthly, Monthly, Weekly, Daily
		$period_count = $date_query->period_count; //first_period, last_period, middle_period
		$period_month = $date_query->month_contribution; //calendar month (in word - January to December)
		$period_year = $date_query->year_contribution; //calendar year

		$tax_period = $group->payroll_group_tax; //Every Period, First Period, Last Period
		$tax_reference = $group->tax_reference;
		$tax_declared = $salary->payroll_employee_salary_taxable;
		$payroll_period_company_id = $date_query->payroll_period_company_id;
		$payroll_company_id = $date_query->payroll_company_id;
		
		// dd($tax_reference);
		if($tax_reference == "declared") //IF REFERENCE IS DECLARED (check tax table for monthly and just divide by two IF every period)
		{

			$tax = Payroll::tax_contribution($shop_id, $tax_declared, $employee->payroll_employee_tax_status, "Monthly");	
			$tax_description = payroll_currency($tax_declared) . " declared TAX Salary (" . $employee->payroll_employee_tax_status . ")";

			if($tax_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				if($payroll_period_category == "Semi-monthly")
				{
					$divisor = 2;
					$tax_description .= "<br> SEMI MONTHLY (EVERY PERIOD) - TAX which is " . payroll_currency($tax) . " was divided by two.";
				}
				elseif($payroll_period_category == "Weekly")
				{				
					$divisor = 4;
					$tax_description .= "<br> WEEKLY (EVERY PERIOD) - TAX which is " . payroll_currency($tax) . " was divided by four.";
				}
				else
				{
					$divisor = 1;
				}

				/* TAX DIVISOR */
				$tax = $tax / $divisor;
			}
			else
			{
				$tax_description .= "<br> TAX is processed only during " . $tax_period . ".";

				if($tax_period == code_to_word($period_count))
				{
					$tax_reference_amount = $tax_declared;
					$tax_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$tax = $tax;
				}
				else
				{
					$tax_reference_amount = 0;
					$tax_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$tax = 0;
				}
			}
		}
		else //BASED ON TAXABLE SALARY
		{
			if($tax_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				$tax_reference_amount = $return->taxable_salary_total;
				$tax_description = "(Every Period Compute) Tax Table for " . $payroll_period_category . " with amount of " . payroll_currency($tax_reference_amount) . " (" . $employee->payroll_employee_tax_status . ").";
				$tax = Payroll::tax_contribution($shop_id, $tax_reference_amount, $employee->payroll_employee_tax_status, $payroll_period_category);
			}
			elseif($tax_period == "Last Period")
			{
				$tax_reference_amount = $return->taxable_salary_total;

				if(code_to_word($period_count) == "Last Period")
				{
					$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
					
					$tax_description = "Computation for Last Period's Taxable Salary";

					if(count($_cutoff) > 0)
					{
						foreach($_cutoff as $cutoff)
						{
							$tax_reference_amount = $tax_reference_amount + $cutoff->taxable_salary;
							$tax_description .= "<br> Add Previous Cutoff " . payroll_currency($cutoff->taxable_salary) . " and the new amount will be " . payroll_currency($tax_reference_amount);
						}
					}

					$tax_description .= "<br>Last Period Computation - Tax Table for Monthly with amount of " . payroll_currency($tax_reference_amount) . " (" . $employee->payroll_employee_tax_status . ").";
					$tax = Payroll::tax_contribution($shop_id, $tax_reference_amount, $employee->payroll_employee_tax_status, "Monthly");
				}
				else
				{
					$tax_reference_amount = $return->taxable_salary_total;
					$tax_description = "Tax Computation only happens during LAST PERIOD. Currently in " . code_to_word($period_count);
					$tax = 0;
				}
			}
			else
			{
				dd("System Error Code 587893. Tax Period (" .  $tax_period . ") is invalid.");
			}
		}


		$return->tax_total = $tax;

		$val["label"] = "Witholding Tax";
		$val["description"] = $tax_description;
		$val["type"] = "tax";
		$val["amount"] = $tax;
		$val["add.gross_pay"] = false;
		$val["deduct.gross_pay"] = false;
		$val["add.taxable_salary"] = false;
		$val["deduct.taxable_salary"] = false;
		$val["add.net_pay"] = false;
		$val["deduct.net_pay"] = true;

		array_push($return->_breakdown, $val);
		$val = null;			

		return $return;
	}

	public static function cutoff_breakdown_compute_gross_pay($return, $data)
	{
		extract($data);


		$return->basic_pay_total = $cutoff_compute->cutoff_basic;
		$return->gross_pay_total = $cutoff_compute->cutoff_basic;
		$return->_gross_pay_breakdown = array();
		
		foreach($return->_breakdown as $breakdown)
		{
			if($breakdown["label"] != "Leave Pay")
			{
				if($breakdown["add.gross_pay"] == true)
				{
					$return->gross_pay_total += $breakdown["amount"];
					$breakdown["mode"] = "plus";
					$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
					array_push($return->_gross_pay_breakdown, $breakdown);	
				}
			}

			if($breakdown["deduct.gross_pay"] == true)
			{
				$return->gross_pay_total -= $breakdown["amount"];
				$breakdown["mode"] = "minus";
				$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
				array_push($return->_gross_pay_breakdown, $breakdown);	
			}
		}

		return $return;
	}

	public static function cutoff_breakdown_compute_gross_basic_pay($return, $data)
	{
		extract($data);
		
		$return->gross_basic_pay = 0;

		if ($group->payroll_group_salary_computation == "Daily Rate") 
		{
			$return->gross_basic_pay = Payroll2::identify_period_salary_daily_rate($cutoff_input);
		}
		elseif ($group->payroll_group_salary_computation == "Hourly Rate") {
			# code...
		}
		else
		{
			$return->gross_basic_pay = Payroll2::identify_period_salary($salary->payroll_employee_salary_monthly, $period_category);
		}
		
		return $return;
	}

	public static function cutoff_breakdown_compute_taxable_salary($return, $data)
	{
		extract($data);
		$return->taxable_salary_total = $return->gross_pay_total;
		$return->_taxable_salary_breakdown = array();

		
		foreach($return->_breakdown as $breakdown)
		{
			if($breakdown["add.taxable_salary"] == true)
			{
				$return->taxable_salary_total += $breakdown["amount"];
				$breakdown["mode"] = "plus";
				$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
				array_push($return->_taxable_salary_breakdown, $breakdown);	
			}
			if($breakdown["deduct.taxable_salary"] == true)
			{
				$return->taxable_salary_total -= $breakdown["amount"];
				$breakdown["mode"] = "minus";
				$breakdown["tr"] = Payroll2::cutoff_breakdown_to_tr($breakdown);
				array_push($return->_taxable_salary_breakdown, $breakdown);	
			}
		}

		return $return;
	}

	public static function cutoff_breakdown_government_contributions($return, $data)
	{

		// dd($data);
		extract($data);

		/* GET INITIAL SETTINGS */
		$payroll_period_category = $date_query->payroll_period_category; //Semi-monthly, Monthly, Weekly, Daily
		$period_count = $date_query->period_count; //first_period, last_period, middle_period
		$period_month = $date_query->month_contribution; //calendar month (in word - January to December)
		$period_year = $date_query->year_contribution; //calendar year
		$sss_period = $group->payroll_group_sss; //Every Period, First Period, Last Period
		$sss_reference = $group->sss_reference;
		$sss_declared = $salary->payroll_employee_salary_sss;
		$philhealth_period = $group->payroll_group_philhealth; //Every Period, First Period, Last Period
		$philhealth_reference = $group->philhealth_reference;
		$philhealth_declared =  $salary->payroll_employee_salary_philhealth;
		$pagibig_period = $group->payroll_group_pagibig; //Every Period, First Period, Last Period
		$pagibig_reference = $group->pagibig_reference;
		$pagibig_declared =  $salary->payroll_employee_salary_pagibig;
		$payroll_period_company_id = $date_query->payroll_period_company_id;
		$payroll_company_id = $date_query->payroll_company_id;

		/* SSS COMPUTATION */
		$sss_description = "";

		if($sss_reference == "declared") //IF REFERENCE IS DECLARED (check tax table for monthly and just divide by two IF every period)
		{
			$sss_contribution = Payroll::sss_contribution($shop_id, $sss_declared);
			$sss_description = payroll_currency($sss_declared) . " declared SSS Salary";

			if($sss_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				$sss_reference_amount = $sss_declared;

				if($payroll_period_category == "Semi-monthly")
				{
					$divisor = 2;
					$sss_description .= "<br> SEMI MONTHLY (EVERY PERIOD) - SSS Contribution which is " . payroll_currency($sss_contribution["ee"]) . " was divided by two.";
				}
				elseif($payroll_period_category == "Weekly")
				{				
					$divisor = 4;
					$sss_description .= "<br> WEEKLY (EVERY PERIOD) - SSS Contribution which is " . payroll_currency($sss_contribution["ee"]) . " was divided by four.";
				}
				else
				{
					$divisor = 1;
				}

				/* CHECK EXCEED MONTH */
				$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
				$total_cutoff = 0;

				foreach($_cutoff as $cutoff)
				{
					$total_cutoff += $cutoff->sss_ee;
				}

				if($total_cutoff >= $sss_contribution["ee"])
				{
					$sss_description .= "<br> EE, ER and EC converted to zero in order to not exceed monthly contribution.";
					$sss_contribution["ee"] = 0;
					$sss_contribution["er"] = 0;
					$sss_contribution["ec"] = 0;
				}
				else
				{
					$sss_contribution["ee"] = $sss_contribution["ee"] / $divisor;
					$sss_contribution["er"] = $sss_contribution["er"] / $divisor;
					$sss_contribution["ec"] = $sss_contribution["ec"] / $divisor;
				}
			}
			else
			{
				$sss_description .= "<br> SSS Contribution is processed only during " . $sss_period . ".";

				if($sss_period == code_to_word($period_count))
				{
					$sss_reference_amount = $sss_declared;
					$sss_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$sss_contribution["ee"] = $sss_contribution["ee"];
					$sss_contribution["er"] = $sss_contribution["er"];
					$sss_contribution["ec"] = $sss_contribution["ec"];
				}
				else
				{
					$sss_reference_amount = 0;
					$sss_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$sss_contribution["ee"] = 0;
					$sss_contribution["er"] = 0;
					$sss_contribution["ec"] = 0;
				}
			}
			/* TODO: IF SSS CONTRIBUTION FOR DECLARED EXCEED 4 PAYROLL PERIOD - THE SSS CONTRIBUTION SHOULD BE ZERO */
		}
		else //BASED ON GROSS OR NET BASIC PAY
		{
			$sss_reference_amount = $return->gross_pay_total;
			$sss_description = payroll_currency($return->gross_pay_total) . " Gross Salary for this cutoff.";

			if($sss_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				$sss_description .= "<br> SSS Contribution is processed every period.";

				/* CHECK LAST CUTOFF OF THIS MONTH SSS CONTRIBUTION */
				if(code_to_word($period_count) == "1st Period")
				{
					$sss_description .= "<br> 1st Period of the Month. No need to refer to previous cutoff.";
					$sss_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
				}
				else
				{
					// $last_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->first();
					// if($last_cutoff)
					// {
					// 	$sss_description .= "<br> Using previous cutoff as reference, previous SSS Salary used is " . payroll_currency($last_cutoff->sss_salary) . " (" . payroll_currency($last_cutoff->sss_ee) . ")";
					// 	$sss_reference_amount = $sss_reference_amount + $last_cutoff->sss_salary;
					// 	$sss_description .= "<br> Adding previous cutoff reference the output is " . payroll_currency($sss_reference_amount);
					// 	$sss_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
					// 	$sss_description .= "<br> New SSS Bracket falls to " . payroll_currency($sss_contribution["ee"]);
					// 	$sss_description .= "<br> NEW BRACKET (" . payroll_currency($sss_contribution["ee"]) . ") LESS PREVIOUS CUTOFF (" . payroll_currency($last_cutoff->sss_ee) . ")";
					// 	$sss_contribution["ee"] = $sss_contribution["ee"] - $last_cutoff->sss_ee;
					// 	$sss_contribution["er"] = $sss_contribution["er"] - $last_cutoff->sss_er;
					// 	$sss_contribution["ec"] = $sss_contribution["ec"] - $last_cutoff->sss_ec;
					// 	$last_cutoff->sss_salary;
					// }

					$last_cutoff 			= Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->first();
					$_period_approved 		= Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
					
					$total_previous_cutoff_sss_ee  	= 0;
					$total_previous_cutoff_sss_er	= 0;
					$total_previous_cutoff_sss_ec	= 0;

					foreach ($_period_approved as $key => $period_approved) 
					{
						$total_previous_cutoff_sss_ee += $period_approved->sss_ee;
						$total_previous_cutoff_sss_er += $period_approved->sss_er;
						$total_previous_cutoff_sss_ec += $period_approved->sss_ec;
					}

					if($last_cutoff)
					{
						$sss_reference_amount = $sss_reference_amount + $last_cutoff->sss_salary;
						$sss_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
						
						$sss_description .= "<br> Using previous cutoff as reference, all previous SSS Salary used is " . payroll_currency($last_cutoff->sss_salary) . " (" . payroll_currency($total_previous_cutoff_sss_ee) . ")";
						$sss_description .= "<br> Adding previous cutoff reference the output is " . payroll_currency($sss_reference_amount);
						$sss_description .= "<br> New SSS Bracket falls to " . payroll_currency($sss_contribution["ee"]);
						$sss_description .= "<br> NEW BRACKET (" . payroll_currency($sss_contribution["ee"]) . ") LESS PREVIOUS CUTOFF (" . payroll_currency($total_previous_cutoff_sss_ee) . ")";
						
						$sss_contribution["ee"] = $sss_contribution["ee"] - $total_previous_cutoff_sss_ee;
						$sss_contribution["er"] = $sss_contribution["er"] - $total_previous_cutoff_sss_er;
						$sss_contribution["ec"] = $sss_contribution["ec"] - $total_previous_cutoff_sss_ec;

						$last_cutoff->sss_salary;
					}
					else
					{
						dd("Warning! This is not the 1st period of the month and the system can't find reference period for the month of $period_month($period_year)");
					}
				}
			}
			else
			{
				$sss_description .= "<br> SSS Contribution is processed only during " . $sss_period . ".";

				if($sss_period == code_to_word($period_count))
				{
					$sss_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";

					if($sss_period == "1st Period")
					{
						dd("ERROR: 1st Period is only allowed if SALARY is DECLARRED");
						$sss_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
					}
					else
					{
						$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();		
						if(count($_cutoff) > 0)
						{
							foreach($_cutoff as $cutoff)
							{
									$sss_reference_amount = $sss_reference_amount + $cutoff->sss_salary;
									$sss_description .= "<br> Add Previous Cutoff " . payroll_currency($cutoff->sss_salary) . " and the new amount will be " . payroll_currency($sss_reference_amount);
							}
						}
						$sss_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
					}
				}
				else
				{
					$sss_reference_amount = $sss_reference_amount;
					$sss_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$sss_contribution["ee"] = 0;
					$sss_contribution["er"] = 0;
					$sss_contribution["ec"] = 0;
				}
			}
		}
		
		/* PHILHEALTH COMPUTATION */	
		if($philhealth_reference == "declared") //IF REFERENCE IS DECLARED (check tax table for monthly and just divide by two IF every period)
		{
			// $philhealth_contribution = Payroll::philhealth_contribution($shop_id, $philhealth_declared);
			$philhealth_contribution = Payroll2::philhealth_contribution_update_2018($philhealth_declared);
			$philhealth_description = payroll_currency($philhealth_declared) . " declared PHILHEALTH Salary";


			if($philhealth_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				$philhealth_reference_amount = $philhealth_declared;

				if($payroll_period_category == "Semi-monthly")
				{
					$divisor = 2;
					$philhealth_description .= "<br> SEMI MONTHLY (EVERY PERIOD) - PHILHEALTH Contribution which is " . payroll_currency($philhealth_contribution["ee"]) . " was divided by two.";
				}
				elseif($payroll_period_category == "Weekly")
				{				
					$divisor = 4;
					$philhealth_description .= "<br> WEEKLY (EVERY PERIOD) - PHILHEALTH Contribution which is " . payroll_currency($philhealth_contribution["ee"]) . " was divided by four.";
				}
				else
				{
					$divisor = 1;
				}

				/* CHECK EXCEED MONTH */
				$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
				$total_cutoff = 0;
				foreach($_cutoff as $cutoff)
				{
					$total_cutoff += $cutoff->philhealth_ee;
				}

				if($total_cutoff >= $philhealth_contribution["ee"])
				{
					$philhealth_description .= "<br> EE and ER converted to zero in order to not exceed monthly contribution.";
					$philhealth_contribution["ee"] = 0;
					$philhealth_contribution["er"] = 0;
				}
				else
				{
					$philhealth_contribution["ee"] = $philhealth_contribution["ee"] / $divisor;
					$philhealth_contribution["er"] = $philhealth_contribution["er"] / $divisor;
				}
			}
			else
			{
				$philhealth_description .= "<br> PHILHEALTH Contribution is processed only during " . $philhealth_period . ".";

				if($philhealth_period == code_to_word($period_count))
				{
					$philhealth_reference_amount = $philhealth_declared;
					$philhealth_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$philhealth_contribution["ee"] = $philhealth_contribution["ee"];
					$philhealth_contribution["er"] = $philhealth_contribution["er"];
				}
				else
				{
					$philhealth_reference_amount = 0;
					$philhealth_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$philhealth_contribution["ee"] = 0;
					$philhealth_contribution["er"] = 0;
				}
			}
		}
		else //BASED ON GROSS OR NET BASIC PAY
		{
			$philhealth_reference_amount = $return->gross_pay_total;
			$philhealth_description = payroll_currency($return->gross_pay_total) . " Gross Salary for this cutoff.";

			if($philhealth_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
			{
				$philhealth_description .= "<br> PHILHEALTH Contribution is processed every period.";

				/* CHECK LAST CUTOFF OF THIS MONTH philhealth CONTRIBUTION */
				if(code_to_word($period_count) == "1st Period")
				{
					$philhealth_description .= "<br> 1st Period of the Month. No need to refer to previous cutoff.";
					// $philhealth_contribution = Payroll::philhealth_contribution($shop_id, $philhealth_reference_amount);
					$philhealth_contribution = Payroll2::philhealth_contribution_update_2018($philhealth_reference_amount);
				}
				else
				{
					// $last_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->first();
					
					// if($last_cutoff)
					// {
					// 	$philhealth_description .= "<br> Using previous cutoff as reference, previous PHILHEALTH Salary used is " . payroll_currency($last_cutoff->phihealth_salary) . " (" . payroll_currency($last_cutoff->philhealth_ee) . ")";
					// 	$philhealth_reference_amount = $philhealth_reference_amount + $last_cutoff->phihealth_salary;
					// 	$philhealth_description .= "<br> Adding previous cutoff reference the output is " . payroll_currency($philhealth_reference_amount);
					// 	$philhealth_contribution = Payroll::philhealth_contribution($shop_id, $philhealth_reference_amount);
					// 	$philhealth_description .= "<br> New PHILHEALTH Bracket falls to " . payroll_currency($philhealth_contribution["ee"]);
					// 	$philhealth_description .= "<br> NEW BRACKET (" . payroll_currency($philhealth_contribution["ee"]) . ") LESS PREVIOUS CUTOFF (" . payroll_currency($last_cutoff->philhealth_ee) . ")";
					// 	$philhealth_contribution["ee"] = $philhealth_contribution["ee"] - $last_cutoff->philhealth_ee;
					// 	$philhealth_contribution["er"] = $philhealth_contribution["er"] - $last_cutoff->philhealth_er;
					// 	$last_cutoff->phihealth_salary;
					// }

					$last_cutoff 		= Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->first();
					$_period_approved 	= Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();

					$total_previous_cutoff_philhealth_ee  	= 0;
					$total_previous_cutoff_philhealth_er	= 0;

					foreach ($_period_approved as $key => $period_approved) 
					{
						$total_previous_cutoff_philhealth_ee += $period_approved->philhealth_ee;
						$total_previous_cutoff_philhealth_er += $period_approved->philhealth_er;
					}

					if($last_cutoff)
					{
						$philhealth_description .= "<br> Using previous cutoff as reference, previous PHILHEALTH Salary used is " . payroll_currency($last_cutoff->phihealth_salary) . " (" . payroll_currency($total_previous_cutoff_philhealth_ee) . ")";
						$philhealth_reference_amount = $philhealth_reference_amount + $last_cutoff->phihealth_salary;
						$philhealth_description .= "<br> Adding previous cutoff reference the output is " . payroll_currency($philhealth_reference_amount);
						// $philhealth_contribution = Payroll::philhealth_contribution($shop_id, $philhealth_reference_amount);
						$philhealth_contribution = Payroll2::philhealth_contribution_update_2018($philhealth_reference_amount);
						$philhealth_description .= "<br> New PHILHEALTH Bracket falls to " . payroll_currency($philhealth_contribution["ee"]);
						$philhealth_description .= "<br> NEW BRACKET (" . payroll_currency($philhealth_contribution["ee"]) . ") LESS PREVIOUS CUTOFF (" . payroll_currency($total_previous_cutoff_philhealth_ee) . ")";
						$philhealth_contribution["ee"] = $philhealth_contribution["ee"] - $total_previous_cutoff_philhealth_ee;
						$philhealth_contribution["er"] = $philhealth_contribution["er"] - $total_previous_cutoff_philhealth_er;
						$last_cutoff->phihealth_salary;
					}
					else
					{
						dd("Warning! This is not the 1st period of the month and the system can't find reference period for the month of $period_month($period_year).");
					}
				}
			}
			else
			{
				$philhealth_description .= "<br> PHILHEALTH Contribution is processed only during " . $philhealth_period . ".";

				if($philhealth_period == code_to_word($period_count))
				{
					$philhealth_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";

					if($philhealth_period == "1st Period")
					{
						dd("ERROR: 1st Period is only allowed if SALARY is DECLARRED");
						$philhealth_contribution = Payroll::sss_contribution($shop_id, $sss_reference_amount);
					}
					else
					{
						$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
						
						if(count($_cutoff) > 0)
						{
							foreach($_cutoff as $cutoff)
							{
								$philhealth_reference_amount = $philhealth_reference_amount + $cutoff->phihealth_salary;
								$philhealth_description .= "<br> Add Previous Cutoff " . payroll_currency($cutoff->phihealth_salary) . " and the new amount will be " . payroll_currency($philhealth_reference_amount);
							}
						}

						$philhealth_contribution = Payroll2::philhealth_contribution_update_2018($sss_reference_amount);
					}
				}
				else
				{
					$philhealth_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
					$philhealth_contribution["ee"] = 0;
					$philhealth_contribution["er"] = 0;
					$philhealth_contribution["ec"] = 0;
				}
			}
		}

		/* PAG-IBIG COMPUTATION */	
		$pagibig_reference_amount 	= $pagibig_declared;
		$pagibig_contribution["ee"] = $pagibig_declared;
		$pagibig_contribution["er"] = $pagibig_declared;
		$pagibig_description 		= payroll_currency($pagibig_declared) . " declared PAGIBIG Contribution";
		$pagibig_tbl 				= tbl_payroll_pagibig::where("shop_id",$data["shop_id"])->first();

		if($pagibig_period == "Every Period") //DIVIDE CONTRIBUTION IF EVERY PERIOD
		{
			if($payroll_period_category == "Semi-monthly")
			{
				$divisor = 2;
				$pagibig_description .= "<br> SEMI MONTHLY (EVERY PERIOD) - PAGIBIG Contribution which is " . payroll_currency($pagibig_contribution["ee"]) . " was divided by two.";
			}
			elseif($payroll_period_category == "Weekly")
			{				
				$divisor = 4;
				$pagibig_description .= "<br> WEEKLY (EVERY PERIOD) - PAGIBIG Contribution which is " . payroll_currency($pagibig_contribution["ee"]) . " was divided by four.";
			}
			else
			{
				$divisor = 1;
			}
			/* CHECK EXCEED MONTH */
			$_cutoff = Tbl_payroll_time_keeping_approved::periodCompany($payroll_company_id)->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", "!=", $payroll_period_company_id)->where("tbl_payroll_time_keeping_approved.employee_id", $employee_id)->where("month_contribution", $period_month)->where("year_contribution", $period_year)->orderBy("time_keeping_approve_id", "desc")->get();
			$total_cutoff = 0;
			foreach($_cutoff as $cutoff)
			{
				$total_cutoff += $cutoff->pagibig_ee;
			}
			if($total_cutoff >= $pagibig_contribution["ee"])
			{
				$pagibig_description .= "<br> EE and ER converted to zero in order to not exceed monthly contribution.";
				$pagibig_contribution["ee"] = 0;
				$pagibig_contribution["er"] = 0;
			}
			else
			{
				$pagibig_contribution["ee"] = $pagibig_contribution["ee"] / $divisor;
				$pagibig_contribution["er"] = @($pagibig_tbl["payroll_pagibig_er_share"] / $divisor);
			}
		}
		else
		{
			$pagibig_description .= "<br> PAGIBIG Contribution is processed only during " . $pagibig_period . ".";

			if($pagibig_period == code_to_word($period_count))
			{
				$pagibig_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
				$pagibig_contribution["ee"] = $pagibig_contribution["ee"];
				$pagibig_contribution["er"] = $pagibig_tbl["payroll_pagibig_er_share"];
			}
			else
			{
				$pagibig_description .= "<br> This cutoff is " .  code_to_word($period_count) . ".";
				$pagibig_contribution["ee"] = 0;
				$pagibig_contribution["er"] = 0;
			}
		}
		
		if ($return->_time_breakdown["time_spent"]["float"] != 0 || $data["group"]->payroll_group_salary_computation == "Flat Rate") 
		{
			$val["label"] = "SSS EE";
			$val["description"] = $sss_description;
			$val["type"] = "government_contributions";
			$val["amount"] = $sss_contribution["ee"];
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = true;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "SSS ER";
			$val["type"] = "government_contributions";
			$val["amount"] = $sss_contribution["er"];			
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = false;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "SSS EC";
			$val["type"] = "government_contributions";
			$val["amount"] = $sss_contribution["ec"];			
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = false;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "PHILHEALTH EE";
			$val["type"] = "government_contributions";
			$val["description"] = $philhealth_description;
			$val["amount"] = $philhealth_contribution["ee"];
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = true;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "PHILHEALTH ER";
			$val["type"] = "government_contributions";
			$val["amount"] = $philhealth_contribution["er"];			
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = false;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "PAGIBIG EE";
			$val["type"] = "government_contributions";
			$val["description"] = $pagibig_description;
			$val["amount"] = $pagibig_contribution["ee"];
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = true;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$val["label"] = "PAGIBIG ER";
			$val["type"] = "government_contributions";
			$val["amount"] = $pagibig_contribution["er"];			
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = false;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = false;
			array_push($return->_breakdown, $val);
			$val = null;

			$return->sss_contribution 					= $sss_contribution;
			$return->pagibig_contribution 				= $pagibig_contribution;
			$return->philhealth_contribution 			= $philhealth_contribution;
			$return->sss_contribution["salary"] 		= $sss_reference_amount;
			$return->philhealth_contribution["salary"] 	= $philhealth_reference_amount;
			$return->pagibig_contribution["salary"] 	= $pagibig_reference_amount;
		}
		else
		{
			/*if no time spent, remove deduction of government deductions*/
			foreach ($sss_contribution as $key => $value) 
			{
				$sss_contribution[$key] = 0;
			}
			foreach ($pagibig_contribution as $key => $value) 
			{
				$pagibig_contribution[$key] = 0;
			}
			foreach ($philhealth_contribution as $key => $value) 
			{
				$philhealth_contribution[$key] = 0;
			}
			$return->sss_contribution 					= $sss_contribution;
			$return->pagibig_contribution 				= $pagibig_contribution;
			$return->philhealth_contribution 			= $philhealth_contribution;
			$return->sss_contribution["salary"] 		= 0;
			$return->philhealth_contribution["salary"] 	= 0;
			$return->pagibig_contribution["salary"] 	= 0;
		}

		return $return;
	}
	public static function cutoff_breakdown_non_taxable_allowances($return, $data)
	{
		extract($data);
		if(!isset($cutoff_compute->render_days))
		{
			$cutoff_compute->render_days = 30;
		}

		$allowances 		= Payroll2::get_allowance($employee_id, $cutoff_compute->render_days);

		if(isset($allowances["obj"]))
		{
			if(count($allowances["obj"]) > 0)
			{
				foreach($allowances["obj"] as $breakdown)
				{
					$val["label"] = $breakdown["name"];
					$val["type"] = "non_taxable_allowance";
					$val["amount"] = $breakdown["amount"];
					$val["add.gross_pay"] = true;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = true;
					$val["add.net_pay"] = true;
					$val["deduct.net_pay"] = false;
					array_push($return->_breakdown, $val);
					$val = null;
				}
			}
		}

		return $return;
	}
	public static function cutoff_breakdown_hidden_allowances($return, $data)
	{
		extract($data);
		return $return;
	}
	public static function cutoff_breakdown_taxable_allowances($return, $data)
	{
		extract($data);
		return $return;
	}
	public static function cutoff_breakdown_additions($return, $data)
	{
		if(isset($data["cutoff_compute"]->_breakdown_addition_summary))
		{
			foreach($data["cutoff_compute"]->_breakdown_addition_summary as $key => $breakdown)
			{
				$val["label"] = $key;
				$val["type"] = "additions";

				if(isset($data["cutoff_compute"]->_breakdown_addition_summary_time[$key]))
				{
					$val["description"] = $data["cutoff_compute"]->_breakdown_addition_summary_time[$key];
				}
				
				$val["amount"] = $breakdown;
				$val["add.gross_pay"] = true;
				$val["deduct.gross_pay"] = false;
				$val["add.taxable_salary"] = false;
				$val["deduct.taxable_salary"] = false;
				$val["add.net_pay"] = false;
				$val["deduct.net_pay"] = false;
				array_push($return->_breakdown, $val);
				$val = null;
			}
		}

		return $return;
	}

	public static function cutoff_breakdown_cola($return, $data)
	{
		$total_cola = 0;

		foreach($data["cutoff_input"] as $cutoff_input)
		{
			$total_cola += $cutoff_input->compute->cola;
		}
		

		$val["label"] = "COLA";
		$val["type"] = "additions";
		$val["amount"] = $total_cola;
		$val["add.gross_pay"] = true;
		$val["deduct.gross_pay"] = false;
		$val["add.taxable_salary"] = false;
		$val["deduct.taxable_salary"] = false;
		$val["add.net_pay"] = false;
		$val["deduct.net_pay"] = false;
		array_push($return->_breakdown, $val);
		$val = null;

		return $return;
	}


	public static function cutoff_daily_fixed_cola($return, $data)
	{
		$total_cola = 0;
	
		foreach($data["cutoff_input"] as $cutoff_input)
		{
			$total_cola += $cutoff_input->compute->cola_daily;
		}

		$val["label"] = "COLA";
		$val["type"] = "additions";
		$val["amount"] = $total_cola;
		$val["add.gross_pay"] = true;
		$val["deduct.gross_pay"] = false;
		$val["add.taxable_salary"] = false;
		$val["deduct.taxable_salary"] = false;
		$val["add.net_pay"] = false;
		$val["deduct.net_pay"] = false;
		array_push($return->_breakdown, $val);
		$val = null;

		return $return;
	}

	


	public static function cutoff_fixed_montly_cola($return, $data)
	{
		$total_cola = 0;
		
		if ($data["period_category"]=="Semi-monthly") 
		{
			$total_cola = @($data["salary"]->monthly_cola/2);
		}
		else if($data["period_category"]=="Weekly")
		{
			$total_cola = @($data["salary"]->monthly_cola/4);
		}
		else if($data["period_category"]=="Monthly")
		{
			$total_cola = $data["salary"]->monthly_cola;
		}
			
		$val["label"] 					= "COLA";
		$val["type"] 					= "additions";
		$val["amount"] 					= $total_cola;
		$val["add.gross_pay"] 			= true;
		$val["deduct.gross_pay"] 		= false;
		$val["add.taxable_salary"] 		= false;
		$val["deduct.taxable_salary"] 	= false;
		$val["add.net_pay"] 			= false;
		$val["deduct.net_pay"] 			= false;
		array_push($return->_breakdown, $val);
		$val = null;

		return $return;
	}


	public static function cutoff_pro_rated_montly_cola($return, $data)
	{

		$total_cola = 0;
		
		if ($data["cutoff_input"][$data["start_date"]]->compute_type=="monthly" || $data["cutoff_input"][$data["start_date"]]->compute_type=="fix" ) 
		{
			if ($data["period_category"]=="Semi-monthly") 
			{
				$total_cola = @($data["salary"]->monthly_cola/2);
			}
			else if($data["period_category"]=="Weekly")
			{
				$total_cola = @($data["salary"]->monthly_cola/4);
			}
			else if($data["period_category"]=="Monthly")
			{
				$total_cola = $data["salary"]->monthly_cola;
			}
		}
		$deducted = 0;
		$added    = 0;
		foreach ($data["cutoff_input"] as $key => $cutoff_input) 
		{
			$total_cola = $total_cola - $cutoff_input->compute->total_day_cola_deduction;
			$total_cola = $total_cola + $cutoff_input->compute->total_day_cola_addition;

			/*checking*/
			$deducted 	+= $cutoff_input->compute->total_day_cola_deduction;
			$added 		+= $cutoff_input->compute->total_day_cola_addition;
		}

		$val["label"] = "COLA";
		$val["type"] = "additions";
		$val["amount"] = $total_cola;
		$val["add.gross_pay"] = true;
		$val["deduct.gross_pay"] = false;
		$val["add.taxable_salary"] = false;
		$val["deduct.taxable_salary"] = false;
		$val["add.net_pay"] = false;
		$val["deduct.net_pay"] = false;
		array_push($return->_breakdown, $val);
		$val = null;

		return $return;
	}



	public static function get_total_allowance_for_a_year($employee_id, $year , $category)
	{
		$_employee = Tbl_payroll_time_keeping_approved::EmployeePeriod($employee_id)->where('tbl_payroll_period.year_contribution',2017)->get();
		$_employee = Payroll2::unserialize_payroll_timesheet_approve_data($_employee);

		$total_allowance_year_taxable 		= 0;
		$total_allowance_year_hidden 		= 0;
		$total_allowance_year_none_taxable 	= 0;

		foreach ($_employee as $key => $employee) 
		{
			foreach ($employee['cutoff_breakdown']->_breakdown as $key => $cutoff_breakdown) 
			{
				
				if (isset($cutoff_breakdown['record_type'])) 
				{
					//if non taxable
					if ($category == "Non-Taxable" && $cutoff_breakdown['deduct.taxable_salary'] == true) 
					{
						if ($cutoff_breakdown['record_type'] == "allowance" || $cutoff_breakdown['record_type'] == "allowance_de_minimis") 
						{
							$total_allowance_year_none_taxable += $cutoff_breakdown['amount'];
						}
					}
					if ($category == "Taxable") 
					{
						# code...
					}
					if ($category == "Hidden") 
					{
						
					}
					
				}
			}
		}

		return $total_allowance_year_none_taxable;
	}


	public static function unserialize_payroll_timesheet_approve_data($_approve_time_sheet)
	{
		foreach ($_approve_time_sheet as $key => $approve_time_sheet) 
		{
			$_approve_time_sheet[$key]['cutoff_input'] 		= unserialize($approve_time_sheet['cutoff_input']);
			$_approve_time_sheet[$key]['cutoff_compute'] 	= unserialize($approve_time_sheet['cutoff_compute']);
			$_approve_time_sheet[$key]['cutoff_breakdown'] 	= unserialize($approve_time_sheet['cutoff_breakdown']);
		}

		return $_approve_time_sheet;
	}



	public static function cutoff_breakdown_allowance_v2($return, $data)
	{
		$_allowance = Tbl_payroll_employee_allowance_v2::where("payroll_employee_id", $data["employee_id"])->where('tbl_payroll_allowance_v2.payroll_allowance_archived',0)->joinAllowance()->get();
		$total_allowance_year_non_taxable = Payroll2::get_total_allowance_for_a_year($data["employee_id"], $data["period_info"]["year_contribution"], "Non-Taxable");
		
		foreach($_allowance as $key => $allowance)
		{
			$allowance_period = strtolower(str_replace(' ', '_', $allowance->payroll_allowance_add_period));

			$deminimis_limit_allowance = 82000; 

			if ($allowance_period == $data['period_info']['period_count'] || $allowance->payroll_allowance_add_period == "Every Period") 
			{
				$allowance_amount 	= $allowance->payroll_employee_allowance_amount;
				$allowance_name 	= $allowance->payroll_allowance_name;

				if($allowance->payroll_allowance_type == "fixed")
				{
					$val["amount"] 	= $allowance_amount;
				}

				else if($allowance->payroll_allowance_type == 'pro_rated')
				{

					$actual_gross_pay = 0;
					$standard_gross_pay = 0;
					$ot_category = array('Rest Day OT', 'Over Time', 'Legal Holiday Rest Day OT', 'Legal OT', 'Special Holiday Rest Day OT', 'Special Holiday OT');
					
					if ($allowance->basic_pay==1) 
					{
						if (isset($data['cutoff_compute']->cutoff_basic)) 
						{
							$actual_gross_pay += $data['cutoff_compute']->cutoff_basic;
							$d['basic'] = $data['cutoff_compute']->cutoff_basic;
						}
					}

					if ($allowance->cola==1) 
					{
						$actual_gross_pay += $data['cutoff_compute']->cutoff_cola;
						$d['cola'] = $data['cutoff_compute']->cutoff_cola;
					}

					$overtime = 0;
					$special_holiday = 0;
					$leave_pay = 0;
					$regular_holiday = 0;
					$deduction = 0;
					$count = 0;

					foreach ($data['cutoff_input'] as $value) 
					{
						if (isset($value->compute->_breakdown_addition)) 
						{
							foreach ($value->compute->_breakdown_addition as $lbl => $values) 
							{

								if ($allowance->over_time_pay==1) 
								{
									if (in_array($lbl, $ot_category)) 
									{
										$actual_gross_pay += $values['rate'];
										$overtime += $values['rate'];
									}
								}

								if($allowance->regular_holiday_pay==1)
								{
									if ($lbl == 'Legal Holiday' || $lbl == 'Legal Holiday Rest Day') 
									{
										$actual_gross_pay += $values['rate'];
										$regular_holiday += $values['rate'];
									}
								}

								if ($allowance->special_holiday_pay==1) 
								{
									if ($lbl == 'Special Holiday' || $lbl == 'Special Holiday Rest Day') 
									{
										$actual_gross_pay += $values['rate'];
										$special_holiday += $values['rate'];
									}
								}

								if ($allowance->leave_pay==1) 
								{
									if ($lbl == 'Leave Pay') 
									{
										$actual_gross_pay += $values['rate'];
										$leave_pay += $values['rate'];
									}
								}
							}
						}


						if (isset($value->compute->_breakdown_deduction)) 
						{
							foreach ($value->compute->_breakdown_deduction as $lbl => $values) 
							{
								if ($value->time_output["leave_hours"] != '00:00:00' || $lbl == 'late' || $lbl == 'undertime' ) 
								{
									$standard_gross_pay += $values['rate'];
									$deduction += $values['rate'];
								}
								// if ($data["group"]->payroll_group_salary_computation != "Daily Rate") 
								// {
								// 	if ($value->time_output["leave_hours"] == '00:00:00') 
								// 	{
								// 		$standard_gross_pay += $values['rate'];
								// 		$deduction += $values['rate'];
								// 	}
								// }
							}
						}

						$d['overtime'] = $overtime;
						$d['regular_holiday'] = $regular_holiday;
						$d['special_holiday'] = $special_holiday;
						$d['leave_pay'] = $leave_pay;
						$d['deductions'] = $deduction;
						$a[$count] = $d;
						$count++;
					}

					// dd($d);
					// dd($a);
					// dd($actual_gross_pay ." / " . $standard_gross_pay ." * " . $allowance_amount);
					$standard_gross_pay += $actual_gross_pay;
					$val["amount"] = (@($actual_gross_pay/$standard_gross_pay) * $allowance_amount) * $return->_time_breakdown["day_spent"]["float"];
					
					// dd($actual_gross_pay ."/". $standard_gross_pay ."*".$allowance_amount." = ".$val["amount"]."*".$return->_time_breakdown["day_spent"]["float"]);

					
				}
				else if ($allowance->payroll_allowance_type == 'daily') 
				{
					$val["amount"] = $allowance_amount * ($return->_time_breakdown["day_spent"]["float"] + $return->_time_breakdown["absent"]["float"]);
				}

				$val["label"] 	= $allowance_name;
				$val["type"] 	= "additions";
				$val["record_type"] = "allowance";
				
				/*Start Get Type*/
				if($allowance->payroll_allowance_category == "Taxable")
				{
					$val["add.gross_pay"] = true;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = false;
					$val["add.net_pay"] = false;
					$val["deduct.net_pay"] = false;
				}
				elseif ($allowance->payroll_allowance_category == "Hidden") 
				{
					$val["add.gross_pay"] = false;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = false;
					$val["add.net_pay"] = true;
					$val["deduct.net_pay"] = false;
				}
				else
				{
					$val["add.gross_pay"] = true;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = true;
					$val["add.net_pay"] = true;
					$val["deduct.net_pay"] = false;

					/*Start for deminis allowance payroll group*/
					if ($total_allowance_year_non_taxable < $deminimis_limit_allowance) 
					{
						//split if total allowance plus val for allowance now is greater than the limit
						if (($total_allowance_year_non_taxable + $val['amount']) > $deminimis_limit_allowance) 
						{
							$val["record_type"] 		 = "allowance_de_minimis";
							$excess_de_minimis_allowance = ($total_allowance_year_non_taxable + $val['amount']) - $deminimis_limit_allowance;
							$val['amount'] 				 = $val['amount'] - $excess_de_minimis_allowance;
							$total_allowance_year_non_taxable += $val['amount'];
							array_push($return->_breakdown, $val);
							
							$val["record_type"] 		 = "allowance";
							$val['amount'] 				 = $excess_de_minimis_allowance;
						}
						else
						{
							$total_allowance_year_non_taxable += $val['amount'];
							$val["record_type"] 		 	  = "allowance_de_minimis";
						}
					}
					/*End for deminis allowance payroll group*/
				}
				/*End Get Type of allowance*/
				
				array_push($return->_breakdown, $val);
				$val = null;
			}
		}
		
		return $return;
	}


	public static function cutoff_breakdown_adjustments($return, $data)
	{
		$_adjustment = Tbl_payroll_adjustment::where("payroll_period_company_id", $data["period_info"]->payroll_period_company_id)->where("payroll_employee_id", $data["employee_id"])->get();
		
		foreach($_adjustment as $adjustment)
		{
			$val["label"] 					= $adjustment->payroll_adjustment_name;
			$val["type"] 					= "adjustment";
			$val["category"]				= $adjustment->payroll_adjustment_category;
			$val["description"] 			= "This is a manual adjustment.<br>Click <a class='delete-adjustment' adjustment_id='" . $adjustment->payroll_adjustment_id . "' href='javascript:'>here</a> to delete this adjustment.";
			$val["amount"] 					= $adjustment->payroll_adjustment_amount;
			$val["add.gross_pay"] 			= ($adjustment->add_gross_pay == 1 ? true : false);
			$val["deduct.gross_pay"] 		= ($adjustment->deduct_gross_pay == 1 ? true : false);
			$val["add.taxable_salary"] 		= ($adjustment->add_taxable_salary == 1 ? true : false);
			$val["deduct.taxable_salary"] 	= ($adjustment->deduct_taxable_salary == 1 ? true : false);
			$val["add.net_pay"] 			= ($adjustment->add_net_pay == 1 ? true : false);
			$val["deduct.net_pay"] 			= ($adjustment->deduct_net_pay == 1 ? true : false);
			array_push($return->_breakdown, $val);
			$val = null;
		}

		return $return;
	}

	public static function cutoff_breakdown_deductions($return, $data)
	{
		/* DAILY DEDUCTIONS */
		
		if(isset($data["cutoff_compute"]->_breakdown_deduction_summary))
		{
			foreach($data["cutoff_compute"]->_breakdown_deduction_summary as $key => $breakdown)
			{

				$val["label"] = $key;
				$val["type"] = "deductions";
				$val["amount"] = $breakdown;
				$val["add.gross_pay"] = false;
				$val["deduct.gross_pay"] = false;
				$val["add.taxable_salary"] = false;
				$val["deduct.taxable_salary"] = false;
				$val["add.net_pay"] = false;
				$val["deduct.net_pay"] = false;
				array_push($return->_breakdown, $val);
				$val = null;
			}
		}
		extract($data);
		/* AGENCY DEDUCTION */
		if($group->payroll_group_agency == Payroll::return_ave($period_category))
		{
			$val["label"] = "Agency Fee";
			$val["type"] = "deductions";
			$val["amount"] = $group->payroll_group_agency_fee;
			$val["add.gross_pay"] = false;
			$val["deduct.gross_pay"] = false;
			$val["add.taxable_salary"] = false;
			$val["deduct.taxable_salary"] = false;
			$val["add.net_pay"] = false;
			$val["deduct.net_pay"] = true;

			array_push($return->_breakdown, $val);
			$val = null;
		}
		
		$deduction = Payroll::getdeduction($employee_id, $start_date, $period_category_arr['period_category'], $period_category, $shop_id);
			
		if(isset($deduction["deduction"]))
		{
			if(count($deduction["deduction"]) > 0)
			{
				foreach($deduction["deduction"] as $breakdown)
				{

					$val["label"] = $breakdown["deduction_name"];
					$val["type"] = "deductions";
					$val["amount"] = $breakdown["payroll_periodal_deduction"];
					$val["add.gross_pay"] = false;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = false;
					$val["add.net_pay"] = false;
					$val["deduct.net_pay"] = true;
			
					array_push($return->_breakdown, $val);
					$val = null;
				}
			}
		}

		$deduction = Payroll::getdeductionv2($employee_id, $end_date, $period_category_arr['period_category'], $period_category, $shop_id);
	
		if(isset($deduction["deduction"]))
		{
			if(count($deduction["deduction"]) > 0)
			{
				foreach($deduction["deduction"] as $breakdown)
				{
					$val["label"] = $breakdown["deduction_name"];
					$val["type"] = "deductions";
					$val["record_type"] = $breakdown['payroll_deduction_type'];
					$val["amount"] = $breakdown["payroll_periodal_deduction"];
					$val["add.gross_pay"] = false;
					$val["deduct.gross_pay"] = false;
					$val["add.taxable_salary"] = false;
					$val["deduct.taxable_salary"] = false;
					$val["add.net_pay"] = false;
					$val["deduct.net_pay"] = true;

					array_push($return->_breakdown, $val);
					
					$val = null;
				}
			}
		}
		return $return;
	}
	public static function cutoff_compute_break($payroll_period_company_id, $employee_id, $cutoff_compute)
	{
		$return 		= array();
		$return['obj']	= array();
	
		$return_array['employee']	= array();
		$return_array['employer']	= array();
		
		$employee_compute['get_net_basic_pay']	= Payroll2::get_net_basic_pay($cutoff_compute);
		$employee_compute['get_gross_pay']		= array();
		$employee_compute['get_taxable_salary']	= array();
		$employee_compute['get_net_pay']		= array();
		
		$total_deminimis = 0;
		$total_deduction = 0;

		/* get period details */
		$date_query 		= Tbl_payroll_period_company::sel($payroll_period_company_id)->first();
		$start_date 		= $date_query->payroll_period_start;
		$end_date 			= $date_query->payroll_period_end;
		$period_category 	= $date_query->payroll_period_category;
		
		/* GET PREVIOUS RECORD */
		$previous_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id);

		/* get employee contract */
		$group 	 =  Tbl_payroll_employee_contract::selemployee($employee_id, $start_date)
												->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
		                                        ->first();
		$shop_id =  $group->shop_id;      
		
		/* GET EMPLOYEE SALARY */
		$salary  =  Tbl_payroll_employee_salary::selemployee($employee_id, $start_date)->first();

		if($salary == null)
		{
			$salary = new stdClass();
			$salary->payroll_employee_salary_minimum_wage 	= 0;
			$salary->payroll_employee_salary_monthly 		= 0;
			$salary->payroll_employee_salary_daily 			= 0;
			$salary->payroll_employee_salary_taxable 		= 0;
			$salary->payroll_employee_salary_sss 			= 0;
			$salary->payroll_employee_salary_pagibig 		= 0;
			$salary->payroll_employee_salary_philhealth 	= 0;
			$salary->is_deduct_tax_default 					= 0;
			$salary->deduct_tax_custom 						= 0;
			$salary->is_deduct_sss_default 					= 0;
			$salary->deduct_sss_custom 						= 0;
			$salary->is_deduct_philhealth_default 			= 0;
			$salary->deduct_philhealth_custom 				= 0;
			$salary->is_deduct_pagibig_default 				= 0;
			$salary->deduct_pagibig_custom 					= 0;
			$salary->tbl_payroll_employee_custom_compute 	= 0;
		}
		
		/* GET EMPLOYEE DATA */
		$employee 			= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->first();
		
		/* ALLOWANCES */
		$allowances 		= Payroll2::get_allowance($employee_id, $cutoff_compute->render_days);
		
		/* allowances */
		$adj_allowance				= array();
		$adjustment_allowance 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Allowance')->get();
		$adj_allowance['total'] 	= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Allowance')->sum('payroll_adjustment_amount');
		$adj_allowance['name'] 		= 'adjustment_allowance';
		$adj_allowance['obj']  		= Payroll2::adjustment_breakdown($adjustment_allowance, 'add');
	
		/* bonus */
		$adj_bonus					= array();
		$adjustment_bonus 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Bonus')->get();
		$adj_bonus['total'] 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Bonus')->sum('payroll_adjustment_amount');
		$adj_bonus['name'] 			= 'adjustment_bonus';
		$adj_bonus['obj']  			= Payroll2::adjustment_breakdown($adjustment_bonus, 'add');
		
		/* commission */
		$adj_commission					= array();
		$adjustment_commission 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Commissions')->get();
		$adj_commission['total'] 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Commissions')->sum('payroll_adjustment_amount');
		$adj_commission['name'] 		= 'adjustment_commission';
		$adj_commission['obj']  		= Payroll2::adjustment_breakdown($adjustment_commission, 'add');
		
		/* incentives */
		$adj_incentives					= array();
		$adjustment_incentives 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Incentives')->get();
		$adj_incentives['total'] 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Incentives')->sum('payroll_adjustment_amount');
		$adj_incentives['name'] 		= 'adjustment_incentives';
		$adj_incentives['obj']  		= Payroll2::adjustment_breakdown($adjustment_incentives, 'add');
	
		
		/* 13 month pay */
		$adj_13m						= array();
		$adjustment_13_month 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, '13 month pay')->get();
		$adj_13m['total'] 				= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , '13 month pay')->sum('payroll_adjustment_amount');
		$adj_13m['name'] 				= 'adjustment_13_month';
		$adj_13m['obj']  				= Payroll2::adjustment_breakdown($adjustment_13_month, 'add');
		
		/* deductions */
		$adj_deduction					= array();
		$adjustment_deductions 			= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Deductions')->get();
		$adj_deduction['total'] 		= Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Deductions')->sum('payroll_adjustment_amount');
		$adj_deduction['name'] 			= 'adjustment_deductions';
		$adj_deduction['obj']  			= Payroll2::adjustment_breakdown($adjustment_deductions, 'minus');
		
		/* GET 13 MONTH PAY */
		$n13_month = 0;
		if(isset($group->payroll_group_13month_basis))
		{
			$n13_month = Payroll2::get_13_month($group->payroll_group_13month_basis, $cutoff_compute->cutoff_basic, $employee_id, $payroll_period_company_id);
		}

		$total_deminimis += $n13_month;
		
		$employee_compute['get_gross_pay']		= Payroll2::get_gross_pay($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month);
	
		/* GET PREVIOUS RECORD OF GOVERNMENT CONTRIBUTION */
		$date_period[0] = date('Y-m-01', strtotime($end_date));
		$date_period[1] = date('Y-m-t', strtotime($end_date));
		
		/* GET ALL PREVIOUS RECORD */
		$pevious_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id);
		// dd($pevious_record);
		
		/* GET SSS CONTRIBUTION */
		$sss_salary				= $salary->payroll_employee_salary_sss;
		$sss_reference			= '(REF. DECLARED)';
		if($group->sss_reference == 'net_basic')
		{
			$sss_salary 	= $employee_compute['get_net_basic_pay']['total_net_basic'];
			$sss_reference	= '(REF. NET BASIC)';
		}
		
		if($group->sss_reference == 'gross_basic')
		{
			$sss_salary 	= $employee_compute['get_gross_pay']['total_gross_pay'];
			$sss_reference	= '(REF. GROSS PAY)';
		}
		
		$sss_record = $previous_record;
		
		if($group->payroll_group_sss == '1st Period')
		{
			$sss_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, true);
			// dd($sss_record);
		}
		if($group->payroll_group_sss == 'Last Period')
		{
			$sss_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, false, true);
		}
		$sss_every = false;
		if($group->payroll_group_sss == 'Every Period')
		{
			$sss_every = true;
		}
		$sss_contribution = Payroll2::get_sss_contribution($shop_id, $sss_salary, $sss_record, $sss_every);
		
		// dd(Payroll2::period_count($group));
		if(Payroll2::period_count($date_query->period_count) != $group->payroll_group_sss && $group->payroll_group_sss != 'Every Period')
		{
			$sss_contribution['ee'] = 0;
			$sss_contribution['er'] = 0;
			$sss_contribution['ec'] = 0;
		}
	
		
		$sss_ee					= $sss_contribution['ee'];
		$sss_er					= $sss_contribution['er'];
		$sss_ec					= $sss_contribution['ec'];
		$sss_data['amount']		= $sss_ee;
		$sss_data['ref']		= $sss_reference;
		$sss_data['ref_amount']	= $sss_salary;

		/* GET PHILHEALTH CONTRIBUTION */
		$philhealth_salary			= $salary->payroll_employee_salary_philhealth;
		$philhealth_reference		= '(REF. DECLARED)';

		if($group->philhealth_reference == 'net_basic')
		{
			$philhealth_salary		= $employee_compute['get_net_basic_pay']['total_net_basic'];
			$philhealth_reference	= '(REF. NET BASIC)';
		}
		
		if($group->philhealth_reference == 'gross_basic')
		{
			$philhealth_salary 	= $employee_compute['get_gross_pay']['total_gross_pay'];
			$philhealth_reference	= '(REF. GROSS PAY)';
		}
	
		$phil_record = $previous_record;

		if($group->payroll_group_philhealth == '1st Period')
		{
			$phil_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, true);
		}

		if($group->payroll_group_philhealth == 'Last Period')
		{
			$phil_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, false, true);
		}
		
		$phil_every = false;

		if($group->payroll_group_sss == 'Every Period')
		{
			$phil_every = true;
		}
	
		$philhealth_contribution 		= Payroll2::get_philhealth_contribution($shop_id, $philhealth_salary, $phil_record, $phil_every);
		
		if(Payroll2::period_count($date_query->period_count) != $group->payroll_group_philhealth && $group->payroll_group_philhealth != 'Every Period')
		{
			$philhealth_contribution['ee'] = 0;
			$philhealth_contribution['er'] = 0;
		}
		
		$philhealth_ee 					= $philhealth_contribution['ee'];
		$philhealth_er 					= $philhealth_contribution['er'];
		$philhealth_data['amount']		= $philhealth_ee;
		$philhealth_data['ref']			= $philhealth_reference;
		$philhealth_data['ref_amount']	= $philhealth_salary;

		// GET PAGIBIG CONTRIBUTION
		$pagibig_salary				= $salary->payroll_employee_salary_pagibig;
		$pagibig_reference			= '(REF. DECLARED)';
		if($group->pagibig_reference == 'net_basic')
		{
			$pagibig_salary 		= $employee_compute['get_net_basic_pay']['total_net_basic'] ;
			$pagibig_reference		= '(REF. NET BASIC)';
		}
		
		if($group->pagibig_reference == 'gross_basic')
		{
			$pagibig_salary 	= $employee_compute['get_gross_pay']['total_gross_pay'];
			$pagibig_reference	= '(REF. GROSS PAY)';
		}
		
		$pagibig_record = $previous_record;
		if($group->payroll_group_pagibig == '1st Period')
		{
			$pagibig_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, true);
		}
		if($group->payroll_group_pagibig == 'Last Period')
		{
			$pagibig_record = Payroll2::getcontribution_record($employee_id, $payroll_period_company_id, false, true);
		}
		
		$pagibig_every = false;
		if($group->payroll_group_sss == 'Every Period')
		{
			$pagibig_every = true;
		}
		
		$pagibig_contribution 		= Payroll2::get_pagibig_contribution($shop_id, $pagibig_salary, $pagibig_record, $pagibig_every);
		

		if(Payroll2::period_count($date_query->period_count) != $group->payroll_group_pagibig && $group->payroll_group_pagibig != 'Every Period')
		{
			$pagibig_contribution['ee'] = 0;
			$pagibig_contribution['er'] = 0;
		}

		$pagibig_ee 				= $pagibig_contribution['ee'];
		$pagibig_er 				= $pagibig_contribution['er'];
		$total_deduction			+= $pagibig_ee;
		$pagibig_data['amount']		= $pagibig_ee;
		$pagibig_data['ref']		= $pagibig_reference;
		$pagibig_data['ref_amount']	= $pagibig_salary;
		
		$employee_compute['get_taxable_salary']	= Payroll2::get_taxable_salary($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month, $employee_compute['get_gross_pay']['total_gross_pay'], $sss_data, $philhealth_data, $pagibig_data);

		$total_taxable_dynamic = $employee_compute['get_taxable_salary']['total_taxable'];
		
		$pevious_record = Tbl_payroll_record::getdate($shop_id, $date_period)
											->where('tbl_payroll_period.payroll_period_category', $period_category)
											->where('payroll_employee_id',$employee_id)
											->get()
											->toArray();

		$previous_tax 			= 0;
		$period_category_arr	 	= Payroll::getperiodcount($shop_id, $end_date, $period_category, $start_date);
		
		if($group->tax_reference == 'declared')
		{
			$employee_compute['get_taxable_salary']['taxable_status'] = ' (REF. DECLARED)';
			$employee_compute['get_taxable_salary']['total_taxable'] = $salary->payroll_employee_salary_taxable / $period_category_arr['period_count'];
		}
		
		if($group->tax_reference == 'net_basic')
		{
			$employee_compute['get_taxable_salary']['total_taxable'] 	= $employee['get_net_basic_pay']['total_net_basic'];
			$employee_compute['get_taxable_salary']['taxable_status'] = ' (REF. NET BASIC)';
		}
		
		if($salary->payroll_employee_salary_minimum_wage == 1)
		{
			$employee_compute['get_taxable_salary']['total_taxable'] = 0;
		}
		
		
		
		/* GET TAX CONTRIBUTION */
		$tax_contribution = Payroll2::get_standard_tax($shop_id, $group->payroll_group_tax, $period_category, $employee_compute['get_taxable_salary']['total_taxable'], $employee->payroll_employee_tax_status, $period_category_arr, $date_query->period_count);
		
		/* AGENCY DEDUCTION */
		$agency_deduction = 0;
		if($group->payroll_group_agency == Payroll::return_ave($period_category))
		{
			$agency_deduction = $group->payroll_group_agency_fee;
		}
		
		$temp['name']	= 'agency_deduction';
		$temp['obj']	= Payroll2::computation_array('AGENCY DEDUCTION', $agency_deduction, 'minus');
		array_push($return['obj'], $temp);

		$deduction = Payroll::getdeduction($employee_id, $start_date, $period_category_arr['period_category'], $period_category, $shop_id);
		$total_deduction += $deduction['total_deduction'];

		$temp['name'] = 'deduction';
		$temp['obj'] = Payroll2::deducton_breakdown($deduction['deduction']);
		array_push($return['obj'], $temp);
		
		$employee_compute['get_net_pay'] = Payroll2::get_net_pay($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month, $adj_deduction, $total_taxable_dynamic, $agency_deduction, $tax_contribution, $deduction);
		$return_array['employer']	= Payroll2::get_employer_contribution($sss_contribution, $philhealth_contribution, $pagibig_contribution);
		$return_array['employee']	=$employee_compute;
		
		return 	$return_array;
	}

	public static function get_13_month($payroll_group_13month_basis, $basic_pay, $employee_id, $payroll_period_company_id)
	{
		$n13_month = 0;
		if($payroll_group_13month_basis == 'Periodically')
		{
			$n13_month = round(($basic_pay / 12), 2);
		}

		if($payroll_group_13month_basis == 'Custom Period')
		{
			$m13 = Payroll::compute_13_month($employee_id, $payroll_period_company_id);
			$n13_month += $m13['data_13m'];
			$count_v = Tbl_payroll_13_month_virtual::getperiod($employee_id, $payroll_period_company_id)->count();
			if($count_v == 1)
			{
				$n13_month += round(($basic_pay / 12), 2);
			}
		}

		return $n13_month;
	}

	public static function get_sss($shop_id, $salary_sss = 0, $period_category_arr, $payroll_group_sss, $is_deduct_sss_default = 0, $deduct_sss_custom = 0, $previous_sss = 0, $period_count = 'first_period')
	{
		$sss_contribution			= Payroll::sss_contribution($shop_id, $salary_sss);
		$sss_contribution_ee_def 	= $sss_contribution['ee'];
		$period_category 			= $period_category_arr['period_category'];
		$data = null;
		
	
		if($payroll_group_sss == 'Every Period')
		{
			$data['sss_ee'] = divide($sss_contribution_ee_def, $period_category_arr['count_per_period']);
			$data['sss_er'] = divide($sss_contribution['er'], $period_category_arr['count_per_period']);
			$data['sss_ec'] = divide($sss_contribution['ec'], $period_category_arr['count_per_period']);
			
			if($is_deduct_sss_default == 0)
			{	
				$data['sss_ee'] = $deduct_sss_custom;

				if($period_category_arr['period_category'] == 'Last Period')
				{
					$data['sss_ee'] = $sss_contribution_ee_def - $previous_sss;
				}
			}	
		}

		else if($payroll_group_sss == $period_category)
		{
			
			if(Payroll::return_ave($period_category) == '1st Period')
			{
				$data['sss_ee'] = $sss_contribution['ee'];
				$data['sss_er'] = $sss_contribution['er'];
				$data['sss_ec'] = $sss_contribution['ec'];
				// dd('1st Period');
			}
			else if(Payroll::return_ave($period_category) == '2nd Period')
			{
				$data['sss_ee'] = divide($sss_contribution['ee'], $period_category_arr['count_per_period']) * 2;
				$data['sss_er'] = divide($sss_contribution['er'], $period_category_arr['count_per_period']) * 2;
				$data['sss_ec'] = divide($sss_contribution['ec'], $period_category_arr['count_per_period']) * 2;
			}

			else if(Payroll::return_ave($period_category) == 'Last Period')
			{
				$data['sss_ee'] = $sss_contribution['ee'];
				$data['sss_er'] = $sss_contribution['er'];
				$data['sss_ec'] = $sss_contribution['ec'];
			}	
		}

		return $data;
	}
	
	public static function get_philhealth($shop_id, $payroll_group_philhealth,$salary_philhealth, $period_category_arr,$is_deduct_philhealth_default = 0, $deduct_philhealth_custom = 0,$previous_philhealth = 0, $period_count = 'first_period')
	{
		$philhealth_contribution 	= Payroll::philhealth_contribution($shop_id, $salary_philhealth);
		$data['philhealth_ee']		= 0;
		$data['philhealth_er']		= 0;
		$period_category			= $period_category_arr['period_category'];

		if($payroll_group_philhealth == 'Every Period')
		{
			// philhealth_contribution
			$philhealth_contribution_ee_def = $philhealth_contribution['ee'];

			$data['philhealth_ee'] = divide($philhealth_contribution_ee_def, $period_category_arr['count_per_period']);
			$data['philhealth_er'] = divide($philhealth_contribution['er'] , $period_category_arr['count_per_period']);

			if($is_deduct_philhealth_default == 0)
			{
				$data['philhealth_ee'] = $deduct_philhealth_custom;
				if($period_category == 'Last Period')
				{
					$data['philhealth_ee'] = $philhealth_contribution_ee_def - $previous_philhealth;
				}
			}
		}

		else if($payroll_group_philhealth == $period_category)
		{
		
			if($period_count == 'last_period' || $period_count == 'first_period')
			{
				$data['philhealth_ee'] = $philhealth_contribution['ee'];
				$data['philhealth_er'] = $philhealth_contribution['er'];
			}
			
			// if(Payroll::return_ave($period_category) == '1st Period')
			// {
			// 	$data['philhealth_ee'] = $philhealth_contribution['ee'];
			// 	$data['philhealth_er'] = $philhealth_contribution['er'];
			// }
			// else if(Payroll::return_ave($period_category) == '2nd Period')
			// {
			// 	$data['philhealth_ee'] = divide($philhealth_contribution['ee'], $period_category_arr['count_per_period']) * 2;
			// 	$data['philhealth_er'] = divide($philhealth_contribution['er'], $period_category_arr['count_per_period']) * 2;
			// }

			// else if(Payroll::return_ave($period_category) == 'Last Period')
			// {
			// 	$data['philhealth_ee'] = $philhealth_contribution['ee'];
			// 	$data['philhealth_er'] = $philhealth_contribution['er'];
			// }
		}


		return $data;
	}

	public static function get_pagibig($shop_id, $salary_pagibig, $payroll_group_pagibig, $period_category_arr, $is_deduct_pagibig_default = 0, $period_category, $deduct_pagibig_custom = 0, $period_count = 'first_period')
	{

		$pagibig_contribution_def = Payroll::pagibig_contribution($shop_id, $salary_pagibig);
		// dd($is_deduct_pagibig_default);
		$data['pagibig_ee'] = 0;
		$data['pagibig_er']	= 0;
		if($payroll_group_pagibig == 'Every Period')
		{
			$pagibig_contribution = divide($pagibig_contribution_def, $period_category_arr['count_per_period']);
			$data['pagibig_ee'] = $pagibig_contribution;
			$data['pagibig_er'] = $pagibig_contribution;
			if($is_deduct_pagibig_default == 0)
			{
				$data['pagibig_ee'] = $data['deduct_pagibig_custom'];

				if($period_category == 'Last Period')
				{
					$data['pagibig_ee'] = $pagibig_contribution_def - $previous_pagibig;
				}
			}
		}

		else if($payroll_group_pagibig == $period_category)
		{

			if($period_count == 'first_period' || $period_count == 'last_period')
			{
				$data['pagibig_ee'] = $pagibig_contribution;
			}
			// if(Payroll::return_ave($period_category) == '1st Period')
			// {
			// 	$data['pagibig_ee'] = $pagibig_contribution;
				
			// }
			// else if(Payroll::return_ave($period_category) == '2nd Period')
			// {
			// 	$data['pagibig_ee'] = divide($pagibig_contribution, $period_category_arr['count_per_period']) * 2;
			// }

			// else if(Payroll::return_ave($period_category) == 'Last Period')
			// {
			// 	$data['pagibig_ee'] = $pagibig_contribution;
			// }
		}

		return $data;
	}

	public static function get_tax($shop_id, $minimum_wage = 0, $payroll_group_tax, $period_category, $payroll_group_before_tax = 0, $salary_taxable = 0, $sss_ee = 0, $philhealth_ee = 0, $pagibig_ee = 0, $tax_status, $period_category_arr)
	{
		$tax_contribution = 0;
		if($minimum_wage == 0)
		{
			// dd($payroll_group_tax);
			// if($payroll_group_tax == 'Every Period' || $payroll_group_tax == $period_category_arr['period_category'])
			if($payroll_group_tax == 'Every Period' )
			{
				if($payroll_group_before_tax == 0)
				{
					$salary_taxable = $salary_taxable - ($sss_ee + $philhealth_ee + $pagibig_ee);
				}
				
				if($salary_taxable <= 0)
				{
					$salary_taxable = 0;
				}
				
				$tax_contribution = divide(Payroll::tax_contribution($shop_id, $salary_taxable, $tax_status, $period_category), $period_category_arr['count_per_period']);

				if($payroll_group_tax == 'Last Period')
				{
					$tax_contribution = $tax_contribution * $period_category_arr['count_per_period'];
				}	
			}
			
		}
		return $tax_contribution;
	}
	
	public static function get_standard_tax($shop_id, $payroll_group_tax, $period_category, $salary_taxable = 0, $tax_status, $period_category_arr, $period_count = 'first_period')
	{
		$tax_contribution = 0;
	
		if($payroll_group_tax == 'Every Period' )
		{
		
			if($salary_taxable <= 0)
			{
				$salary_taxable = 0;
			}
			
			$tax_contribution = Payroll::tax_contribution($shop_id, $salary_taxable, $tax_status, $period_category);
			// dd($salary_taxable);
			if($payroll_group_tax == 'Last Period')
			{
				$tax_contribution = $tax_contribution * $period_category_arr['count_per_period'];
			}	
		}

		return $tax_contribution;
	}

	public static function get_allowance($employee_id, $rendered_days = 0)
	{
		$data['obj'] 		= array();
		$data['total'] 		= 0;
		/* DAILY ALLOWANCES */
		$_allowance_daily = Tbl_payroll_employee_allowance::employee_allowance($employee_id,'daily')
													->orderBy('tbl_payroll_allowance.payroll_allowance_name')
													->select('tbl_payroll_allowance.*')
													->get();
		foreach($_allowance_daily as $daily)
		{
			$temp['name'] = $daily->payroll_allowance_name;
			$temp['amount'] = $daily->payroll_allowance_amount * $rendered_days;
			$temp['type']	= 'add';

			if($temp['amount'] > 0)
			{
				array_push($data['obj'], $temp);
			}

			$data['total'] += $temp['amount'];
		}

		/* PER PERIOD ALLOWANCES */
		$_allowance_fixed = Tbl_payroll_employee_allowance::employee_allowance($employee_id,'fixed')
														->orderBy('tbl_payroll_allowance.payroll_allowance_name')
														->select('tbl_payroll_allowance.*')
														->get();
		foreach($_allowance_fixed as $fixed)
		{
			$temp['name'] = $fixed->payroll_allowance_name;
			$temp['amount'] = $fixed->payroll_allowance_amount;
			$temp['type']	= 'add';
			array_push($data['obj'], $temp);
			$data['total'] += $temp['amount'];
		}


		return $data;
	}
	
	public static function adjustment_breakdown($_adjustment, $type = 'add')
	{
		$return = array();
		foreach($_adjustment as $adjustment)
		{
			$temp['name']	= $adjustment->payroll_adjustment_name;
			$temp['amount']	= $adjustment->payroll_adjustment_amount;
			$temp['type']	= $type;
			
			array_push($return, $temp);
		}

		return $return;
	}

	public static function computation_array($name, $amount, $type)
	{
		$data 			= array();
		$data['name'] 	= $name;
		$data['amount'] = $amount;
		$data['type'] 	= $type;

		return $data;
	}

	public static function deducton_breakdown($_deduction = array())
	{
		$data = array();
		foreach($_deduction as $deduction)
		{
			$temp['name'] 	= $deduction['deduction_name'];
			$temp['amount'] = $deduction['payroll_periodal_deduction'];
			$temp['type']	= 'minus';
			array_push($data, $temp);
		}

		return $data;
	}
	
	public static function convert_period_cat($cat = '')
	{
		$return = 'daily';
		if($cat == 'Daily Rate')
		{
			$return = 'daily';
		}
		if($cat == 'Monthly Rate')
		{
			$return = 'monthly';
		}
		if ($cat == 'Hourly Rate') 
		{
			$return = 'hourly';
		}
		if($cat == 'Flat Rate')
		{
			$return = 'fix';
		}
		
		return $return;
	}
	
	public static function get_net_basic_pay($cutoff_compute)
	{
		$data['total_net_basic']	= $cutoff_compute->cutoff_basic;
		$data['obj']				= array();
		if(isset($cutoff_compute->_breakdown_deduction_summary))
		{
			foreach($cutoff_compute->_breakdown_deduction_summary as $key => $amount)
			{
				$temp['name']	= $key;
				$temp['amount']	= $amount;
				$temp['type']	= 'less';
				array_push($data['obj'], $temp);
			}
		}
		return $data;
	}
	
	public static function get_gross_pay($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month)
	{
		$data['total_gross_pay']	= $cutoff_compute->cutoff_basic;
		$data['obj']				= array();
		
		if(isset($cutoff_compute->_breakdown_addition_summary))
		{

			foreach($cutoff_compute->_breakdown_addition_summary as $key => $amount)
			{
				$temp['name']	= $key;
				$temp['amount']	= $amount;
				$temp['type']	= 'add';
				$data['total_gross_pay'] += $amount;
				array_push($data['obj'], $temp);
			}
		}
		
		foreach($allowances['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_allowance['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		
		foreach($adj_bonus['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		
		foreach($adj_commission['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_incentives['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_13m['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		if($n13_month > 0)
		{
			$temp['name']	= '13 MONTH PAY';
			$temp['amount']	= $n13_month;
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $n13_month;
			array_push($data['obj'], $temp);
		}
		
		if($cutoff_compute->cutoff_cola > 0)
		{
			$temp['name']	= 'COLA';
			$temp['amount']	= $cutoff_compute->cutoff_cola;
			$temp['type']	= 'add';
			$data['total_gross_pay'] += $cutoff_compute->cutoff_cola;
			array_push($data['obj'], $temp);
		}
		
		// dd($data);
		return $data;
	}
	
	public static function get_taxable_salary($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month, $total_gross_pay, $sss_ee, $philhealth_ee, $pagibig_ee)
	{
		// dd($total_gross_pay);
		$data['total_taxable']	= $total_gross_pay;
		$data['taxable_status']	= '(REF. COMPUTED TAXABLE SALARY)';
		$data['obj']				= array();
		// dd($total_gross_pay);
		
		$temp = array();
		if($sss_ee > 0)
		{
			$temp['name']	= 'SSS';
			$temp['amount']	= $sss_ee['amount'];
			$temp['type']	= 'less';
			$temp['ref']	= $sss_ee['ref'];
			$temp['ref_amount'] = $sss_ee['ref_amount'];
			$data['total_taxable'] -= $sss_ee['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		if($philhealth_ee > 0)
		{
			$temp['name']	= 'PHILHEALTH';
			$temp['amount']	= $philhealth_ee['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= $philhealth_ee['ref'];
			$temp['ref_amount'] = $philhealth_ee['ref_amount'];
			$data['total_taxable'] -= $philhealth_ee['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		if($pagibig_ee > 0)
		{
			$temp['name']	= 'PAGIBIG';
			$temp['amount']	= $pagibig_ee['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= $pagibig_ee['ref'];
			$temp['ref_amount'] = $pagibig_ee['ref_amount'];
			$data['total_taxable'] -= $pagibig_ee['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($allowances['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($adj_allowance['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($adj_bonus['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($adj_commission['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($adj_incentives['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		foreach($adj_13m['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		if($n13_month > 0)
		{
			$temp['name']	= '13 MONTH PAY';
			$temp['amount']	= $n13_month;
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $n13_month;
			array_push($data['obj'], $temp);
		}
		
		$temp = array();
		if($cutoff_compute->cutoff_cola > 0)
		{
			$temp['name']	= 'COLA';
			$temp['amount']	= $cutoff_compute->cutoff_cola;
			$temp['type']	= 'minus';
			$temp['ref']	= '';
			$temp['ref_amount'] = null;
			$data['total_taxable'] -= $cutoff_compute->cutoff_cola;
			array_push($data['obj'], $temp);
		}
		
		return $data;
	}
	
	public static function get_net_pay($cutoff_compute, $allowances, $adj_allowance, $adj_bonus, $adj_commission, $adj_incentives, $adj_13m, $n13_month, $adj_deduction, $total_taxable_dynamic, $agency_deduction, $tax_contribution, $_deduction)
	{
		$data['total_net_pay']	= $total_taxable_dynamic;
		$data['obj']			= array();
		
	
		foreach($allowances['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_allowance['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		
		foreach($adj_bonus['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		
		foreach($adj_commission['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_incentives['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_13m['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'add';
			$data['total_net_pay'] += $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		if($n13_month > 0)
		{
			$temp['name']	= '13 MONTH PAY';
			$temp['amount']	= $n13_month;
			$temp['type']	= 'add';
			$data['total_net_pay'] += $n13_month;
			array_push($data['obj'], $temp);
		}
		
		if($cutoff_compute->cutoff_cola > 0)
		{
			$temp['name']	= 'COLA';
			$temp['amount']	= $cutoff_compute->cutoff_cola;
			$temp['type']	= 'add';
			$data['total_net_pay'] += $cutoff_compute->cutoff_cola;
			array_push($data['obj'], $temp);
		}
		
		// if($tax_contribution > 0)
		// {
		// 	$temp['name']	= 'TAX';
		// 	$temp['amount']	= $tax_contribution;
		// 	$temp['type']	= 'minus';
		// 	$data['total_net_pay'] -= $tax_contribution;
		// 	array_push($data['obj'], $temp);
		// }
		$temp['name']	= 'TAX';
		$temp['amount']	= $tax_contribution;
		$temp['type']	= 'minus';
		$data['total_net_pay'] -= $tax_contribution;
		array_push($data['obj'], $temp);
		
		if($agency_deduction > 0)
		{
			$temp['name']	= 'AGENCY DEDUCTION';
			$temp['amount']	= $agency_deduction;
			$temp['type']	= 'minus';
			$data['total_net_pay'] -= $agency_deduction;
			array_push($data['obj'], $temp);
		}
		
		foreach($adj_deduction['obj'] as $key => $obj)
		{
			$temp['name']	= $obj['name'];
			$temp['amount']	= $obj['amount'];
			$temp['type']	= 'minus';
			$data['total_net_pay'] -= $obj['amount'];
			array_push($data['obj'], $temp);
		}
		
		foreach($_deduction['deduction'] as $key => $deduction)
		{
			$temp['name']	= $deduction['deduction_name'];
			$temp['amount']	= $deduction['payroll_periodal_deduction'];
			$temp['type']	= 'minus';
			$data['total_net_pay'] -= $deduction['payroll_periodal_deduction'];
			array_push($data['obj'], $temp);
		}
		
		// dd($data);
		return $data;
	}
	
	public static function get_employer_contribution($sss_contribution, $philhealth_contribution, $pagibig_contribution)
	{
	
		$data = array();
		
		$temp['name'] = 'SSS ER';
		$temp['amount'] = $sss_contribution['er'];
		array_push($data, $temp);
		
		$temp['name'] = 'SSS EC';
		$temp['amount'] = $sss_contribution['ec'];
		array_push($data, $temp);
		
		$temp['name'] = 'PHILHEALTH ER';
		$temp['amount'] = $philhealth_contribution['er'];
		array_push($data, $temp);
		
		$temp['name'] = 'PAGIBG ER';
		$temp['amount'] = $pagibig_contribution['er'];
		array_push($data, $temp);
		
		return $data;
	}
	
	public static function getcontribution_record($employee_id, $payroll_period_company_id, $isfirst = false, $islast = false)
	{
		$period = Tbl_payroll_period_company::getcompanyperiod($payroll_period_company_id)->first();
		// dd($isfirst);
		$month_number = date('m', strtotime($period->month_contribution.' 01, 2000'));
		
		if($isfirst)
		{
			
			$month_number -= 1;
			
			if($month_number < 1)
			{
				$month_number = 12;
				$period->year_contribution --;
			}
			if($month_number <= 9)
			{
				$month_number = '0'.$month_number;
			}
			
			$period->month_contribution = date('F', strtotime('2000-'.$month_number.'-01'));

		}
		
		$_record = Tbl_payroll_time_keeping_approved::monthrecord($employee_id, $period->payroll_period_category, $period->month_contribution, $period->year_contribution, $isfirst, $islast)
													->select(DB::raw('IFNULL(sum(tbl_payroll_time_keeping_approved.net_basic_pay), 0) as net_basic_pay, IFNULL(sum(tbl_payroll_time_keeping_approved.gross_pay), 0) as gross_pay, IFNULL(sum(tbl_payroll_time_keeping_approved.taxable_salary), 0) as taxable_salary, IFNULL(sum(tbl_payroll_time_keeping_approved.net_pay), 0) as net_pay, IFNULL(sum(tbl_payroll_time_keeping_approved.sss_salary), 0) as sss_salary, IFNULL(sum(tbl_payroll_time_keeping_approved.sss_ee), 0) as sss_ee, IFNULL(sum(tbl_payroll_time_keeping_approved.sss_er), 0) as sss_er, IFNULL(sum(tbl_payroll_time_keeping_approved.sss_ec), 0) as sss_ec, IFNULL(sum(tbl_payroll_time_keeping_approved.phihealth_salary), 0) as phihealth_salary, IFNULL(sum(tbl_payroll_time_keeping_approved.philhealth_ee), 0) as philhealth_ee, IFNULL(sum(tbl_payroll_time_keeping_approved.philhealth_er), 0) as philhealth_er, IFNULL(sum(tbl_payroll_time_keeping_approved.pagibig_salary), 0) as pagibig_salary, IFNULL(sum(tbl_payroll_time_keeping_approved.pagibig_ee), 0) as pagibig_ee, IFNULL(sum(tbl_payroll_time_keeping_approved.pagibig_er), 0) as pagibig_er'))
													->first();
		
		// dd($_record);
		return $_record;
	}
	


	public static function get_sss_contribution($shop_id, $sss_salary = 0, $record, $isevery_period = false)
	{
		// dd($record);
		if(!$isevery_period)
		{
			$sss_salary += $record->sss_salary;
		}
		// dd($sss_salary);
		$sss_contribution	= Payroll::sss_contribution($shop_id, $sss_salary);
		// dd($sss_contribution);
		if($record->sss_ee > 0 && !$isevery_period)
		{
			$sss_contribution['ee'] -= $record->sss_ee;
			$sss_contribution['er'] -= $record->sss_er;
			$sss_contribution['ec'] -= $record->sss_ec;
		}
		// dd($sss_contribution);
		return $sss_contribution;
	}

	public static function get_philhealth_contribution($shop_id, $philhealth_salary = 0, $record, $isevery_period = false)
	{
		if(!$isevery_period)
		{
			$philhealth_salary += $record->phihealth_salary;
		}
		
		$philhealth_contribution 	= Payroll::philhealth_contribution($shop_id, $philhealth_salary);
		if($record->philhealth_ee > 0 && !$isevery_period)
		{
			$philhealth_contribution['ee'] -= $record->philhealth_ee;
			$philhealth_contribution['er'] -= $record->philhealth_er;
		}
		
		return $philhealth_contribution;
		
	}

	public static function get_pagibig_contribution($shop_id, $pagibig_salary = 0, $record, $isevery_period = false)
	{
	
		if(!$isevery_period)
		{
			$pagibig_salary += $record->pagibig_salary;
		}
		$pagibig_contribution_def = Payroll::pagibig_contribution($shop_id, $pagibig_salary);
		// dd($is_deduct_pagibig_default);
		$data['ee'] = $pagibig_contribution_def;
		$data['er']	= $pagibig_contribution_def;
		if($data['er'] > 100)
		{
			$data['er'] = 100;
		}
		if($record->pagibig_ee > 0 && !$isevery_period)
		{
			$data['ee'] -= $record->pagibig_ee;
			$data['er'] -= $record->pagibig_er;
		}
		return $data;
	}
	
	public static function period_count($period_count)
	{
		$period = '';
		switch($period_count)
		{
			case 'last_period' : 
				$period = 'Last Period';
				break;
			case 'first_period' : 
				$period = '1st Period';
				break;
		}
		
		return $period;
	}


	public static function get_total_payroll_register($data)
	{
		$test = array();
		$total_gross_basic 			= 0;
		$total_basic 				= 0;
		$total_gross	 			= 0;
		$total_cutoff_basic 		= 0;
		$total_net 					= 0;
		$total_tax 					= 0;

		$g_total_er 				= 0;
		$g_total_ee 				= 0;
		$g_total_ec 				= 0;

		$total_sss_ee 				= 0;
		$total_sss_er 				= 0;
		$total_sss_ec 				= 0;
		$total_philhealth_ee 		= 0;
		$total_philhealth_er 		= 0;
		$total_pagibig_ee 			= 0;
		$total_pagibig_er 			= 0;
		$total_deduction 			= 0;

		$total_deduction_employee 	= 0;

		$_other_deduction 					= null;
		$_addition 							= null;
		$_deduction 						= null;

		$deduction_total 				= 0;
		$cola_total 					= 0;
		$sss_ee_total 					= 0;
		$sss_er_total 					= 0;
		$sss_ec_total 					= 0;
		$hdmf_ee_total 					= 0;
		$hdmf_er_total 					= 0;
		$philhealth_ee_total 			= 0;
		$philhealth_er_total 			= 0;
		$witholding_tax_total 			= 0;
		$adjustment_deduction_total 	= 0;
		$adjustment_allowance_total 	= 0;
		$allowance_total 				= 0;
		$allowance_de_minimis_total 	= 0;
		$cash_bond_total 				= 0;
		$cash_advance_total				= 0;
		$hdmf_loan_total				= 0;
		$sss_loan_total					= 0;
		$other_loans_total				= 0;

		$overtime_total 		 			= 0;
		$special_holiday_total 				= 0;
		$regular_holiday_total 				= 0;
		$leave_pay_total 	     			= 0;
		$late_total 			 			= 0;
		$undertime_total 		 			= 0;
		$absent_total 		 				= 0;
		$nightdiff_total 		 			= 0;
		$restday_total 		 				= 0;
		$rendered_days_total				= 0;

		$total_adjustment_allowance					= 0;
		$total_adjustment_bonus						= 0;
		$total_adjustment_commission				= 0;
		$total_adjustment_incentives				= 0;
		$total_adjustment_cash_advance				= 0;
		$total_adjustment_cash_bond					= 0;
		$total_adjustment_additions					= 0;
		$total_adjustment_deductions				= 0;
		$total_adjustment_others					= 0;
		$total_adjustment_13th_month_and_other 		= 0;
		$total_adjustment_de_minimis_benefit 		= 0;

		$time_total_time_spent				= 0;
		$time_total_overtime				= 0;
		$time_total_night_differential		= 0;
		$time_total_leave_hours				= 0;
		$time_total_undertime				= 0;
		$time_total_late					= 0;
		$time_total_regular_holiday			= 0;
		$time_total_special_holiday			= 0;
		$time_total_absent					= 0;

		foreach($data["_employee"] as $key => $employee)
		{
			// dd(unserialize($employee["cutoff_input"]));
			$payroll_group_salary_computation = Tbl_payroll_employee_contract::Group()->where('tbl_payroll_employee_contract.payroll_employee_id',$employee->payroll_employee_id)->first();

			$total_er = $employee->sss_er + $employee->philhealth_er +  $employee->pagibig_er;
			$total_ee = $employee->sss_ee + $employee->philhealth_ee +  $employee->pagibig_ee;
			$total_ec = $employee->sss_ec;

			$total_sss_ee 			+= Payroll2::payroll_number_format($employee->sss_ee,2);
			$total_sss_er 			+= Payroll2::payroll_number_format($employee->sss_er,2);
			$total_sss_ec 			+= Payroll2::payroll_number_format($employee->sss_ec,2);
			$total_philhealth_ee 	+= Payroll2::payroll_number_format($employee->philhealth_er,2);
			$total_philhealth_er 	+= Payroll2::payroll_number_format($employee->philhealth_er,2);
			$total_pagibig_ee 		+= Payroll2::payroll_number_format($employee->pagibig_ee,2);
			$total_pagibig_er 		+= Payroll2::payroll_number_format($employee->pagibig_er,2);

			// $total_deduction_employee += $employee["total_deduction"];

			$data["_employee"][$key] 		   = $employee;
			$data["_employee"][$key]->total_er = $total_er;
			$data["_employee"][$key]->total_ee = $total_ee;
			$data["_employee"][$key]->total_ec = $total_ec;



			$g_total_ec += Payroll2::payroll_number_format($total_ec,2);
			$g_total_er += Payroll2::payroll_number_format($total_er,2);
			$g_total_ee += Payroll2::payroll_number_format($total_ee,2);

			$total_deduction += ($total_ee);

			if (isset($employee->cutoff_breakdown)) 
			{

				$time_performance = unserialize($employee->cutoff_breakdown)->_time_breakdown;
				
				$data["_employee"][$key]->time_spent 				= $time_performance["time_spent"]["time"];
				$data["_employee"][$key]->time_overtime 			= $time_performance["overtime"]["time"];
				$data["_employee"][$key]->time_night_differential 	= $time_performance["night_differential"]["time"];
				$data["_employee"][$key]->time_leave_hours 			= $time_performance["leave_hours"]["time"];
				$data["_employee"][$key]->time_regular_holiday 		= $time_performance["regular_holiday"]["float"];
				$data["_employee"][$key]->time_special_holiday 		= $time_performance["special_holiday"]["float"];

				if ($payroll_group_salary_computation->payroll_group_code != "Flat Rate") 
				{
					$data["_employee"][$key]->time_absent 			= $time_performance["absent"]["float"];
					$data["_employee"][$key]->time_undertime 		= $time_performance["undertime"]["time"];
					$data["_employee"][$key]->time_late 			= $time_performance["late"]["time"];
				}
				else
				{
					$data["_employee"][$key]->time_absent 			= 0;
					$data["_employee"][$key]->time_undertime 		= 0;
					$data["_employee"][$key]->time_late 			= 0;
				}

				$time_total_time_spent				+= $time_performance["time_spent"]["time"];
				$time_total_overtime				+= $time_performance["overtime"]["time"];
				$time_total_night_differential		+= $time_performance["night_differential"]["time"];
				$time_total_leave_hours				+= $time_performance["leave_hours"]["time"];
				$time_total_regular_holiday			+= $time_performance["regular_holiday"]["float"];
				$time_total_special_holiday			+= $time_performance["special_holiday"]["float"];
				
				if ($payroll_group_salary_computation->payroll_group_code != "Flat Rate") 
				{
					$time_total_undertime				+= $time_performance["undertime"]["time"];
					$time_total_late					+= $time_performance["late"]["time"];
					$time_total_absent					+= $time_performance["absent"]["float"];
				}	
			}


			if(isset($employee->cutoff_breakdown))
			{
				$_duction_break_down = unserialize($employee->cutoff_breakdown)->_breakdown;
				$deduction 				= 0;
				$cola 					= 0;
				$sss_ee 				= 0;
				$sss_er 				= 0;
				$sss_ec 				= 0;
				$hdmf_ee 				= 0;
				$hdmf_er 				= 0;
				$philhealth_ee 			= 0;
				$philhealth_er 			= 0;
				$witholding_tax 		= 0;
				$adjustment_deduction 	= 0;
				$adjustment_allowance 	= 0;
				$allowance 				= 0;
				$allowance_de_minimis   = 0;
				$cash_bond 				= 0;
				$cash_advance			= 0;
				$hdmf_loan				= 0;
				$sss_loan				= 0;
				$other_loans			= 0;

				$adjustment_allowance 				= 0;
				$adjustment_bonus 					= 0;
				$adjustment_commission 				= 0;
				$adjustment_incentives 				= 0;
				$adjustment_cash_advance 			= 0;
				$adjustment_cash_bond 				= 0;
				$adjustment_additions 				= 0;
				$adjustment_deductions 				= 0;
				$adjustment_others 					= 0;
				$adjustment_13th_month_and_other 	= 0;
				$adjustment_de_minimis_benefit 		= 0;


				$adj_allowance_plus_allowance				= 0;
				$adj_de_menimis_plus_allowance_de_menimis	= 0;
				$adj_cashbond_plus_cashbond					= 0;
				$adj_cash_advance_plus_cash_advance			= 0;

				foreach($_duction_break_down as $breakdown)
				{
					if($breakdown["deduct.net_pay"] == true)
					{
						$total_deduction_employee += $breakdown["amount"];
						$deduction 				  += $breakdown["amount"];
					}
					if($breakdown["deduct.gross_pay"] == true)
					{
						$total_deduction_employee += $breakdown["amount"];
						$deduction 				  += $breakdown["amount"];
					}
					if ($breakdown["label"] == "SSS EE" || $breakdown["label"] == "PHILHEALTH EE" || $breakdown["label"] == "PAGIBIG EE" ) 
					{
						$total_deduction_employee += Payroll2::payroll_number_format($breakdown["amount"], 2);
						$deduction 			      += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if ($breakdown["label"] == "COLA") 
					{
						$cola 					  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "SSS EE")
					{
						$sss_ee 				  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "SSS ER")
					{
						$sss_er 				  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "SSS EC")
					{
						$sss_ec 				  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "PAGIBIG EE")
					{
						$hdmf_ee 				  += $breakdown["amount"];
					}
					if($breakdown["label"] == "PAGIBIG ER")
					{
						$hdmf_er 				  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "PHILHEALTH EE")
					{
						$philhealth_ee 			  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if($breakdown["label"] == "PHILHEALTH ER")
					{
						$philhealth_er 			  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					if ($breakdown["label"] == "Witholding Tax") 
					{
						$witholding_tax 		  += Payroll2::payroll_number_format($breakdown["amount"], 2);
					}
					
					if ($breakdown["type"] == "adjustment") 
					{

						if ($breakdown["deduct.net_pay"] == true) 
						{
							$adjustment_deduction += Payroll2::payroll_number_format($breakdown["amount"], 2);
						}
						else
						{
							$adjustment_allowance += Payroll2::payroll_number_format($breakdown["amount"], 2);
						}


						if (isset($breakdown["category"])) 
						{
							// dd(strcasecmp($breakdown["category"], "incentives") == 0);
							if (strcasecmp($breakdown["category"], "Allowance") == 0) 
							{
								$adjustment_allowance += Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "Bonus") == 0) 
							{
								$adjustment_bonus 	  += Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "Commission") == 0) 
							{
								$adjustment_commission 	+= Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "incentives") == 0) 
							{
								$adjustment_incentives 	+= Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if ($breakdown["category"] == "cash_advance") 
							{
								$adjustment_cash_advance += Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "Cash Bond") == 0) 
							{
								$adjustment_cash_bond 	+= Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "additions") == 0) 
							{
								$adjustment_additions 	+= Payroll2::payroll_number_format($breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "deductions") == 0) 
							{
								$adjustment_deductions 	+= Payroll2::payroll_number_format( $breakdown["amount"], 2);
							}
							if (strcasecmp($breakdown["category"], "other") == 0) 
							{
								$adjustment_others 		+= $breakdown["amount"];
							}
							if (strcasecmp($breakdown["category"], "13th Month and Other Non Taxable Benefits") == 0) 
							{
								$adjustment_13th_month_and_other 	+= $breakdown["amount"];
							}
							if (strcasecmp($breakdown["category"], "De Minimis Benefit") == 0) 
							{
								$adjustment_de_minimis_benefit 		+= $breakdown["amount"];
							}
						}
					}
					if (isset($breakdown["record_type"])) 
					{
						
						if ($breakdown["record_type"] == "allowance_de_minimis") 
						{
							$allowance_de_minimis += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "allowance") 
						{
							$allowance += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "Cash Bond") 
						{
							$cash_bond += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "Cash Advance") 
						{
							$cash_advance += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "SSS Loan") 
						{
							$sss_loan += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "HDMF Loan") 
						{
							$hdmf_loan += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						if ($breakdown["record_type"] == "Others") 
						{
							$other_loans += Payroll2::payroll_number_format($breakdown["amount"],2);	
						}
					}


				}


				$data["_employee"][$key]->total_deduction_employee 	= $deduction;
				$data["_employee"][$key]->cola 						= $cola;
				$data["_employee"][$key]->sss_ee 					= $sss_ee;
				$data["_employee"][$key]->sss_er 					= $sss_er;
				$data["_employee"][$key]->sss_ec 					= $sss_ec;
				$data["_employee"][$key]->hdmf_ee 					= $hdmf_ee;
				$data["_employee"][$key]->hdmf_er 					= $hdmf_er;
				$data["_employee"][$key]->philhealth_ee 			= $philhealth_ee;
				$data["_employee"][$key]->philhealth_er 			= $philhealth_er;
				$data["_employee"][$key]->witholding_tax 			= $witholding_tax;
				$data["_employee"][$key]->adjustment_deduction 		= $adjustment_deduction;
				$data["_employee"][$key]->adjustment_allowance 		= $adjustment_allowance;
				$data["_employee"][$key]->allowance_de_minimis 		= $allowance_de_minimis;
				$data["_employee"][$key]->allowance 				= $allowance;
				$data["_employee"][$key]->cash_bond					= $cash_bond;
				$data["_employee"][$key]->cash_advance				= $cash_advance;
				$data["_employee"][$key]->sss_loan					= $sss_loan;
				$data["_employee"][$key]->hdmf_loan					= $hdmf_loan;
				$data["_employee"][$key]->other_loans				= $other_loans;

				$data["_employee"][$key]->adjustment_allowance 					= $adjustment_allowance;
				$data["_employee"][$key]->adjustment_bonus 						= $adjustment_bonus;
				$data["_employee"][$key]->adjustment_commission 				= $adjustment_commission;
				$data["_employee"][$key]->adjustment_incentives 				= $adjustment_incentives;
				$data["_employee"][$key]->adjustment_cash_advance 				= $adjustment_cash_advance;
				$data["_employee"][$key]->adjustment_cash_bond 					= $adjustment_cash_bond;
				$data["_employee"][$key]->adjustment_additions 					= $adjustment_additions;
				$data["_employee"][$key]->adjustment_deductions 				= $adjustment_deductions;
				$data["_employee"][$key]->adjustment_others 					= $adjustment_others;
				$data["_employee"][$key]->adjustment_13th_month_and_other 		= $adjustment_13th_month_and_other;
				$data["_employee"][$key]->adjustment_de_minimis_benefit 		= $adjustment_de_minimis_benefit;


				$deduction_total				+= Payroll2::payroll_number_format($deduction, 2);
				$cola_total						+= Payroll2::payroll_number_format($cola, 2);

				$sss_ee_total						+= Payroll2::payroll_number_format($sss_ee, 2);
				$sss_er_total						+= Payroll2::payroll_number_format($sss_er, 2);
				$sss_ec_total						+= Payroll2::payroll_number_format($sss_ec, 2);
				$hdmf_ee_total						+= Payroll2::payroll_number_format($hdmf_ee, 2);
				$hdmf_er_total						+= Payroll2::payroll_number_format($hdmf_er, 2);
				$philhealth_ee_total				+= Payroll2::payroll_number_format($philhealth_ee, 2);
				$philhealth_er_total				+= Payroll2::payroll_number_format($philhealth_er, 2);
				$witholding_tax_total				+= Payroll2::payroll_number_format($witholding_tax, 2);

				$adjustment_deduction_total		+= Payroll2::payroll_number_format($adjustment_deduction,2);
				$adjustment_allowance_total		+= Payroll2::payroll_number_format($adjustment_allowance,2);
				$allowance_de_minimis_total		+= Payroll2::payroll_number_format($allowance_de_minimis,2);
				$allowance_total				+= Payroll2::payroll_number_format($allowance,2);
				$cash_bond_total				+= Payroll2::payroll_number_format($cash_bond,2);
				$cash_advance_total				+= Payroll2::payroll_number_format($cash_advance,2);
				$hdmf_loan_total				+= Payroll2::payroll_number_format($hdmf_loan,2);
				$sss_loan_total					+= Payroll2::payroll_number_format($sss_loan,2);
				$other_loans_total				+= Payroll2::payroll_number_format($other_loans,2);

				$total_adjustment_allowance					+= Payroll2::payroll_number_format($adjustment_allowance,2);
				$total_adjustment_bonus						+= Payroll2::payroll_number_format($adjustment_bonus,2);
				$total_adjustment_commission				+= Payroll2::payroll_number_format($adjustment_commission,2);
				$total_adjustment_incentives				+= Payroll2::payroll_number_format($adjustment_incentives,2);
				$total_adjustment_cash_advance				+= Payroll2::payroll_number_format($adjustment_cash_advance,2);
				$total_adjustment_cash_bond					+= Payroll2::payroll_number_format($adjustment_cash_bond,2);
				$total_adjustment_additions					+= Payroll2::payroll_number_format($adjustment_additions,2);
				$total_adjustment_deductions				+= Payroll2::payroll_number_format($adjustment_deductions,2);
				$total_adjustment_others					+= Payroll2::payroll_number_format($adjustment_others,2);
				
				$total_adjustment_13th_month_and_other		+= Payroll2::payroll_number_format($adjustment_13th_month_and_other,2);
				$total_adjustment_de_minimis_benefit		+= Payroll2::payroll_number_format($adjustment_de_minimis_benefit,2);
			}


			if (isset($employee->cutoff_input)) 
			{
				$_cutoff_input_breakdown = unserialize($employee->cutoff_input);
				
				$overtime 		 = 0;
				$special_holiday = 0;
				$regular_holiday = 0;
				$leave_pay 	     = 0;
				$late 			 = 0;
				$undertime 		 = 0;
				$absent 		 = 0;
				$nightdiff 		 = 0;
				$restday 		 = 0;
				$rendered_days   = 0;

				$ot_category = array('Rest Day OT', 'Over Time', 'Legal Holiday Rest Day OT', 'Legal OT', 'Special Holiday Rest Day OT', 'Special Holiday OT');
				$nd_category = array('Legal Holiday Rest Day ND','Legal Holiday ND','Special Holiday Rest Day ND','Special Holiday ND','Rest Day ND','Night Differential');
				// $rd_category = array('Rest Day','Legal Holiday Rest Day','Special Holiday Rest Day');
				foreach ($_cutoff_input_breakdown as $value) 
				{

					if (isset($value->compute->_breakdown_addition)) 
					{
						foreach ($value->compute->_breakdown_addition as $lbl => $values) 
						{
							if (in_array($lbl, $ot_category)) 
							{
								$overtime 			+= Payroll2::payroll_number_format($values['rate'],2);
							}
							if ($lbl == 'Legal Holiday' || $lbl == 'Legal Holiday Rest Day') 
							{
								$regular_holiday 	+= Payroll2::payroll_number_format($values['rate'],2);
							}
							if ($lbl == 'Special Holiday' || $lbl == 'Special Holiday Rest Day') 
							{
								$special_holiday 	+= Payroll2::payroll_number_format($values['rate'],2);
							}
							if ($lbl == 'Leave Pay') 
							{
								$leave_pay 			+= Payroll2::payroll_number_format($values['rate'],2);
							}
							if ($lbl == 'Rest Day') 
							{
								$restday 			+= Payroll2::payroll_number_format($values['rate'],2);
							}
							if (in_array($lbl, $nd_category)) 
							{
								$nightdiff 			+= Payroll2::payroll_number_format($values['rate'],2);
							}
						}
					}

					if(isset($value->compute->rendered_days))
					{
					
							$rendered_days  		+= Payroll2::payroll_number_format($value->compute->rendered_days,2);
						
					}

					if (isset($value->compute->_breakdown_deduction)) 
					{
						foreach ($value->compute->_breakdown_deduction as $lbl => $values) 
						{
							if ($value->time_output["leave_hours"] == '00:00:00') 
							{
								if ($lbl == 'late') 
								{
									$late 			+= Payroll2::payroll_number_format($values['rate'],2);
								}
								if ($lbl == 'absent' && $payroll_group_salary_computation->payroll_group_code != "Flat Rate") 
								{
									$absent 		+= Payroll2::payroll_number_format($values['rate'],2);
								}
								if ($lbl == 'undertime') 
								{
									$undertime 		+= Payroll2::payroll_number_format($values['rate'],2);
								}
								$deduction 			+= Payroll2::payroll_number_format($values['rate'],2);
							}
						}
					}


				}

				$data["_employee"][$key]->overtime 			= $overtime;
				$data["_employee"][$key]->regular_holiday 	= $regular_holiday;
				$data["_employee"][$key]->special_holiday 	= $special_holiday;
				$data["_employee"][$key]->leave_pay 		= $leave_pay;
				$data["_employee"][$key]->absent 			= $absent;
				$data["_employee"][$key]->late 				= $late;
				$data["_employee"][$key]->undertime 		= $undertime;
				$data["_employee"][$key]->nightdiff 		= $nightdiff;
				$data["_employee"][$key]->restday 			= $restday;
				$data["_employee"][$key]->rendered_days     = $rendered_days;

				$overtime_total 		 		+=	Payroll2::payroll_number_format($overtime,2);
				$special_holiday_total 			+=	Payroll2::payroll_number_format($special_holiday,2);
				$regular_holiday_total 			+=	Payroll2::payroll_number_format($regular_holiday,2);
				$leave_pay_total 	     		+=	Payroll2::payroll_number_format($leave_pay,2);
				$late_total 			 		+=	Payroll2::payroll_number_format($late,2);
				$undertime_total 		 		+=	Payroll2::payroll_number_format($undertime,2);
				$absent_total 		 			+=	Payroll2::payroll_number_format($absent,2);
				$nightdiff_total 		 		+=	Payroll2::payroll_number_format($nightdiff,2);
				$restday_total 		 			+=	Payroll2::payroll_number_format($restday,2);
				$rendered_days_total	        +=	Payroll2::payroll_number_format($rendered_days,2);
			}

			if (isset($employee["cutoff_breakdown"]->_breakdown)) 
			{
				# code...
				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{
					if($breakdown["deduct.net_pay"] == true)
					{
						if(isset($_other_deduction[$breakdown["label"]]))
						{
							$_other_deduction[$breakdown["label"]]  += Payroll2::payroll_number_format($breakdown["amount"],2);
							$total_deduction 						+= Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						else
						{
							$_other_deduction[$breakdown["label"]] = $breakdown["amount"];
							$total_deduction += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
					}
				}

				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{	
					if($breakdown["add.gross_pay"] == true)
					{
						if(isset($_addition[$breakdown["label"]]))
						{
							$_addition[$breakdown["label"]] += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						else
						{
							$_addition[$breakdown["label"]] = Payroll2::payroll_number_format($breakdown["amount"],2);
						}
					}
				}

				foreach($employee["cutoff_breakdown"]->_breakdown as $breakdown)
				{

					if($breakdown["type"] == "deductions")
					{
						if(isset($_deduction[$breakdown["label"]]))
						{
							$_deduction[$breakdown["label"]] += Payroll2::payroll_number_format($breakdown["amount"],2);
						}
						else
						{
							$_deduction[$breakdown["label"]] = Payroll2::payroll_number_format($breakdown["amount"],2);
						}
					}
				}
			}
			
			$employee->net_basic_pay = $employee->net_basic_pay - $leave_pay;

			// $total_cutoff_basic 	+= Payroll2::payroll_number_format(unserialize($employee->cutoff_compute)->cutoff_basic,2);
			$total_gross_basic		+= Payroll2::payroll_number_format($employee->gross_basic_pay,2);
			$total_basic 			+= Payroll2::payroll_number_format($employee->net_basic_pay,2);
			$total_gross 			+= Payroll2::payroll_number_format($employee->gross_pay,2);
			$total_net 				+= Payroll2::payroll_number_format($employee->net_pay,2);
			$total_tax 				+= Payroll2::payroll_number_format($employee->tax_ee,2);

			/*combination*/
			$data["_employee"][$key]->adj_allowance_plus_allowance 			   = $allowance + $adjustment_allowance;
			$data["_employee"][$key]->adj_de_menimis_plus_allowance_de_menimis = $allowance_de_minimis + $adjustment_de_minimis_benefit;
			$data["_employee"][$key]->adj_cashbond_plus_cashbond 			   = $cash_bond + $adjustment_cash_bond;
			$data["_employee"][$key]->adj_cash_advance_plus_cash_advance 	   = $cash_advance + $adjustment_cash_advance;



		}

		// $data["total_cutoff_basic"]					= $total_cutoff_basic;
		$data["total_gross_basic"]					= $total_gross_basic;
		$data["total_basic"] 						= $total_basic;
		$data["total_gross"] 						= $total_gross;
		$data["total_net"] 							= $total_net;
		$data["total_er"] 							= $g_total_er;
		$data["total_ee"] 							= $g_total_ee;
		$data["total_ec"] 							= $g_total_ec;
		$data["total_tax"] 							= $total_tax;
		$data["total_grand"] 						= $total_net + $g_total_er + $g_total_ee + $g_total_ec + $total_tax;
		$data["total_sss_ee"] 						= $total_sss_ee;
		$data["total_sss_er"] 						= $total_sss_er;
		$data["total_sss_ec"] 						= $total_sss_ec;
		$data["total_philhealth_ee"] 				= $total_philhealth_ee;
		$data["total_philhealth_er"] 				= $total_philhealth_er;
		$data["total_pagibig_ee"] 					= $total_pagibig_ee;
		$data["total_pagibig_er"] 					= $total_pagibig_er;
		$data["_other_deduction"] 					= $_other_deduction;
		$data["_addition"] 							= $_addition;
		$data["_deduction"] 						= $_deduction;
		$data["total_deduction"] 					= $total_deduction;
		$data["total_deduction_of_all_employee"] 	= $total_deduction_employee;

		$data["deduction_total"] 						= $deduction_total;
		$data["cola_total"] 							= $cola_total;
		$data["sss_ee_total"] 							= $sss_ee_total;
		$data["sss_er_total"] 							= $sss_er_total;
		$data["sss_ec_total"] 							= $sss_ec_total;
		$data["hdmf_ee_total"] 							= $hdmf_ee_total;
		$data["hdmf_er_total"] 							= $hdmf_er_total;
		$data["philhealth_ee_total"] 					= $philhealth_ee_total;
		$data["philhealth_er_total"] 					= $philhealth_er_total;
		$data["witholding_tax_total"] 					= $witholding_tax_total;
		$data["adjustment_deduction_total"] 			= $adjustment_deduction_total;
		$data["adjustment_allowance_total"] 			= $adjustment_allowance_total;
		$data["allowance_de_minimis_total"]				= $allowance_de_minimis_total;
		$data["allowance_total"] 						= $allowance_total;
		$data["cash_bond_total"] 						= $cash_bond_total;
		$data["cash_advance_total"]						= $cash_advance_total;
		$data["hdmf_loan_total"]						= $hdmf_loan_total;
		$data["sss_loan_total"]							= $sss_loan_total;
		$data["other_loans_total"]						= $other_loans_total;

		$data["overtime_total"] 		 			= $overtime_total;
		$data["special_holiday_total"] 				= $special_holiday_total;
		$data["regular_holiday_total"] 				= $regular_holiday_total;
		$data["leave_pay_total"] 	     			= $leave_pay_total;
		$data["late_total"] 			 			= $late_total;
		$data["undertime_total"] 		 			= $undertime_total;
		$data["absent_total"] 		 				= $absent_total;
		$data["nightdiff_total"] 		 			= $nightdiff_total;
		$data["restday_total"] 		 				= $restday_total;
		$data["rendered_days_total"]				= $rendered_days_total;

		$data["total_adjustment_allowance"]				= $total_adjustment_allowance;	
		$data["total_adjustment_bonus"]					= $total_adjustment_bonus;		
		$data["total_adjustment_commission"]			= $total_adjustment_commission;
		$data["total_adjustment_incentives"]			= $total_adjustment_incentives;
		$data["total_adjustment_cash_advance"]			= $total_adjustment_cash_advance;
		$data["total_adjustment_cash_bond"]				= $total_adjustment_cash_bond;	
		$data["total_adjustment_additions"]				= $total_adjustment_additions;	
		$data["total_adjustment_deductions"]			= $total_adjustment_deductions;
		$data["total_adjustment_others"]				= $total_adjustment_others;	
		$data["total_adjustment_13th_month_and_other"] 	= $total_adjustment_13th_month_and_other;
		$data["total_adjustment_de_minimis_benefit"] 	= $total_adjustment_de_minimis_benefit;

		$data["time_total_time_spent"]				= $time_total_time_spent;				
		$data["time_total_overtime"]				= $time_total_overtime;				
		$data["time_total_night_differential"]		= $time_total_night_differential;		
		$data["time_total_leave_hours"]				= $time_total_leave_hours;				
		$data["time_total_undertime"]				= $time_total_undertime;				
		$data["time_total_late"]					= $time_total_late;					
		$data["time_total_regular_holiday"]			= $time_total_regular_holiday;		
		$data["time_total_special_holiday"]			= $time_total_special_holiday;
		$data["time_total_absent"]					= $time_total_absent;

		$data["total_adj_allowance_plus_allowance"]					= $total_adjustment_allowance + $allowance_total;
		$data["total_adj_de_menimis_plus_allowance_de_menimis"]		= $total_adjustment_de_minimis_benefit + $allowance_de_minimis_total;
		$data["total_adj_cashbond_plus_cashbond"]					= $total_adjustment_cash_bond + $cash_bond_total;
		$data["total_adj_cash_advance_plus_cash_advance"]			= $total_adjustment_cash_advance + $cash_advance_total;
		
		return $data;
	}

	public static function payroll_number_format($number,$decimal_places)
	{
		return number_format((float)$number, $decimal_places, '.', '');
	}

	public static function identify_period_salary_daily_rate($_timesheet)
	{
		$salary_period = 0;
		
		foreach ($_timesheet as $key => $timesheet) 
		{
			$salary_period += $timesheet->compute->daily_rate;
		}

		return $salary_period;
	}

	public static function identify_period_salary($salary = 0, $period = '')
	{
		$salary_period = 0;

		if($period == 'Monthly')
		{
			$salary_period = $salary;
		}
		else if($period == 'Semi-monthly')
		{
			$salary_period = $salary / 2;
		}
		else if($period == 'Weekly')
		{
			$salary_period = $salary / 4;
		}
		else if($period == 'Daily')
		{
			$salary_period = ($salary * 12) / 365;
		}
		
		return $salary_period;
	}


	public static function insert_journal_entry_per_period($period_company_id, $shop_id)
	{
		$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();;
		$data = Payroll2::get_total_payroll_register($data);
		$_chart_of_account= Tbl_payroll_journal_tag::gettag($shop_id)
												 ->join('tbl_chart_account_type','tbl_chart_of_account.account_type_id','=','tbl_chart_account_type.chart_type_id')
												 ->join('tbl_payroll_journal_tag_entity','tbl_payroll_journal_tag_entity.payroll_journal_tag_id','=','tbl_payroll_journal_tag.payroll_journal_tag_id')
												 ->join('tbl_payroll_entity','tbl_payroll_entity.payroll_entity_id','=','tbl_payroll_journal_tag_entity.payroll_entity_id')
												 ->get();
		
		$_chart_of_account = collect($_chart_of_account)->groupBy('payroll_journal_tag_id','account_id');
		
		$total_period_record = array('Gross Basic Pay' => $data['total_gross_basic'], 'Basic Pay' => $data['total_basic'], 'Gross Pay' => $data['total_gross'], 'Take Home Pay' => $data['total_net'], 'Leave Pay' => $data['leave_pay_total'], 'Night Differential Pay' => $data['nightdiff_total'], 'Regular Holiday Pay' => $data['regular_holiday_total'], 'Special Holiday Pay' => $data['special_holiday_total'], 'Over Time Pay' => $data['overtime_total'], 'Rest Day Pay' => $data['restday_total'], 'COLA' => $data['cola_total']
									  , 'Allowance Pay' => $data['total_adj_allowance_plus_allowance'], 'Bonus Pay' => $data['total_adjustment_bonus'], 'Commission Pay' => $data['total_adjustment_commission'], '13th Month and Other Non Taxable Benifits Pay' => $data['total_adjustment_13th_month_and_other'], 'Incentive Pay' => $data['total_adjustment_incentives'], 'Deminimis Pay' => $data['total_adj_de_menimis_plus_allowance_de_menimis'], 'Other Allowances' => $data['total_adjustment_others']
									  , 'HDMF EE' => $data['hdmf_ee_total'], 'HDMF ER' => $data['hdmf_er_total'], 'Philhealth EE' => $data['philhealth_ee_total'], 'Philhealth ER' => $data['philhealth_er_total'], 'SSS EC' => $data['sss_ec_total'], 'SSS EE' => $data['sss_ee_total'], 'SSS ER' => $data['sss_er_total'], 'Witholding Tax' => $data['witholding_tax_total']
									  , 'Deductions' => $data['total_adjustment_deductions'], 'Cash Advance' => $data['total_adj_cash_advance_plus_cash_advance'], 'Cash Bond' => $data['total_adj_cashbond_plus_cashbond'], 'SSS Loans' => $data['sss_loan_total'], 'HDMF Loans' => $data['hdmf_loan_total'], 'Other Loan' => $data['other_loans_total'], 'Late' => $data['late_total'], 'Absent' => $data['absent_total'], 'Undertime' => $data['undertime_total']);
		
		$_chart_of_account_insert = array();

		foreach ($_chart_of_account as $key => $_chart_of_account_record) 
		{
			foreach ($_chart_of_account_record as $key2 => $chart_of_account_record) 
			{
				if (in_array($chart_of_account_record['entity_name'], $total_period_record)) 
				{
					if (isset($_chart_of_account_insert[$key])) 
					{
						$_chart_of_account_insert[$key]['amount'] += $total_period_record[$chart_of_account_record['entity_name']];
					}
					else
					{
						$_chart_of_account_insert[$key]['amount'] = $total_period_record[$chart_of_account_record['entity_name']];
						$_chart_of_account_insert[$key]['account_id'] = $chart_of_account_record['account_id'];
						$_chart_of_account_insert[$key]['account_type'] = ucfirst($chart_of_account_record['normal_balance']);

						// $_chart_of_account_insert[$key]['date'] 		= date('Y-m-d');
					}
				}
				
			}
		}
		
		$return = PayrollAccounting::postPayrollManualJournalEntries($shop_id, Carbon::now(), $_chart_of_account_insert);
		
		return $_chart_of_account_insert;
	}


	public static function philhealth_contribution_update_2018($rate)
	{
		$data['ee'] = 0;
		$data['er'] = 0;
		if($rate > 0)
		{
			if ($rate <= 10000) 
			{
				$data['ee'] = 137.50;
				$data['er'] = 137.50;
			}
			else if($rate > 10000 && $rate < 40000)
			{
				$philhealth_contri = $rate * 0.0275;

				$data['ee'] = @($philhealth_contri/2);
				$data['er'] = @($philhealth_contri/2);
			}
			else if($rate >= 40000)
			{
				$data['ee'] = 550.00;
				$data['er'] = 550.00;
			}
		}

		return $data;
	}

	public static function date_has_year($date)
	{
		$has_year = false;
		$date = explode("-", $date);
		
		if (isset($date[2])) 
		{
			$has_year = true;
		}

		return $has_year;
	}

	public static function number_to_word( $num = '' )
	{
	    $num    = ( string ) ( ( int ) $num );
	   
	    if( ( int ) ( $num ) && ctype_digit( $num ) )
	    {
	        $words  = array( );
	       
	        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
	       
	        $list1  = array('','one','two','three','four','five','six','seven',
	            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
	            'fifteen','sixteen','seventeen','eighteen','nineteen');
	       
	        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
	            'seventy','eighty','ninety','hundred');
	       
	        $list3  = array('','thousand','million','billion','trillion',
	            'quadrillion','quintillion','sextillion','septillion',
	            'octillion','nonillion','decillion','undecillion',
	            'duodecillion','tredecillion','quattuordecillion',
	            'quindecillion','sexdecillion','septendecillion',
	            'octodecillion','novemdecillion','vigintillion');
	       
	        $num_length = strlen( $num );
	        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
	        $max_length = $levels * 3;
	        $num    = substr( '00'.$num , -$max_length );
	        $num_levels = str_split( $num , 3 );
	       
	        foreach( $num_levels as $num_part )
	        {
	            $levels--;
	            $hundreds   = ( int ) ( $num_part / 100 );
	            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
	            $tens       = ( int ) ( $num_part % 100 );
	            $singles    = '';
	           
	            if( $tens < 20 )
	            {
	                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
	            }
	            else
	            {
	                $tens   = ( int ) ( $tens / 10 );
	                $tens   = ' ' . $list2[$tens] . ' ';
	                $singles    = ( int ) ( $num_part % 10 );
	                $singles    = ' ' . $list1[$singles] . ' ';
	            }
	            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
	        }
	       
	        $commas = count( $words );
	       
	        if( $commas > 1 )
	        {
	            $commas = $commas - 1;
	        }
	       
	        $words  = implode( ', ' , $words );
	       
	        //Some Finishing Touch
	        //Replacing multiples of spaces with one space
	        $words  = trim( str_replace( ' ,' , ',' , Self::trim_all( ucwords( $words ) ) ) , ', ' );
	        if( $commas )
	        {
	            $words  = Self::str_replace_last( ',' , ' and' , $words );
	        }
	       
	        return $words;
	    }
	    else if( ! ( ( int ) $num ) )
	    {
	        return 'Zero';
	    }
	    return '';
	}

	public static function trim_all( $str , $what = NULL , $with = ' ' )
	{
	    if( $what === NULL )
	    {
	        //  Character      Decimal      Use
	        //  "\0"            0           Null Character
	        //  "\t"            9           Tab
	        //  "\n"           10           New line
	        //  "\x0B"         11           Vertical Tab
	        //  "\r"           13           New Line in Mac
	        //  " "            32           Space
	       
	        $what   = "\\x00-\\x20";    //all white-spaces and control chars
	    }
	   
	    return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}

	public static function str_replace_last( $search , $replace , $str ) {
	    if( ( $pos = strrpos( $str , $search ) ) !== false ) 
	    {
	        $search_length  = strlen( $search );
	        $str    = substr_replace( $str , $replace , $pos , $search_length );
	    }
	    return $str;
	}
}
