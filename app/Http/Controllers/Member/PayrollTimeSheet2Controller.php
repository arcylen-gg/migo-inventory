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
use App\Models\Tbl_payroll_holiday_employee;
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
use App\Globals\Pdf_global;
use PDF2;

use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;


use DB;

class PayrollTimeSheet2Controller extends Member
{
	public function index($period_id)
	{
		$data["payroll_period_id"] = $period_id;
		$data["page"] = "Employee List Summary";

		$this->index_redirect_if_time_keeping_does_not_exist($period_id);
		$data["company"] = $this->db_get_company_period_information($period_id);

		if (isset($data["company"]->payroll_company_id)) 
		{
			$data["_company"] = $this->db_get_list_of_company_for_period($data["company"]->payroll_company_id);
			return view('member.payroll2.employee_summary', $data);
		}
		else
		{
			return Redirect::to('/member/payroll/time_keeping');
		}
	}
	public function index_redirect_if_time_keeping_does_not_exist($period_id)
	{
		$count = Tbl_payroll_period_company::check($period_id, $this->user_info->shop_id)->count();

		if($count == 0)
		{
			return Redirect::to('/member/payroll/time_keeping')->send();
		}
	}
	public function index_table($period_id)
	{
		$search_value 		= Request::input("search");
		$mode 				= Request::input("mode") == "pending" ? 0 : 1;
		$branch 			= Request::input("branch");
		$data["page"] 		= "Employee List Summary";
		
		$this->index_redirect_if_time_keeping_does_not_exist($period_id);

		$data["company"] 	= $this->db_get_company_period_information($period_id);
		$data["_employee"] 	= $this->db_get_list_of_employees_by_company_with_search($data["company"]->payroll_company_id, $search_value, $mode, $period_id, $data["company"]->payroll_period_start, $branch);
		$data['access'] = Utilities::checkAccess('payroll-timekeeping','salary_rates');
		
		if($mode == "pending")
		{
			return view('member.payroll2.employee_summary_table', $data);
		}
		else
		{
			return view('member.payroll2.employee_summary_table_approved', $data);
		}
		
	}
	public function timesheet($period_id, $employee_id)
	{
		$data["page"]					= "Employee Timesheet";
		$data["employee_id"]			= $this->$employee_id = $employee_id;
		$data["employee_info"]			= $this->db_get_employee_information($employee_id); 
		$data["company_period"] 		= $this->db_get_company_period_information($period_id);
		$data["show_period_start"]		= date("F d, Y", strtotime($data["company_period"]->payroll_period_start));
		$data["show_period_end"]		= date("F d, Y", strtotime($data["company_period"]->payroll_period_end));
		$data["_timesheet"] 			= Payroll2::timesheet_info($data["company_period"], $employee_id);
		$data["access_salary_rates"]	= $access = Utilities::checkAccess('payroll-timekeeping','salary_rates');
		$check_approved 				= Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_id)->first();
		$data["time_keeping_approved"] 	= $check_approved ? true : false;

		$employee_contract = $this->db_get_current_employee_contract($employee_id, $data["company_period"]->payroll_period_start);

		$data["compute_type"] = $employee_contract->payroll_group_salary_computation;

		$data["period_id"] = $period_id;
		// dd($data);
		if($data["compute_type"] == "Flat Rate")
		{
			echo "<div style='padding: 100px; text-align: center;'>FLAT RATE COMPUTATION DOES'T HAVE TIMESHEET</div>";
		}
		else
		{
			return view('member.payroll2.employee_timesheet', $data);
		}
	}
	
	public function timesheet_pdf($period_id, $employee_id)
	{
		$data["page"]					= "Employee Timesheet";
		$data["employee_id"]			= $this->$employee_id = $employee_id;
		$data["employee_info"]			= $this->db_get_employee_information($employee_id); 
		$data["company_period"] 		= $this->db_get_company_period_information($period_id);

		$data["show_period_start"]		= date("F d, Y", strtotime($data["company_period"]->payroll_period_start));
		$data["show_period_end"]		= date("F d, Y", strtotime($data["company_period"]->payroll_period_end));
		$data["_timesheet"] 			= Payroll2::timesheet_info($data["company_period"], $employee_id);

		$data["access_salary_rates"]	= $access = Utilities::checkAccess('payroll-timekeeping','salary_rates');
		$check_approved 				= Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_id)->first();
		$data["time_keeping_approved"] 	= $check_approved ? true : false;

		$employee_contract = $this->db_get_current_employee_contract($employee_id, $data["company_period"]->payroll_period_start);

		$data["compute_type"] = $employee_contract->payroll_group_salary_computation;

		$data["period_id"] = $period_id;

		$data['compute_cutoff'] = $this->compute_whole_cutoff($period_id, $employee_id);

		if($data["compute_type"] == "Flat Rate")
		{
			echo "<div style='padding: 100px; text-align: center;'>FLAT RATE COMPUTATION DOES'T HAVE TIMESHEET</div>";
		}
		else
		{
			$format["title"] = "A4";
			$format["format"] = "A4";
			$format["default_font"] = "sans-serif";

	        $pdf = PDF2::loadView('member.payroll2.employee_timesheet_pdf', $data, [], $format);
			return $pdf->stream('document.pdf');
		}
	}
	public function approve_timesheets($period_id = 0, $employee_id = 0)
	{
		if(Request::input("period_id") != 0)
		{
			$period_id = Request::input("period_id");
			$employee_id = Request::input("employee_id");
			$payroll_period_id = Request::input("payroll_period_id");
		}
		
		$compute_cutoff 						= $this->compute_whole_cutoff($period_id, $employee_id);
		$check_approved 						= Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_id)->where("employee_id", $employee_id)->first();
		$company_id 							= Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_id)->value('payroll_employee_company_id');
		$group 									= $this->db_get_current_employee_contract($employee_id, $compute_cutoff["period_info"]["payroll_period_start"]);
		$compute_cutoff["salary_compute_type"] 	= $group["payroll_group_salary_computation"];

		if($check_approved)
		{
			Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_id)->where("employee_id", $employee_id)->delete();
		}
		else
		{
			$time_keeping_approve_id = Tbl_payroll_time_keeping_approved::insertRecord($employee_id, $period_id, $company_id, $compute_cutoff["cutoff_breakdown"], $compute_cutoff);
			Tbl_payroll_time_sheet::updateCompute($compute_cutoff["cutoff_input"]);
			Tbl_payroll_time_keeping_approved_breakdown::insertBreakdown($time_keeping_approve_id, $compute_cutoff["cutoff_breakdown"]->_breakdown);
			Tbl_payroll_time_keeping_approved_daily_breakdown::insertBreakdown($time_keeping_approve_id, $compute_cutoff["cutoff_input"]);
			Tbl_payroll_time_keeping_approved_performance::insertBreakdown($time_keeping_approve_id, $compute_cutoff["cutoff_breakdown"]->_time_breakdown);
		}
		
		//add payment in deduction
		PayrollDeductionController::approve_deduction_payment($period_id, $employee_id, $payroll_period_id);
		
		return json_encode(Request::input());
	}
	
	

	public function unapprove($period_id, $employee_id)
	{
		//$unapproved = true;
		//$compute_cutoff = $this->compute_whole_cutoff($period_id, $employee_id, $unapproved);
		$check_approved = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_id)->where("employee_id", $employee_id)->first();
		
		if($check_approved)
		{
			Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_id)->where("employee_id", $employee_id)->delete();
		}

		Tbl_payroll_deduction_payment_v2::where('payroll_period_company_id',$period_id)->where("payroll_employee_id", $employee_id)->delete();
		
		echo json_encode("success");
	}

	public function time_change($period_id, $employee_id)
	{
		$data["period"] = $period = $this->db_get_company_period_information($period_id);
		$data["request"] = Request::input();
		
		/* GET CURRENT TIMESHEET FOR THE DAY */
		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db($employee_id, Request::input("date"));
		
		/*added for remark not saving in absent*/
		$check_time_sheet_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->first();
		/*added for remark not saving in absent*/

		if ($check_time_sheet_record) 
		{
			$update_remarks["payroll_time_shee_activity"] = isset(Request::input("remarks")[0]) ? Request::input("remarks")[0] : "";
			Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->update($update_remarks);
		}
		

		/* DELETE TIME SHEET RECORD */
		Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->where("payroll_time_sheet_origin", "Manually Encoded")->delete();
		Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->delete();

		$update_time_sheet["time_keeping_approved"] = 0;
		Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->update($update_time_sheet);
		
		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db($employee_id, Request::input("date"));
		
		/* INSERT NEW TIME SHEET RECORD */
		$insert = null;
		
		if(Request::input("time-in"))
		{
			$add = 0;
			foreach(Request::input("time-in") as $key => $time_in)
			{
				$time_out = Request::input("time-out")[$key];
				$remarks = Request::input("remarks")[$key];
				
				if($time_in != "" || $time_out != "")
				{
					$insert[$key]["payroll_time_sheet_id"] = $timesheet_db->payroll_time_sheet_id;
					$insert[$key]["payroll_company_id"] = $period->payroll_company_id;
					$insert[$key]["payroll_time_sheet_in"] = date("H:i:s", strtotime($time_in));
					$insert[$key]["payroll_time_sheet_out"] = date("H:i:s ", strtotime($time_out));
					$insert[$key]["payroll_time_shee_activity"] = $remarks;
					$insert[$key]["payroll_time_sheet_origin"] = "Manually Encoded";
				}
			}
		}
		
		/*added for remark not saving in absent*/
		if($insert == null)
		{
			if(!$check_time_sheet_record)
			{
				$insert["payroll_time_shee_activity"] = isset(Request::input("remarks")[0]) ? Request::input("remarks")[0] : "";
				$insert["payroll_time_sheet_id"] 	  = $timesheet_db->payroll_time_sheet_id;
				$insert["payroll_company_id"] 	      = $data["period"]->payroll_company_id;
				$insert["payroll_time_sheet_origin"]  = "Manually Encoded";
			}
		}

		if($insert)
		{
			Tbl_payroll_time_sheet_record::insert($insert);
		}

		/* RETURN DATA TO SERVER */
		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db($employee_id, Request::input("date"));
		$data["daily_info"] = Payroll2::timesheet_process_daily_info($employee_id, Request::input("date"), $timesheet_db, $period_id);
		$daily_income = $data["daily_info"]->compute->total_day_income;
		
		$return["no_changes"] 	 = false;
		$return["income"] 		 = $daily_income;
		$return["string_income"] = $data["daily_info"]->value_html;
	
		echo json_encode($return);
	}


	public function remarks_change($period_id, $employee_id)
	{
		$timesheet_db = $this->timesheet_info_db($employee_id, Request::input("date"));
		$period = $this->db_get_company_period_information($period_id);
		$_time_sheet_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_db->payroll_time_sheet_id)->get();

		foreach ($_time_sheet_record as $key => $time_sheet_record) 
		{
			if(isset(Request::input("remarks")[$key]))
			{
				$update["payroll_time_shee_activity"] = Request::input("remarks")[$key];
				Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $time_sheet_record->payroll_time_sheet_record_id)->update($update);
			}
		}
		
		//absent and no time_sheet_record
		if (count($_time_sheet_record) == 0) 
		{
			$insert["payroll_time_sheet_id"] = $timesheet_db->payroll_time_sheet_id;
			$insert["payroll_company_id"] = $period->payroll_company_id;
			$insert["payroll_time_shee_activity"] = Request::input("remarks")[0];
			$insert["payroll_time_sheet_origin"] = "Manually Encoded";
			Tbl_payroll_time_sheet_record::insert($insert);
		}
		
		return "success updating";
	}

	public function timesheet_daily_income_to_string($compute_type, $timesheet_id, $compute, $approved, $period_company_id, $time_keeping_approved = 0)
	{
		if($compute_type == "daily")
		{
			$income = $compute->total_day_income;
		}
		elseif ($compute_type == "hourly") 
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
	public function timesheet_info($company_period, $employee_id) 
	{

		$_timesheet = null;
		$from = $data["start_date"] = $company_period->payroll_period_start;
		$to = $data["end_date"] = $company_period->payroll_period_end;
		$payroll_period_company_id = $company_period->payroll_period_company_id;
		$shift_code_id = Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->value("shift_code_id");
	
		while($from <= $to)
		{
			$timesheet_db = $this->timesheet_info_db($employee_id, $from);

			if($timesheet_db->custom_shift == 1)
			{
				$_shift =  $this->shift_raw($this->db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from));
			}
			else
			{
				$_shift =  $this->shift_raw($this->db_get_shift_of_employee_by_code($shift_code_id, $from));
			}

			$timesheet_db = $this->timesheet_info_db($employee_id, $from);
			
			/* CREATE TIMESHEET DB IF EMPTY */
			if(!$timesheet_db)
			{
				$_shift_real =  $this->db_get_shift_of_employee_by_code($shift_code_id, $from);
				$_shift =  $this->shift_raw($this->db_get_shift_of_employee_by_code($shift_code_id, $from));
				
				$insert = null;
				$insert["payroll_employee_id"] = $employee_id;
				$insert["payroll_time_date"] = $from;
				$insert["payroll_time_shift_raw"] = serialize($_shift);
				Tbl_payroll_time_sheet::insert($insert);
				$timesheet_db =$this->timesheet_info_db($employee_id, $from);
				$insert = null;
			}


			$timesheet_db = $this->timesheet_info_db($employee_id, $from);

			if($timesheet_db->custom_shift == 1)
			{
				$_shift_real =  $this->db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from);
				$_shift =  $this->shift_raw($this->db_get_shift_of_employee_by_code($timesheet_db->custom_shift_id, $from));
			}
			else
			{
				$_shift_real =  $this->db_get_shift_of_employee_by_code($shift_code_id, $from);
				$_shift =  $this->shift_raw($this->db_get_shift_of_employee_by_code($shift_code_id, $from));
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
			
			$_timesheet[$from] = new stdClass();
			$_timesheet[$from]->payroll_time_sheet_id = $timesheet_db->payroll_time_sheet_id;
			$_timesheet[$from]->custom_shift = $timesheet_db->custom_shift;
			$_timesheet[$from]->custom_shift_id = $timesheet_db->custom_shift_id;
			$_timesheet[$from]->date = Carbon::parse($from)->format("Y-m-d");
			$_timesheet[$from]->day_number = Carbon::parse($from)->format("d");
			$_timesheet[$from]->day_word = Carbon::parse($from)->format("D");
			$_timesheet[$from]->record = $this->timesheet_process_in_out($timesheet_db);
			$_timesheet[$from]->is_holiday = $this->timesheet_get_is_holiday($employee_id, $from);
			

			if(isset($_shift_real[0]))
			{
				$_timesheet[$from]->day_type = $day_type = $this->timesheet_get_day_type($_shift_real[0]->shift_rest_day, $_shift_real[0]->shift_extra_day);
			}
			else
			{
				$_timesheet[$from]->day_type = "regular";
			}
			
			$_timesheet[$from]->default_remarks = $this->timesheet_default_remarks($_timesheet[$from]);
			$_timesheet[$from]->daily_info = $this->timesheet_process_daily_info($employee_id, $from, $timesheet_db, $payroll_period_company_id);
			
			$from = Carbon::parse($from)->addDay()->format("Y-m-d");
		}
		
		return $_timesheet;
	}

	public function timesheet_process_in_out($timesheet_db)
	{
		$_timesheet_record = null;

		if($timesheet_db)
		{
			$_timesheet_record_db = $this->db_get_time_sheet_record_of_in_and_out($timesheet_db->payroll_time_sheet_id);
			$_timesheet_record = $this->timesheet_process_in_out_record($_timesheet_record_db);
		}
		else
		{
			$_timesheet_record = $this->timesheet_process_in_out_default();
		}

		return $_timesheet_record;
	}

	public function timesheet_process_daily_info($employee_id, $date, $timesheet_db, $payroll_period_company_id)
	{
		$return = new stdClass();

		if($timesheet_db)
		{
			$approved_record = $this->db_get_time_sheet_record_of_in_and_out_approved($timesheet_db->payroll_time_sheet_id);
			$approved = false;

			if(count($approved_record) > 0)
			{
				$_record = $approved_record;
				$approved = true;
			}
			else
			{
				$_record = $this->db_get_time_sheet_record_of_in_and_out($timesheet_db->payroll_time_sheet_id);
			}

			$return = $this->timesheet_process_daily_info_record($employee_id, $date, $approved, $_record, $timesheet_db->payroll_time_sheet_id, $payroll_period_company_id, $timesheet_db->time_keeping_approved,  $timesheet_db->custom_shift,  $timesheet_db->custom_shift_id);
		
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

	public function error_page($message)
	{
		dd($message);
	}

	public function timesheet_process_daily_info_record($employee_id, $date, $approved, $_time, $payroll_time_sheet_id, $payroll_period_company_id, $time_keeping_approved, $custom_shift, $custom_shift_id)
	{

		$return = new stdClass();
		$return->for_approval	= ($approved == true ? 0 : 1);
		$return->daily_salary	= 0;
		$employee_contract		= $this->db_get_current_employee_contract($employee_id, $date);
		$shift_code_id 			= Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->value("shift_code_id");

		if($custom_shift == 1)
		{
			$_shift =  $this->db_get_shift_of_employee_by_code($custom_shift_id, $date);
		}
		else
		{
			$_shift =  $this->db_get_shift_of_employee_by_code($shift_code_id, $date);
		}

		$_shift_raw 			= $this->shift_raw($_shift);
		$_time_raw				= $this->time_raw($_time);
		$mode					= "daily";

		$return->_time	= $_time_raw;
		$return->_shift = $_shift_raw;
		$return->time_compute_mode = "regular";
		
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

		if ($_shift[0]->shift_break_hours != null) 
		{
			$return->shift_break_hours = $_shift[0]->shift_break_hours;
		}
		else
		{
			$return->shift_break_hours = "0.00";
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
		
			$this->save_clean_shift_to_approved_table($payroll_time_sheet_id, $return->clean_shift, $_shift_raw);
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
	
		$return->shift_approved = $this->check_if_shift_approved($return->clean_shift);
		$return->compute_shift = $this->remove_not_auto_approve($return->clean_shift);

		if(!$employee_contract)
		{
			$this->error_page("This employee doesn't have contract at this point of time.");
		}


		$rate = $this->getdaily_rate($employee_id, $date, $employee_contract->payroll_group_working_day_month);
		$daily_rate = $rate['daily'];
		$cola		= $rate['cola'];
		/* CHECK IF LATE GRACT TIME WORKING */

		$return->late_grace_time = $late_grace_time = $employee_contract->late_grace_time;
		$return->grace_time_rule_late = $grace_time_rule_late = $employee_contract->grace_time_rule_late;
		$return->overtime_grace_time = $overtime_grace_time = $employee_contract->overtime_grace_time;
		$return->grace_time_rule_overtime = $grace_time_rule_overtime = $employee_contract->grace_time_rule_overtime;
		

		if(isset($_shift[0]))
		{

			$return->day_type = $day_type = $this->timesheet_get_day_type($_shift[0]->shift_rest_day, $_shift[0]->shift_extra_day);
		}
		else
		{
			$return->day_type = $day_type = "regular";
		}

		$return->is_holiday = $is_holiday = $this->timesheet_get_is_holiday($employee_id, $date);
		//$return->leave = $leave = $this->timesheet_get_leave_hours($employee_id, $date, $_shift_raw);

		/*START leave function*/
		// $leave_data_all = PayrollLeave::employee_leave_data($employee_id);
  		// $leave_cap_data = PayrollLeave::employee_leave_capacity($employee_id);
        $leave_date_data = PayrollLeave::employee_leave_date_data($employee_id,$date);
        $use_leave = false;
        $leave = "00:00:00";
        $data_this = PayrollLeave::employee_leave_capacity_consume_remaining($employee_id)->get();
        
        if (count($leave_date_data)>0) 
        {
        	// $used_leave_data = PayrollLeave::employee_leave_consumed($leave_date_data["payroll_leave_employee_id"]);
        	// $remaining_leave_data = PayrollLeave::employee_leave_remaining($employee_id, $leave_data_all["payroll_leave_employee_id"]);
        	$use_leave = true;
        	$leave=$leave_date_data["leave_hours"];
        }

    	$return->use_leave 		 		= $use_leave;             
		$return->leave 			 		= $leave;
		$return->leave_fill_date 		= $leave_fill_late = 1;
		$return->leave_fill_undertime 	= $leave_fill_undertime = 1;
		$return->default_remarks 	    = $this->timesheet_default_remarks($return);
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

		$return->compute = Payroll2::compute_income_day_pay($return->time_output, $daily_rate, $employee_contract->payroll_group_id, $cola, $compute_type, $return->time_compute_mode);
		
		$return->value_html = $this->timesheet_daily_income_to_string($return->compute_type, $payroll_time_sheet_id, $return->compute, $return->shift_approved, $payroll_period_company_id, $time_keeping_approved);
		

		return $return;
	}
	public function timesheet_get_day_type($shift_rest_day, $shift_extra_day)
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
	public function timesheet_get_is_holiday($employee_id, $date)
	{
		$day_type	= 'not_holiday';
		$company_id	= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->value('payroll_employee_company_id');
		// $holiday	= Tbl_payroll_holiday_company::getholiday($company_id, $date)->first();
		$holiday	= Tbl_payroll_holiday_employee::getholidayv2($employee_id, $date)->first();
		
		if($holiday != null)
		{
			$day_type = strtolower($holiday->payroll_holiday_category);
		}

		return $day_type;
	}
	public function timesheet_default_remarks($data)
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
		if($remarks)
		{
			return implode(",", $remarks);
		}
		else
		{
			return "";
		}
		
	}
	public function timesheet_process_in_out_default()
	{
		$_timesheet_record[0] = new stdClass();
		$_timesheet_record[0]->time_sheet_in = "";
		$_timesheet_record[0]->time_sheet_out = "";
		$_timesheet_record[0]->time_sheet_activity = "";
		return $_timesheet_record;
	}
	public function timesheet_get_leave_hours($employee_id, $date, $shift)
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
	public function getdaily_rate($employee_id, $date, $target_days)
	{
		$salary = Tbl_payroll_employee_salary::selemployee($employee_id, $date)->first();
	
		if(!$salary)
		{
			dd("This employee doesn't have salary as of this date (" .  payroll_date_format($date) . "). Please check effectivity date of salary.");
		}
	
		$return['daily']	=  $salary->payroll_employee_salary_daily;
		$return['cola'] 	=  $salary->payroll_employee_salary_cola;
		
		return $return;
	}	
	public function timesheet_process_in_out_record($_timesheet_record_db)
	{
		$_timesheet_record = null;

		foreach($_timesheet_record_db as $key => $record)
		{
			$_timesheet_record[$key] = new stdClass();
			$_timesheet_record[$key]->time_sheet_in = $this->gb_convert_time_from_db_to_timesheet($record->payroll_time_sheet_in);
			$_timesheet_record[$key]->time_sheet_out = $this->gb_convert_time_from_db_to_timesheet($record->payroll_time_sheet_out);
			$_timesheet_record[$key]->time_sheet_activity = $record->payroll_time_shee_activity;
			$_timesheet_record[$key]->branch = $this->timesheet_get_branch($record->payroll_company_id)->name;
			$_timesheet_record[$key]->source = $record->payroll_time_sheet_origin;
			$_timesheet_record[$key]->payroll_time_sheet_id = $record->payroll_time_sheet_id;
		}

		return $_timesheet_record;
	}

	public function timesheet_get_branch($payroll_company_id)
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

	public function day_summary($timesheet_id)
	{

		$data["period_company_id"] = Request::input("period_company_id");
		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db_by_id($timesheet_id);
		$data["payroll_time_sheet_id"] = $timesheet_db->payroll_time_sheet_id;
		$data["employee_id"] = $employee_id = $timesheet_db->payroll_employee_id;
		$data["employee_info"] =  $this->db_get_employee_information($timesheet_db->payroll_employee_id); 
		$data["timesheet_info"] = $timesheet_info = Payroll2::timesheet_process_daily_info($timesheet_db->payroll_employee_id, $timesheet_db->payroll_time_date, $timesheet_db, $data["period_company_id"]);
		$employee_contract		= $this->db_get_current_employee_contract($timesheet_db->payroll_employee_id, $timesheet_db->payroll_time_date);
		$data["compute_type"] = $employee_contract->payroll_group_salary_computation;

		$data["access_salary_rate"] = Utilities::checkAccess('payroll-timekeeping','salary_rates');


		$data["compute_html"] = view('member.payroll2.employee_day_summary_compute', $data);
	
		
		/* COMPUTATION FOR CUTOFF */
		$data["period_info"] = $company_period = Tbl_payroll_period_company::sel($data["period_company_id"])->first();
		// return json_encode("123");
		// $x = view('member.payroll2.employee_day_summary', $data);
		// return json_encode($x->render());
		return view('member.payroll2.employee_day_summary', $data);
	}
	public function compute_process_cutoff($payroll_time_keeping_approved_info)
	{
		$employee_id = $payroll_time_keeping_approved_info->employee_id;
		$period_company_id = $payroll_time_keeping_approved_info->payroll_period_company_id;
		$data["payroll_time_keeping_approved_info"] = $payroll_time_keeping_approved_info;
		$data["period_info"] = $company_period = Tbl_payroll_period_company::sel($payroll_time_keeping_approved_info->payroll_period_company_id)->first();
		$from = $data["start_date"] = $company_period->payroll_period_start;
		$to = $data["end_date"] = $company_period->payroll_period_end;
		

		if($payroll_time_keeping_approved_info->cutoff_input == "")
		{
 			$this->approve_timesheets($period_company_id, $employee_id); //DELETE TIMESHEET
 			$this->approve_timesheets($period_company_id, $employee_id); //RE-POPULATE TIME SHEET

			$payroll_time_keeping_approved_info = Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_company_id)->first();
		}

		$data["cutoff_input"] 		= unserialize($payroll_time_keeping_approved_info->cutoff_input);
		$data["cutoff_compute"] 	= unserialize($payroll_time_keeping_approved_info->cutoff_compute);
		$data["cutoff_breakdown"] 	= unserialize($payroll_time_keeping_approved_info->cutoff_breakdown);

		return $data;
	}
	public function compute_whole_cutoff($period_company_id, $employee_id,$unapproved = false)
	{
		/* COMPUTATION FOR CUTOFF */
		$data["period_info"] = $company_period = Tbl_payroll_period_company::sel($period_company_id)->first();
		
		/* EMPLOYEE SALARY */	
		$salary = $this->get_salary($employee_id, $company_period->payroll_period_start);		
	
		/* EMPLOYEE GROUP */
		$group = $this->db_get_current_employee_contract($employee_id, $company_period->payroll_period_start);
		
		if(!$group)
		{
			$this->error_page("This employee doesn't have contract at this point of time.");
		}
		
		$compute_type = Payroll2::convert_period_cat($group->payroll_group_salary_computation);
		
		if(!$salary)
		{
			dd("This employee doesn't have salary as of this date (" .  payroll_date_format($company_period->payroll_period_start) . "). Please check effectivity date of salary.");
		}

		$from  = $data["start_date"]  = $company_period->payroll_period_start;
		$to    = $data["end_date"]    = $company_period->payroll_period_end;
		
		while($from <= $to)
		{
			$timesheet_db = $this->timesheet_info_db($employee_id, $from);
			$_timesheet[$from] = Payroll2::timesheet_process_daily_info($employee_id, $from, $timesheet_db, $period_company_id);
			$_timesheet[$from]->record = Payroll2::timesheet_process_in_out($timesheet_db);
			$_timesheet[$from]->branch_source_company_id 	= 0;
			
			if ($_timesheet[$from]->record != null) 
			{
				foreach ($_timesheet[$from]->record as $key => $rec) 
				{
					$temp_time_float = 0;
					if (Payroll::time_float(Payroll::time_diff($rec->time_sheet_in,$rec->time_sheet_out)) >= $temp_time_float) 
					{
						$temp_time_float = Payroll::time_float(Payroll::time_diff($rec->time_sheet_in,$rec->time_sheet_out));
						$_timesheet[$from]->branch_source_company_id = isset($rec->branch_id) ? $rec->branch_id : 0;
					}
				}
			}
			//check if approved

			if(!isset($timesheet_db))
			{
				dd("Please open timesheet first.");
			}

			$_timesheet[$from]->payroll_time_sheet_id = $timesheet_db->payroll_time_sheet_id;
			$from = Carbon::parse($from)->addDay()->format("Y-m-d");
		}
		/*START Identify Cutoff rate*/
		//base on daily rate of time sheet
		// if ($compute_type == "daily") 
		// {
		// 	$cutoff_rate = $this->identify_period_salary_daily_rate($_timesheet);
		// }
		// //base on monthly salary
		// else
		// {
			$cutoff_rate = $this->identify_period_salary($salary->payroll_employee_salary_monthly, $company_period->payroll_period_category);
		// }
		/*END Identify Cutoff rate*/
		$cutoff_rate = $this->identify_period_salary($salary->payroll_employee_salary_monthly, $company_period->payroll_period_category);
		
		$cutoff_cola = $this->identify_period_salary($salary->monthly_cola, $company_period->payroll_period_category);
		
		$cutoff_target_days = $this->identify_period_salary($salary->payroll_group_working_day_month, $group->payroll_period_category);

		$data["cutoff_input"] 				= $_timesheet;
		$data["cutoff_compute"] 			= $cutoff_compute = Payroll2::cutoff_compute_gross_pay($compute_type, $cutoff_rate, $cutoff_cola, $cutoff_target_days, $_timesheet);
		$data["cutoff_breakdown"] 			= $cutoff_breakdown = Payroll2::cutoff_breakdown($period_company_id, $employee_id, $cutoff_compute, $data);
		$data["payroll_13th_month_pay"] 	= $payroll_13th_month_pay = Payroll2::cutoff_compute_13th_month_pay($employee_id, $data);
		
		return $data;
	}
	public function day_summary_info($timesheet_id)
	{
		$data["period_company_id"] = Request::input("period_company_id");
		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db_by_id($timesheet_id);
		$data["payroll_time_sheet_id"] = $timesheet_db->payroll_time_sheet_id;
		$data["employee_id"] = $timesheet_db->payroll_employee_id;
		$data["employee_info"] = $this->db_get_employee_information($timesheet_db->payroll_employee_id); 
		$data["timesheet_info"] = $timesheet_info = Payroll2::timesheet_process_daily_info($timesheet_db->payroll_employee_id, $timesheet_db->payroll_time_date, $timesheet_db, $data["period_company_id"]);
		$employee_contract = $this->db_get_current_employee_contract($timesheet_db->payroll_employee_id, $timesheet_db->payroll_time_date);
		$data["compute_type"] = $employee_contract->payroll_group_salary_computation;

		$data["access_salary_rate"] = Utilities::checkAccess('payroll-timekeeping','salary_rates');

		return view('member.payroll2.employee_day_summary_compute', $data);
	}
	public function day_summary_change()
	{
		$data["request"] = $request = Request::input();
		$time_sheet_id = Request::input("payroll_time_sheet_id");
		$approve = Request::input("approve");
		
		if($approve == "true")
		{
			$update_time_sheet["time_keeping_approved"] = 1;
			Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $time_sheet_id)->update($update_time_sheet);
		}


		if(Request::input("time-in"))
		{
			foreach(Request::input("time-in") as $key => $time_in)
			{
				/* GET INITIAL INFORMATION NEEDED */
				$payroll_time_sheet_record_id = Request::input("payroll_time_sheet_record_id")[$key];
				$record = Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", Request::input("payroll_time_sheet_id"))->where("payroll_time_sheet_record_id", Request::input("payroll_time_sheet_record_id")[$key])->first();
			
				//$payroll_time_serialize = unserialize($record->payroll_time_serialize);
				$time_out = Request::input("time-out")[$key];
				$approve_checkbox = isset($request["approve-checkbox"][$key]) ? 1 : 0;
				$overtime_checkbox = isset($request["overtime-checkbox"][$key]) ? 1 : 0;
				
				/* UPDATE INFORMATION */
				$update = null;
				$update["payroll_time_sheet_in"] = $this->c_24_hour_format($time_in);
				$update["payroll_time_sheet_out"] = $this->c_24_hour_format($time_out);
				$update["payroll_time_sheet_auto_approved"] = $approve_checkbox;
				$update["payroll_time_serialize"] = "";

				Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", Request::input("payroll_time_sheet_id"))->where("payroll_time_sheet_record_id", Request::input("payroll_time_sheet_record_id")[$key])->update($update);
			
			}
		}		

		
		echo json_encode("success");
	}
	public function income_summary($period_company_id, $employee_id)
	{
		$data["date"]  = $date  = Tbl_payroll_period_company::sel($period_company_id)->value('payroll_period_start');

		$data["group"] = $group = $this->db_get_current_employee_contract($employee_id, $date);

		if(!$group)
		{
			dd("You need to set a PAYROLL GROUP in order to show summary.");
		}

		$check_approved = Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_company_id)->first();
		
		if($check_approved)
		{
			$data = $this->compute_process_cutoff($check_approved);
			// dd($this->compute_process_cutoff($check_approved));
			$data["computation_type"] = $computation_type = $group->payroll_group_salary_computation;
			
			if($computation_type != "Flat Rate" && $computation_type != "Hourly Rate")
			{
				if(isset($data["cutoff_compute"]->cutoff_rate))
				{
					$computation_type = "Monthly Rate";
				}
				else
				{
					$computation_type = "Daily Rate";
				}
			}
		}
		else
		{
			$data = $this->compute_whole_cutoff($period_company_id, $employee_id);
			$data["computation_type"] 	= $computation_type = $group->payroll_group_salary_computation;
		}

		$data["employee_salary"] 		= tbl_payroll_employee_salary::where("payroll_employee_id", $employee_id);
		$data["employee_id"] 			= $employee_id;
		$data["employee_info"] 			= $this->db_get_employee_information($employee_id); 
		$check_approved 				= Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_company_id)->first();
		$data["time_keeping_approved"]  = $check_approved ? true : false;
		
		$data["employee_salary"]   		= $this->get_salary($employee_id,$data["start_date"]);

		$data['access_salary_rate'] 	= Utilities::checkAccess('payroll-timekeeping','salary_rates');

		switch ($computation_type)
		{
			case "Daily Rate":
				return $this->income_summary_daily_computation($data);
			break;
			case "Hourly Rate":
				return $this->income_summary_hourly_computation($data);
			break;
			case "Monthly Rate":
				return $this->income_summary_monthly_computation($data);
			break;
			case "Flat Rate":
				return $this->income_summary_flat_rate_computation($data);
			break;
		}
	}
	public function delete_adjustment($period_company_id, $employee_id, $adjustment_id)
	{
		$data["page"] 				= "Delete Adjustment";
		$data["period_id"] 			= $period_company_id;
		$data["employee_id"] 		= $employee_id;
		$data["adjustment_id"]		= $adjustment_id;

		if(Request::isMethod("post"))
		{
			Tbl_payroll_adjustment::where("payroll_employee_id", $employee_id)->where("payroll_period_company_id", $period_company_id)->where("payroll_adjustment_id", $adjustment_id)->delete();
			$return["status"] = "success";
			$return["call_function"] = "apply_adjustment_submit_done";
			$return["period_id"] 		= $period_company_id;
			$return["employee_id"] 		= $employee_id;
			echo json_encode($return);
		}
		else
		{
			$data["company_period"] 	= $this->db_get_company_period_information($period_company_id);
			$data["employee_info"] 		= $employee_info = $this->db_get_employee_information($employee_id);
			$data["show_period_start"]	= date("F d, Y", strtotime($data["company_period"]->payroll_period_start));
			$data["show_period_end"]	= date("F d, Y", strtotime($data["company_period"]->payroll_period_end));
			$data["adjustment"]			= Tbl_payroll_adjustment::where("payroll_adjustment_id", $adjustment_id)->first();

			$check_approved = Tbl_payroll_time_keeping_approved::where("employee_id", $employee_id)->where("payroll_period_company_id", $period_company_id)->first();
			
			if($check_approved)
			{
				return view("member.payroll2.delete_adjustment_warning", $data);
			}
			else
			{
				return view("member.payroll2.delete_adjustment", $data);
			}
		}
	}
	public function make_adjustment($period_company_id, $employee_id)
	{
		if(Request::isMethod("post"))
		{
			if(Request::input("adjustment_type") == "addition")
			{
				switch (Request::input("adjustment_setting"))
				{
					case 'taxable':
						$add_gross_pay = true;
					break;
					case 'non-taxable':
						$add_gross_pay = true;
						$deduct_taxable_salary = true;
						$add_net_pay = true;
					break;
					default:
						$add_net_pay = true;
					break;
				}
			}
			else
			{
				$deduct_net_pay = true;
			}

			$insert["payroll_employee_id"] 				= $employee_id;
			$insert["payroll_period_company_id"]		= $period_company_id;
			$insert["payroll_adjustment_name"] 			= Request::input("adjustment_name");
			$insert["payroll_adjustment_category"] 		=  Request::input("adjustment_category"); //Request::input("adjustment_type");
			$insert["payroll_adjustment_amount"] 		= Request::input("adjustment_amount");
			$insert["adjustment_setting"] 				= Request::input("adjustment_setting");

			$insert["add_gross_pay"] 			= (isset($add_gross_pay) ? 1 : 0);
			$insert["deduct_gross_pay"] 		= (isset($deduct_gross_pay) ? 1 : 0);
			$insert["add_taxable_salary"] 		= (isset($add_taxable_salary) ? 1 : 0);
			$insert["deduct_taxable_salary"] 	= (isset($deduct_taxable_salary) ? 1 : 0);
			$insert["add_net_pay"] 				= (isset($add_net_pay) ? 1 : 0);
			$insert["deduct_net_pay"] 			= (isset($deduct_net_pay) ? 1 : 0);

			Tbl_payroll_adjustment::insert($insert);
			$return["status"] = "success";
			$return["call_function"] = "apply_adjustment_submit_done";
			$return["period_id"] 		= $period_company_id;
			$return["employee_id"] 		= $employee_id;
			
			echo json_encode($return);
		}
		else
		{
			$data["page"] 				= "Make Adjustment";
			$data["period_id"] 			= $period_company_id;
			$data["employee_id"] 		= $employee_id;
			$data["company_period"] 	= $this->db_get_company_period_information($period_company_id);
			$data["employee_info"] 		= $employee_info = $this->db_get_employee_information($employee_id);
			$data["show_period_start"]	= date("F d, Y", strtotime($data["company_period"]->payroll_period_start));
			$data["show_period_end"]	= date("F d, Y", strtotime($data["company_period"]->payroll_period_end));

			return view("member.payroll2.make_adjustment", $data);
		}
	}

	public function income_summary_daily_computation($data)
	{
		return view("member.payroll2.employee_income_summary_daily", $data);
	}
	public function income_summary_hourly_computation($data)
	{
		return view("member.payroll2.employee_income_summary_hourly", $data);
	}
	public function income_summary_monthly_computation($data)
	{
		return view("member.payroll2.employee_income_summary_monthly", $data);
	}
	public function income_summary_flat_rate_computation($data)
	{
		return view("member.payroll2.employee_income_summary_flat", $data);
	}

	public function custom_shift()
	{
		$data = Request::input();

		
		$employee_id = $data["employee_id"];
		$date = $data["date"];
		$period_company_id = $data["period_id"];
		$timesheet_id = $data["timesheet_id"];

		$data["timesheet_db"] = $timesheet_db = $this->timesheet_info_db_by_id($timesheet_id);
		$data["employee_info"] = $employee_info = $this->db_get_employee_information($employee_id);
		
		$shift_code_id = $employee_info->shift_code_id;

		if($timesheet_db->custom_shift == 1)
		{
			$shift_code_id = $timesheet_db->custom_shift_id;
		}

	    $data['shift_code'] = Tbl_payroll_shift_code::where('shift_code_id', $shift_code_id)->first();
	    $_day = Tbl_payroll_shift_day::where('shift_code_id', $shift_code_id)->get();

		foreach($_day as $key => $day)
		{
			if($day->shift_day == date("D", strtotime($date)))
			{
				$data["_day"][$key] = $day;
				$data["_day"][$key]->time_shift = Tbl_payroll_shift_time::where("shift_day_id", $day->shift_day_id)->get();
			}

		}
		return view("member.payroll2.custom_shift", $data);
	}
	public function custom_shift_update()
	{
		$timesheet_id = Request::input("timesheet_id");

		/* CREATE NEW SHIFT CODE FOR CUSTOM SHIFT */
		$insert_code["shift_code_name"] = "Custom Shift";
		$insert_code["shift_archived"] = 2;
		$insert_code["shop_id"] = $this->user_info->shop_id;
		$shift_code_id = Tbl_payroll_shift_code::insertGetId($insert_code);

		/* CHECK EXIST */
		$shift_code = Tbl_payroll_shift_code::where("shop_id", $this->user_info->shop_id)->where("shift_code_id", $shift_code_id)->first();

		if($shift_code)
		{
		   $insert_shift = array();

		   /* INSERT DAY */
		   $key = 0;
		   $tc  = 0;

		   Tbl_payroll_shift_day::where("shift_code_id", $shift_code_id)->delete();

		   foreach(Request::input("day") as $day)
		   {
		        /* INSERT SHIFT DAY */
		        $insert_day["shift_day"] = $day;
		        $insert_day["shift_code_id"] = $shift_code_id;
		        $insert_day["shift_target_hours"] = Request::input("target_hours")[$day];
		        $insert_day["shift_break_hours"] = Request::input("break_hours")[$day];
		        $insert_day["shift_rest_day"] = Request::input("rest_day_" . $day) == 1 ? 1 : 0;
		        $insert_day["shift_extra_day"] = Request::input("extra_day_" . $day) == 1 ? 1 : 0;
		        $insert_day["shift_flexi_time"] = Request::input("flexitime_day_" . $day) == 1 ? 1 : 0;

		        $key++;

		        $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);

		        /* INSERT SHIFT TIME */
		        foreach(Request::input("work_start")[$day] as $k => $time)
		        {
		             if($time != "") //MAKE SURE TIME IS NOT BLANK
		             {
		                  $insert_time[$tc]["shift_day_id"] = $shift_day_id;
		                  $insert_time[$tc]["shift_work_start"] = DateTime::createFromFormat( 'H:i A', $time);
		                  $insert_time[$tc]["shift_work_end"] = DateTime::createFromFormat( 'H:i A', Request::input("work_end")[$day][$k]);
		                  $tc++;
		             }
		        }

		        if(isset($insert_time))
		        {
		             Tbl_payroll_shift_time::insert($insert_time);
		             $insert_time = null;
		        }   
		   }

		}

		/* UPDATE TIMESHEET CUSTOM MODE */
		$update_sheet["custom_shift"] = 1;
		$update_sheet["custom_shift_id"] = $shift_code_id;
		Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $timesheet_id)->update($update_sheet);

		$timesheet_info = $this->timesheet_info_db_by_id($timesheet_id);
		$return['function_name'] = 'custom_shift_success';
		$return['payroll_time_sheet_id'] = $timesheet_id;
		$return['sheet_date'] = $timesheet_info->payroll_time_date;
		$return['status']        = 'success';
		return collect($return)->toJson();

		$return["status"] = "success";
		echo json_encode($return);
	}

	/* GLOBAL FUNCTION FOR THIS CONTROLLER */
	public function c_24_hour_format($time)
	{
	    return date("H:i:s", strtotime($time));
	}
	public function convert_to_serialize_row_from_approved_clean_shift($_time)
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
	public function shift_raw($_shift)
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
	public function remove_not_auto_approve($_time)
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
	public function time_raw($_time)
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
	public function gb_convert_time_from_db_to_timesheet($db_time)
	{
		if($db_time == "00:00:00")
		{
			$return = "";
		}
		else
		{
			$return = $db_time;
		}

		return $return;
	}
	public function check_if_shift_approved($_time)
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

	/* DB CONNECT FUNCTIONS */
	public function save_clean_shift_to_approved_table($time_sheet_id, $_clean_shift, $_shift_raw)
	{
		if($_clean_shift)
		{
			foreach($_clean_shift as $key => $clean_shift)
			{

				$insert[$key]["payroll_time_sheet_id"] 				= $time_sheet_id;
				$insert[$key]["payroll_time_sheet_in"] 				= $clean_shift->time_in;
				$insert[$key]["payroll_time_sheet_out"] 			= $clean_shift->time_out;
				$insert[$key]["payroll_time_shee_activity"] 		= "";
				$insert[$key]["payroll_time_sheet_origin"] 			= "";
				$insert[$key]["payroll_time_sheet_auto_approved"] 	= $clean_shift->auto_approved;
				$insert[$key]["payroll_time_serialize"] 			= serialize($clean_shift);
			}
			
			Tbl_payroll_time_sheet_record_approved::insert($insert);
		}
	}
	public function db_get_list_of_company_for_period($id)
	{
		$_data = Tbl_payroll_company::orderBy('tbl_payroll_company.payroll_company_name')->where("payroll_parent_company_id", $id)->get();
		return $_data;
	}
	public function db_get_employee_information($employee_id)
	{
		return Tbl_payroll_employee_basic::where("shop_id", $this->user_info->shop_id)->where("payroll_employee_id", $employee_id)->first();
	}
	public function db_get_list_of_employees_by_company($company_id)
	{
		return Tbl_payroll_employee_basic::where("shop_id", $this->user_info->shop_id)->where("payroll_employee_company_id", $company_id)->orderBy("payroll_employee_number")->get();
	}
	public function db_get_list_of_employees_by_company_with_search($company_id, $search = "", $time_keeping_approved = 0, $period_company_id, $period_start, $branch = 0)
	{
		$query = Tbl_payroll_employee_basic::select("*");

		// DB::raw("(SELECT time_keeping_approve_id FROM tbl_payroll_time_keeping_approved WHERE employee_id = tbl_payroll_employee_basic.payroll_employee_id AND tbl_payroll_time_keeping_approved.payroll_period_company_id = '" . $period_company_id . "');
		if($time_keeping_approved == 0)
		{
			// dd("(SELECT time_keeping_approve_id FROM tbl_payroll_time_keeping_approved WHERE employee_id = tbl_payroll_employee_basic.payroll_employee_id AND tbl_payroll_time_keeping_approved.payroll_period_company_id = '" . $period_company_id . "')");
			$query->whereNull(DB::raw("(SELECT time_keeping_approve_id FROM tbl_payroll_time_keeping_approved WHERE employee_id = tbl_payroll_employee_basic.payroll_employee_id AND tbl_payroll_time_keeping_approved.payroll_period_company_id = '" . $period_company_id . "')"));
		}
		else
		{
			$query->join("tbl_payroll_time_keeping_approved", "tbl_payroll_time_keeping_approved.employee_id","=", "tbl_payroll_employee_basic.payroll_employee_id")->where("tbl_payroll_time_keeping_approved.payroll_period_company_id", $period_company_id);
		}
		
		$query->where("tbl_payroll_employee_basic.shop_id", $this->user_info->shop_id);
		$query->join("tbl_payroll_company", "tbl_payroll_employee_basic.payroll_employee_company_id", "=", "tbl_payroll_company.payroll_company_id");
		
		if($branch == 0)
		{
			$query->where(function($quer) use ($company_id)
			{
				$quer->where("payroll_company_id", $company_id);
				$quer->orWhere("payroll_parent_company_id", $company_id);
			});
		}
		else
		{
			$query->where("payroll_company_id", $branch);
		}

		

		$query->orderBy("payroll_employee_last_name");
		$query->groupBy("tbl_payroll_employee_basic.payroll_employee_id");
		
		if($search != "")
		{
			$query->where("tbl_payroll_employee_basic.payroll_employee_display_name", "LIKE", "%" . $search . "%");
		}

	

		$query->where('tbl_payroll_employee_contract.payroll_employee_contract_status','<=','7')
				->join('tbl_payroll_employee_contract','tbl_payroll_employee_contract.payroll_employee_id', '=', 'tbl_payroll_employee_basic.payroll_employee_id');

		
		$_table = $query->get();
		
		$_return = null;
		
		$period_info = Tbl_payroll_period::where('tbl_payroll_period_company.payroll_period_company_id', $period_company_id)->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_period_id','=','tbl_payroll_period.payroll_period_id')->first();

		foreach($_table as $key => $row)
		{
			$payroll_employee_contract = Tbl_payroll_employee_contract::where('payroll_employee_id',$row->payroll_employee_id)->first(); 

			$payroll_group = $this->db_get_current_employee_contract($row->payroll_employee_id, $period_start);
			
			if ($payroll_group["payroll_group_period"] == $period_info["payroll_period_category"]) 
			{	
				$_return[$key] = $row;

				if($payroll_group)
				{
					$_return[$key]->payroll_group_id = $payroll_group->payroll_group_id;
					$_return[$key]->payroll_group_code = $payroll_group->payroll_group_code;
				}
				else
				{
					$_return[$key]->payroll_group_id = null;
					$_return[$key]->payroll_group_code = null;
				}
				
				$shift = Tbl_payroll_shift_code::where("shift_code_id", $row->shift_code_id)->first();
				
				if($shift)
				{
					$_return[$key]->shift_code_name = $shift->shift_code_name;
					$_return[$key]->shift_code_link = "action_load_link_to_modal('/member/payroll/shift_template/modal_view_shift_template/" . $shift->shift_code_id . "', 'lg')";
				}
				else
				{
					$_return[$key]->shift_code_name = "";
					$_return[$key]->shift_code_link = "";
				}
			}	
		}

		return $_return;
	}
	public function db_get_company_period_information($period_id)
	{
		return Tbl_payroll_period_company::sel($period_id)->select('tbl_payroll_company.*','tbl_payroll_period.*','tbl_payroll_period_company.*')->first();
	}
	public function db_get_time_sheet_record_of_in_and_out($time_sheet_id)
	{
		return Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $time_sheet_id)->get();
	}
	public function db_get_time_sheet_record_of_in_and_out_approved($time_sheet_id)
	{
		return Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $time_sheet_id)->get();
	}
	public function db_get_current_employee_contract($employee_id, $date = '0000-00-00')
	{
		// return Tbl_payroll_employee_contract::where("payroll_employee_id", $employee_id)->orderBy("payroll_employee_contract_id", "desc")->first();
	
		return Tbl_payroll_employee_contract::selemployee($employee_id, $date)
											->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
											->first();
	}
	public function db_get_shift_of_employee($employee_id, $date)
	{
		return Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->shift()->day()->where("shift_day", date("D", strtotime($date)))->time()->get();
	}
	public function db_get_shift_of_employee_by_code($shift_code_id, $date)
	{
		return Tbl_payroll_shift_code::where('tbl_payroll_shift_code.shift_code_id', $shift_code_id)->day()->time()->where("shift_day", date("D", strtotime($date)))->get();
	}

	public function timesheet_info_db($employee_id, $date)
	{
		return Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($date)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->first();
	}
	public function timesheet_info_db_by_id($timesheet_id)
	{
		return Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $timesheet_id)->first();
	}
	
	public function get_salary($employee_id, $date)
	{
		return Tbl_payroll_employee_salary::selemployee($employee_id, $date)->first();
	}
	
	public function identify_period_salary($salary = 0, $period = '')
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

	public function identify_period_salary_daily_rate($_timesheet)
	{
		$salary_period = 0;
		
		foreach ($_timesheet as $key => $timesheet) 
		{
			$salary_period += $timesheet->compute->daily_rate;
		}
		return $salary_period;
	}

	public function identify_monthly_cola_salary($monthly_cola = 0, $period = '')
	{
		$monthly_cola = 0;
		if($period == 'Monthly')
		{
			$monthly_cola = $monthly_cola;
		}
		else if($period == 'Semi-monthly')
		{
			$monthly_cola = $monthly_cola / 2;
		}
		else if($period == 'Weekly')
		{
			$monthly_cola = $monthly_cola / 4;
		}

		return $monthly_cola;
	}
	
	public function gettotal_break($data)
	{
		$return = array();
		$return['basic_total']		= 0;
		$return['basic_gross']		= 0;
		$return['basic_cola']		= 0;
		$return['basic_gross_cola'] = 0;
		
		foreach($data['cutoff_input'] as $key => $cutoff)
		{
			$return['basic_total']		+= $cutoff->compute->total_day_basic;
			$return['basic_gross']		+= $cutoff->compute->total_day_income;
			$return['basic_cola']		+= $cutoff->compute->cola;
			$return['basic_gross_cola'] += $cutoff->compute->total_day_income_plus_cola;
		}

		return $return;
	}
	
	public function generate_adjustment($data)
	{
		// dd($data);
		$_obj = $data['netpay_compute']['obj'];
		$return = array();

		$additional = array();
		$deduction = array();
		
		foreach($_obj as $obj)
		{
			foreach($obj['obj'] as $adj)
			{
				if(isset($adj['name']))
				{
					$temp['name'] = $adj['name'];
					$temp['amount'] = $adj['amount'];
					if($adj['type'] == 'add')
					{
						array_push($additional, $temp);
					}
					else if($adj['type'] == 'minus')
					{
						array_push($deduction, $temp);
					}
				}
				
			}
			
			if(isset($obj['obj']['name']))
			{
				$temp['name'] = $obj['obj']['name'];
				$temp['amount'] = $obj['obj']['amount'];
				if($obj['obj']['type'] == 'add')
				{
					array_push($additional, $temp);
				}
				else if($obj['obj']['type'] == 'minus')
				{
					array_push($deduction, $temp);
				}
			}
		}
		
		$return['add'] = $additional;
		$return['minus'] = $deduction;
		return $return;
	}
	
}