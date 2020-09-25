<?php

namespace App\Http\Controllers\Member;

use Request;
use stdClass;
use Redirect;
use Session;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_group_rest_day;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_shift;
use App\Models\Tbl_payroll_employee_schedule;
use App\Models\Tbl_payroll_time_keeping_approved;


use App\Globals\Payroll;

class PayrollTimeSheetController extends Member
{
	public function index()
	{
		$data["_employee"] = Tbl_payroll_employee_basic::where("shop_id", $this->user_info->shop_id)->get();
		$data["current_employee"] = $current_employee = Tbl_payroll_employee_basic::where("shop_id", $this->user_info->shop_id)->where("payroll_employee_id", Request::input("employee_id"))->first();

		/* REDIRECT IF NO DEFAULT */
		if(empty($data["current_employee"]))
		{
			return Redirect::to("/member/payroll/employee_timesheet?employee_id=" . $data["_employee"][0]->payroll_employee_id)->send();
		}

		$data["employee_info"] = Tbl_payroll_employee_contract::selemployee($data["current_employee"]->payroll_employee_id)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();
		$data["default_time_in"] = Carbon::parse($data["employee_info"]->payroll_group_start)->format("h:i A");
		$data["default_time_out"] = Carbon::parse($data["employee_info"]->payroll_group_end)->format("h:i A");
		$data['_company'] = Payroll::company_heirarchy($this->user_info->shop_id);
		// dd($data);
		return view('member.payroll.employee_timesheet', $data);
	}


	public function company_timesheet($id)
	{
		$count = Tbl_payroll_period_company::check($id, $this->user_info->shop_id)->count();

		if($count == 0)
		{
			return Redirect::to('/member/payroll/time_keeping')->send();
		}
		
		$data['payroll_period_company_id'] = $id;
		$data['company'] = Tbl_payroll_period_company::sel($id)->select('tbl_payroll_company.*','tbl_payroll_period.*','tbl_payroll_period_company.*')->first();
		$data['_employee'] = Tbl_payroll_employee_contract::employeefilter($data['company']->payroll_company_id, 0, 0, $data['company']->payroll_period_end, $this->user_info->shop_id)
							->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
							->where('tbl_payroll_group.payroll_group_period', $data['company']->payroll_period_category)
							->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
							->get();
		
		$payroll_employee_id = 0;
		if(isset($data['_employee'][0]))
		{
			$payroll_employee_id = $data['_employee'][0]->payroll_employee_id;
		}

		$data["current_employee"] = $current_employee = Tbl_payroll_employee_basic::where("payroll_employee_id", $payroll_employee_id)->first();

		$current_employee_id = 0;
		if(isset($data["current_employee"]->payroll_employee_id))
		{
			$current_employee_id = $data["current_employee"]->payroll_employee_id;
		}	
		
		$data["employee_info"] = Tbl_payroll_employee_contract::selemployee($current_employee_id)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();

		$payroll_group_start 	= '12:00:00';
		$payroll_group_end 		= '12:00:00';
		if(isset($data["employee_info"]->payroll_group_start))
		{
			$payroll_group_start 	= $data["employee_info"]->payroll_group_start;
			$payroll_group_end 		= $data["employee_info"]->payroll_group_end;
		}

		$data["default_time_in"] = Carbon::parse($payroll_group_start)->format("h:i A");
		$data["default_time_out"] = Carbon::parse($payroll_group_end)->format("h:i A");

		// dd($data);
		return view('member.payroll.employee_timesheet', $data);
	}

	public function timesheet($employee_id, $payroll_period_id)
	{

		$data["employee_id"] = $employee_id;
		$data["page"] = "Timesheet Table";

		$period = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();		/* GET PAYROLL PERIOD */
		$from = $data["start_date"] = $period->payroll_period_start;
		$to = $data["end_date"] = $period->payroll_period_end;


		/* GET EMPLOYEE INFORMATION */
		$data["employee_info"] = Tbl_payroll_employee_basic::where("payroll_employee_id", $employee_id)->first();

		$data["employee_contract"] = Tbl_payroll_employee_contract::selemployee($employee_id)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();

		// $payroll_group_start 	= $data["employee_contract"]->payroll_group_start;
		// $payroll_group_end 		= $data["employee_contract"]->payroll_group_end;

		$payroll_group_start 	= '00:00:00';
		$payroll_group_end 		= '00:00:00';

		/* INITALIZE SETTINGS FOR EMPLOYEE */
		$time_rule = $data["time_rule"] = "regulartime"; //flexitime, regulartime

		$data["default_time_in"] = $default_time_in = Carbon::parse($payroll_group_start)->format("h:i A");

		$data["default_time_out"] = $default_time_out =Carbon::parse($payroll_group_end)->format("h:i A");

		$data["default_working_hours"] = $default_working_hours = "08:00";

		/* CREATE ARRAY */
		while($from <= $to)
		{
			/* INITITAL DATA */

			$day 				= Carbon::parse($from)->format("D");
			$day_number 		= Carbon::parse($from)->format("d");
			$symbol 			= '<i class="table-check fa fa-unlock-alt hidden"></i>';
			$holiday_class 		= '';
			$virtural_holiday 	= '';
			$disable 			= '';
			

			/* check if holiday */
			$holiday = Tbl_payroll_holiday_company::getholiday($data["employee_info"]->payroll_employee_company_id, Carbon::parse($from)->format("Y-m-d"))->get();

			if($holiday->count() > 0)
			{
				$holiday_class = ' color-red';
				$day 		= '<span class="color-red">'.$day.'</span>';
				$day_number = '<span class="color-red">'.$day_number.'</span>';
				$symbol 	= '<a href="#" class="popup" size="sm" link="/member/payroll/employee_timesheet/show_holiday/'.$data["employee_info"]->payroll_employee_company_id.'/'.Carbon::parse($from)->format("Y-m-d").'"><i class="table-check fa fa-calendar hidden color-red"></i></a>';
				$virtural_holiday 	= '08:00';
			}

			/* pre set the time record */
			$approved = Payroll::process_time($employee_id, $from);
			// dd($approved);

			$data["_timesheet"][$from] 						= new stdClass();
			$data["_timesheet"][$from]->date 				= Carbon::parse($from)->format("Y-m-d");
			$data["_timesheet"][$from]->day_number 			= $day_number;
			$data["_timesheet"][$from]->day_word 			= $day;
			$data['_timesheet'][$from]->symbol 				= $symbol;
			$data['_timesheet'][$from]->holiday_class 		= $holiday_class;
			$data['_timesheet'][$from]->virtural_holiday 	= $virtural_holiday;
			$data['_timesheet'][$from]->virtural_holiday 	= $virtural_holiday;
			$data['_timesheet'][$from]->approved 			= $approved;
			$data['_timesheet'][$from]->color 				= 'color-red';
	
			if($approved->payroll_time_sheet_approved == 1)
			{
				$data['_timesheet'][$from]->color = 'color-green';
			}

			/* GET DATA FOR SPECIFIC DATE */
			$data["timesheet_info"] = Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($from)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->first();

			if(empty($data["timesheet_info"]))
			{
				// Tbl_payroll_time_sheet
				$sheet_insert['payroll_time_date'] 		= Carbon::parse($from)->format("Y-m-d");
				$sheet_insert['payroll_employee_id'] 	= $employee_id;
				$payroll_time_sheet_id = Tbl_payroll_time_sheet::insertGetId($sheet_insert);
				$data["timesheet_info"] = Tbl_payroll_time_sheet::where('payroll_time_sheet_id', $payroll_time_sheet_id)->first();
			}
			
			if(!empty($data["timesheet_info"])) //IF TIME SHEET RECORD EXIST GET RECORD
			{
				$data["_timesheet"][$from]->break = "01:00";
				$data["_timesheet"][$from]->time_record_count = 1;
				$data["_timesheet"][$from]->payroll_time_sheet_approved = $data["timesheet_info"]->payroll_time_sheet_approved;
				$_timesheet_record = Tbl_payroll_time_sheet_record::getrecord($data["timesheet_info"]->payroll_time_sheet_id)->get();
				
				if($_timesheet_record->isEmpty())
				{
					/* insert new record if empty */
					$temp_time_insert['payroll_time_sheet_id'] = $data["timesheet_info"]->payroll_time_sheet_id;
					$temp_time_insert['payroll_time_sheet_in'] = '00:00:00';
					$temp_time_insert['payroll_time_sheet_out'] = '00:00:00';
					Tbl_payroll_time_sheet_record::insert($temp_time_insert);

					$_timesheet_record = Tbl_payroll_time_sheet_record::getrecord($data["timesheet_info"]->payroll_time_sheet_id)->get();
					
				}
				
				foreach($_timesheet_record as $key => $timesheet_record)
				{
					$data["_timesheet"][$from]->time_record[$key] = new stdClass();

					$data["_timesheet"][$from]->time_record[$key]->payroll_time_sheet_record_id = $timesheet_record->payroll_time_sheet_record_id;
					$data['_timesheet'][$from]->time_record[$key]->company = $timesheet_record->payroll_company_name;

					$data["_timesheet"][$from]->time_record[$key]->time_in = "";
					$data["_timesheet"][$from]->time_record[$key]->time_out = "";


					if($timesheet_record->payroll_time_sheet_out != "00:00:00" || $timesheet_record->payroll_time_sheet_out != null || $timesheet_record->payroll_time_sheet_out != '')
					
					{
						if($data["timesheet_info"]->payroll_time_sheet_approved == 1)
						{
							$data["_timesheet"][$from]->time_record[$key]->time_in =  Carbon::parse($timesheet_record->payroll_time_sheet_approved_in)->format("h:i A");

							$data["_timesheet"][$from]->time_record[$key]->time_out = Carbon::parse($timesheet_record->payroll_time_sheet_approved_out)->format("h:i A");
							
						}
						else
						{
							$data["_timesheet"][$from]->time_record[$key]->time_in =  Carbon::parse($timesheet_record->payroll_time_sheet_in)->format("h:i A");

							$data["_timesheet"][$from]->time_record[$key]->time_out = Carbon::parse($timesheet_record->payroll_time_sheet_out)->format("h:i A");
						}
					}

					$data["_timesheet"][$from]->time_record[$key]->activities =  $timesheet_record->payroll_time_shee_activity;

					$data["_timesheet"][$from]->time_record[$key]->origin = $timesheet_record->payroll_time_sheet_origin;

					if($timesheet_record->payroll_time_sheet_origin != '' && $timesheet_record->payroll_time_sheet_origin != 'Payroll Time Sheet')
					{
						$disable = '';
						// $disable = 'disabled="disabled"';
					}

					if($timesheet_record->payroll_time_sheet_origin == '' || $timesheet_record->payroll_time_sheet_origin == 'Payroll Time Sheet')
					{
						$disable = '';
					}

					$data["_timesheet"][$from]->time_record[$key]->disable = $disable;

				}
				
			}
			else //DEFAULT IF EMPTY RECORD
			{
				$data["_timesheet"][$from]->break = "00:00";
				$data["_timesheet"][$from]->time_record_count = 1;
				$data["_timesheet"][$from]->time_record[0] = new stdClass();
				$data["_timesheet"][$from]->time_record[0]->time_in = "";
				$data["_timesheet"][$from]->time_record[0]->time_out = "";
				$data["_timesheet"][$from]->time_record[0]->activities = "";
				$data["_timesheet"][$from]->time_record[0]->disable = $disable;
				$data["_timesheet"][$from]->time_record[0]->origin = '';
				$data["_timesheet"][$from]->payroll_time_sheet_approved = 0;
			}

			$from = Carbon::parse($from)->addDay()->format("Y-m-d");
		}
		// dd($data);
		$data['summary'] = Self::timesheet_summary($employee_id, $payroll_period_id);

		$payroll_period_company_id = Tbl_payroll_period_company::where('payroll_period_id', $payroll_period_id)->where('payroll_company_id', $data["employee_info"]->payroll_employee_company_id)->value('payroll_period_company_id');

		$data['_remarks'] = Payroll::view_remarks($this->user_info->shop_id, $payroll_period_company_id);
		$data['payroll_period_company_id'] = $payroll_period_company_id;
		// dd($data["_timesheet"]);
		return view('member.payroll.employee_timesheet_table', $data);
	}

	public function timesheet_summary($employee_id = 0, $payroll_period_id = 0)
	{

		$period = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();		
		$group  = Tbl_payroll_employee_contract::selemployee($employee_id)
												->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
												->select('tbl_payroll_group.*')
												->first();
		
		$param_target = 'Daily';
		$param_hour	  = 0;
		if(isset($group->payroll_group_target_hour_parameter))
		{
			$param_target 	= $group->payroll_group_target_hour_parameter;
		}
		
		if(isset($group->payroll_group_target_hour))
		{
			$param_hour 	= $group->payroll_group_target_hour;
		}

		$from = $period->payroll_period_start;
		$to   = $period->payroll_period_end;

		$total_time_spent 				= '00:00';
		$total_regular_hours 			= '00:00';
		$total_late_overtime 			= '00:00';
		$total_early_overtime 			= '00:00';
		$total_late_hours 				= '00:00';
		$total_under_time 				= '00:00';
		$total_rest_day_hours 			= '00:00';
		$total_extra_day_hours 			= '00:00';
		$total_total_hours 				= '00:00';
		$total_night_differential 		= '00:00';
		$total_special_holiday_hours 	= '00:00';
		$total_regular_holiday_hours 	= '00:00';
		$total_break_hours				= '00:00';

		$regular_day_count 				= 0;
		$rest_day_count 				= 0;
		$extra_day_count				= 0;
		$special_holiday_count			= 0;
		$regular_holiday_count			= 0;
		$total_working_days				= 0;

		$leave_with_pay					= 0;
		$leave_wo_pay					= 0;
		$absent 						= 0;

		$data = array();
		$array = array();
		while($from <= $to)
		{
			
			$date = Carbon::parse($from)->format("Y-m-d");
			$record = Payroll::process_time($employee_id, $date);

			// dd($record);
			$approved_timesheet = $record->approved_timesheet;
			
			if($param_target == 'Daily')
			{
				$regular_day_count += divide(Payroll::time_float($approved_timesheet->regular_hours) , $param_hour);

				$rest_day_count    += divide(Payroll::time_float($approved_timesheet->rest_day_hours) , $param_hour);
				$extra_day_count   += divide(Payroll::time_float($approved_timesheet->extra_day_hours) , $param_hour);
				$special_holiday_count += divide(Payroll::time_float($approved_timesheet->special_holiday_hours) , $param_hour);
				$regular_holiday_count += divide(Payroll::time_float($approved_timesheet->regular_holiday_hours) , $param_hour);
			}

			if($approved_timesheet->absent)
			{
				$absent++;
			}
			if($approved_timesheet->leave == 'without_pay')
			{
				$leave_wo_pay++;
			}
			if($approved_timesheet->leave == 'with_pay')
			{
				$leave_with_pay++;
			}

			$total_time_spent = Payroll::sum_time($total_time_spent, $approved_timesheet->time_spent);
			$total_regular_hours = Payroll::sum_time($total_regular_hours, $approved_timesheet->regular_hours);

			$total_late_overtime = Payroll::sum_time($total_late_overtime, $approved_timesheet->late_overtime);
			$total_early_overtime = Payroll::sum_time($total_early_overtime, $approved_timesheet->early_overtime);
			$total_late_hours = Payroll::sum_time($total_late_hours, $approved_timesheet->late_hours);
			$total_under_time = Payroll::sum_time($total_under_time, $approved_timesheet->under_time);
			$total_rest_day_hours = Payroll::sum_time($total_rest_day_hours, $approved_timesheet->rest_day_hours);
			$total_extra_day_hours = Payroll::sum_time($total_extra_day_hours, $approved_timesheet->extra_day_hours);
			$total_total_hours = Payroll::sum_time($total_total_hours, $approved_timesheet->total_hours);
			$total_night_differential = Payroll::sum_time($total_night_differential, $approved_timesheet->night_differential);
			$total_special_holiday_hours = Payroll::sum_time($total_special_holiday_hours, $approved_timesheet->special_holiday_hours);
			$total_regular_holiday_hours = Payroll::sum_time($total_regular_holiday_hours, $approved_timesheet->regular_holiday_hours);

			$total_break_hours = Payroll::sum_time($total_break_hours, $approved_timesheet->break);

			$from = Carbon::parse($from)->addDay()->format("Y-m-d");

		}
		
		$total_working_days += (round($regular_day_count, 2) + round($rest_day_count, 2) + round($extra_day_count, 2) + round($special_holiday_count, 2) + round($regular_holiday_count, 2)) + round($leave_with_pay, 2);

		$data['time_spent'] 			= Payroll::if_zero_time($total_time_spent);
		$data['regular_hours'] 			= Payroll::if_zero_time($total_regular_hours);
		$data['break_hours']			= Payroll::if_zero_time($total_break_hours);
		$data['late_overtime'] 			= Payroll::if_zero_time($total_late_overtime);
		$data['early_overtime'] 		= Payroll::if_zero_time($total_early_overtime);
		$data['late_hours'] 			= Payroll::if_zero_time($total_late_hours);
		$data['under_time'] 			= Payroll::if_zero_time($total_under_time);
		$data['rest_day_hours'] 		= Payroll::if_zero_time($total_rest_day_hours);
		$data['extra_day_hours'] 		= Payroll::if_zero_time($total_extra_day_hours);
		$data['total_hours'] 			= Payroll::if_zero_time($total_total_hours);
		$data['night_differential'] 	= Payroll::if_zero_time($total_night_differential);
		$data['special_holiday_hours'] 	= Payroll::if_zero_time($total_special_holiday_hours);
		$data['regular_holiday_hours'] 	= Payroll::if_zero_time($total_regular_holiday_hours);

		$data['regular_day_count'] 		= Payroll::if_zero($regular_day_count);
		$data['rest_day_count'] 		= Payroll::if_zero($rest_day_count);
		$data['extra_day_count'] 		= Payroll::if_zero($extra_day_count);
		$data['special_holiday_count'] 	= Payroll::if_zero($special_holiday_count);
		$data['regular_holiday_count'] 	= Payroll::if_zero($regular_holiday_count);
		$data['total_working_days'] 	= Payroll::if_zero($total_working_days);
		$data['absent']					= Payroll::if_zero($absent);
		$data['leave_wo_pay']			= Payroll::if_zero($leave_wo_pay);
		$data['leave_with_pay']			= Payroll::if_zero($leave_with_pay);
		
		return $data;
	}

	public function json_process_time()
	{
		$employee_id = Request::input("employee_id");
		
		/* COMPUTE TIME FOR EACH DATE */
		foreach(Request::input('date') as $key => $_time)
		{
			$_timesheet[$key] = new stdClass();
			$_timesheet[$key]->date = $key;

			$default_working_hours = Request::input("default_working_hours");
			Payroll::adjust_payroll_approved_in_and_out($employee_id, $key);
			$processed_timesheet[$key] = Payroll::process_time($employee_id, $key, true);
 		}

		return json_encode($processed_timesheet);
	}

	public function save_time_record()
	{
		$time_in 		= date('H:i', strtotime(Request::input('time_in')));
		$time_out 		= date('H:i', strtotime(Request::input('time_out')));
		$time_id 		= Request::input('time_id');
		$date 			= date('Y-m-d', strtotime(Request::input('date')));
		$employee_id 	= Request::input('employee_id');
		$break 			= Request::input('break_');
		$record 		= array();		

		$payroll_time_sheet = Tbl_payroll_time_sheet::where("payroll_time_date", $date)->where("payroll_employee_id", $employee_id)->first();

		if($payroll_time_sheet != null)
		{

			if($break != null)
			{
				// dd($break);
				$break = date('H:i', strtotime($break));
				$update['payroll_time_sheet_break'] = $break;
				$update['is_break_update'] = 1;
				Tbl_payroll_time_sheet::where("payroll_time_date", $date)->where("payroll_employee_id", $employee_id)->update($update);
			}

			$record['payroll_time_sheet_id'] = $payroll_time_sheet->payroll_time_sheet_id;
			$record['payroll_time_sheet_in'] = $time_in;
			$record['payroll_time_sheet_out'] = $time_out;

			if($time_id != '' || $time_id != 0 || $time_id != null)
			{
				$time_record = Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $time_id)->first();

				if($time_record != null)
				{
					Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $time_id)->update($record);
				}
			}

			else
			{
				$record['payroll_time_sheet_origin'] = 'Payroll Time Sheet';
				Tbl_payroll_time_sheet_record::insert($record);
			}
		}
		
		return 'success';
	}

	public function new_time_tr()
	{
		$date 			= date('Y-m-d', strtotime(Request::input('date')));
		$employee_id 	= Request::input('employee_id');

		$time 			= Tbl_payroll_time_sheet::where('payroll_time_date', $date)->where('payroll_employee_id', $employee_id)->first();

		$company 		= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->value('payroll_employee_company_id');

		$payroll_time_sheet_id = 0;

		if($time == null)
		{
			$date_insert['payroll_time_date'] 	= $date;
			$date_insert['payroll_employee_id'] = $employee_id;

			$payroll_time_sheet_id = Tbl_payroll_time_sheet::insertGetId($date_insert);
		}
		else
		{
			$payroll_time_sheet_id = $time->payroll_time_sheet_id;
		}

		$insert['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
		$insert['payroll_time_sheet_origin'] 	= 'Payroll Time Sheet';
		$insert['payroll_company_id']			= $company;

		$return['id'] 		= Tbl_payroll_time_sheet_record::insertGetId($insert);
		$return['company'] 	= Tbl_payroll_company::where('payroll_company_id',$company)->value('payroll_company_name');
		return collect($return)->toJson();
	}

	public function remove_time_record()
	{
		$id = Request::input("id");

		Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $id)->delete();

		return 'success';
	}

	public function json_process_time_single($date, $employee_id, $return_type = "json")
	{
		/* UPDATE TIME IN AND OUT */
		if(!empty(Request::input("time_in")))
		{
			foreach(Request::input("time_in") as $id => $time_in)
			{
				$update["payroll_time_sheet_approved_in"] = Carbon::parse($time_in)->format("H:i");
				Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $id)->update($update);
				$update = null;
			}
		}

		if(!empty(Request::input("time_out")))
		{
			foreach(Request::input("time_out") as $id => $time_out)
			{
				$update["payroll_time_sheet_approved_out"] = Carbon::parse($time_out)->format("H:i");
				Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $id)->update($update);
				$update = null;
			}
		}


		/* GET TIMESHEET */
		$_timesheet = new stdClass();

		$processed_timesheet = Payroll::process_time($employee_id, $date);
		return json_encode($processed_timesheet);
	}
	public function adjustment_form()
	{
		$data["page"] = "Adjustment Form";

		$payroll_time_sheet_id = Request::input("payroll_time_sheet_id");

		$data["timesheet_info"] = $timesheet_info = Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $payroll_time_sheet_id)->first();

		$employee_information = Tbl_payroll_employee_contract::selemployee($timesheet_info->payroll_employee_id)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();

		$_rest_day = Tbl_payroll_group_rest_day::where("payroll_group_id", $employee_information->payroll_group_id)->get();

		$data["rest_day"] = $rest_day = false;

		/* REST DAY TICK */
		// foreach($_rest_day as $rest_day)
		// {
		// 	if($rest_day->payroll_group_rest_day == Carbon::parse($timesheet_info->payroll_time_date)->format("l"))
		// 	{
		// 		$data["rest_day"] = $rest_day = true;
		// 	}
		// }

		// $schedule = Tbl_payroll_employee_schedule::getschedule($employee_information->payroll_employee_id, $timesheet_info->payroll_time_date)->first();

		// if($schedule == null)
		// {	
		// 	$schedule = Tbl_payroll_shift::getshift($employee_information->payroll_group_id, date('D', strtotime($timesheet_info->payroll_time_date)))->first();
		// }

		$schedule = Payroll::getshift_emp($employee_information->payroll_employee_id, $timesheet_info->payroll_time_date, $employee_information->payroll_group_id);

		// dd($schedule);
		
		if($schedule->rest_day == 1)
		{
			$data["rest_day"] = $rest_day = true;
		}
		$work_end_24 = '00:00:00';

		if(isset($schedule->work_end))
		{
			$work_end_24 = $schedule->work_end;
		}	
		

		if(Payroll::time_float($schedule->work_end) <= 12)
		{
			$work_end_24 = Payroll::sum_time($schedule->work_end, '24:00');
		}


		/* SETTINGS FOR EMPLOYEE PAYROLL GROUP */
		$data["default_time_in"] = $default_time_in = Carbon::parse($schedule->work_start)->format("h:i A");

		$data["default_time_out"] = $default_time_out = Carbon::parse($schedule->work_end)->format("h:i A");
		/* TIMESHEET  */
		$_time_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $timesheet_info->payroll_time_sheet_id)->get();
	
		foreach($_time_record as $key => $time_record)
		{
			$data["_time_record"][$key] = $time_record;
			$data["_time_record"][$key]->time_in = Carbon::parse($time_record->payroll_time_sheet_in)->format("h:i A"); 
			$data["_time_record"][$key]->time_out = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A"); 

			/* CHECK TIME IN */
			if(c_time_to_int($data["_time_record"][$key]->time_in) < c_time_to_int($default_time_in))
			{
				if(c_time_to_int($work_end_24) < c_time_to_int($default_time_in))
				{
					$max_time_in = $data["_time_record"][$key]->time_out;
				}
				else
				{
					$max_time_in = $default_time_in;
				}

				$min_time_in =  $time_record->payroll_time_sheet_in;

				$data["_time_record"][$key]->time_in_enabled = true;
				$data["_time_record"][$key]->time_in_max = Carbon::parse($max_time_in)->format("h:i A");
				$data["_time_record"][$key]->time_in_min = Carbon::parse($min_time_in)->format("h:i A");
			}
			else //DISABLE TIME-IN
			{
				if($rest_day == true)
				{
					$data["_time_record"][$key]->time_in_enabled = true;
					$data["_time_record"][$key]->time_in_max = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A");
					$data["_time_record"][$key]->time_in_min = Carbon::parse($time_record->payroll_time_sheet_in)->format("h:i A");
				}
				else
				{
					$data["_time_record"][$key]->time_in_enabled = false;
					$data["_time_record"][$key]->time_in_max = Carbon::parse($time_record->payroll_time_sheet_in)->format("h:i A");
					$data["_time_record"][$key]->time_in_min = Carbon::parse($time_record->payroll_time_sheet_in)->format("h:i A");
				}

			}

			/* CHECK TIME OUT */
			if(c_time_to_int($data["_time_record"][$key]->time_out) > c_time_to_int($default_time_out))
			{
				$data["_time_record"][$key]->time_out_enabled = true;
				
				if(c_time_to_int($data["_time_record"][$key]->time_in) > c_time_to_int($default_time_out))
				{
					$min_time_out = $data["_time_record"][$key]->time_in;
				}
				else
				{
					$min_time_out = $default_time_out;
				}

				$data["_time_record"][$key]->time_out_max = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A");
				$data["_time_record"][$key]->time_out_min = Carbon::parse($min_time_out)->format("h:i A");

			}
			else //DISABLED TIME-OUT
			{
				if($rest_day == true)
				{
					$data["_time_record"][$key]->time_out_enabled = true;
					$data["_time_record"][$key]->time_out_max = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A");
					$data["_time_record"][$key]->time_out_min = Carbon::parse($time_record->payroll_time_sheet_in)->format("h:i A");
				}
				else
				{
					$data["_time_record"][$key]->time_out_enabled = false;
					$data["_time_record"][$key]->time_out_max = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A");
					$data["_time_record"][$key]->time_out_min = Carbon::parse($time_record->payroll_time_sheet_out)->format("h:i A");
				}
			}

		}

		return view('member.payroll.employee_timesheet_adjustment', $data);
	}
	public function adjustment_form_approve()
	{
		$update["payroll_time_sheet_approved"] = Request::input('approve');
		$date = Request::input("date");
		$employee_id = Request::input("employee_id");
		Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($date)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->update($update);
		echo json_encode("success");
	}

	/* show availabel holidays */
	public function show_holiday($payroll_company_id, $date)
	{
		$data['_holiday'] = Tbl_payroll_holiday_company::getholiday($payroll_company_id, $date)->get();
		return view('member.payroll.modal.modal_show_holiday', $data);
	}


	/* generate timesheet summary */
	public function show_summary($summary, $period_id)
	{
		$data['header'] = Self::ru($summary).'Summary';

		$period = Tbl_payroll_period_company::sel($period_id)->first();	
	
		$_employee = Tbl_payroll_employee_contract::employeefilter($period->payroll_company_id, 0, 0, $period->payroll_period_end, $this->user_info->shop_id)
							->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
							->where('tbl_payroll_group.payroll_group_period', $period->payroll_period_category)
							->get();

		$start = $period->payroll_period_start;

		$end = $period->payroll_period_end;

		$data['period'] = date('F d, Y', strtotime($start)).' to '.date('F d, Y', strtotime($end));
		$data['_employee'] = array();
		
		foreach($_employee as $employee)
		{
			$temp_employee['employee'] = $employee;
			$temp_employee['time'] = array();

			$start 		= $period->payroll_period_start;
			$total_time = '00:00';

			while($start <= $end)
			{
				$date = Carbon::parse($start)->format("Y-m-d"); 

			
				$check_approved = Tbl_payroll_time_keeping_approved::where("employee_id", $employee->payroll_employee_id)->where("payroll_period_company_id", $period_id)->first();
				$time = '00:00';

				if ($check_approved!=null)
				{
					$whole_data = unserialize($check_approved->cutoff_input);
					$time_arr = $whole_data[$date];

					if($summary == 'late'){
						$time = $time_arr->time_output['late'];
					}

					if($summary == 'under_time'){
						$time = $time_arr->time_output['undertime'];
					}

					if($summary == 'over_time'){
						$time = $time_arr->time_output['overtime'];
					}
				}

				$total_time = Payroll::sum_time($total_time, $time);
				array_push($temp_employee['time'], $time);
				$start = Carbon::parse($start)->addDay()->format("Y-m-d");
			}

			$temp_employee['total_time'] = $total_time;
			array_push($data['_employee'], $temp_employee);
		}

		$start = $period->payroll_period_start;

		$data['_date'] = array();
		while($start <= $end)
		{
			$date = Carbon::parse($start)->format("Y-m-d"); 
			array_push($data['_date'], $start);
			$start = Carbon::parse($start)->addDay()->format("Y-m-d");
		}

		return view('member.payroll.modal.modal_time_summary', $data);
	}

	public function ru($str = '')
	{
		$nstr = '';
		$_split = explode('_', $str);
		// dd($_split);
		foreach($_split as $split)
		{
			$nstr .= ucfirst($split).' ';
		}
		return $nstr;
	}


	/* send reminder timesheet */
	public function send_reminder()
	{
		$payroll_remarks = Request::input('payroll_remarks');
		$file_name = Request::input('file_name');

		$insert['shop_id'] = $this->user_info->shop_id;
		$insert['user_id'] = $this->user_info->user_id;
		$insert['payroll_period_company_id'] = Request::input("payroll_period_company_id");
		$insert['payroll_remarks_date'] = date('Y-m-d h:i:s');

		if(Request::has('payroll_remarks') && Request::input('payroll_remarks') != '')
		{
			$insert['payroll_remarks'] 	= Request::input('payroll_remarks');
			$insert['payroll_type'] 	= 'text';

			Payroll::insert_remarks($insert);
		}

		if(Request::hasFile('file_name'))
		{

		}
	}


	/* time sheet comment start */

	public function modal_timesheet_comment($id)
	{
		$data['time_sheet'] = Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $id)->first();
		$data['date_record'] = Tbl_payroll_time_sheet::where('payroll_time_sheet_id', $data['time_sheet']->payroll_time_sheet_id)->first();
		return view('member.payroll.modal.modal_timesheet_comment', $data);
	}

	public function modal_choose_company($id)
	{
		$data['_company'] = Payroll::company_heirarchy($this->user_info->shop_id);
		$data['record']	  = Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $id)->first();
		return view('member.payroll.modal.modal_choose_company', $data);
	}

	public function choose_company_save()
	{
		$payroll_time_sheet_record_id = Request::input('payroll_time_sheet_record_id');
		$update['payroll_company_id'] = Request::input('payroll_company_id');
		Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $payroll_time_sheet_record_id)->update($update);

		$company = Tbl_payroll_company::where('payroll_company_id',Request::input('payroll_company_id'))->value('payroll_company_name');

		$return['status'] 			= 'success';
		$return['function_name'] 	= 'get_company';
		$return['id']	 			= $payroll_time_sheet_record_id;
		$return['company']			= $company;

		return collect($return)->toJson();
	}

	public function time_sheet_comment_save()
	{
		$payroll_time = Request::input('payroll_time');
		$update['time_sheet_record_remarks'] = Request::input('time_sheet_record_remarks');
		Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $payroll_time)->update($update);

		$return['status'] = 'success';
		$return['function_name'] = '';
		return collect($return)->toJson();
	}



	/* time sheet comment end */
}