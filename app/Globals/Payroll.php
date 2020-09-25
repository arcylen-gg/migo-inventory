<?php
namespace App\Globals;
use App\Models\Tbl_payroll_tax_default;
use App\Models\Tbl_payroll_tax_reference;
use App\Models\Tbl_payroll_copy_log_requirements;
use App\Models\Tbl_payroll_tax_period;
use App\Models\Tbl_payroll_sss;
use App\Models\Tbl_payroll_sss_default;
use App\Models\Tbl_payroll_philhealth_default;
use App\Models\Tbl_payroll_philhealth;
use App\Models\Tbl_payroll_pagibig_default;
use App\Models\Tbl_payroll_pagibig;
use App\Models\Tbl_payroll_employee_search;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_deduction;
use App\Models\Tbl_payroll_deduction_employee;
use App\Models\Tbl_payroll_deduction_payment;
use App\Models\Tbl_payroll_group_rest_day;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_tax_period_default;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_holiday;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_overtime_rate;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_employee_allowance;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_record;
use App\Models\Tbl_payroll_adjustment;
use App\Models\Tbl_payroll_allowance_record;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_payroll_entity;
use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_leave_temp;
use App\Models\Tbl_payroll_leave_employee;
use App\Models\Tbl_payroll_paper_sizes;
use App\Models\Tbl_payroll_13_month_compute;
use App\Models\Tbl_payroll_13_month_virtual;
use App\Models\Tbl_payroll_process_leave;
use App\Models\Tbl_payroll_remarks;
use App\Models\Tbl_payroll_shift;
use App\Models\Tbl_payroll_shift_template;
use App\Models\Tbl_payroll_employee_schedule;

use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;

use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;

use Carbon\Carbon;
use stdClass;
use DB;

class Payroll
{
	public static function tax_reference($shop_id = 0)
	{
		$count = Tbl_payroll_copy_log_requirements::where('shop_id',$shop_id)->where('requirements_category','tax')->count();
		if($count == 0)
		{
			$_tax = Tbl_payroll_tax_default::get();

			$insert = array();

			foreach($_tax as $key => $tax)
			{

				$payroll_tax_status_id = Tbl_payroll_tax_period::getperiod($shop_id,$tax->payroll_tax_status_id)->value('payroll_tax_period_id');

				$insert[$key]['shop_id'] 				= $shop_id;
				$insert[$key]['payroll_tax_status_id'] 	= $payroll_tax_status_id;
				$insert[$key]['tax_category'] 			= $tax->tax_category;
				$insert[$key]['tax_first_range'] 		= $tax->tax_first_range;
				$insert[$key]['tax_second_range'] 		= $tax->tax_second_range;
				$insert[$key]['tax_third_range'] 		= $tax->tax_third_range;
				$insert[$key]['tax_fourth_range'] 		= $tax->tax_fourth_range;
				$insert[$key]['tax_fifth_range'] 		= $tax->tax_fifth_range;
				$insert[$key]['taxt_sixth_range'] 		= $tax->taxt_sixth_range;
				$insert[$key]['tax_seventh_range'] 		= $tax->tax_seventh_range;
			}

			Tbl_payroll_tax_reference::insert($insert);

			$insertlog['shop_id'] 					= $shop_id;
			$insertlog['requirements_category']	 	= 'tax';
			$insertlog['requirements_copy_date']	= Carbon::now();
			Tbl_payroll_copy_log_requirements::insert($insertlog);
		}
	}

	public static function tax_break($shop_id = 0)
	{
		$data = array();
		$_period = Tbl_payroll_tax_period::check($shop_id)->get();
		$count = 1;
		foreach($_period as $key => $period)
		{
			$data[$key]['category'] 				= $period->payroll_tax_period;
			$data[$key]['payroll_tax_period_id'] 	= $period->payroll_tax_period_id;
			$data[$key]['data']						= Tbl_payroll_tax_reference::where('shop_id', $shop_id)->where('payroll_tax_status_id', $period->payroll_tax_period_id)->get();
			$status = '';
			if($count == 1)
			{
				$status = 'active';
			}

			$data[$key]['status'] = $status;
			$count++;
		}

		return $data;
	}

	public static function generate_tax_period($shop_id = 0)
	{
		$_default = Tbl_payroll_tax_period_default::get();
		$insert = array();
		foreach($_default as $default)
		{
			$count = Tbl_payroll_tax_period::where('shop_id', $shop_id)->where('payroll_tax_period',$default->payroll_tax_period)->count();

			if($count == 0)
			{
				$temp['payroll_tax_period'] = $default->payroll_tax_period;
				$temp['shop_id']			= $shop_id;
				array_push($insert, $temp);
			}
		}
		if(!empty($insert))
		{
			Tbl_payroll_tax_period::insert($insert);
		}
	}

	public static function generate_sss($shop_id = 0)
	{
		$count = Tbl_payroll_copy_log_requirements::where('shop_id',$shop_id)->where('requirements_category','sss')->count();
		if($count == 0)
		{
			$_sss = Tbl_payroll_sss_default::get();
			$insert = array();
			foreach($_sss as $key => $sss)
			{
				$insert[$key]['shop_id'] 			= $shop_id;
				$insert[$key]['payroll_sss_min'] 	= $sss->payroll_sss_min;
				$insert[$key]['payroll_sss_max'] 	= $sss->payroll_sss_max;
				$insert[$key]['payroll_sss_monthly_salary'] = $sss->payroll_sss_monthly_salary;
				$insert[$key]['payroll_sss_er'] 	= $sss->payroll_sss_er;
				$insert[$key]['payroll_sss_ee'] 	= $sss->payroll_sss_ee;
				$insert[$key]['payroll_sss_total'] 	= $sss->payroll_sss_total;
				$insert[$key]['payroll_sss_eec'] 	= $sss->payroll_sss_eec;
			}
			Tbl_payroll_sss::insert($insert);
			$insertlog['shop_id'] 					= $shop_id;
			$insertlog['requirements_category']	 	= 'sss';
			$insertlog['requirements_copy_date']	= Carbon::now();
			Tbl_payroll_copy_log_requirements::insert($insertlog);
		}
	}

	public static function generate_philhealth($shop_id = 0)
	{
		$count = Tbl_payroll_copy_log_requirements::where('shop_id',$shop_id)->where('requirements_category','philhealth')->count();

		if($count == 0)
		{
			$_philhealth = Tbl_payroll_philhealth_default::get();
			$insert = array();
			foreach($_philhealth as $key => $philhealth)
			{
				$insert[$key]['shop_id']					= $shop_id;
				$insert[$key]['payroll_philhealth_min'] 	= $philhealth->payroll_philhealth_min;
				$insert[$key]['payroll_philhealth_max'] 	= $philhealth->payroll_philhealth_max;
				$insert[$key]['payroll_philhealth_base'] 	= $philhealth->payroll_philhealth_base;
				$insert[$key]['payroll_philhealth_premium'] = $philhealth->payroll_philhealth_premium;
				$insert[$key]['payroll_philhealth_ee_share'] = $philhealth->payroll_philhealth_ee_share;
				$insert[$key]['payroll_philhealth_er_share'] = $philhealth->payroll_philhealth_er_share;
			}

			Tbl_payroll_philhealth::insert($insert);

			$insertlog['shop_id'] 					= $shop_id;
			$insertlog['requirements_category']	 	= 'philhealth';
			$insertlog['requirements_copy_date']	= Carbon::now();
			Tbl_payroll_copy_log_requirements::insert($insertlog);
		}
	}

	public static function generate_pagibig($shop_id = 0)
	{

		$count = Tbl_payroll_copy_log_requirements::where('shop_id',$shop_id)->where('requirements_category','pagibig')->count();
		if($count == 0)
		{
			$pagibig = Tbl_payroll_pagibig_default::value('payroll_pagibig_percent');
			$insert['payroll_pagibig_percent']  = $pagibig;
			$insert['shop_id']					= $shop_id;
			Tbl_payroll_pagibig::insert($insert);

			$insertlog['shop_id'] 					= $shop_id;
			$insertlog['requirements_category']	 	= 'pagibig';
			$insertlog['requirements_copy_date']	= Carbon::now();
			Tbl_payroll_copy_log_requirements::insert($insertlog);
		}
	}


	public static function generate_paper_size($shop_id = 0)
	{
		// Tbl_payroll_paper_sizes

		// $count = Tbl_payroll_paper_sizes::where('shop_id')->count();
		$count = Tbl_payroll_copy_log_requirements::where('shop_id',$shop_id)->where('requirements_category','paper size')->count();

		if($count == 0)
		{
			$insert[0]['shop_id'] 			= $shop_id;
			$insert[0]['paper_size_name']	= 'A0';
			$insert[0]['paper_size_width']	= '84.1';
			$insert[0]['paper_size_height']	= '118.9';

			$insert[2]['shop_id'] 			= $shop_id;
			$insert[2]['paper_size_name']	= 'A1';
			$insert[2]['paper_size_width']	= '59.4';
			$insert[2]['paper_size_height']	= '84.1';

			$insert[3]['shop_id'] 			= $shop_id;
			$insert[3]['paper_size_name']	= 'A2';
			$insert[3]['paper_size_width']	= '42';
			$insert[3]['paper_size_height']	= '59.4';

			$insert[4]['shop_id'] 			= $shop_id;
			$insert[4]['paper_size_name']	= 'A3';
			$insert[4]['paper_size_width']	= '29.7';
			$insert[4]['paper_size_height']	= '42';

			$insert[5]['shop_id'] 			= $shop_id;
			$insert[5]['paper_size_name']	= 'A4';
			$insert[5]['paper_size_width']	= '21';
			$insert[5]['paper_size_height']	= '29.7';

			$insert[6]['shop_id'] 			= $shop_id;
			$insert[6]['paper_size_name']	= 'Legal';
			$insert[6]['paper_size_width']	= '21.6';
			$insert[6]['paper_size_height']	= '33.6';

			$insert[7]['shop_id'] 			= $shop_id;
			$insert[7]['paper_size_name']	= 'Letter';
			$insert[7]['paper_size_width']	= '21.6';
			$insert[7]['paper_size_height']	= '27.9';

			Tbl_payroll_paper_sizes::insert($insert);

			$insertlog['shop_id'] 					= $shop_id;
			$insertlog['requirements_category']	 	= 'paper size';
			$insertlog['requirements_copy_date']	= Carbon::now();
			Tbl_payroll_copy_log_requirements::insert($insertlog);
		}
	}

	/* GET HEIRARCHICAL COMPANY */
	public static function company_heirarchy($shop_id = 0)
	{
		$data = array();
		$_parent = Tbl_payroll_company::selcompany($shop_id)->where('payroll_parent_company_id',0)->orderBy('payroll_company_name')->get();

		foreach($_parent as $parent)
		{
			$temp['company'] = $parent;
			$temp['branch'] = Tbl_payroll_company::selcompany($shop_id)->where('payroll_parent_company_id', $parent->payroll_company_id)->orderBy('payroll_company_name')->get();
			array_push($data, $temp);
		}
		
		return $data;

	}



	/* TABLE FOR EMPLOYEE SEARCH INSERT OR UPDATE */
	public static function generate_emplyoee_search($employee_id = 0)
	{

		if($employee_id == 0)
		{
			// $_emp 	= Tbl_payroll_employee_basic::get();
			// $insert = array();
			// foreach($_emp as $key => $emp)
			// {
			// 	$insert[$key]['payroll_search_employee_id'] = $emp->payroll_employee_id;
			// 	$insert[$key]['body'] = $emp->payroll_employee_title_name.' '.$emp->payroll_employee_first_name.' '.$emp->payroll_employee_middle_name.' '.$emp->payroll_employee_last_name.' '.$emp->payroll_employee_suffix_name.' '.$emp->payroll_employee_display_name.' '.$emp->payroll_employee_email;

			// }
			// Tbl_payroll_employee_search::insert($insert);
		}
		else
		{
			$count = Tbl_payroll_employee_search::where('payroll_search_employee_id', $employee_id)->count();
			$employee = Tbl_payroll_employee_basic::where('payroll_search_employee_id', $employee_id)->first();

			$search['body'] = $employee->payroll_employee_title_name.' '.$employee->payroll_employee_first_name.' '.$employee->payroll_employee_middle_name.' '.$employee->payroll_employee_last_name.' '.$employee->payroll_employee_suffix_name.' '.$employee->payroll_employee_display_name.' '.$employee->payroll_employee_email;
			if($count == 0)
			{
				$search['payroll_search_employee_id'] = $employee_id;
				Tbl_payroll_employee_search::insert($search);
			}
			else
			{
				Tbl_payroll_employee_search::where('payroll_search_employee_id',$employee_id)->update($search);
			}
		}
		

	}
	
	/* GET EMPLOYEE DEDUCTION BALANCE */
	public static function getbalance($shop_id = 0, $deduction_id = 0)
	{
		$data = array();

		$total_amount = Tbl_payroll_deduction::where('payroll_deduction_id',$deduction_id)->value('payroll_deduction_amount');


		$_deduction = Tbl_payroll_deduction_employee::selbyemployee($deduction_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();

		$data['active'] = array();
		$data['zero']	= array();
		$data['cancel']	= array();
		foreach($_deduction as $key => $deduction)
		{
			$payment = Tbl_payroll_deduction_payment::selbyemployee($deduction_id, $deduction->payroll_employee_id)->sum('payroll_payment_amount');

			$balance = $total_amount - $payment;
			$index = 'active';
			if($balance <= 0)
			{
				$index = 'zero';
			}
			$data[$index][$key]['deduction'] 	= $deduction;
			$data[$index][$key]['balance'] 		= $balance;
		}
		
		$_canceled = Tbl_payroll_deduction_employee::selbyemployee($deduction_id,1)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();
		foreach($_canceled as $key => $cancel)
		{
			$balance = $total_amount - Tbl_payroll_deduction_payment::selbyemployee($cancel->payroll_employee_id, $deduction_id)->sum('payroll_payment_amount');

			$data['cancel'][$key]['deduction'] 	= $cancel;
			$data['cancel'][$key]['balance'] 		= $balance;
		}
		
		return $data;
	}


	public static function getbalancev2($shop_id = 0, $deduction_id = 0)
	{
		$data = array();

		$total_amount = Tbl_payroll_deduction_v2::where('payroll_deduction_id',$deduction_id)->value('payroll_deduction_amount');


		$_deduction = Tbl_payroll_deduction_employee_v2::selbyemployee($deduction_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();

		$data['active'] = array();
		$data['zero']	= array();
		$data['cancel']	= array();
		foreach($_deduction as $key => $deduction)
		{
			$payment = Tbl_payroll_deduction_payment_v2::selbyemployee($deduction_id, $deduction->payroll_employee_id)->sum('payroll_payment_amount');

			$balance = $total_amount - $payment;
			$index = 'active';
			if($balance <= 0)
			{
				$index = 'zero';
			}
			$data[$index][$key]['deduction'] 	= $deduction;
			$data[$index][$key]['balance'] 		= $balance;
		}
		
		$_canceled = Tbl_payroll_deduction_employee_v2::selbyemployee($deduction_id,1)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();
		
		foreach($_canceled as $key => $cancel)
		{
			$balance = $total_amount - Tbl_payroll_deduction_payment_v2::selbyemployee($cancel->payroll_employee_id, $deduction_id)->sum('payroll_payment_amount');

			$data['cancel'][$key]['deduction'] 	= $cancel;
			$data['cancel'][$key]['balance'] 		= $balance;
		}
		
		return $data;
	}


	/* RETURN IF INPUT IS CHECKED [FROm REST DAY AND EXTRA DAY ONLY (PAYROLL GROUPD)] */
	public static function restday_checked($payroll_group_id = 0, $origin = 'payroll_group')
	{
		$data = array();
		$_day = ['Sun', 'Mon', 'Tue', 'Wed','Thu','Fri','Sat'];
		foreach($_day as $day)
		{
			$temp['day'] = $day;
			$rest_checked = '';
			// $rest_count = Tbl_payroll_group_rest_day::selcheck($payroll_group_id, $day)->count();
			// if($rest_count >= 1)
			// {
			// 	$rest_checked = 'checked';
			// }
			$temp['rest_day_checked'] = $rest_checked;
			$temp['extra_day'] = $day;
			$extra_checked = '';
			// $extra_count = Tbl_payroll_group_rest_day::selcheck($payroll_group_id, $day,'extra day')->count();
			// if($extra_count >= 1)
			// {
			// 	$extra_checked = 'checked';
			// }
			$temp['extra_day_checked'] 	= $extra_checked;
			$temp['target_hours'] 		= 0;
			$temp['work_start'] 		= '00:00:00';
			$temp['work_end'] 			= '00:00:00';
			$temp['break_start'] 		= '00:00:00';
			$temp['break_end'] 			= '00:00:00';
			$temp['flexi'] 				= 0;
			$temp['rest_day'] 			= 0;
			$temp['extra_day'] 			= 0;
			$temp['night_shift']		= 0;

			$shift = null;

			if($origin == 'payroll_group')
			{
				$shift = Tbl_payroll_shift::getshift($payroll_group_id, $day)->first();
			}

			if($origin == 'shift_template')
			{
				$shift = Tbl_payroll_shift_template::getshift($payroll_group_id, $day)->first();
			}
			
			if($shift != null)
			{
				$temp['target_hours'] 		= $shift->target_hours;
				$temp['work_start'] 		= $shift->work_start;
				$temp['work_end'] 			= $shift->work_end;
				$temp['break_start'] 		= $shift->break_start;
				$temp['break_end'] 			= $shift->break_end;
				$temp['flexi'] 				= $shift->flexi;
				$temp['rest_day'] 			= $shift->rest_day;
				$temp['extra_day'] 			= $shift->extra_day;
				$temp['night_shift'] 		= $shift->night_shift;
			}

			array_push($data, $temp);
		}
		return $data;
	}


	public static function adjust_payroll_approved_in_and_out($employee_id, $date)
	{
		/* GET INITIAL INFORMATION AND DATABASE */
		$employee_information = Tbl_payroll_employee_contract::selemployee($employee_id, $date)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();
		$time_sheet_info = Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($date)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->first();
		$_rest_day = Tbl_payroll_group_rest_day::where("payroll_group_id", $employee_information->payroll_group_id)->get();

		$schedule = Payroll::getshift_emp($employee_id, $date, $employee_information->payroll_group_id);

		// dd($schedule);

		// if($time_sheet_info == null)
		// {
		// 	dd($date);
		// }
		if($time_sheet_info != null)
		{
			if($time_sheet_info->payroll_time_sheet_approved == 0) //ONLY UPDATE THOSE WHO ARE NOT APPROVED
			{
				$_time_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $time_sheet_info->payroll_time_sheet_id)->get();

				// dd($_time_record);

				foreach($_time_record as $time_record)
				{
					$payroll_time_sheet_approved_in = $time_record->payroll_time_sheet_in;
					$payroll_time_sheet_approved_out = $time_record->payroll_time_sheet_out;
					$payroll_time_sheet_in = false;
					$payroll_time_sheet_out = false;


					/* IF TIME IN HAPPENS LATER AFTER TIME OUT - LOL - SET TO ZERO */
					// if(c_time_to_int($time_record->payroll_time_sheet_in) > c_time_to_int($time_record->payroll_time_sheet_out))
					// {
					// 	$payroll_time_sheet_approved_in = "00:00";
					// 	$payroll_time_sheet_approved_out = "00:00";
					// 	$payroll_time_sheet_in = "00:00";
					// 	$payroll_time_sheet_out = "00:00";
					// }
					// dd($employee_information->payroll_group_is_flexi_time);
					if(isset($schedule->flexi))
					{
						if($schedule->flexi == 0)
						{
							/* OVERTIME RULE */
							// if(c_time_to_int($time_record->payroll_time_sheet_in) < c_time_to_int($employee_information->payroll_group_start))

							// dd($schedule->work_start);

							if(c_time_to_int($time_record->payroll_time_sheet_in) < c_time_to_int($schedule->work_start))
							{
								// $payroll_time_sheet_approved_in = $employee_information->payroll_group_start;
								$payroll_time_sheet_approved_in = $schedule->work_start;
							}
							else
							{
								$payroll_time_sheet_approved_in = $time_record->payroll_time_sheet_in;
							}

							// if(c_time_to_int($time_record->payroll_time_sheet_out) > c_time_to_int($employee_information->payroll_group_end))
							if(c_time_to_int($time_record->payroll_time_sheet_out) > c_time_to_int($schedule->work_end))
							{
								$payroll_time_sheet_approved_out = $schedule->work_end;
							}
							else
							{
								$payroll_time_sheet_approved_out = $time_record->payroll_time_sheet_out;
							}

							// /* IF ONE OF THE TIME IS ZERO */
							if($time_record->payroll_time_sheet_in == "00:00:00" || $time_record->payroll_time_sheet_out == "00:00:00")
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}

							/* IF TIME IN IS LATER THAN DEFAULT TIME OUT */
							if($time_record->payroll_time_sheet_in > $schedule->work_end)
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}

							/* IF TIME OUT IS EARLIER THAN DEFAULT TIME IN */
							if($time_record->payroll_time_sheet_out < $schedule->work_start)
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}
						}
						else
						{
							/* OVERTIME RULE */
							
							$payroll_time_sheet_approved_in = $time_record->payroll_time_sheet_in;

							$target = $schedule->target_hours;

							$render = Payroll::time_float($time_record->payroll_time_sheet_out) - Payroll::time_float($time_record->payroll_time_sheet_in);


							if($render > $target)
							{
								$new_time_out = Payroll::time_float($time_record->payroll_time_sheet_in) + $target;
								$payroll_time_sheet_approved_out = Payroll::float_time($new_time_out);
							}
							else
							{
								$payroll_time_sheet_approved_out = $time_record->payroll_time_sheet_out;
							}

							// /* IF ONE OF THE TIME IS ZERO */
							if($time_record->payroll_time_sheet_in == "00:00:00" || $time_record->payroll_time_sheet_out == "00:00:00")
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}

							/* IF TIME IN IS LATER THAN DEFAULT TIME OUT */
							if($time_record->payroll_time_sheet_in > $schedule->work_end)
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}

							/* IF TIME OUT IS EARLIER THAN DEFAULT TIME IN */
							if($time_record->payroll_time_sheet_out < $schedule->work_start)
							{
								$payroll_time_sheet_approved_in = "00:00";
								$payroll_time_sheet_approved_out = "00:00";
							}
						}
					}
					

					$update["payroll_time_sheet_approved_in"] = Carbon::parse($payroll_time_sheet_approved_in)->format("H:i");
					$update["payroll_time_sheet_approved_out"] = Carbon::parse($payroll_time_sheet_approved_out)->format("H:i");

					if($payroll_time_sheet_in || $payroll_time_sheet_out)
					{
						$update["payroll_time_sheet_in"] = Carbon::parse($payroll_time_sheet_in)->format("H:i");
						$update["payroll_time_sheet_out"] = Carbon::parse($payroll_time_sheet_out)->format("H:i");
					}

					Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $time_record->payroll_time_sheet_record_id)->update($update);
				}
			}
		}
		
	}



	public static function getshift_emp($payroll_employee_id = 0, $date = '0000-00-00', $payroll_group_id = 0)
	{
		$schedule = Tbl_payroll_employee_schedule::getschedule($payroll_employee_id, $date)->first();

		if($schedule == null)
		{	
			$schedule = Tbl_payroll_shift::getshift($payroll_group_id, date('D', strtotime($date)))->first();
		}

		return $schedule;
	}


	/* Returns normal hours rendered and overtime (Guillermo Tabligan) */
	public static function process_time($employee_id, $date)
	{

		$data["time_sheet_info"] = array();
		$time_sheet_info = Tbl_payroll_time_sheet::where("payroll_time_date", Carbon::parse($date)->format("Y-m-d"))->where("payroll_employee_id", $employee_id)->first();

		// dd($time_sheet_info);

		if(!empty($time_sheet_info))
		{
			$data["time_sheet_info"] = $time_sheet_info;
		}

		// else  {
		// 	$data["time_sheet_info"] = new stdClass();
		// 	$data["time_sheet_info"]->payroll_time_date;
		// }

		$data["employee_information"] = $employee_information = Tbl_payroll_employee_contract::selemployee($employee_id, $date)->leftJoin("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=","tbl_payroll_employee_contract.payroll_group_id")->first();

		/* GET HOLIDAY PER COMPANY */
		$payroll_company_id = Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->value('payroll_employee_company_id');

		$data['holiday']	= Tbl_payroll_holiday_company::getholiday($payroll_company_id, $date)->select('tbl_payroll_holiday.*')->first();


		$data["time_rule"] = "regulartime";
		/* EMPLOYEE COMPUTATION SETTINGS */

		if(!isset($employee_information->payroll_employee_id))
		{
			dd($date);
		}

		$schedule = Payroll::getshift_emp($employee_information->payroll_employee_id, $date, $employee_information->payroll_group_id);

		$data["default_time_in"] = '00:00:00';
		$data["default_time_out"] = '00:00:00';
		$data["default_working_hours"] = 0;

		if($schedule != null)
		{
			$data["default_time_in"] = $schedule->work_start;
			$data["default_time_out"] = $schedule->work_end;
			$data["default_working_hours"] = $schedule->target_hours;
		}

		

		$payroll_time_sheet_approved = 0;
		if(isset($time_sheet_info->payroll_time_sheet_approved))
		{
			$payroll_time_sheet_approved = $time_sheet_info->payroll_time_sheet_approved;
		}

		$payroll_time_sheet_id = 0;
		if(isset($time_sheet_info->payroll_time_sheet_id))
		{
			$payroll_time_sheet_id = $time_sheet_info->payroll_time_sheet_id;
		}

		$data["_time_record"] = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $payroll_time_sheet_id)->get();
		
		// dd($data["_time_record"]);

		$return = new stdClass();
	

		$data["compute_approved"] = 0;
		$return->pending_timesheet = Payroll::process_time_regulartime($data, $date, $data["time_rule"]);

		$data["compute_approved"] = 1;
		$return->approved_timesheet = Payroll::process_time_regulartime($data, $date, $data["time_rule"]);

		$payroll_time_date = '0000-00-00';
		if(isset($time_sheet_info->payroll_time_date))
		{
			$payroll_time_date = $time_sheet_info->payroll_time_date;
		}

		$return->payroll_time_sheet_id = $payroll_time_sheet_id;
		$return->date = $payroll_time_date;
		$return->payroll_time_sheet_approved = $payroll_time_sheet_approved;

		return $return;
	}
	
	public static function process_time_flexitime($time_rule, $default_time_in, $default_time_out, $_time_record, $break, $default_working_hours)
	{
		$data["break"] = $break = c_time_to_int($break);
		$data["default_working_hours"] = $default_working_hours = c_time_to_int($default_working_hours);

		$total_time_spent = 0;
		$total_early_overtime = 0;
		$total_late_overtime = 0;
		$total_regular_hours = 0;

		foreach($_time_record as $time_record)
		{
			$time_in = c_time_to_int($time_record->time_in);
			$time_out = c_time_to_int($time_record->time_out);

			if($time_out > $time_in)
			{
				$time_spent = ($time_out - $time_in);
			}
			else
			{
				$time_spent = 0;
			}
			
			$total_time_spent += $time_spent;
		}

		if($total_time_spent <= 0)
		{
			$total_time_spent = 0;
		}
		else
		{
			if($break > $total_time_spent)
			{
				$total_time_spent = 0;
			}
			else
			{
				$total_time_spent = $total_time_spent - $break;
			}
			
		}


		/* COMPUTE OVERTIME */
		$total_time_spent = c_time_to_int(date("H:i", $total_time_spent));
		$default_working_hours = c_time_to_int(date("H:i", $default_working_hours));
		if($total_time_spent > $default_working_hours)
		{
			$total_late_overtime = $total_time_spent - $default_working_hours;
			$total_regular_hours = $default_working_hours;
		}
		else
		{
			$total_regular_hours = $total_time_spent;
		}

		$return->time_spent = convert_seconds_to_hours_minutes("H:i", $total_time_spent);
		$return->regular_hours = convert_seconds_to_hours_minutes("H:i", $total_regular_hours);
		$return->late_overtime = convert_seconds_to_hours_minutes("H:i", $total_late_overtime);
		$return->early_overtime = convert_seconds_to_hours_minutes("H:i", $total_early_overtime);
		
		return $return;
	}

	public static function process_time_regulartime($data, $date = '0000-00-00', $category = 'regulartime')
	{
		// dd($data);

		$schedule = Tbl_payroll_employee_schedule::getschedule($data["employee_information"]->payroll_employee_id, $date)->first();

		if($schedule == null)
		{
			$schedule = Tbl_payroll_shift::getshift($data["employee_information"]->payroll_group_id, date('D', strtotime($date)))->first();
		}

		$default_time_in = '00:00:00';
		$default_time_out = '00:00:00';
		$target_hour_param = 0;
		$target_hour = 0;
		$break_start = '00:00:00';
		$break_end = '00:00:00';
		$flexi = 0;
		$rest_day = 0;
		$extra_day = 0;
		// dd($schedule);
		if($schedule != null)
		{
			$target_hour_param 		= $schedule->target_hours;
			$target_hour 			= $schedule->target_hours;
			$default_time_in 		= $schedule->work_start;
			$default_time_out 		= $schedule->work_end;
			$break_start 			= $schedule->break_start;
			$break_end 				= $schedule->break_end;
			$flexi  				= $schedule->flexi;
			$rest_day				= $schedule->rest_day;
			$extra_day				= $schedule->extra_day;
		}

		$is_editable = false;
	
		$def_in_float 	= Payroll::time_float($default_time_in);
		$def_out_float 	= Payroll::time_float($default_time_out);

		$time_rule 				= $data["time_rule"];
		$_time_record 			= $data["_time_record"];
		$default_working_hours 	= $data["default_working_hours"];
		$late_grace_time 		= $data["employee_information"]->payroll_group_grace_time * 60;
		$compute_approved 		= $data["compute_approved"];
		$holiday 				= $data['holiday'];

		/* for flexi time */
		
	
		$return = new stdClass();
		$data["default_working_hours"] = $default_working_hours = c_time_to_int($default_working_hours);

		$total_time_spent 			= 0;
		$total_early_overtime 		= 0;
		$total_late_overtime 		= 0;
		$total_regular_hours 		= 0;
		$total_late_hours 			= 0;
		$total_hours 				= 0;
		$total_night_differential 	= 0;
		$earliest_time_in 			= 86340;
		$latest_time_out 			= 0;
		$special_holiday_hours 		= 0;
		$regular_holiday_hours 		= 0;
		
		$total_under_time 			= 0;
		$absent						= false;
		$leave 						= Payroll::check_if_employee_leave($data["employee_information"]->payroll_employee_id, $date);

		$break 						= Payroll::time_diff(date('H:i', strtotime($break_start)), date('H:i', strtotime($break_end)));


		$break_nd = 0;

		if(isset($data['time_sheet_info']->is_break_update))
		{
			if($data['time_sheet_info']->is_break_update == 1)
			{
				$break = date('h:i',strtotime($data['time_sheet_info']->payroll_time_sheet_break));
				if($data['time_sheet_info']->payroll_time_sheet_break == '00:00:00')
				{
					$break = '00:00';
				}	
			}
		}

		
		$time_record = collect($data['time_sheet_info'])->toArray();

		if(isset($time_record['payroll_time_sheet_break']))
		{
			if($time_record['payroll_time_sheet_break'] != '00:00:00')
			{
				$break = date('H:i', strtotime($time_record['payroll_time_sheet_break']));
			}
		}

		$default_time_in 	= c_time_to_int($default_time_in);
		$default_time_out 	= c_time_to_int($default_time_out);
		$time_rec = null;

		$night_differential_pm = c_time_to_int("10:00 PM");
		$night_differential_am = c_time_to_int("6:00 AM");

		$time_in = 0;
		$time_out = 0;

		$time_in_str 	= '00:00';
		$time_out_str 	= '00:00';
		$float_nd 		= 0;


		/* CHECK EACH TIME */
		foreach($_time_record as $key => $time_record)
		{
			/* CHECK IF COMPUTE APPROVED OR THE PENDING */

			/* for time in within night diff range */

			if($compute_approved == 1)
			{
				$time_in 	= c_time_to_int($time_record->payroll_time_sheet_approved_in);
				$time_out 	= c_time_to_int($time_record->payroll_time_sheet_approved_out);
				
				$time_in_str 	= $time_record->payroll_time_sheet_approved_in;
				$time_out_str 	= $time_record->payroll_time_sheet_approved_out;
			}

			else
			{
				$time_in 	= c_time_to_int($time_record->payroll_time_sheet_in);
				$time_out 	= c_time_to_int($time_record->payroll_time_sheet_out);

				$time_in_str 	= $time_record->payroll_time_sheet_in;
				$time_out_str 	= $time_record->payroll_time_sheet_out;
			}

			$time_in_str = date('H:i', strtotime($time_in_str));
			
			if($time_in == 0) //SET BOTH TO BLANK IF TIME IN HAS NO INPUT
			{
				$time_rec[$key]["time_in"] = "";
				$time_rec[$key]["time_out"] = "";
			}
			elseif($time_in == $time_out)
			{
				$time_rec[$key]["time_in"] = "";
				$time_rec[$key]["time_out"] = "";
			}
			else
			{
				$time_rec[$key]["time_in"] = convert_seconds_to_hours_minutes("h:i A", $time_in);
				$time_rec[$key]["time_out"] = convert_seconds_to_hours_minutes("h:i A", $time_out);
			}

			$early_overtime = 0;
			$late_overtime = 0;
			$night_differential = 0;

			/* GET EARLIEST TIME IN - USE FOR LATE */
			if($earliest_time_in > $time_in)
			{
				$earliest_time_in = $time_in;
			}
		
			/* GET LATEST TIME OUT - USE FOR UNDER TIME */
			if($latest_time_out < $time_out)
			{
				$latest_time_out = $time_out;
			}

			$time_spent = 0;
			$time_spent = ($time_out - $time_in);

			/* if logs happen in graveyard shift */
			if($time_spent < 0)
			{
				$time_spent += c_time_to_int('24:00:00');
			}
			
			$regular_hours = $time_spent;
			
			$float_in 		= Payroll::time_float($time_in_str);
			$float_out 		= Payroll::time_float($time_out_str);
			$float_nd_in 	= Payroll::time_float('22:00');
			$float_nd_out 	= Payroll::time_float('30:00');
			$float_mx_out	= Payroll::time_float('36:00');

			/* if regular time */
			
			if($flexi == 0)
			{
				/* CHECK IF EARLY OVERTIME */

				if($float_in < $def_in_float && $float_in != 0)
				{
					$early_overtime = $def_in_float - $float_in;
					$early_overtime = c_time_to_int(Payroll::float_time($early_overtime));
					$regular_hours = $regular_hours - $early_overtime;
				}

				/* CHECK IF LATE OVERTIME */

				if($float_out > $def_out_float && $float_out != 0)
				{
					if($float_in > $default_time_out)
					{
						$late_overtime = $float_out - $float_in;
						$regular_hours = 0;
					}
					else
					{
						$late_overtime = $float_out - $def_out_float;
						$late_overtime = c_time_to_int(Payroll::float_time($late_overtime));
						$regular_hours = $regular_hours - $late_overtime;
					}
				}

			}	

			$late_overtime_flexi = 0;

			if($flexi == 1)
			{

				$target_flexi = c_time_to_int(Payroll::float_time($target_hour_param));
				$flexi_spent = $time_out - $time_in;
				$late_overtime_flexi = $flexi_spent - $target_flexi;
				// dd(convert_seconds_to_hours_minutes("H:i", $late_overtime));
				if($late_overtime_flexi < 0)
				{
					$late_overtime_flexi = 0;
				}
			}
			
			
			$time_in_str = date('H:i', strtotime($time_in_str));
			
			if(Payroll::time_float($time_in_str) <= 12 && Payroll::time_float($time_in_str) > 0)
			{
				$time_in_str = Payroll::sum_time($time_in_str,'24:00');
			}

			if(Payroll::time_float($time_out_str) <= 12 && Payroll::time_float($time_out_str) > 0)
			{
				$time_out_str = Payroll::sum_time($time_out_str,'24:00');
			}

			/* for night diff break */
			$break_start 	= Payroll::hour_24($break_start);
			$break_end 		= Payroll::hour_24($break_end);

			$float_nd = Payroll::get_night_diff($float_in, $float_out);

			$break_nd = Payroll::get_night_diff(Payroll::time_float($break_start), Payroll::time_float($break_end), true);

			$float_nd -= $break_nd;

			if($float_nd < 0)
			{
				$float_nd = 0;
			}

			$total_early_overtime += $early_overtime;
			$total_late_overtime += $late_overtime + $late_overtime_flexi;
			$total_regular_hours += $regular_hours;
			$total_time_spent += $time_spent;
		}
		/* CLEARLY EARLIEST TIME IN IF TIME RECORD IS NULL */
		if($time_rec == null)
		{
			$earliest_time_in = 0;
		}

		/* IF TOTAL TIME SPENT IS LESS THAN ZERO - SET IT TO ZERO */
		if($total_time_spent <= 0)
		{
			$total_time_spent = 0;
		}
		else
		{
			//IF BREAK IS GREATER THAN REGULAR HOURS - SET REGULAR HOURS TO ZERO
			if($break > $total_regular_hours)
			{
				$total_regular_hours = 0;
			}
			else
			{
				$total_regular_hours = $total_regular_hours - c_time_to_int($break);
			}
		}

		
		if($flexi == 0)
		{
			/* COMPUTE LATE BASED ON EARLIEST TIME IN */
			if($default_time_in < $time_in)
			{
				$total_late_hours = $time_in - $default_time_in;

				if($total_late_hours <= $late_grace_time)
				{
					$total_regular_hours = $total_regular_hours + $total_late_hours;
					$total_late_hours = 0;
				}
			}

			else
			{
				$total_late_hours = 0;
			}
			/* COMPUTE UNDER TIME BASED ON OLDEST TIME */
			if($default_time_out > $latest_time_out && $latest_time_out != 0)
			{
				$total_under_time = $default_time_out - $latest_time_out;
			}
			else
			{
				$total_under_time = 0;
			}
		}

		if($flexi == 1)
		{
			/* get under time */
			$total_under_time = $target_hour - $total_time_spent;
			if($total_under_time < 0)
			{
				$total_under_time = 0;
			}

			/* get late overtime */
			// $total_late_overtime = $total_time_spent - $target_hour;
			// if($total_late_overtime < 0)
			// {
			// 	$total_late_overtime = 0;
			// }
		}

		
		$total_hours = $total_regular_hours + $total_early_overtime + $total_late_overtime + c_time_to_int(Payroll::float_time($float_nd));

		if($total_hours == '00:00' || $total_hours <= 0)
		{
			$break = c_time_to_int('00:00:00');
			$total_hours = 0;
		}	

		if($total_hours > 0)
		{
			// $total_hours -= 2($break);
		}
		
		/* COMPUTE EXTRA DAY AND REST DAY */
		$total_rest_day_hours = 0;
		$total_extra_day_hours = 0;
		$rest_day_today = false;
		$extra_day_today = false;
		$holiday_today = false;
		$_rest_day = Tbl_payroll_group_rest_day::where("payroll_group_id", $data["employee_information"]->payroll_group_id)->get();

		if(isset($data["time_sheet_info"]->payroll_time_date))
		{	
			$date = $data["time_sheet_info"]->payroll_time_date;
		}

		if($rest_day == 1)
		{
			if($total_hours == '00:00:00' || $total_hours == '00:00')
			{
				$total_hours = 0;
			}

			$total_rest_day_hours = $total_regular_hours;
			$total_regular_hours = 0;
			$rest_day_today = true;
		}

		if($extra_day == 1)
		{
			
			if($total_hours == '00:00:00' || $total_hours == '00:00')
			{
				$total_hours = 0;
			}
			else
			{
				// $total_hours += c_time_to_int($break);
			}
			
			// $total_extra_day_hours = $total_hours;
			$total_extra_day_hours	= $total_regular_hours;
			$total_regular_hours = 0;
			$extra_day_today = true;
		}

		if($holiday != null || !empty($holiday))
		{
			$total_regular_hours = 0;
			if($holiday->payroll_holiday_category == 'Regular')
			{
				$regular_holiday_hours = $total_hours;
				$holiday_today = true;
			}
			else
			{
				$special_holiday_hours = $total_hours;
			}
		}

		/* CHECK IF LEAVE */
		

		/* CHECK IF ABSENT */
		$absent = false;

		if($total_time_spent == 0 && $extra_day_today == false && $holiday_today == false && $rest_day_today == false)
		{
			$absent = true;
		}



		$return->time_spent 		= convert_seconds_to_hours_minutes("H:i", $total_time_spent);
		$return->regular_hours 		= convert_seconds_to_hours_minutes("H:i", $total_regular_hours);
		$return->late_overtime 		= convert_seconds_to_hours_minutes("H:i", $total_late_overtime);
		$return->early_overtime 	= convert_seconds_to_hours_minutes("H:i", $total_early_overtime);
		$return->late_hours 		= convert_seconds_to_hours_minutes("H:i", $total_late_hours);
		$return->under_time 		= convert_seconds_to_hours_minutes("H:i", $total_under_time);
		$return->rest_day_hours 	= convert_seconds_to_hours_minutes("H:i", $total_rest_day_hours);
		$return->extra_day_hours 	= convert_seconds_to_hours_minutes("H:i", $total_extra_day_hours);
		$return->total_hours 		= convert_seconds_to_hours_minutes("H:i", $total_hours);
		$return->night_differential = Payroll::float_time($float_nd);
		$return->special_holiday_hours = convert_seconds_to_hours_minutes("H:i", $special_holiday_hours);
		$return->regular_holiday_hours = convert_seconds_to_hours_minutes("H:i", $regular_holiday_hours);


		if($break == 0)
		{
			$break = '00:00';
		}

		if(isset($data['time_sheet_info']->is_break_update))
		{
			if($data['time_sheet_info']->is_break_update == 1)
			{
				$break = date('h:i',strtotime($data['time_sheet_info']->payroll_time_sheet_break));
				if($data['time_sheet_info']->payroll_time_sheet_break == '00:00:00')
				{
					$break = '00:00';
				}	
			}
		}
		
		$return->break 				= $break;
		$return->time_record 		= $time_rec;
		$return->absent 			= $absent;
		$return->leave 				= $leave;

		return $return;
	}


	public static function get_night_diff($float_in = 0, $float_out = 0, $is_nd_break = false)
	{
		$float_nd_in 	= Payroll::time_float('15:00');
		$float_nd_out 	= Payroll::time_float('30:00');
		$float_mx_out 	= Payroll::time_float('36:00');
		$float_nd 		= 0;

		$temp_in_float = Payroll::time_float('22:00');
		if($float_in > $temp_in_float)
		{
			$temp_in_float = $float_in;
		}

		if($float_in <= $float_nd_out && $float_in >= $float_nd_in)
		{
			
			if($float_in <= $float_nd_in)
			{
				$float_in = $float_nd_in;
			}
			
			if(($float_nd_out - $temp_in_float) < 0)
			{
				$float_nd += 0;
			}
			else
			{
				if($float_out < $float_nd_out && $is_nd_break)
				{
					$float_nd_out = $float_out;
				}
				$float_nd += $float_nd_out - $temp_in_float;
			}
			// dd($float_nd);

		}

		if($float_out <= $float_mx_out && $float_out >= $float_nd_in)
		{
			// dd('dito');
			$temp_out_float = $float_out;
			if($float_out > $float_nd_out)
			{
				$temp_out_float = $float_nd_out;
			}
			else if($float_out < Payroll::time_float('22:00'))
			{
				$temp_out_float = 0;
			}
			$float_nd += $temp_out_float - $temp_in_float;
			// $float_nd += $temp_out_float - Payroll::time_float('22:00');

			// dd($float_nd);
			if($is_nd_break)
			{
				if($float_nd > ($temp_out_float - $temp_in_float))
				{
					$float_nd -= ($temp_out_float - $temp_in_float);
				}
			}
			

		}

		if($float_nd < 0)
		{
			$float_nd = 0;
		}	

		return $float_nd;
	}

	public static function hour_24($time = '00:00:00')
	{
		$time = date('H:i', strtotime($time));
		$time_float = Payroll::time_float($time);
		if($time_float <= 12)
		{
			$time_float += 24;
		}

		return Payroll::float_time($time_float);
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

	public static function return_time($hour = 0, $min = 0)
	{

		if($min >= 60)
		{	
			$min = $min - 60;
			$hour++;
		}

		if($min < 0)
		{
			$min += 60;
			$hour--;
		}

		if($min <= 9)
		{
			$min = '0'.$min;
		}
		if($hour <= 9)
		{
			$hour = '0'.$hour;
		}

		return $hour.':'.$min;
	}

	public static function if_zero_time($time = '00:00')
	{
		if($time == '00:00')
		{
			$time = '--:--';
		}
		return $time;
	}

	public static function if_zero($value = 0)
	{
		if($value <= 0)
		{
			$value = '--';
		}
		else
		{
			$value = number_format($value,2);
		}
		return $value;
	}

	/* accept 24 hour format only */
	public static function time_diff($start = '00:00', $end = '00:00', $is_night = true)
	{
		/* check if time2 is greater than time1 */
		$f_start= Payroll::time_float($start);
		$f_end = Payroll::time_float($end);
		if($f_start > $f_end && $is_night)
		{
			$end = Payroll::sum_time($end, '24:00');
		}

		$ex_start = explode(':', $start);
		$ex_end = explode(':', $end);

		$hour = $ex_end[0] - $ex_start[0];
		$min = 0;
		if(isset($ex_end[1]) && isset($ex_start[1]))
		{
			$min = $ex_end[1] - $ex_start[1];
		}
		return Payroll::return_time($hour, $min);
	}

	public static function time_float($time = '00:00')
	{
		$extime = explode(':', $time);
		
		$min = 0;
		if(count($extime) > 1)
		{
			$min = $extime[1] / 60;
		}
		$hour = $extime[0];
		
		return $hour + $min;
	}

	public static function float_time($float = 0)
	{
		// if(is_numeric($float))
		// {
		// 	$hour = intval($float);
		// 	$min = round(($float - $hour) * 60);
		// 	return Payroll::return_time($hour, $min);
		// }
		// else
		// {
		// 	return $float;
		// }
		$hour = intval($float);

		$min = round(($float - $hour) * 60);
		return Payroll::return_time($hour, $min);
		
	}

	public static function float_to_time($float)
	{

	}

	public static function process_compute($shop_id = 0, $status = 'processed')
	{
		$data = array();

		$_period = Tbl_payroll_period_company::period($shop_id, $status)
                                                       ->orderBy('.tbl_payroll_period.payroll_period_category')
                                                       ->orderBy('tbl_payroll_period.payroll_period_start')
                                                       ->get();

        foreach($_period as $period)
        {
        	
			array_push($data, Payroll::company_period($period, $shop_id));
        }
        // dd($data);
        return $data;
	}

	public static function company_period($period = array(), $shop_id = 0)
	{
		$payroll_period_category = $period->payroll_period_category;
    	$payroll_company_id = $period->payroll_company_id;
    	// dd($period);
    	$_employee = Tbl_payroll_employee_contract::employeefilter($payroll_company_id, 0, 0, $period->payroll_period_end, $shop_id)
						->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
						->where('tbl_payroll_group.payroll_group_period', $payroll_period_category)
						->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
						->get();

		// dd($period);

		$start 	= $period->payroll_period_start;
		$end 	= $period->payroll_period_end;

		$temp_period['period'] 			= $period;
		$temp_period['total_gross'] 	= 0;
		$temp_period['total_deduction'] = 0;
		$temp_period['total_net'] 		= 0;
		$temp_period['_list']			= array();

		foreach($_employee as $employee)
		{

			$compute = Payroll::compute_per_employee($employee->payroll_employee_id, $start, $end, $shop_id, $payroll_period_category, $period->payroll_period_company_id);

			$temp['compute']						= $compute;
			$temp['payroll_employee_id'] 			= $employee->payroll_employee_id;
			$temp['payroll_employee_first_name'] 	= $employee->payroll_employee_first_name;
			$temp['payroll_employee_middle_name'] 	= $employee->payroll_employee_middle_name;
			$temp['payroll_employee_last_name'] 	= $employee->payroll_employee_last_name;
			$temp['payroll_employee_suffix_name'] 	= $employee->payroll_employee_suffix_name;
			$temp['payroll_employee_display_name'] 	= $employee->payroll_employee_display_name;
			$temp['total_gross'] 					= $compute['total_gross'];
			$temp['total_deduction'] 				= $compute['total_deduction'];
			$temp['total_net'] 						= $compute['total_net'];

			$temp_period['total_gross'] 			+= $compute['total_gross'];
			$temp_period['total_deduction'] 		+= $compute['total_deduction'];
			$temp_period['total_net'] 				+= $compute['total_net'];

			array_push($temp_period['_list'], $temp);
		}	
		return $temp_period;
	}

	public static function compute_per_employee($employee_id, $start = '0000-00-00', $end = '0000-00-00', $shop_id = 0, $payroll_period_category = '', $payroll_period_company_id = 0)
	{
		$period_category_arr = Payroll::getperiodcount($shop_id, $end, $payroll_period_category, $start);

		$period_category = $period_category_arr['period_category'];

		$basic_employee  = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_id)->first();

		$total_hours_render					= 0;

		$data['daily_rate']					= 0;


		$data['payroll_employee_id']		= $employee_id;
		$data['payroll_period_company_id']	= $payroll_period_company_id;
		$data['tax_status']					= $basic_employee->payroll_employee_tax_status;
		$data['branch_location_id']			= $basic_employee->branch_location_id;
		$data['salary_taxable'] 			= 0;
		$data['salary_sss'] 				= 0;
		$data['salary_pagibig'] 			= 0;
		$data['salary_philhealth'] 			= 0;

		$data['is_deduct_tax_default']		= 0;
		$data['deduct_tax_custom']			= 0;
		$data['is_deduct_sss_default']		= 0;
		$data['deduct_sss_custom']			= 0;
		$data['is_deduct_philhealth_default']	= 0;
		$data['deduct_philhealth_custom']	= 0;
		$data['is_deduct_pagibig_default']	= 0;
		$data['deduct_pagibig_custom']		= 0;

		$data['tax_contribution']			= 0;
		$data['sss_contribution_ee']		= 0;
		$data['sss_contribution_er']		= 0;
		$data['sss_contribution_ec']		= 0;
		$data['pagibig_contribution']		= 0;
		$data['philhealth_contribution_ee']	= 0;
		$data['philhealth_contribution_er']	= 0;

		$data['payroll_cola']				= 0;

		$data['extra_salary']				= 0;
		$data['extra_early_overtime'] 		= 0;
		$data['extra_reg_overtime'] 		= 0;
		$data['extra_night_diff'] 			= 0;

		$data['regular_salary'] 			= 0;
		$data['regular_early_overtime'] 	= 0;
		$data['regular_reg_overtime'] 		= 0;
		$data['regular_night_diff'] 		= 0;

		$data['rest_day_salary'] 			= 0;
		$data['rest_day_early_overtime']	= 0;
		$data['rest_day_reg_overtime'] 		= 0;
		$data['rest_day_night_diff'] 		= 0;

		$data['rest_day_sh'] 				= 0;
		$data['rest_day_sh_early_overtime'] = 0;
		$data['rest_day_sh_reg_overtime'] 	= 0;
		$data['rest_day_sh_night_diff'] 	= 0;

		$data['rest_day_rh'] 				= 0;
		$data['rest_day_rh_early_overtime'] = 0;
		$data['rest_day_rh_reg_overtime'] 	= 0;
		$data['rest_day_rh_night_diff'] 	= 0;

		$data['rh_salary'] 					= 0;
		$data['rh_early_overtime'] 			= 0;
		$data['rh_reg_overtime'] 			= 0;
		$data['rh_night_diff'] 				= 0;

		$data['sh_salary'] 					= 0;
		$data['sh_early_overtime'] 			= 0;
		$data['sh_reg_overtime'] 			= 0;
		$data['sh_night_diff'] 				= 0;

		$data['13_month']					= 0;
		$data['13_month_computed'] 			= 0;
		$data['13_month_id']				= array();
		
		$data['total_allowance']			= 0;
		$data['allowance']					= array();

		$data['deduction']					= array();
		
		$data['late_deduction'] 			= 0;
		$data['under_time']					= 0;
		$data['agency_deduction'] 			= 0;
		$data['absent_deduction']			= 0;
		$data['absent_count']				= 0;

		$data['total_net']					= 0;
		$data['total_gross']				= 0;
		$data['total_deduction']			= 0;

		$data['regular_hours']				= 0;
		$data['late_overtime']				= 0;
		$data['early_overtime']				= 0;
		$data['late_hours']					= 0;
		$data['under_time_hours']			= 0;
		$data['rest_day_hours']				= 0;
		$data['extra_day_hours']			= 0;
		$data['total_hours']				= 0;
		$data['night_differential']			= 0;
		$data['special_holiday_hours']		= 0;
		$data['regular_holiday_hours']		= 0;

		$data['total_regular_days']			= 0;
		$data['total_rest_days']			= 0;
		$data['total_extra_days']			= 0;
		$data['total_rh']					= 0;
		$data['total_sh']					= 0;
		$data['total_worked_days']			= 0;
		$data['adjustment']					= array();

		$data['_details']					= array();

		$data['_total_unused_leave']		= 0;
		$data['_unused_leave']				= array();

		$data['leave_count_wo_pay']			= 0;
		$data['leave_count_w_pay']			= 0;
		$data['leave_amount']				= 0;
		$data['break_deduction']			= 0;
		$data['break_time']					= 0;


		$tax_status 	= Tbl_payroll_employee_basic::where('payroll_employee_id', $employee_id)->value('payroll_employee_tax_status');

		$group = array();

		/* fixed allowances */
		$_allowance_fixed = Tbl_payroll_employee_allowance::employee_allowance($employee_id,'fixed')
														->orderBy('tbl_payroll_allowance.payroll_allowance_name')
														->select('tbl_payroll_allowance.*')
														->get();

		/* daily allowances */	
		$_allowance_daily = Tbl_payroll_employee_allowance::employee_allowance($employee_id,'daily')
														->orderBy('tbl_payroll_allowance.payroll_allowance_name')
														->select('tbl_payroll_allowance.*')
														->get();


		$dd_array = array();
		/* compute absent */
		$absent_count = 0;
		$absent_deduction = 0;

		/* salary */
		$monthly_salary =0;

		/* daily cola */
		$cola = 0;

		/* daily late */
		$daily_late = 0;

		/* daily undertime */
		$daily_undertime = 0;


		/* array for dd() */
		$array_dd = array();


		while($start <= $end)
		{
			$date = Carbon::parse($start)->format("Y-m-d"); 

			$time = Payroll::process_time($employee_id, $date);

			$count_rh = Tbl_payroll_holiday_company::getholiday($basic_employee->payroll_employee_company_id, $date)
													->where('tbl_payroll_holiday.payroll_holiday_category','Regular')
													->count();

			$approved = $time->approved_timesheet;
			// array_push($dd_array, $approved);


			$temp_hour 					= 0;

			$regular_hours 				= Payroll::time_float($approved->regular_hours);
			$late_overtime 				= Payroll::time_float($approved->late_overtime);
			$early_overtime 			= Payroll::time_float($approved->early_overtime);
			$late_hours 				= Payroll::time_float($approved->late_hours);
			$under_time 				= Payroll::time_float($approved->under_time);
			$rest_day_hours 			= Payroll::time_float($approved->rest_day_hours);
			$extra_day_hours 			= Payroll::time_float($approved->extra_day_hours);
			$total_hours 				= Payroll::time_float($approved->total_hours);
			$night_differential 		= Payroll::time_float($approved->night_differential);
			$special_holiday_hours 		= Payroll::time_float($approved->special_holiday_hours);
			$regular_holiday_hours 		= Payroll::time_float($approved->regular_holiday_hours);
			$break_hours 				= Payroll::time_float($approved->break);

			$data['regular_hours']				+= $regular_hours;
			$data['late_overtime']				+= $late_overtime;
			$data['early_overtime']				+= $early_overtime;
			$data['late_hours']					+= $late_hours;
			$data['under_time_hours']			+= $under_time;
			$data['rest_day_hours']				+= $rest_day_hours;
			$data['extra_day_hours']			+= $extra_day_hours;
			$data['total_hours']				+= $total_hours;
			$data['night_differential']			+= $night_differential;
			$data['special_holiday_hours']		+= $special_holiday_hours;
			$data['regular_holiday_hours']		+= $regular_holiday_hours;
			$data['break_time']					+= $break_hours;

			/* EMPLOYEE SALARY */
			$salary = Tbl_payroll_employee_salary::selemployee($employee_id, $date)->where('payroll_employee_salary_archived',0)->orderBy('payroll_employee_salary_effective_date','desc')->first();

			$data['minimum_wage'] 			= 0;
			$data['salary_monthly'] 		= 0;
			$data['salary_daily'] 			= 0;
			$data['salary_taxable'] 		= 0;
			$data['salary_sss'] 			= 0;
			$data['salary_pagibig'] 		= 0;
			$data['salary_philhealth'] 		= 0;


			$data['is_deduct_tax_default'] 			= 0;
			$data['deduct_tax_custom'] 				= 0;
			$data['is_deduct_sss_default'] 			= 0;
			$data['deduct_sss_custom'] 				= 0;
			$data['is_deduct_philhealth_default'] 	= 0;
			$data['deduct_philhealth_custom'] 		= 0;
			$data['is_deduct_pagibig_default'] 		= 0;
			$data['deduct_pagibig_custom'] 			= 0;

			$cola 							= 0;

			if(isset($salary->payroll_employee_salary_minimum_wage))
			{
				$data['minimum_wage'] 		= $salary->payroll_employee_salary_minimum_wage;
				$data['salary_monthly'] 	= $salary->payroll_employee_salary_monthly;
				// $data['salary_daily'] 		= $salary->payroll_employee_salary_daily;
				$data['salary_taxable'] 	= $salary->payroll_employee_salary_taxable;
				$data['salary_sss'] 		= $salary->payroll_employee_salary_sss;
				$data['salary_pagibig'] 	= $salary->payroll_employee_salary_pagibig;
				$data['salary_philhealth'] 	= $salary->payroll_employee_salary_philhealth;
				$cola 						= $salary->payroll_employee_salary_cola;

				$data['is_deduct_tax_default']		= $salary->is_deduct_tax_default;
				$data['deduct_tax_custom']			= $salary->deduct_tax_custom;
				$data['is_deduct_sss_default']		= $salary->is_deduct_sss_default;
				$data['deduct_sss_custom']			= $salary->deduct_sss_custom;
				$data['is_deduct_philhealth_default']	= $salary->is_deduct_philhealth_default;
				$data['deduct_philhealth_custom']	= $salary->deduct_philhealth_custom;
				$data['is_deduct_pagibig_default']	= $salary->is_deduct_pagibig_default;
				$data['deduct_pagibig_custom']		= $salary->deduct_pagibig_custom;
			}

			
			$group = Tbl_payroll_employee_contract::selemployee($employee_id, $date)
												   ->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
												   ->select('tbl_payroll_group.*')
												   ->first();

			// dd($group);

			$working_day_month = $group->payroll_group_working_day_month;

			$target_hour = 0;
			/* default shift */
			$default_shift = Tbl_payroll_shift::getshift($group->payroll_group_id, date('D', strtotime($start)))->first();
			if($default_shift != null)
			{
				$target_hour = $default_shift->target_hours;
			}

			/* custom shift */
			$custom_shift = Tbl_payroll_employee_schedule::getschedule($employee_id, $start)->first();

			if($custom_shift != null)
			{
				$target_hour = $custom_shift->target_hours;
			}

			// 
			

			$under_time = divide($under_time, $target_hour);
			$data['total_regular_days']			+= divide($regular_hours, $target_hour);
			$data['total_rest_days']			+= divide($rest_day_hours, $target_hour);
			$data['total_extra_days']			+= divide($extra_day_hours, $target_hour);
			$data['total_rh']					+= divide($regular_holiday_hours, $target_hour);
			$data['total_sh']					+= divide($special_holiday_hours, $target_hour);
			/* daily late */
			$daily_late 						+= divide($late_hours, $target_hour);
			/* daily undertime */
			$daily_undertime 					+= $under_time;

			$daily_rate = divide($data['salary_monthly'] , $working_day_month);

			// dd($working_day_month);

			$hourly_rate = divide($daily_rate, $target_hour);

			

			$data['daily_rate'] = $daily_rate;
			
			$data['salary_daily'] = $daily_rate;

			/* compute leave */


			/* check if has leave */
			$get_leave = Tbl_payroll_leave_schedule::checkemployee($employee_id, $start)->first();
			$has_leave = false;

			$daily_leave = 0;
			$daily_leave_amount = 0;

			if($get_leave != null)
			{
				$has_leave = true;
				
				$daily_leave = 1;
				if($get_leave->payroll_leave_temp_with_pay == 1)
				{
					$data['leave_amount'] += $daily_rate;
					$daily_leave_amount = $daily_rate;
					$data['leave_count_w_pay']++;
				}
				else
				{
					$data['leave_count_wo_pay']++;
				}
			}

			/* compute absent */
			$daily_absent = 0;
			if($approved->absent)
			{
				if(!$has_leave && $group->payroll_group_salary_computation == 'Monthly Rate')
				{
					$absent_deduction += $daily_rate;
					$daily_absent = $daily_rate;
				}
				$absent_count++;
			}

			/* PAYROLL OVER TIME RATES */
			$query_arr['payroll_group_id'] 				= $group->payroll_group_id;
			$query_arr['payroll_overtime_name'] 		= 'Regular';

			/* REGULAR DAYS */
			$regular_hour['regular'] 					= 0;
			$regular_hour['late_overtime'] 				= 0;
			$regular_hour['early_overtime'] 			= 0;
			$regular_hour['night_differential']  		= 0;

			$rest_reg_hour['regular']					= 0;
			$rest_reg_hour['late_overtime'] 			= 0;
			$rest_reg_hour['early_overtime'] 			= 0;
			$rest_reg_hour['night_differential']  		= 0;

			$extra_hour['regular'] 						= 0;
			$extra_hour['late_overtime'] 				= 0;
			$extra_hour['early_overtime'] 				= 0;
			$extra_hour['night_differential']  			= 0;

			$sh_hour['regular'] 						= 0;
			$sh_hour['late_overtime'] 					= 0;
			$sh_hour['early_overtime'] 					= 0;
			$sh_hour['night_differential']  			= 0;

			$sh_rest_hour['regular'] 					= 0;
			$sh_rest_hour['late_overtime'] 				= 0;
			$sh_rest_hour['early_overtime'] 			= 0;
			$sh_rest_hour['night_differential'] 		= 0;

			$rh_hour['regular'] 						= 0;
			$rh_hour['late_overtime'] 					= 0;
			$rh_hour['early_overtime'] 					= 0;
			$rh_hour['night_differential'] 				= 0;

			$rh_rest_hour['regular'] 					= 0;
			$rh_rest_hour['late_overtime'] 				= 0;
			$rh_rest_hour['early_overtime'] 			= 0;
			$rh_rest_hour['night_differential'] 		= 0;


			/* for legal holiday if not rest day */
			if($rest_day_hours <= 0 && $count_rh > 0)
			{
				$rh_hour['regular'] = $count_rh;
			}

			// array_push($array_dd, $extra_day_hours);
			/* EXTRA DAYS */
			if($extra_day_hours > 0 && $regular_hour['regular'] <= 0 && $special_holiday_hours <= 0 && $regular_holiday_hours <= 0)
			{
				$temp_hour								= $extra_day_hours;
				$extra_hour['regular'] 					= divide($extra_day_hours, $target_hour);
				$extra_hour['late_overtime'] 			= divide($late_overtime, $target_hour);
				$extra_hour['early_overtime'] 			= divide($early_overtime, $target_hour);
				$extra_hour['night_differential']  		= divide($night_differential, $target_hour);
				// $extra_hour['cola']						= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Regular';
			}

			/* REGULAR DAYS */
			if($regular_hours > 0 && $rest_day_hours <= 0 && $special_holiday_hours <= 0 && $regular_holiday_hours <= 0)
			{
				$temp_hour								= $regular_hours;
				$regular_hour['regular'] 				= divide($regular_hours, $target_hour);
				$regular_hour['late_overtime'] 			= divide($late_overtime, $target_hour);
				$regular_hour['early_overtime'] 		= divide($early_overtime, $target_hour);
				$regular_hour['night_differential']  	= divide($night_differential, $target_hour);
				// $regular_hour['cola']					= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Regular';
			}

			/* SPECIAL HOLIDAY */

			if($regular_hours <= 0 && $rest_day_hours > 0 && $special_holiday_hours <= 0 && $regular_holiday_hours <= 0)
			{
				$rest_reg_hour['regular']				= divide($rest_day_hours, $target_hour);
				$rest_reg_hour['late_overtime']			= divide($late_overtime, $target_hour);
				$rest_reg_hour['early_overtime'] 		= divide($early_overtime, $target_hour);
				$rest_reg_hour['night_differential']  	= divide($night_differential, $target_hour);
				// $rest_reg_hour['cola']					= $cola;

				$query_arr['payroll_overtime_name'] 	= 'Regular';
				
				$temp_hour								= $rest_day_hours;
			}

			
			/* LEGAL HOLIDAY */
			if($regular_hours <= 0 && $rest_day_hours <= 0 && $special_holiday_hours > 0 && $regular_holiday_hours <= 0)
			{
				$sh_hour['regular'] 					= divide($special_holiday_hours, $target_hour);
				$sh_hour['late_overtime'] 				= divide($late_overtime, $target_hour);
				$sh_hour['early_overtime'] 				= divide($early_overtime, $target_hour);
				$sh_hour['night_differential']  		= divide($night_differential, $target_hour);
				// $sh_hour['cola']						= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Special Holiday';
				$temp_hour								= $special_holiday_hours;

			}


			if($regular_hours <= 0 && $rest_day_hours > 0 && $special_holiday_hours > 0 && $regular_holiday_hours <= 0)
			{
				$sh_rest_hour['regular'] 				= divide($rest_day_hours, $target_hour);
				$sh_rest_hour['late_overtime'] 			= divide($late_overtime, $target_hour);
				$sh_rest_hour['early_overtime'] 		= divide($early_overtime, $target_hour);
				$sh_rest_hour['night_differential'] 	= divide($night_differential, $target_hour);
				// $sh_rest_hour['cola']					= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Special Holiday';
				$temp_hour								= $rest_day_hours;
			}
			


			if($regular_hours <= 0 && $rest_day_hours <= 0 && $special_holiday_hours <= 0 && $regular_holiday_hours > 0)
			{
				$rh_hour['regular'] 					= divide($regular_holiday_hours, $target_hour);
				$rh_hour['late_overtime'] 				= divide($late_overtime, $target_hour);
				$rh_hour['early_overtime'] 				= divide($early_overtime, $target_hour);
				$rh_hour['night_differential'] 			= divide($night_differential, $target_hour);
				// $rh_hour['cola']						= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Legal Holiday';
				$temp_hour								= $regular_holiday_hours;

			}

			if($regular_hours <= 0 && $rest_day_hours > 0 && $special_holiday_hours <= 0 && $regular_holiday_hours > 0)
			{

				$rh_rest_hour['regular'] 				= divide($rest_day_hours, $target_hour);
				$rh_rest_hour['late_overtime'] 			= divide($late_overtime, $target_hour);
				$rh_rest_hour['early_overtime'] 		= divide($early_overtime, $target_hour);
				$rh_rest_hour['night_differential'] 	= divide($night_differential, $target_hour);
				// $rh_rest_hour['cola']					= $cola;
				$query_arr['payroll_overtime_name'] 	= 'Legal Holiday';
				$temp_hour								= $rest_day_hours;

			}

			$regular_day = Payroll::compute_overtime($query_arr, $regular_hour, $daily_rate, 'Regular', $cola);

			$regular_day_rest = Payroll::compute_overtime($query_arr, $rest_reg_hour, $daily_rate, 'Rest Regular', $cola);

			$extra_day = Payroll::compute_overtime($query_arr, $extra_hour, $daily_rate, 'Extra', $cola);

			$legal_holiday = Payroll::compute_overtime($query_arr, $rh_hour, $daily_rate, 'Legal Holiday', $cola);
			$legal_holiday_rest = Payroll::compute_overtime($query_arr, $rh_rest_hour, $daily_rate, 'Rest Legal Holiday', $cola);
			$special_holiday = Payroll::compute_overtime($query_arr, $sh_hour, $daily_rate, 'Special Holiday', $cola);
			$special_holiday_rest = Payroll::compute_overtime($query_arr, $sh_rest_hour, $daily_rate, 'Rest Special Holiday', $cola);
			// dd($legal_holiday);

			/* ALLOWANCE DAILY START */

			$one_day_render = $regular_hours + $rest_day_hours + $extra_day_hours + $special_holiday_hours + $regular_holiday_hours;

			$total_hours_render += $one_day_render;

			if($one_day_render > 0)
			{
				foreach($_allowance_daily as $key => $daily_allowance)
				{
					$data['allowance'] = Payroll::push_allowance($data['allowance'], $daily_allowance, $payroll_period_category, $period_category, ($one_day_render / $target_hour));
				}
			}

			/* ALLOWANCE DAILY END */

			$data['extra_salary']				+= $extra_day['regular'];
			$data['extra_early_overtime'] 		+= $extra_day['early_overtime'];
			$data['extra_reg_overtime'] 		+= $extra_day['late_overtime'];
			$data['extra_night_diff'] 			+= $extra_day['night_differential'];

			$data['regular_salary'] 			+= $regular_day['regular'];
			$data['regular_early_overtime'] 	+= $regular_day['early_overtime'];
			$data['regular_reg_overtime'] 		+= $regular_day['late_overtime'];
			$data['regular_night_diff'] 		+= $regular_day['night_differential'];

			$data['rest_day_salary'] 			+= $regular_day_rest['regular'];
			$data['rest_day_early_overtime']	+= $regular_day_rest['early_overtime'];
			$data['rest_day_reg_overtime'] 		+= $regular_day_rest['late_overtime'];
			$data['rest_day_night_diff'] 		+= $regular_day_rest['night_differential'];

			$data['rest_day_sh'] 				+= $special_holiday_rest['regular'];
			$data['rest_day_sh_early_overtime'] += $special_holiday_rest['early_overtime'];
			$data['rest_day_sh_reg_overtime'] 	+= $special_holiday_rest['late_overtime'];
			$data['rest_day_sh_night_diff'] 	+= $special_holiday_rest['night_differential'];

			$data['rest_day_rh'] 				+= $legal_holiday_rest['regular'];
			$data['rest_day_rh_early_overtime'] += $legal_holiday_rest['early_overtime'];
			$data['rest_day_rh_reg_overtime'] 	+= $legal_holiday_rest['late_overtime'];
			$data['rest_day_rh_night_diff'] 	+= $legal_holiday_rest['night_differential'];

			$data['rh_salary'] 					+= $legal_holiday['regular'];
			$data['rh_early_overtime'] 			+= $legal_holiday['early_overtime'];
			$data['rh_reg_overtime'] 			+= $legal_holiday['late_overtime'];
			$data['rh_night_diff'] 				+= $legal_holiday['night_differential'];

			$data['sh_salary'] 					+= $special_holiday['regular'];
			$data['sh_early_overtime'] 			+= $special_holiday['early_overtime'];
			$data['sh_reg_overtime'] 			+= $special_holiday['late_overtime'];
			$data['sh_night_diff'] 				+= $special_holiday['night_differential'];


			$data['payroll_cola']				+= $extra_day['cola'] + $regular_day['cola'] + $regular_day_rest['cola'] + $special_holiday_rest['cola'] + $legal_holiday_rest['cola'] + $legal_holiday['cola'] + $special_holiday['cola'];

			$temp_cola = $extra_day['cola'] + $regular_day['cola'] + $regular_day_rest['cola'] + $special_holiday_rest['cola'] + $legal_holiday_rest['cola'] + $legal_holiday['cola'] + $special_holiday['cola'];

			array_push($dd_array, $regular_day['late_overtime']);
			/* LATE COMPUTATION START */

			$late_deduction 		= 0;
			$undertime_deduction 	= 0;

			if($group->payroll_late_category == 'Custom')
			{	
				$interval 				= $group->payroll_late_interval;
				$parameter 				= $group->payroll_late_parameter;
				$payroll_late_deduction = $group->payroll_late_deduction;

				if($parameter == 'Second')
				{
					$late_hours = ($late_hours * 60) * 60;
				}
				else if($parameter == 'Minute')
				{
					$late_hours = $late_hours * 60;
				}

				$interval = intval(divide($late_hours, $intval));

				$late_deduction = $interval * $payroll_late_deduction;
			}
			else if($group->payroll_late_category == 'Base on Salary')
			{
				$late_deduction = $late_hours * $hourly_rate;
			}


			/* under time deduction */
			if($group->payroll_under_time_category == 'Base on Salary')
			{
				$undertime_deduction = $under_time * $hourly_rate;
			}
			if($group->payroll_under_time_category == 'Custom')
			{	
				$ut_interval 					= $group->payroll_under_time_interval;
				$ut_parameter 					= $group->payroll_under_time_parameter;
				$payroll_under_time_deduction 	= $group->payroll_under_time_deduction;

				if($ut_parameter == 'Second')
				{
					$under_time = ($under_time * 60) * 60;
				}
				else if($ut_parameter == 'Minute')
				{
					$under_time = $under_time * 60;
				}

				$ut_interval = intval(divide($under_time, $ut_interval));

				$undertime_deduction = $ut_interval * $payroll_under_time_deduction;
			}

			/* break duduction */
			$break_deduction = 0;
			if($group->payroll_break_category == 'Base on Salary')
			{
				$break_deduction = $break_hours * $hourly_rate;
			}
 

			$data['late_deduction']	+= round($late_deduction, 2);
			$data['under_time'] += round($undertime_deduction, 2);
			$data['break_deduction'] += $break_deduction;
			
			// array_push($dd_array, $late_deduction);


			$details['date'] 						= $start;
			$details['extra_salary'] 				= $extra_day['regular'];
			$details['extra_early_overtime'] 		= $extra_day['early_overtime'];
			$details['extra_reg_overtime'] 			= $extra_day['late_overtime'];
			$details['extra_night_diff'] 			= $extra_day['night_differential'];
			$details['regular_salary'] 				= $regular_day['regular'];
			$details['regular_early_overtime'] 		= $regular_day['early_overtime'];
			$details['regular_reg_overtime'] 		= $regular_day['late_overtime'];
			$details['regular_night_diff'] 			= $regular_day['night_differential'];
			$details['rest_day_salary'] 			= $regular_day_rest['regular'];
			$details['rest_day_early_overtime'] 	= $regular_day_rest['early_overtime'];
			$details['rest_day_reg_overtime'] 		= $regular_day_rest['late_overtime'];
			$details['rest_day_night_diff'] 		= $regular_day_rest['night_differential'];
			$details['rest_day_sh'] 				= $special_holiday_rest['regular'];
			$details['rest_day_sh_early_overtime'] 	= $special_holiday_rest['early_overtime'];
			$details['rest_day_sh_reg_overtime'] 	= $special_holiday_rest['late_overtime'];
			$details['rest_day_sh_night_diff'] 		= $special_holiday_rest['night_differential'];
			$details['rest_day_rh'] 				= $legal_holiday_rest['regular'];
			$details['rest_day_rh_early_overtime'] 	= $legal_holiday_rest['early_overtime'];
			$details['rest_day_rh_reg_overtime'] 	= $legal_holiday_rest['late_overtime'];
			$details['rest_day_rh_night_diff'] 		= $legal_holiday_rest['night_differential'];
			$details['rh_salary'] 					= $legal_holiday['regular'];
			$details['rh_early_overtime'] 			= $legal_holiday['early_overtime'];
			$details['rh_reg_overtime'] 			= $legal_holiday['late_overtime'];
			$details['rh_night_diff'] 				= $legal_holiday['night_differential'];
			$details['sh_salary'] 					= $special_holiday['regular'];
			$details['sh_early_overtime'] 			= $special_holiday['early_overtime'];
			$details['sh_reg_overtime'] 			= $special_holiday['late_overtime'];
			$details['sh_night_diff'] 				= $special_holiday['night_differential'];
			$details['cola'] 						= $temp_cola;
			$details['late_deduction']				= round($late_deduction, 2);
			$details['under_time']					= round($undertime_deduction, 2);
			$details['absent_deduction']			= round($daily_absent, 2);
			$details['leave']						= round($daily_leave_amount, 2);
			$details['break']						= round($break_deduction, 2);


			$details['total_early_ot']				= $extra_day['early_overtime'] + $regular_day['early_overtime'] + $regular_day_rest['early_overtime'] + $special_holiday_rest['early_overtime'] + $legal_holiday_rest['early_overtime'] + $legal_holiday['early_overtime'] + $special_holiday['early_overtime'];

			$details['total_reg_ot']				= $extra_day['late_overtime'] + $regular_day['late_overtime'] + $regular_day_rest['late_overtime'] + $special_holiday_rest['late_overtime'] + $legal_holiday_rest['late_overtime'] + $legal_holiday['late_overtime'] + $special_holiday['late_overtime'];

			$details['total_rest_days']				= $regular_day_rest['regular'] + $special_holiday_rest['regular'] + $legal_holiday_rest['regular'];

			$details['total_night_differential']	= $extra_day['night_differential'] + $regular_day['night_differential'] + $regular_day_rest['night_differential'] + $special_holiday_rest['night_differential'] + $legal_holiday_rest['night_differential'] + $legal_holiday['night_differential'] + $special_holiday['night_differential'];



			if($group->payroll_group_salary_computation == 'Flat Rate')
			{
				$details['date'] 						= $start;
				$details['extra_salary'] 				= 0;
				$details['extra_early_overtime'] 		= 0;
				$details['extra_reg_overtime'] 			= 0;
				$details['extra_night_diff'] 			= 0;
				$details['regular_salary'] 				= 0;
				$details['regular_early_overtime'] 		= 0;
				$details['regular_reg_overtime'] 		= 0;
				$details['regular_night_diff'] 			= 0;
				$details['rest_day_salary'] 			= 0;
				$details['rest_day_early_overtime'] 	= 0;
				$details['rest_day_reg_overtime'] 		= 0;
				$details['rest_day_night_diff'] 		= 0;
				$details['rest_day_sh'] 				= 0;
				$details['rest_day_sh_early_overtime'] 	= 0;
				$details['rest_day_sh_reg_overtime'] 	= 0;
				$details['rest_day_sh_night_diff'] 		= 0;
				$details['rest_day_rh'] 				= 0;
				$details['rest_day_rh_early_overtime'] 	= 0;
				$details['rest_day_rh_reg_overtime'] 	= 0;
				$details['rest_day_rh_night_diff'] 		= 0;
				$details['rh_salary'] 					= 0;
				$details['rh_early_overtime'] 			= 0;
				$details['rh_reg_overtime'] 			= 0;
				$details['rh_night_diff'] 				= 0;
				$details['sh_salary'] 					= 0;
				$details['sh_early_overtime'] 			= 0;
				$details['sh_reg_overtime'] 			= 0;
				$details['sh_night_diff'] 				= 0;
				$details['cola'] 						= 0;
				$details['late_deduction']				= 0;
				$details['under_time']					= 0;
				$details['absent_deduction']			= 0;
				$details['leave']						= 0;
				$details['total_early_ot']				= 0;
				$details['total_reg_ot']				= 0;
				$details['total_rest_days']				= 0;
				$details['total_night_differential']	= 0;
			}

			array_push($data['_details'], $details);
			$start = Carbon::parse($start)->addDay()->format("Y-m-d");

		}



		// dd($array_dd);
		$payroll_group_salary_computation = $group->payroll_group_salary_computation;
		$data['payroll_group_salary_computation'] = $payroll_group_salary_computation;


		$data['display_monthly_rate'] 	= $group->display_monthly_rate;
		$data['display_daily_rate']		= $group->display_daily_rate;

		/* get monthly cola start */
		$monthly_cola = $cola * $group->payroll_group_working_day_month;
		/* get monthly cola end */

		$monthly_salary = $data['salary_monthly'];
		if($group->payroll_group_period == 'Semi-monthly')
		{
			$monthly_salary = $monthly_salary / 2;
			$monthly_cola 	= $monthly_cola / 2;
		}

		if($group->payroll_group_period == 'Weekly')
		{
			$monthly_salary = $monthly_salary / 4;
			$monthly_cola 	= $monthly_cola / 4;
		}

		if($group->payroll_group_period == 'Daily')
		{
			$monthly_salary = $monthly_salary / $group->payroll_group_working_day_month;
			$monthly_cola 	= $cola;
		}

		// dd($payroll_group_salary_computation);

		// if($payroll_group_salary_computation == 'Daily Rate')
		// {
		// 	$data['under_time']					= 0;
		// 	// $data['late_deduction'] 			= 0;
		// }

		$data['absent_count']				= $absent_count;
		$data['absent_deduction']			= $absent_deduction;


		if($payroll_group_salary_computation == 'Flat Rate')
		{
			$data['extra_salary']				= 0;
			$data['extra_early_overtime'] 		= 0;
			$data['extra_reg_overtime'] 		= 0;
			$data['extra_night_diff'] 			= 0;

			$data['regular_salary'] 			= $monthly_salary;
			$data['regular_early_overtime'] 	= 0;
			$data['regular_reg_overtime'] 		= 0;
			$data['regular_night_diff'] 		= 0;

			$data['rest_day_salary'] 			= 0;
			$data['rest_day_early_overtime']	= 0;
			$data['rest_day_reg_overtime'] 		= 0;
			$data['rest_day_night_diff'] 		= 0;

			$data['rest_day_sh'] 				= 0;
			$data['rest_day_sh_early_overtime'] = 0;
			$data['rest_day_sh_reg_overtime'] 	= 0;
			$data['rest_day_sh_night_diff'] 	= 0;

			$data['rest_day_rh'] 				= 0;
			$data['rest_day_rh_early_overtime'] = 0;
			$data['rest_day_rh_reg_overtime'] 	= 0;
			$data['rest_day_rh_night_diff'] 	= 0;

			$data['rh_salary'] 					= 0;
			$data['rh_early_overtime'] 			= 0;
			$data['rh_reg_overtime'] 			= 0;
			$data['rh_night_diff'] 				= 0;

			$data['sh_salary'] 					= 0;
			$data['sh_early_overtime'] 			= 0;
			$data['sh_reg_overtime'] 			= 0;
			$data['sh_night_diff'] 				= 0;

			$data['late_deduction'] 			= 0;
			$data['under_time']					= 0;

			$data['absent_deduction']			= 0;
			$data['absent_count']				= 0;

			$data['payroll_cola']				= $monthly_cola;

		}

		if($payroll_group_salary_computation == 'Monthly Rate')
		{
			$payroll_sss_monthly_salary = $monthly_salary - ($data['late_deduction'] + $data['under_time'] + $data['absent_deduction']);

			$data['regular_salary'] 			= $monthly_salary;
			
			$less_cola = ($daily_late * $cola) + ($daily_undertime * $cola) + ($absent_count * $cola);
			// dd($less_cola);
			$data['payroll_cola']				= round((($monthly_cola + ($data['leave_count_w_pay'] * $cola)) - $less_cola), 2);
		}

	

		$data['total_gross'] += ($data['extra_salary'] + $data['extra_early_overtime'] + $data['extra_reg_overtime'] + $data['extra_night_diff'] + $data['regular_salary'] + $data['regular_early_overtime'] + $data['regular_reg_overtime'] + $data['regular_night_diff'] + $data['rest_day_salary'] + $data['rest_day_early_overtime'] + $data['rest_day_reg_overtime'] + $data['rest_day_night_diff'] + $data['rest_day_sh'] + $data['rest_day_sh_early_overtime'] + $data['rest_day_sh_reg_overtime'] + $data['rest_day_sh_night_diff'] + $data['rest_day_rh'] + $data['rest_day_rh_early_overtime'] + $data['rest_day_rh_reg_overtime'] + $data['rest_day_rh_night_diff'] + $data['rh_salary'] + $data['rh_early_overtime'] + $data['rh_reg_overtime'] + $data['rh_night_diff'] + $data['sh_salary'] + $data['sh_early_overtime'] + $data['sh_reg_overtime'] + $data['sh_night_diff']);

		$data['total_gross'] += $data['payroll_cola'];

		/* PAYROLL ADJUSTMEMT START */
		/* allowances */
		$adjustment_allowance = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Allowance')->get();

		$adjustment_allowance_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Allowance')->sum('payroll_adjustment_amount');

		/* bonus */
		$adjustment_bonus = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Bonus')->get();

		$adjustment_bonus_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Bonus')->sum('payroll_adjustment_amount');
		
		/* commission */
		$adjustment_commission = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Commissions')->get();

		$adjustment_commission_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Commissions')->sum('payroll_adjustment_amount');

		/* incentives */
		$adjustment_incentives = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Incentives')->get();

		$adjustment_incentives_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Incentives')->sum('payroll_adjustment_amount');

		/* 13 month pay */
		$adjustment_13_month = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, '13 month pay')->get();

		$adjustment_13_month_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , '13 month pay')->sum('payroll_adjustment_amount');

		/* deductions */
		$adjustment_deductions = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Deductions')->get();

		$adjustment_deductions_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Deductions')->sum('payroll_adjustment_amount');

		$data['adjustment']['allowance'] 			= $adjustment_allowance;
		$data['adjustment']['total_allowance'] 		= $adjustment_allowance_total;

		$data['adjustment']['bonus'] 				= $adjustment_bonus;
		$data['adjustment']['total_bonus'] 			= $adjustment_bonus_total;

		$data['adjustment']['incentives'] 			= $adjustment_incentives;
		$data['adjustment']['total_incentives'] 	= $adjustment_incentives_total;

		$data['adjustment']['commission'] 			= $adjustment_commission;
		$data['adjustment']['total_commission'] 	= $adjustment_commission_total;

		$data['adjustment']['13_month'] 			= $adjustment_13_month;
		$data['adjustment']['total_13_month'] 		= $adjustment_13_month_total;

		$data['adjustment']['deductions'] 			= $adjustment_deductions;
		$data['adjustment']['total_deductions'] 	= $adjustment_deductions_total;

		/* PAYROLL ADJUSTMENT END */


		if(isset($group->payroll_group_13month_basis))
		{
			if($group->payroll_group_13month_basis == 'Periodically')
			{
				$data['13_month'] = round(($data['regular_salary'] / 12), 2);
				$data['13_month_computed'] = 1;
			}

			if($group->payroll_group_13month_basis == 'Custom Period')
			{
				$m13 = Payroll::compute_13_month($employee_id, $payroll_period_company_id);
				$data['13_month'] += $m13['data_13m'];
				$data['13_month_computed'] = 0;
				$data['13_month_id'] = $m13['id'];

				$count_v = Tbl_payroll_13_month_virtual::getperiod($employee_id, $payroll_period_company_id)->count();

				if($count_v == 1)
				{
					$data['13_month'] += round(($data['regular_salary'] / 12), 2);
					$data['13_month_computed'] = 1;
				}
			}
		}

		// $data['total_gross'] += $data['13_month'];

		if($total_hours_render > 0 || ($total_hours_render <= 0 && $payroll_group_salary_computation == 'Flat Rate'))
		{	
			foreach($_allowance_fixed as $key => $fixed_allowance)
			{
				$data['allowance'] = Payroll::push_allowance($data['allowance'], $fixed_allowance, $payroll_period_category, $period_category);
			}
		}


		if(!empty($data['allowance']))
		{
			$_collection = collect($data['allowance'])->groupBy('payroll_allowance_id');

			$data['allowance'] 	= array();
			$temp_allowance 	= array();

			foreach($_collection as $collection)
			{
				$temp_allowance['payroll_allowance_id'] 	= $collection[0]['payroll_allowance_id'];
				$temp_allowance['payroll_allowance_name'] 	= $collection[0]['payroll_allowance_name'];
				$temp_allowance['payroll_allowance_amount'] = collect($collection)->sum('payroll_allowance_amount');

				$data['total_allowance']					+= collect($collection)->sum('payroll_allowance_amount');

				array_push($data['allowance'], $temp_allowance);
			}
		}


		$total_deminimis = collect($data['allowance'])->sum('payroll_allowance_amount') + $adjustment_bonus_total + $adjustment_commission_total + $adjustment_incentives_total + $adjustment_allowance_total;


		/* COMPUTE UNUSED LEAVE  START */
		$data['_total_unused_leave']		= 0;
		$data['_unused_leave']				= array();

		$_leave_avail = Tbl_payroll_process_leave::getleave($employee_id, $payroll_period_company_id)->get();

		foreach($_leave_avail as $avail)
		{
			$tmp_leave['payroll_leave_temp_name'] 	= $avail->payroll_leave_temp_name;
			$tmp_leave['process_leave_quantity']  	= $avail->process_leave_quantity;
			$tmp_leave['process_leave_amount']  	= $avail->process_leave_quantity * $data['daily_rate'];
			// $tmp_leave['process_leave_amount']  	= $data['daily_rate'];

			$data['_total_unused_leave'] += round($tmp_leave['process_leave_amount'], 2);

			array_push($data['_unused_leave'], $tmp_leave);
			
		}

		$total_deminimis	+=  $data['_total_unused_leave'] + $data['leave_amount'];

		/* COMPUTE UNUSED LEAVE END */
		$data['total_deminimis'] = $total_deminimis;

		// $data['total_gross'] += $total_deminimis;

		/* GET SSS CONTRIBUTION */

		$date_period[0] = date('Y-m-01', strtotime($end));
		$date_period[1] = date('Y-m-t', strtotime($end));

		$pevious_record = Tbl_payroll_record::getdate($shop_id, $date_period)
											->where('tbl_payroll_period.payroll_period_category', $payroll_period_category)
											->where('payroll_employee_id',$employee_id)
											->get()
											->toArray();

		// dd($pevious_record);

		
		$previous_tax 			= collect($pevious_record)->sum('tax_contribution');
		$previous_sss 			= collect($pevious_record)->sum('sss_contribution_ee');
		$previous_pagibig 		= collect($pevious_record)->sum('pagibig_contribution');
		$previous_philhealth	= collect($pevious_record)->sum('philhealth_contribution_ee');

		// dd($previous_sss);

		$sss_contribution		= Payroll::sss_contribution($shop_id, $data['salary_sss']);
		$sss_contribution_ee 	= $sss_contribution['ee'];


		if($group->payroll_group_sss == 'Every Period')
		{
			
			$data['sss_contribution_ee'] = divide($sss_contribution_ee, $period_category_arr['count_per_period']);
			$data['sss_contribution_er'] = divide($sss_contribution_ee, $period_category_arr['count_per_period']);
			$data['sss_contribution_ec'] = divide($sss_contribution_ee, $period_category_arr['count_per_period']);
			// dd($data['sss_contribution_ee']);

			if($data['is_deduct_sss_default'] == 0)
			{	
				$data['sss_contribution_ee'] = $data['deduct_sss_custom'];

				if($period_category == 'Last Period')
				{
					$data['sss_contribution_ee'] = $sss_contribution_ee - $previous_sss;
				}
			}	

		}

		else if($group->payroll_group_sss == $period_category)
		{

			if(Payroll::return_ave($period_category) == '1st Period')
			{
				$data['sss_contribution_ee'] = $sss_contribution['ee'];
				$data['sss_contribution_er'] = $sss_contribution['er'];
				$data['sss_contribution_ec'] = $sss_contribution['ec'];
				// dd('1st Period');
			}
			else if(Payroll::return_ave($period_category) == '2nd Period')
			{
				$data['sss_contribution_ee'] = divide($sss_contribution['ee'], $period_category_arr['count_per_period']) * 2;
				$data['sss_contribution_er'] = divide($sss_contribution['er'], $period_category_arr['count_per_period']) * 2;
				$data['sss_contribution_ec'] = divide($sss_contribution['ec'], $period_category_arr['count_per_period']) * 2;
				// dd('2nd Period');
			}

			else if(Payroll::return_ave($period_category) == 'Last Period')
			{
				$data['sss_contribution_ee'] = $sss_contribution['ee'];
				$data['sss_contribution_er'] = $sss_contribution['er'];
				$data['sss_contribution_ec'] = $sss_contribution['ec'];
				// dd('Last Period');
			}	
		}


		// dd($data['sss_contribution_ee']);
		/* GET PHILHEALTH CONTRIBUTION */
		$philhealth_contribution = Payroll::philhealth_contribution($shop_id, $data['salary_philhealth']);

		// dd($group->payroll_group_philhealth);

		if($group->payroll_group_philhealth == 'Every Period')
		{
			// philhealth_contribution
			$philhealth_contribution_ee = $philhealth_contribution['ee'];

			$data['philhealth_contribution_ee'] = divide($philhealth_contribution_ee, $period_category_arr['count_per_period']);
			$data['philhealth_contribution_er'] = divide($philhealth_contribution['er'] , $period_category_arr['count_per_period']);

			if($data['is_deduct_philhealth_default'] == 0)
			{
				$data['philhealth_contribution_ee'] = $data['deduct_philhealth_custom'];
				if($period_category == 'Last Period')
				{
					$data['philhealth_contribution_ee'] = $philhealth_contribution_ee - $previous_philhealth;
				}
			}
		}

		else if($group->payroll_group_philhealth == $period_category)
		{
			if(Payroll::return_ave($period_category) == '1st Period')
			{
				$data['philhealth_contribution_ee'] = $philhealth_contribution['ee'];
				$data['philhealth_contribution_er'] = $philhealth_contribution['er'];
			}
			else if(Payroll::return_ave($period_category) == '2nd Period')
			{
				$data['philhealth_contribution_ee'] = divide($philhealth_contribution['ee'], $period_category_arr['count_per_period']) * 2;
				$data['philhealth_contribution_er'] = divide($philhealth_contribution['er'], $period_category_arr['count_per_period']) * 2;
			}

			else if(Payroll::return_ave($period_category) == 'Last Period')
			{
				$data['philhealth_contribution_ee'] = $philhealth_contribution['ee'];
				$data['philhealth_contribution_er'] = $philhealth_contribution['er'];
			}
		}


		// GET PAGIBIG CONTRIBUTION
		$pagibig_contribution = Payroll::pagibig_contribution($shop_id, $data['salary_pagibig']);
		if($group->payroll_group_pagibig == 'Every Period')
		{
			// philhealth_contribution
			$data['pagibig_contribution'] = divide($pagibig_contribution, $period_category_arr['count_per_period']);

			if($data['is_deduct_pagibig_default'] == 0)
			{
				$data['pagibig_contribution'] = $data['deduct_pagibig_custom'];

				if($period_category == 'Last Period')
				{
					$data['pagibig_contribution'] = $pagibig_contribution - $previous_pagibig;
				}
			}

		}



		else if($group->payroll_group_pagibig == $period_category)
		{

			if(Payroll::return_ave($period_category) == '1st Period')
			{
				$data['pagibig_contribution'] = $pagibig_contribution;
				
			}
			else if(Payroll::return_ave($period_category) == '2nd Period')
			{
				$data['pagibig_contribution'] = divide($pagibig_contribution, $period_category_arr['count_per_period']) * 2;
			}

			else if(Payroll::return_ave($period_category) == 'Last Period')
			{
				$data['pagibig_contribution'] = $pagibig_contribution;
			}
		}

		/* GET TAX CONTRIBUTION */
		if($data['minimum_wage'] == 0)
		{
			if($group->payroll_group_tax == 'Every Period' || $group->payroll_group_tax == $period_category)
			{
				
				$salary_taxable = $data['salary_taxable'];


				if($group->payroll_group_before_tax == 0)
				{
					$salary_taxable = $data['salary_taxable'] - ($data['sss_contribution_ee'] + $data['philhealth_contribution_ee'] + $data['pagibig_contribution']);
				}
				
				if($salary_taxable <= 0)
				{
					$salary_taxable = 0;
				}

				$data['tax_contribution'] = divide(Payroll::tax_contribution($shop_id, $salary_taxable, $data['tax_status'], $payroll_period_category), $period_category_arr['count_per_period']);

				// dd($salary_taxable);

				if($group->payroll_group_tax == 'Last Period')
				{
					$data['tax_contribution'] = $data['tax_contribution'] * $period_category_arr['count_per_period'];
				}	
			}
			
		}

		if($group->payroll_group_agency == Payroll::return_ave($period_category))
		{
			$data['agency_deduction'] = $group->payroll_group_agency_fee;
		}

		$data['total_deduction']	+= $data['tax_contribution'];
		$data['total_deduction']	+= $data['sss_contribution_ee'];
		$data['total_deduction']	+= $data['pagibig_contribution'];
		$data['total_deduction']	+= $data['philhealth_contribution_ee'];

		// dd((p$data['philhealth_contribution_ee']) );

		$data['total_deduction']	+= $data['absent_deduction'];
		$data['total_deduction']	+= $data['late_deduction'];
		$data['total_deduction']	+= $data['under_time'];
		$data['total_deduction']	+= $data['agency_deduction'];
		$data['total_deduction']	+= $data['break_deduction'];

		if($data['total_deduction'] > $data['total_gross'])
		{
			$data['total_deduction']			= 0;
			$data['tax_contribution'] 			= 0;
			$data['sss_contribution_ee'] 		= 0;
			$data['pagibig_contribution'] 		= 0;
			$data['philhealth_contribution_ee'] = 0;
			$data['late_deduction'] 			= 0;
			$data['under_time'] 				= 0;
			$data['agency_deduction'] 			= 0;
		}

		// DEDUCTION START [LOANS, CASH ADVANCE, CASH BOND AND OTHER DEDUCTION]
		$deduction = Payroll::getdeduction($employee_id, $date,$period_category, $payroll_period_category, $shop_id);

		$data['deduction'] 			= $deduction['deduction'];
		$data['total_deduction'] 	+= $deduction['total_deduction'] + $adjustment_deductions_total;

		$data['total_net'] 					= ($data['total_gross'] - $data['total_deduction']) + $total_deminimis + $data['13_month'] + $adjustment_13_month_total;



		$data['total_gross'] 				+=  $total_deminimis + $data['13_month'] + $adjustment_13_month_total;
		
		$data['total_regular_days']			= round($data['total_regular_days'], 2);
		$data['total_rest_days']			= round($data['total_rest_days'], 2);
		$data['total_extra_days']			= round($data['total_extra_days'], 2);
		$data['total_rh']					= round($data['total_rh'], 2);
		$data['total_sh']					= round($data['total_sh'], 2);

		// leave_count_w_pay
		// leave_count_wo_pay

		$data['total_worked_days'] = $data['total_regular_days'] + $data['total_rest_days'] + $data['total_extra_days'] + $data['total_rh'] + $data['total_sh'] + $data['leave_count_w_pay'] + $data['leave_count_wo_pay'];

		
		return $data;
	}


	public static function compute_13_month($employee_id = 0, $payroll_period_company_id = 0)
	{
		$data['id'] = array();
		$_13m = Tbl_payroll_13_month_compute::get13m($employee_id, $payroll_period_company_id)->get()->toArray();

		$data_13m = 0;
		foreach($_13m as $m13)
		{
			$data_13m += ($m13['regular_salary'] / 12);
			array_push($data['id'], $m13['payroll_record_id']);
		}


		$data['data_13m'] = $data_13m;

		return $data;
	}

	public static function getperiodcount($shop_id = 0, $date = '0000-00-00', $payroll_period_category = '', $start = '0000-00-00')
	{
		$strtotime 		= strtotime($date);

		$datearr[0] 	= date('Y-m-01', $strtotime);
		$datearr[1] 	= date('Y-m-t', $strtotime);
		$date_end_count = date('t', $strtotime);
		$date_day		= date('D', $strtotime);
		$date_month 	= date('m', $strtotime);
		$date_year 		= date('Y', $strtotime);

		$current_month 	= date('m', $strtotime);

		$current_count 	= 0;

		// dd($date_end_count);

		$period_category = 'None';
		$count_per_period = 0;

		$_period = Tbl_payroll_period::sel($shop_id, 0)
									->where('payroll_period_category', $payroll_period_category)
									->whereBetween('payroll_period_end', $datearr)
									->orderBy('payroll_period_end')
									->get()
									->toArray();

		$period_count = count($_period);
		$index_count = 1;

		foreach($_period as $period)
		{
			if($period['payroll_period_end'] == $date)
			{
				break;
			}
			$index_count++;
		}

		if($payroll_period_category == 'Weekly')
		{
			$next_month 	= date('m', strtotime("+7 day", $strtotime));

			if($index_count  == 1)
			{
				$period_category = 'First Period';
			}
			else if($index_count == 2)
			{
				$period_category = 'Second Period';
			}
			else if($next_month != $current_month)
			{
				$period_category = 'Last Period';
			}

			/* get total number of weeks per month */
			for($i = 1; $i <= $date_end_count; $i++)
			{

				$istr = $i;
				if($i <= 9)
				{
					$istr = '0'.$i;
				}

				if($date_day == date('D', strtotime($date_year.'-'.$date_month.'-'.$istr)))
				{
					$count_per_period++;
				}
			}

		}
		else if($payroll_period_category == 'Semi-monthly')
		{
			$next_month 	= date('m', strtotime("+15 day", $strtotime));

			// dd($next_month);

			if($index_count == 1)
			{
				$period_category = 'First Period';
			}
			if($next_month != $current_month)
			{
				// dd($current_month);
				$period_category = 'Last Period';
			}

			$count_per_period = 2;
		}
		else if($payroll_period_category == 'Monthly')
		{
			$period_category 	= 'Last Period';
			$count_per_period 	= 1;
			$current_count		= 1;
		}

		$data['period_category']  	= $period_category;
		$data['period_count']	  	= $period_count;
		$data['count_per_period'] 	= $count_per_period;
		$data['current_count']		= $current_count;

		return $data;

	}

	public static function return_ave($str = '')
	{
		if($str == 'First Period')
		{
			$str = '1st Period';
		}
		else if($str == 'Second Period')
		{
			$str = '2nd Period';
		}
		return $str;
	}

	/* PUSH TO ARRAY ALLOWANCES */
	public static function push_allowance($allowance_data = array(), $allowance = array(), $payroll_period_category = '', $period_category = '', $day_count = 1)
	{
		$temp_allowance['payroll_allowance_id']		= $allowance->payroll_allowance_id;
		$temp_allowance['payroll_allowance_name'] 	= $allowance->payroll_allowance_name;
		$temp_allowance['payroll_allowance_amount'] = $allowance->payroll_allowance_amount;

		if($allowance->payroll_allowance_category == 'daily')
		{
			$temp_allowance['payroll_allowance_amount'] = $allowance->payroll_allowance_amount * $day_count;
		}

		// dd($allowance->payroll_allowance_add_period);

		if($payroll_period_category == 'Weekly')
		{
			if($period_category == $allowance->payroll_allowance_add_period)
			{
				array_push($allowance_data, $temp_allowance);
			}

			if($allowance->payroll_allowance_add_period == 'Every Period')
			{
				array_push($allowance_data, $temp_allowance);
			}
		}
		else if($payroll_period_category == 'Semi-monthly')
		{
			if(($period_category == 'First Period') && ($allowance->payroll_allowance_add_period == 'First Period'))
			{
				array_push($allowance_data, $temp_allowance);
			}
			else if((($period_category == 'Second Period') && ($allowance->payroll_allowance_add_period == 'Second Period')) || (($period_category == 'Last Period') && ($allowance->payroll_allowance_add_period == 'Last Period')))
			{
				array_push($allowance_data, $temp_allowance);
			}	

			else if($allowance->payroll_allowance_add_period == 'Every Period')
			{
				array_push($allowance_data, $temp_allowance);
			}
		}

		else if($payroll_period_category == 'Monthly')
		{
			if($period_category != 'None')
			{
				array_push($allowance_data, $temp_allowance);
			}
		}


		// dd($allowance_data);
		return $allowance_data;

	}

	public static function compute_overtime($query = array(), $hours = array(), $rate = 0, $day_type = 'Regular', $cola = 0)
	{
		// dd($rate);
		$param = Tbl_payroll_overtime_rate::getrate($query['payroll_group_id'], $query['payroll_overtime_name'])->first();

		$regular 			= 0;
		$late_overtime 		= 0;
		$early_overtime 	= 0;
		$night_differential = 0;
		$cola_var 			= 0;


		if($day_type == 'Regular' || $day_type == 'Extra' || $day_type == 'Legal Holiday' || $day_type == 'Special Holiday')
		{
			$regular 			= $param->payroll_overtime_regular;
			$late_overtime 		= $param->payroll_overtime_overtime;
			$early_overtime 	= $param->payroll_overtime_overtime;
			$night_differential = $param->payroll_overtime_nigth_diff;
			$cola_var			= $regular;
			// if($regular == 0)
			// {
			// 	$cola_var = 1;
			// }
		}

		else if($day_type == 'Rest Regular' || $day_type == 'Rest Legal Holiday' || $day_type == 'Rest Special Holiday')
		{
			$regular 			= $param->payroll_overtime_rest_day;
			$late_overtime 		= $param->payroll_overtime_rest_overtime;
			$early_overtime 	= $param->payroll_overtime_rest_overtime;
			$night_differential = $param->payroll_overtime_rest_night;
			$cola_var			= $regular;
		}

		$cola_var 			= $cola_var * $cola;
		// $regular 			= $regular * $rate;
		

		if($hours['regular'] <= 0)
		{
			$regular = 0;
		}
		if($hours['late_overtime'] <= 0)
		{
			$late_overtime = 0;
		}
		if($hours['early_overtime'] <= 0)
		{
			$early_overtime = 0;
		}
		if($hours['night_differential'] <= 0)
		{
			$night_differential = 0;
		}	

		$temp_overtime 	= $hours['late_overtime'] * $rate;
		$temp_earlyOT 	= $hours['early_overtime'] * $rate;
		$temp_night		= $hours['night_differential'] * $rate;
		$temp_regular	= $hours['regular'] * $rate;

		$data['regular']			= round(($temp_regular + ( $temp_regular * $regular )), 2);
		$data['late_overtime']		= round(($temp_overtime + ($temp_overtime * $late_overtime)), 2);
		$data['early_overtime']		= round(($temp_earlyOT + ($temp_earlyOT * $early_overtime)), 2);
		$data['night_differential']	= round(($night_differential + ($temp_night * $night_differential)), 2);

		$tota = 0;

		$total = $data['regular'] + $data['late_overtime'] + $data['early_overtime'] + $data['night_differential'];

		$data['cola'] 				= round(($cola_var + ($cola * $hours['regular'])), 2);

		if($total <= 0)
		{
			
			$data['cola'] = 0;
		}

		return $data;
		
	}
	

	/* GET TAX VALUE */
	public static function tax_contribution($shop_id = 0, $rate = 0, $tax_category = '', $payroll_tax_period = '')
	{
		// $tax_category = "Z"; //static base for now
		
		$tax 		= Tbl_payroll_tax_reference::sel($shop_id, $tax_category, $payroll_tax_period)->first();
		$exemption 	= Tbl_payroll_tax_reference::sel($shop_id, 'Excemption', $payroll_tax_period)->first();
		$status 	= Tbl_payroll_tax_reference::sel($shop_id, 'Status', $payroll_tax_period)->first();
		$tax_index 	= '';
		$tax_contribution = 0;
		// dd($tax);
		// if($rate >= $tax->tax_first_range && $rate < $tax->tax_second_range)
		if($tax != null)
		{
			if($tax->tax_first_range <= $rate && $tax->tax_second_range > $rate)
			{
				$tax_index = 'tax_first_range';
			}

			if($tax->tax_second_range <= $rate && $tax->tax_third_range > $rate)
			{
				$tax_index = 'tax_second_range';
			}

			if($tax->tax_second_range <= $rate && $tax->tax_third_range > $rate)
			{
				$tax_index = 'tax_second_range';
			}

			if($tax->tax_third_range <= $rate && $tax->tax_fourth_range > $rate)
			{
				$tax_index = 'tax_third_range';
			}

			if($tax->tax_fourth_range <= $rate && $tax->tax_fifth_range > $rate)
			{
				$tax_index = 'tax_fourth_range';
			}

			
			if($tax->tax_fifth_range <= $rate && $tax->taxt_sixth_range > $rate)
			{
				$tax_index = 'tax_fifth_range';
			}


			if($rate >= $tax->taxt_sixth_range &&  $rate < $tax->tax_seventh_range)
			{
				$tax_index = 'taxt_sixth_range';
			}

			// dd($tax_index);
			if($tax_index != '')
			{
				$exemption_num = $exemption->$tax_index;
				$status_num = $status->$tax_index;
				// dd($status_num);
				// dd("((" . $rate . " - " . $tax->$tax_index . ") * (" . $status_num . " / 100" . ")) " . " + " . $exemption_num . ")");
				
				$tax_contribution = (($rate - $tax->$tax_index) * ($status_num / 100)) + $exemption_num;
			}
		}
		else
		{
			dd("NOT FOUND ON TAX TABLE. PLEASE CONTACT ADMINISTRATOR.");
		}
		
		return round($tax_contribution, 2);
	}

	/* GET SSS VALUE */
	public static function sss_contribution($shop_id = 0, $rate = 0)
	{
		$data['ee'] = 0;
		$data['er'] = 0;
		$data['ec'] = 0;

		if($rate > 0)
		{
			$max_sss = Tbl_payroll_sss::where('shop_id', $shop_id)->orderBy('payroll_sss_min','desc')->first();
			$sss = Tbl_payroll_sss::where('shop_id', $shop_id)->where('payroll_sss_min','<=',$rate)->where('payroll_sss_max','>=',$rate)->first();
			// dd($max_sss);
			// dd($sss);
			if($sss == null)
			{	
				if($rate >= $max_sss->payroll_sss_min)
				{
					$data['ee'] = $max_sss->payroll_sss_ee;
					$data['er'] = $max_sss->payroll_sss_er;
					$data['ec'] = $max_sss->payroll_sss_eec;

				}
			}
			else if($sss != null)
			{
				$data['ee'] = $sss->payroll_sss_ee;
				$data['er'] = $sss->payroll_sss_er;
				$data['ec'] = $sss->payroll_sss_eec;
			}
		}

		// dd($data);

		return $data;
	}

	/* GET PHILHEALTH VALUE */
	public static function philhealth_contribution($shop_id = 0, $rate = 0)
	{
		$data['ee'] = 0;
		$data['er'] = 0;
		if($rate > 0)
		{
			$_philhealth_max = Tbl_payroll_philhealth::where('shop_id', $shop_id)->orderBy('payroll_philhealth_min','desc')->first();

			$philhealth = Tbl_payroll_philhealth::where('shop_id', $shop_id)
												->where('payroll_philhealth_min','<=', $rate)
												->where('payroll_philhealth_max','>=', $rate)
												->first();


			if($philhealth != null)
			{
				$data['ee'] = $philhealth->payroll_philhealth_ee_share;
				$data['er'] = $philhealth->payroll_philhealth_er_share;

			}
			else if($_philhealth_max->payroll_philhealth_min >= $rate)
			{	
				$data['ee'] = $_philhealth_max->payroll_philhealth_ee_share;
				$data['er'] = $_philhealth_max->payroll_philhealth_er_share;
			}
			else
			{
				$data['ee'] = $_philhealth_max->payroll_philhealth_ee_share;
				$data['er'] = $_philhealth_max->payroll_philhealth_er_share;
			}
		}
		

		return $data;
	}

	public static function pagibig_contribution($shop_id = 0, $rate)
	{
		$data = 0;
		$pagibig = Tbl_payroll_pagibig::where('shop_id', $shop_id)->first();
		
		if($pagibig != null)
		{
			$data = $rate;
		}

		return round($data, 2);
	}


	public static function getdeduction($employee_id = 0, $date = '0000-00-00', $period = '', $payroll_period_category = '', $shop_id = 0)
	{
		$month[0] = date('Y-m-01', strtotime($date));
		$month[1] = date('Y-m-t', strtotime($date));

		$_deduction = Tbl_payroll_deduction_employee::getdeduction($employee_id, $date, $period, $month)->get();
		
		$payroll_record_id = Tbl_payroll_record::getperiod($shop_id, $payroll_period_category)->pluck('payroll_record_id');

		$data['deduction'] 			= array();
		$data['total_deduction'] 	= 0;

		foreach($_deduction as $deduction)
		{
			if ($deduction->payroll_deduction_archived != 1) 
			{
				$temp['deduction_name'] 		= $deduction->payroll_deduction_name;
				$temp['deduction_category'] 	= $deduction->payroll_deduction_category;
				$temp['payroll_deduction_id'] 	= $deduction->payroll_deduction_id;
				$temp['payroll_periodal_deduction'] = $deduction->payroll_periodal_deduction;
				/* get total payment deduction per month */
				$total_payment = Tbl_payroll_deduction_payment::getpayment($employee_id, $payroll_record_id, $deduction->payroll_deduction_id)->select(DB::raw('IFNULL(sum(payroll_payment_amount), 0) as total_payment'))->value('total_payment');

				if($temp == 'Last Period')
				{
					$temp['payroll_periodal_deduction'] = $deduction->payroll_monthly_amortization - $total_payment;
				}
				$data['total_deduction'] += $temp['payroll_periodal_deduction'];
				array_push($data['deduction'], $temp);
			}
		}
		
		return $data;
	}


	public static function getdeductionv2($employee_id = 0, $date = '0000-00-00', $period = '', $payroll_period_category = '', $shop_id = 0)
	{
		$month[0] = date('Y-m-01', strtotime($date));
		$month[1] = date('Y-m-t', strtotime($date));
		
		$_deduction = Tbl_payroll_deduction_employee_v2::getdeduction($employee_id, $date, $period, $month)->get();
		$payroll_record_id = Tbl_payroll_record::getperiod($shop_id, $payroll_period_category)->pluck('payroll_record_id');

		$data['deduction'] 			= array();
		$data['total_deduction'] 	= 0;
		
		foreach($_deduction as $deduction)
		{
			$temp['deduction_name'] 			= $deduction->payroll_deduction_name;
			$temp['deduction_category'] 		= $deduction->payroll_deduction_category;
			$temp['payroll_deduction_id'] 		= $deduction->payroll_deduction_id;
			$temp['payroll_periodal_deduction'] = $deduction->payroll_periodal_deduction;
			$temp['payroll_deduction_type']		= $deduction->payroll_deduction_type;

			$payroll_total_payment_amount = Tbl_payroll_deduction_payment_v2::gettotaldeductionpayment($employee_id, $temp['payroll_deduction_id'], $temp['deduction_name'])->first();
			
			$payroll_month_payment_amount = Tbl_payroll_deduction_payment_v2::getmonthdeductionpayment($employee_id, $temp['payroll_deduction_id'], $temp['deduction_name'], $month)->first();
						
			/*Check Total payment of the month and if total payment and deduction is greater than monthly amortization*/
			if (($payroll_month_payment_amount["total_payment"] + $deduction->payroll_periodal_deduction) > $deduction->payroll_monthly_amortization) 
			{
				$temp['payroll_periodal_deduction'] = $deduction->payroll_monthly_amortization - $payroll_month_payment_amount["total_payment"];
			}

			if($temp == 'Last Period')
			{
				$temp['payroll_periodal_deduction'] = $deduction->payroll_monthly_amortization - $payroll_total_payment_amount["total_payment"];
			}

			if ($period == "Last Period") 
			{
				if (($temp["payroll_periodal_deduction"] + $payroll_month_payment_amount["total_payment"]) <=  $deduction->payroll_monthly_amortization) 
				{
					$temp['payroll_periodal_deduction'] = $deduction->payroll_monthly_amortization - $payroll_month_payment_amount["total_payment"];
				}
			}
			
			if ($payroll_total_payment_amount["total_payment"] < $deduction->payroll_deduction_amount) 
			{
				$data['total_deduction'] += $temp['payroll_periodal_deduction'];

				if ($temp['payroll_periodal_deduction'] > 0) 
				{
					array_push($data['deduction'], $temp);
				}
			}
		}
		// dd($data);
		return $data;
	}

	public static function getrecord_breakdown($record = array())
	{
		$data = $record->toArray();
		// dd($data);

		$employee_id 				= $data['payroll_employee_id'];
		$payroll_period_company_id 	= $data['payroll_period_company_id'];

		$_deduction = Tbl_payroll_deduction_payment::where('payroll_record_id', $data['payroll_record_id'])
												  ->orderBy('deduction_name')
												  ->get();

		$_allowance = Tbl_payroll_allowance_record::where('payroll_record_id', $data['payroll_record_id'])
												 ->orderBy('payroll_allowance_name')
												 ->get();

		$group = Tbl_payroll_employee_contract::selemployee($employee_id)->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')->first();

		$data['allowance']					= array();
		$data['deduction']					= array();

		$data['_total_unused_leave']		= 0;
		$data['_unused_leave']				= array();

		$data['display_monthly_rate']		= 1;
		$data['display_daily_rate']			= 1;

		if($group != null)
		{
			$data['display_monthly_rate']		= $group->display_monthly_rate;
			$data['display_daily_rate']			= $group->display_daily_rate;
		}

		$total_allowance = 0;
		$total_deduction = 0;

		foreach ($_allowance as $key => $allowance) {
			$temp_allowance['payroll_allowance_id'] 	= $allowance->payroll_allowance_id;
			$temp_allowance['payroll_allowance_name'] 	= $allowance->payroll_allowance_name;
			$temp_allowance['payroll_allowance_amount'] = $allowance->payroll_record_allowance_amount;

			$total_allowance += $allowance->payroll_record_allowance_amount;

			array_push($data['allowance'], $temp_allowance);	
		}

		foreach($_deduction as $deduction)
		{
			$temp_deduction['deduction_name'] 				= $deduction->deduction_name;
			$temp_deduction['deduction_category'] 			= $deduction->deduction_category;
			$temp_deduction['payroll_deduction_id'] 		= $deduction->payroll_deduction_id;
			$temp_deduction['payroll_periodal_deduction'] 	= $deduction->payroll_payment_amount;

			$total_deduction += $deduction->payroll_payment_amount;

			array_push($data['deduction'], $temp_deduction);
		}

		$adjustment_allowance = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Allowance')->get();

		$adjustment_allowance_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Allowance')->sum('payroll_adjustment_amount');

		$adjustment_bonus = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Bonus')->get();

		$adjustment_bonus_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Bonus')->sum('payroll_adjustment_amount');
		
		/* commission */
		$adjustment_commission = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Commissions')->get();

		$adjustment_commission_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Commissions')->sum('payroll_adjustment_amount');

		/* incentives */
		$adjustment_incentives = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Incentives')->get();

		$adjustment_incentives_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Incentives')->sum('payroll_adjustment_amount');

		/* 13 month pay */
		$adjustment_13_month = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, '13 month pay')->get();

		$adjustment_13_month_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , '13 month pay')->sum('payroll_adjustment_amount');

		/* deductions */
		$adjustment_deductions = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id, 'Deductions')->get();

		$adjustment_deductions_total = Tbl_payroll_adjustment::getadjustment($employee_id, $payroll_period_company_id , 'Deductions')->sum('payroll_adjustment_amount');


		$data['adjustment']['allowance'] 			= $adjustment_allowance;
		$data['adjustment']['total_allowance'] 		= $adjustment_allowance_total;

		$data['adjustment']['bonus'] 				= $adjustment_bonus;
		$data['adjustment']['total_bonus'] 			= $adjustment_bonus_total;

		$data['adjustment']['incentives'] 			= $adjustment_incentives;
		$data['adjustment']['total_incentives'] 	= $adjustment_incentives_total;

		$data['adjustment']['commission'] 			= $adjustment_commission;
		$data['adjustment']['total_commission'] 	= $adjustment_commission_total;

		$data['adjustment']['13_month'] 			= $adjustment_13_month;
		$data['adjustment']['total_13_month'] 		= $adjustment_13_month_total;

		$data['adjustment']['deductions'] 			= $adjustment_deductions;
		$data['adjustment']['total_deductions'] 	= $adjustment_deductions_total;

		// $total_contribution 				= $data['']
		$data['total_gross'] = 0;
		$data['total_gross'] += ($data['extra_salary'] + $data['extra_early_overtime'] + $data['extra_reg_overtime'] + $data['extra_night_diff'] + $data['regular_salary'] + $data['regular_early_overtime'] + $data['regular_reg_overtime'] + $data['regular_night_diff'] + $data['rest_day_salary'] + $data['rest_day_early_overtime'] + $data['rest_day_reg_overtime'] + $data['rest_day_night_diff'] + $data['rest_day_sh'] + $data['rest_day_sh_early_overtime'] + $data['rest_day_sh_reg_overtime'] + $data['rest_day_sh_night_diff'] + $data['rest_day_rh'] + $data['rest_day_rh_early_overtime'] + $data['rest_day_rh_reg_overtime'] + $data['rest_day_rh_night_diff'] + $data['rh_salary'] + $data['rh_early_overtime'] + $data['rh_reg_overtime'] + $data['rh_night_diff'] + $data['sh_salary'] + $data['sh_early_overtime'] + $data['sh_reg_overtime'] + $data['sh_night_diff']) + $adjustment_allowance_total + $adjustment_incentives_total + $adjustment_commission_total + $adjustment_13_month_total;


		/* COMPUTE UNUSED LEAVE  START */
		$data['_total_unused_leave']		= 0;
		$data['_unused_leave']				= array();

		$_leave_avail = Tbl_payroll_process_leave::getleave($employee_id, $payroll_period_company_id)->get();

		// dd($payroll_period_company_id);

		foreach($_leave_avail as $avail)
		{
			$tmp_leave['payroll_leave_temp_name'] 	= $avail->payroll_leave_temp_name;
			$tmp_leave['process_leave_quantity']  	= $avail->process_leave_quantity;
			$tmp_leave['process_leave_amount']  	= $avail->process_leave_quantity * $data['salary_daily'];

			$data['_total_unused_leave'] += round($tmp_leave['process_leave_amount'], 2);

			array_push($data['_unused_leave'], $tmp_leave);
			
		}

		/* for leave */
		// $data['leave_amount'] = 0;

		/* for absents */
		// $data['absent_deduction'] = 0;
		// $total_deminimis	=  $data['_total_unused_leave'] + $data['payroll_cola'] + $data['13_month'] + $total_allowance + $adjustment_bonus_total + $adjustment_commission_total + $adjustment_incentives_total + $data['leave_amount'];

		$total_deminimis	=  $data['_total_unused_leave'] + $data['payroll_cola'] + $data['13_month'] + $total_allowance + $adjustment_bonus_total + $adjustment_commission_total + $adjustment_incentives_total + $data['leave_amount'] + $adjustment_13_month_total;


		/* COMPUTE UNUSED LEAVE END */

		// $data['total_deminimis'] = $total_allowance + $adjustment_bonus_total + $adjustment_commission_total + $adjustment_incentives_total;
		$data['total_deminimis'] = $total_deminimis;


		$total_deduction += $data['tax_contribution'] + $data['sss_contribution_ee'] + $data['philhealth_contribution_ee'] + $data['pagibig_contribution'] + $data['late_deduction'] + $data['under_time'] + $data['agency_deduction'] + $data['adjustment']['total_deductions'] + $data['absent_deduction'];

		if(isset($data['payroll_under_time_deduction']))
		{
			$total_deduction += $data['payroll_under_time_deduction'];
		}


		// dd($total_deminimis);

		$data['total_net']					= ($data['total_gross'] + $total_deminimis) - $total_deduction;
		$data['total_gross']				+= $data['total_deminimis'];
		$data['total_deduction']			= $total_deduction;
		$data['total_allowance']			= $total_allowance;
		return $data;
	}

	public static function record_by_date($shop_id = 0, $date_start = '0000-00-00', $date_end = '0000-00-00')
	{
		$date[0] = date('Y-m-d',strtotime($date_start));
		$date[1] = date('Y-m-d',strtotime($date_end));
		$_record = Tbl_payroll_record::getdate($shop_id, $date)->get();

		$data = array();

		foreach($_record as $record)
		{
			
			// array_push($data, Payroll::getrecord_breakdown($record));

			$record = Payroll::getrecord_breakdown($record);
			
			$employee_details = Tbl_payroll_employee_basic::where('payroll_employee_id', $record['payroll_employee_id'])->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')->first();



			$temp['payroll_employee_id'] 	= $record['payroll_employee_id'];
			$temp['employee_name'] 			= $employee_details->payroll_employee_title_name.' '.$employee_details->payroll_employee_first_name.' '.$employee_details->payroll_employee_middle_name.' '.$employee_details->payroll_employee_last_name.' '.$employee_details->payroll_employee_suffix_name;
			$temp['employee_name']			= $employee_details->payroll_employee_display_name;
			$temp['payroll_company_id']		= $employee_details->payroll_company_id;
			$temp['payroll_company_name']	= $employee_details->payroll_company_name;
			$temp['13 Month Pay'] 			= $record['13_month'];
			$temp['Basic Salary Pay'] 		= $record['regular_salary'];
			$temp['Early Over Time Pay'] 	= $record['regular_early_overtime'] + $record['extra_early_overtime'] + $record['rest_day_early_overtime'] + $record['rest_day_sh_early_overtime'] + $record['rest_day_rh_early_overtime'] + $record['rh_early_overtime'] + $record['sh_early_overtime'];
			$temp['Extra Day Pay'] 			= $record['extra_salary'];
			$temp['Leave With Pay'] 		= 0;
			$temp['Night Differential Pay'] = $record['regular_night_diff'] + $record['extra_night_diff'] + $record['rest_day_night_diff'] + $record['rest_day_sh_night_diff'] + $record['rest_day_rh_night_diff'] + $record['rh_night_diff'] + $record['sh_night_diff'];
			$temp['Regular Holiday Pay']	= $record['rh_salary'];
			$temp['Regular Over Time Pay']	= $record['regular_reg_overtime'] + $record['extra_reg_overtime'] + $record['rest_day_reg_overtime'] + $record['rest_day_sh_reg_overtime'] + $record['rest_day_rh_reg_overtime'] + $record['rh_reg_overtime'] + $record['sh_reg_overtime'];
			$temp['Rest Day Pay']			= $record['rest_day_salary'];
			$temp['COLA']					= $record['payroll_cola'];
			$temp['Special Holiday Pay']	= $record['sh_salary'];

			$allowance 						= 0;
			foreach($record['allowance'] as $all)
			{
				$allowance += $all['payroll_allowance_amount'];
			}

			$temp['Bonus Pay']			= n2z($record['adjustment']['total_bonus']);
			$temp['Commission Pay']		= n2z($record['adjustment']['total_commission']);
			$temp['Incentive Pay']		= n2z($record['adjustment']['total_incentives']);
			$temp['Pagibig']			= $record['pagibig_contribution'];
			$temp['Philhealth EE']		= $record['philhealth_contribution_ee'];
			$temp['Philhealth ER']		= $record['philhealth_contribution_er'];
			$temp['SSS EC']				= $record['sss_contribution_ec'];
			$temp['SSS EE']				= $record['sss_contribution_ee'];
			$temp['SSS ER']				= $record['sss_contribution_er'];
			$temp['Tax']				= $record['tax_contribution'];

			$deduction 					= collect($record['deduction']);

			// dd($deduction);
			$temp['Cash Advance']		= n2z($deduction->where('deduction_category','Cash Advance')->sum('payroll_periodal_deduction'));

			$temp['Cash Bond']			= n2z($deduction->where('deduction_category','Cash Bond')->sum('payroll_periodal_deduction'));

			$temp['Loans']				= n2z($deduction->where('deduction_category','Loans')->sum('payroll_periodal_deduction'));

			$temp['Loans']				= n2z($deduction->where('deduction_category','Other Deduction')->sum('payroll_periodal_deduction'));

			$temp['Late']				= $record['late_deduction'];
			$temp['Absent']				= 0;
			$temp['Under Time']			= $record['under_time'];

			array_push($data, $temp);
		}
		
		// dd(collect($data)->groupBy());
		return $data;
	}


	/* check if employee has schedule leave */
	public static function check_if_employee_leave($employee_id = 0, $date = '0000-00-00')
	{
		$data = 'no_leave';

		$leave_count = Tbl_payroll_leave_schedule::checkemployee($employee_id, $date)->count();
		if($leave_count > 0)
		{
			$leave = Tbl_payroll_leave_schedule::checkemployee($employee_id, $date)->first();

			$data = 'without_pay';
			
			if($leave->payroll_leave_temp_with_pay == 1)
			{
				$data = 'with_pay';
			}
		}
		return $data;
	}	


	public static function view_remarks($shop_id = 0, $payroll_period_company_id = 0)
	{
		$data = array();
		$_remaks = Tbl_payroll_remarks::getremarks($shop_id, $payroll_period_company_id)->get();

		foreach($_remaks as $remarks)
		{
			$message = $remarks->payroll_remarks;

			if($remarks->payroll_type == 'file')
			{
				$message = '<a href="'.$remarks->payroll_remarks.'">'.$remarks->file_name.'</a>';
			}

			$temp['message'] = $message;
			$temp['date']	 = $remarks->payroll_remarks_date;
			$temp['user']	 = $remarks->user_first_name;

			array_push($data, $temp);
		}

		return $data;
	}

	public static function insert_remarks($insert = array())
	{
		if(!empty($insert))
		{
			Tbl_payroll_remarks::insert($insert);
		}
	}
	
	public static function get_month()
	{
		$data = array();
		for($i = 1; $i <= 12; $i++)
		{
			$month = date('F', strtotime(date('Y').'-'.$i.'-01'));
			array_push($data, $month);
		}
		return $data;
	}


	/*public static function payslip_template($id)
	{
		$payslip  = Tbl_payroll_payslip::payslip(Self::shop_id())->first();

          if(empty($payslip))
          {
               $payslip  = Tbl_payroll_payslip::payslip(Self::shop_id(), 0)->first();
          }
          //dd($payslip);

          $data['logo_position']   = '';
          $data['logo']            = false;
          $data['colspan']         = 1;

          if($payslip->company_position == '.company-logo-center' || $payslip->company_position == '.company-center')
          {
               $data['logo_position'] = 'text-center';
          }

          if($payslip->company_position == '.company-logo-left' || $payslip->company_position == '.company-left')
          {
               $data['logo_position'] = 'text-left';
          }

          if($payslip->company_position == '.company-logo-right' || $payslip->company_position == '.company-right')
          {
               $data['logo_position'] = 'text-right';
          }

          if($payslip->company_position == '.company-logo-center' || $payslip->company_position == '.company-logo-left' || $payslip->company_position == '.company-logo-right')
          {
               $data['logo']          = true;
          }

          

          if($payslip->include_time_summary == 1)
          {
               $data['colspan']         = 2;
          }

          

          $data['payslip']  = $payslip;
          $data['_record']  = array();
          $period = Tbl_payroll_period_company::getcompanyperiod($id)->first();

          $_record = Tbl_payroll_record::getcompanyrecord($id)
                                        ->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
                                        ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                                        ->get();
          
          $_record2  = Tbl_payroll_time_keeping_approved::Basic()->where('payroll_period_company_id', $id)
                                        ->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
                                        ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                                        ->get();
          //dd($_record2);                                   

          // dd($period);
          foreach($_record2 as $record)
          {

               //$compute = Payroll::getrecord_breakdown($record);
               $temp['period'] = date('M d, Y', strtotime($period->payroll_period_start)).' to '.date('M d, Y', strtotime($period->payroll_period_end));
               //$temp['break'] = Self::breakdown_uncompute($compute,'approved');
               $temp['display_name']    = $record->payroll_employee_display_name;
               $temp['company_name']    = $record->payroll_company_name;
               $temp['company_address'] = $record->payroll_company_address;
               $temp['company_logo']    = $record->payroll_company_logo;
               $temp['emp']             = Tbl_payroll_employee_contract::selemployee($record->payroll_employee_id, $period->payroll_period_start)
                                                            ->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_employee_contract.payroll_department_id')
                                                            ->leftjoin('tbl_payroll_jobtitle','tbl_payroll_jobtitle.payroll_jobtitle_id','=','tbl_payroll_employee_contract.payroll_jobtitle_id')
                                                            ->first();
               $temp['_record']          = Tbl_payroll_time_keeping_approved::Basic()
                                             ->where('payroll_period_company_id', $id)
                                             ->where('employee_id', $record->employee_id)
                                             ->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
                                             ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                                             ->get();

               $temp['_ptkab']          = Tbl_payroll_time_keeping_approved_breakdown::where('time_keeping_approve_id', $record->time_keeping_approve_id)
                                             ->get();  

               array_push($data['_record'], $temp);
          }
	}

	public function group_ptkab($time_keeping_approve_id)
	{
		$_temp_record 	= array();
		$_ptkab			= Tbl_payroll_time_keeping_approved_breakdown::where('time_keeping_approve_id', $time_keeping_approve_id)
                            ->get();
        
	}*/
}