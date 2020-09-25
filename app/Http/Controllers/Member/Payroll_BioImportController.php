<?php

namespace App\Http\Controllers\Member;
use App\Globals\AuditTrail;
use App\Models\Tbl_audit_trail;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Request;
use Session;
use Excel;
use DB;
use File;
use stdClass;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_company;
use DateTime;
use App\Globals\Payroll;
use App\Globals\Payroll2;

class Payroll_BioImportController extends Member
{

	/* SHOP ID */
	public function shop_id()
	{
		return $shop_id = $this->user_info->user_shop;
	}


	public function import_global()
	{
		$file 		= Request::file('file');
		$biometric 	= Request::input('biometric');
		$company_id = Request::input('company');
		
		if ($company_id != '') 
		{
			$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company_id)->first();
			$company = $data['company_info']->payroll_company_id;
		}
		else
		{
			$company="";
		}
		
		if($biometric == 'ZKTime 5.0')
		{
			return Self::import_ZKTime_5_0($file , $company);
		}

		if($biometric == 'ZKTeco TX628')
		{
			return Self::import_zkteco_TX628($file, $company);
		}

		if($biometric == 'ZKTeco- YH 803A (UPS)')
		{
			return Self::import_zkteco_yh803aups($file, $company);
		}

		if($biometric == 'Digital Persona')
		{
			return Self::import_Digital_Persona($file, $company);
		}

		if($biometric == 'C7')
		{
			return Self::import_c7($file, $company);
		}
		if($biometric == 'Manual Template')
		{
			return Self::import_manual_v2($file, $company);
		}

		if($biometric == 'Mustard Seed')
		{
			return Self::import_mustard_seed_v2($file, $company);
		}

		if($biometric == 'Touchlink V1')
		{
			return Self::import_touchlink_v1($file, $company);
		}
		
		if($biometric == 'ANVIZ Biometrics EP Series')
		{
			return Self::import_anviz_biometrics_ep_series($file , $company);
		}
	}


	public function import_anviz_biometrics_ep_series($file, $company)
	{
		$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('no.', 'name','datetime', 'status','status_name'));
		
		if(isset($_time[0]['no.']) && isset($_time[0]['name']) && isset($_time[0]['datetime']) && isset($_time[0]['status']) && isset($_time[0]['status_name']))
		{
		
		 foreach ($_time as $key => $value) 
		 {
		 	
		 	if($value["no."] != "no.")
		 	{
		 		
				$date = date("Y-m-d", strtotime($value["datetime"]));
				$time = date("H:i:s", strtotime($value["datetime"]));
				
				if (!isset($_record[$date][$value["no."]])) 
				{
					$_record[$date][$value["no."]]['time_in']  = $time;
					$_record[$date][$value["no."]]['time_out'] = $time;
					$_record[$date][$value["no."]]['record_debugging_time_in']   = "first record";
					$_record[$date][$value["no."]]['record_debugging_time_out']   = "first record";
				}
				else
				{
					if ($_record[$date][$value["no."]]['time_in'] > $time) 
					{
						$_record[$date][$value["no."]]['record_debugging_time_in']   = "DATE: ".$date."  time in record: ".$_record[$date][$value["no."]]['time_in']." > ".$time . " change time in ";
						$_record[$date][$value["no."]]['time_in'] = $time;
					}

					if ($_record[$date][$value["no."]]["time_out"] < $time) 
					{
						$_record[$date][$value["no."]]['record_debugging_time_out']   = "DATE: ".$date."  time out record: ".$_record[$date][$value["no."]]['time_out']." > ".$time . " change time out ";
						$_record[$date][$value["no."]]['time_out'] = $time;
					}
				}
		 	}
		 }
		 $data = Self::save_time_record($_record, $company, $this->user_info->shop_id, "ANVIZ Biometrics EP Series");
		 echo Self::import_report_table($data);
		}
		else
		{
			echo "<div>INVALID FILE FORMAT</div>";
		}
	}

	public function import_report_table($data)
	{
		$html = "<div><h4 class='text-success'>SUCCESS: ".$data["success"]."</h4><h4 class='text-primary'>OVERWRITTEN: ".$data["overwritten"]."</h4><h4 class='text-danger'>FAILED: ".$data["failed"]."</h4></div>";
		$html .= "<table class='table'>
					<thead>
					      <tr>
					        <th class='text-center'>DATE</th>
					        <th class='text-center'>EMPLOYEE/BIOMETRIC NO.</th>
					        <th class='text-center'>COMPANY NAME</th>
					        <th class='text-center'>TIME IN</th>
					        <th class='text-center'>TIME OUT</th>
					        <th class='text-center'>BIOMETRIC NAME</th>
					        <th class='text-center'>STATUS</th>
					      </tr>
					</thead>
					<tbody>";
		foreach ($data['_report'] as $date => $report) 
		{
			foreach ($report as $employee_number => $value) 
			{
				$html .= "<tr>
							<td class='text-center'>".$date."</td>
							<td class='text-center'>".$employee_number."</td>
							<td class='text-center'>".$value['company_name']."</td>
							<td class='text-center'>".$value['time_in']."</td>
							<td class='text-center'>".$value['time_out']."</td>
							<td class='text-center'>".$value['biometric_name']."</td>
							<td class='text-center'>".$value['status']."</td>
						  </tr>";
			}
		}
		$html .= "   </tbody>
				  </table>";
		return $html;
	}

	public function save_time_record($_time_record, $company ,$shop_id, $biometric_name)
	{
		$success 		= 0;
		$failed 		= 0;
		$incomplete 	= 0;
		$overwritten 	= 0;

		foreach($_time_record as $date => $time_record) 
		{
			foreach($time_record as $employee_number => $value) 
			{
				$check_employee = null;

				$check_employee = Tbl_payroll_employee_basic::where("payroll_employee_biometric_number", $employee_number)->where("shop_id", Self::shop_id())->first();

				$value['employee_number'] = $employee_number;
				$value['date']			  = $date;

				if (!$check_employee) 
				{
					$check_employee = Tbl_payroll_employee_basic::where("payroll_employee_number", $employee_number)->where("shop_id", Self::shop_id())->first();
				}

				$value['employee_number'] = $employee_number;
				$value['date']			  = $date;

				if ($check_employee) 
				{
					/* Get Tbl payroll time sheet data  */
					$timesheet_db 	= Payroll2::timesheet_info_db($check_employee->payroll_employee_id, $date);

					/*Get Shift Code id*/
					$shift_code_id 	= Tbl_payroll_employee_basic::where("payroll_employee_id", $check_employee->payroll_employee_id)->value("shift_code_id");
					
					/* CREATE TIMESHEET DB IF EMPTY */
					if(!$timesheet_db)
					{
						$_shift_real 	=  Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $date);
						$_shift 		=  Payroll2::shift_raw(Payroll2::db_get_shift_of_employee_by_code($shift_code_id, $date));
						
						$insert_report 							=	null;
						$insert 								=	null;
						$insert["payroll_employee_id"] 			= $check_employee->payroll_employee_id;
						$insert["payroll_time_date"] 			= $date;
						$insert["payroll_time_shift_raw"] 		= serialize($_shift);

						$payroll_time_sheet_id = Tbl_payroll_time_sheet::insertGetId($insert);

						$insert 									= null;
						$insert_time['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
						$insert_time['payroll_company_id'] 			= $check_employee->payroll_employee_company_id;
						$insert_time['payroll_time_sheet_in'] 		= $value["time_in"];
						$insert_time['payroll_time_sheet_out'] 		= $value["time_out"];
						$insert_time['payroll_time_sheet_origin'] 	= $biometric_name;


						if($company != '' || $company != 0 || $company != null)
		    			{
		    				$insert_time['payroll_company_id'] = $company;
		    			}

						Tbl_payroll_time_sheet_record::insert($insert_time);

						$success++;
						$_time_record[$date][$employee_number]['status'] = 'INSERTED';
						$_time_record[$date][$employee_number]['biometric_name'] = $biometric_name;
						$_time_record[$date][$employee_number]['company_name'] = Tbl_payroll_company::where('payroll_company_id',$insert_time['payroll_company_id'])->value('payroll_company_name');
					}
					else
					{
						/* Fail Import if timesheet is already approve by time keeper */
						if ($timesheet_db->time_keeping_approved == 1) 
						{
							$_time_record[$date][$employee_number]['status'] 		 = "<div class='text-danger'>FAILED, TIMESHEET WAS ALREADY APPROVED.</div>";
							$_time_record[$date][$employee_number]['biometric_name'] = $biometric_name;
							$_time_record[$date][$employee_number]['company_name']   = "";
							
							$failed++;
						}
						else
						{
							// dd($_time_record["2017-10-09"]);
							$status = "<div class='text-success'>INSERTED</div>";

							$_time_sheet_record = Tbl_payroll_time_sheet_record::where('payroll_time_sheet_id',$timesheet_db->payroll_time_sheet_id)->get();
							
							/*check if import time will be conflicted in database time sheet record*/
							foreach ($_time_sheet_record as $key => $time_sheet_record) 
							{
								$time_in_record = $time_sheet_record->payroll_time_sheet_in;
								$time_out_record = $time_sheet_record->payroll_time_sheet_out;

								if(($time_in_record > $value["time_in"] && $value["time_out"]  < $time_out_record)
								|| ($time_in_record > $value["time_in"] && $value["time_out"]  < $time_out_record) 
								|| ($time_in_record == $value["time_in"])
								|| ($time_out_record == $value["time_out"]))
								{
									Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id',$time_sheet_record->payroll_time_sheet_record_id)->delete();
									$overwritten++;
									$status = "<div class='text-primary'>OVERWRITTEN</div>";
								}
							}

							if ($status != "<div class='text-primary'>OVERWRITTEN</div>") 
							{
								$success++;
							}
							
							$update = null;
							$update['payroll_time_sheet_id'] 		= $timesheet_db->payroll_time_sheet_id;
							$update['payroll_company_id'] 			= $check_employee->payroll_employee_company_id;
							$update['payroll_time_sheet_in'] 		= $value["time_in"];;
							$update['payroll_time_sheet_out'] 		= $value["time_out"];;
							$update['payroll_time_sheet_origin'] 	= $biometric_name;

							if($company != '' || $company != 0 || $company != null)
			    			{
			    				$update['payroll_company_id'] = $company;
			    			}
							
							Tbl_payroll_time_sheet_record::insert($update);

							$_time_record[$date][$employee_number]['status'] 			= $status;
							$_time_record[$date][$employee_number]['biometric_name'] 	= $biometric_name;
							$_time_record[$date][$employee_number]['company_name'] 		= Tbl_payroll_company::where('payroll_company_id',$update['payroll_company_id'])->value('payroll_company_name');
						}
					}
				}
				else
				{
					$_time_record[$date][$employee_number]['status'] = "<div class='text-danger'>FAILED WRONG EMPLOYEE NO.</div>";
					$_time_record[$date][$employee_number]['biometric_name'] = $biometric_name;
					$_time_record[$date][$employee_number]['company_name'] = "";
					
					$failed++;
				}
			}
		}
		
		$data["success"] 	 = $success;
		$data["failed"] 	 = $failed;
		$data["overwritten"] = $overwritten;
		$data['_report'] 	 = $_time_record;
		// dd($data['_report']);
		// if ($success != 0 || $overwritten != 0) 
		// {
		// 	$count_inserted = $success + $overwritten;
		// 	$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
	 	// 	AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using import_mustard_seed   Template.', "", "" ,"");	
		// }
		
		return $data;
	}


    public function import_touchlink_v1($file, $company) /* BIO METRICS START */
    {
    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('employee_no','employee_name','date','day','in','out','next_day'));

    	if(isset($_time[0]['employee_no']) && isset($_time[0]['employee_name']) && isset($_time[0]['date']) && isset($_time[0]['day']) && isset($_time[0]['in']) && isset($_time[0]['out']))
    	{
	    	$key_employee = 0;
	    	$key_date = 0;
	    	$new_record = false;

	    	foreach($_time as $excel)
	    	{
	    		if($excel["employee_no"] != null)
	    		{
	    			$new_record = true;
	    			$key_employee++;
	    		}

	    		if($new_record == true)
	    		{
			    	$_record[$key_employee] = new stdClass();
			    	$_record[$key_employee]->employee_no 							= $excel["employee_no"];
			    	$_record[$key_employee]->employee_biometrics_name 				= $excel["employee_name"];
			    	$new_record = false;
			    	$key_date = 0;
	    		}

	    		$_record[$key_employee]->employee_record[$key_date]				= new stdClass();

	    		$date_has_year = Payroll2::date_has_year($excel["date"]);
	    		
	    		if ($date_has_year) //Check if date has year
	    		{
	    			$_record[$key_employee]->employee_record[$key_date]->date 		= $excel["date"];
	    		}
	    		else
	    		{
	    			$_record[$key_employee]->employee_record[$key_date]->date 		= $excel["date"] . "-" . date("Y");
	    		}

		    	$_record[$key_employee]->employee_record[$key_date]->time_in 	= $excel["in"];
		    	$_record[$key_employee]->employee_record[$key_date]->time_out 	= $excel["out"];
		    	$_record[$key_employee]->employee_record[$key_date]->branch 	= "";
		    	$_record[$key_employee]->employee_record[$key_date]->status 	= "status";
		    	$key_date++; 	
	    	}
	    
	    	$data = Self::save_record($_record, $company, $this->user_info->shop_id, "TOUCHLINK RDS");

	    	return view("member.payroll2.biometrics", $data);
    	}
    	else
    	{
    		echo "<div class='text-center'>INVALID FILE FORMAT</div>";
    	}
    }

    public static function save_record($_record, $company, $shop_id, $biometerics_name)
    {
    	$success = 0;
    	$failed = 0;
    	$incomplete = 0;
    	$overwritten = 0;

    	foreach($_record as $key_employee => $employee)
    	{
    		/* CHECK IF EMPLOYEE EXIST */
    		$check_employee = Tbl_payroll_employee_basic::where("payroll_employee_biometric_number", $employee->employee_no)->where("shop_id", $shop_id)->first();
    		
    		if (!$check_employee) 
    		{
    			$check_employee = Tbl_payroll_employee_basic::where("payroll_employee_number", $employee->employee_no)->where("shop_id", $shop_id)->first();
    		}

    		
    		if($check_employee)
    		{
    			foreach($employee->employee_record as $key_date => $date)
    			{
    				/* CHECK IF DATA IS INCOMPLETE */
    				if($date->time_in == null || $date->time_out == null)
    				{
    					$incomplete++;
    					$_record[$key_employee]->employee_record[$key_date]->status = "<span style='color: blue;'>Incomplete Data</span>";
    				}
    				else
    				{
    					$record_date = date("Y-m-d", strtotime($date->date));

    					$check_timesheet = Tbl_payroll_time_sheet::where("payroll_employee_id", $check_employee->payroll_employee_id)->where("payroll_time_date", $record_date)->first();	

    					/* IF TIMESHEET DOESN'T EXIST */
    					if(!$check_timesheet)
    					{
	    					$insert_time_sheet["payroll_employee_id"] = $check_employee->payroll_employee_id;
	    					$insert_time_sheet["payroll_time_date"] = $record_date;
	    					Tbl_payroll_time_sheet::insert($insert_time_sheet);
	    					$check_timesheet = Tbl_payroll_time_sheet::where("payroll_employee_id", $check_employee->payroll_employee_id)->where("payroll_time_date", $record_date)->first();	
    					}

    					$_record_exist = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_id", $check_timesheet->payroll_time_sheet_id)->get();

    					foreach($_record_exist as $record_exist)
    					{
    						$exist_in = strtotime($record_exist->payroll_time_sheet_in);
    						$exist_out = strtotime($record_exist->payroll_time_sheet_out);
    						$new_in = strtotime($date->time_in);
    						$new_out = strtotime($date->time_out);

    						if($exist_in >= $new_in && $exist_in <= $new_out)
    						{
    							$overflow = 1;
    						}
    						elseif($exist_out >= $new_in && $exist_out <= $new_out)
    						{
    							$overflow = 1;
    						}
    						else
    						{
    							$overflow = 0;
    						}

    						if($overflow == 1)
    						{
    							Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $record_exist->payroll_time_sheet_record_id)->delete();
    							$overwritten++;
    						}
    					}

			    		/* CREATE RECORD */
			    		$insert_record["payroll_time_sheet_id"] 	= $check_timesheet->payroll_time_sheet_id;
			    		$insert_record["payroll_company_id"] 		= $check_employee->payroll_employee_company_id;
			    		$insert_record["payroll_time_sheet_in"] 	= date("H:i:s", strtotime($date->time_in));
			    		$insert_record["payroll_time_sheet_out"] 	= date("H:i:s", strtotime($date->time_out));
			    		$insert_record["payroll_time_sheet_origin"] = $biometerics_name;
			    		$time_sheet_record_id = Tbl_payroll_time_sheet_record::insertGetId($insert_record);

    					$success++;
    					$_record[$key_employee]->employee_record[$key_date]->status = "<span style='color: green;'>Import Success</span>";
    				}
    			}
    		}
    		else
    		{
    			foreach($employee->employee_record as $key_date => $date)
    			{
    				$failed++;
    				$_record[$key_employee]->employee_record[$key_date]->status = "<span style='color: red;'>Invalid Employee Number</span>";
    			}
    		}
    	}

    	$data["record_success"] = $success;
    	$data["record_failed"] = $failed;
    	$data["record_incomplete"] = $incomplete;
    	$data["record_overwritten"] = $overwritten;
    	$data["_record"] = $_record;
    	return $data;
    }

	

	/* MODAL IMPORT OF BIOMETRICS START*/
	public function modal_biometrics()
	{
		$data['_company'] = Payroll::company_heirarchy(Self::shop_id());
		return view('member.payroll.modal.modal_biometrics', $data);
	}

	/* MODAL IMPORT OF BIOMETRICS END */

	/* CHECK EMPLOYEE NUMBER START */
	public function check_employee_number($payroll_employee_number = '')
	{
		$bool = true;

		/*$count = Tbl_payroll_employee_basic::where('payroll_employee_number', $payroll_employee_number)->where('shop_id', Self::shop_id())->count();*/
		$count = Tbl_payroll_employee_basic::where("payroll_employee_biometric_number", $payroll_employee_number)->where("shop_id", Self::shop_id())->first();

		if(!$count)
		{
			$count = Tbl_payroll_employee_basic::where("payroll_employee_number", $payroll_employee_number)->where("shop_id", Self::shop_id())->first();
			if (!$count) 
			{
				$bool = false;
			}
		}
		return $bool;
	}
	/* CHECK EMPLOYEE NUMBER END */


	/* GET EMPLOYEE ID START */
	public function getemployeeId($payroll_employee_number = '', $value = 'payroll_employee_id')
	{

		$employee_info = Tbl_payroll_employee_basic::where('payroll_employee_biometric_number', $payroll_employee_number)->where('shop_id', Self::shop_id())->value($value);


		
		if (!$employee_info) 
		{
			$employee_info 	= Tbl_payroll_employee_basic::where('payroll_employee_number', $payroll_employee_number)->where('shop_id', Self::shop_id())->value($value);
		}

		return $employee_info;
	}

	public function getTimeSheetId($payroll_employee_id = 0, $date = '0000-00-00')
	{
		$count = Tbl_payroll_time_sheet::checkdata($payroll_employee_id,$date)->count();
		$payroll_time_sheet_id = 0;
		if($count == 0)
		{
			$insert_time['payroll_employee_id'] = $payroll_employee_id;
			$insert_time['payroll_time_date'] 	= $date;
			$payroll_time_sheet_id = Tbl_payroll_time_sheet::insertGetId($insert_time);
		}
		else
		{
			$payroll_time_sheet_id = Tbl_payroll_time_sheet::checkdata($payroll_employee_id, $date)->value('payroll_time_sheet_id');
		}

		return $payroll_time_sheet_id;
	}
	/* GET EMPLOYEE ID END */


	/* CHECK IF HOLIDAY OR REGULAR DAY START */
	public function check_holiday($company_id = 0,$date = '0000-00-00')
	{
		$day = 'RG';
		$holiday = Tbl_payroll_holiday_company::getholiday($company_id, $date)->value('payroll_holiday_category');
	
		if($holiday == 'Regular')
		{
			$day = 'RH';
		}

		if($holiday == 'Special')
		{
			$day = 'SH';
		}
		return $day;
	}
	/* CHECK IF HOLIDAY OR REGULAR DAY END */


	/* CHECK IF REST DAY, EXTRA DAY OR REGULAR DAY START */
	public function check_day($employee_id = 0, $date = '0000-00-00', $daystr = 'RG')
	{	
		$day = $daystr;
		$target = date('l', strtotime($date));
		$_day = Tbl_payroll_employee_contract::selemployee($employee_id, $date)
											->join('tbl_payroll_group_rest_day','tbl_payroll_group_rest_day.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
											->where('payroll_group_rest_day', $target)
											->value('tbl_payroll_group_rest_day.payroll_group_rest_day_category');
		if($_day == 'rest day')
		{
			$day = 'RD';
			if($daystr != 'RG')
			{
				$day = $daystr.',RD';
			}
		}
		if($_day == 'extra day')
		{
			$day = 'ED';
			if($daystr != 'RG')
			{
				$day = $daystr.',ED';
			}
		}

		return $day;
	}	
	/* CHECK IF REST DAY, EXTRA DAY OR REGULAR DAY END */

    public function import_zkteco_TX628($file, $company)
    {
    	$_test = file($file, FILE_IGNORE_NEW_LINES);
    	
    	$temp = preg_split("/[\t]/", $_test[0]);
 		
    	$message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';

    	if(isset($temp[0]) && isset($temp[1]) && isset($temp[2]) && isset($temp[3]) && isset($temp[4]) && isset($temp[5]))
    	{
    		$success_count = 0;
	    	$temp_date = '';
	    	$insert_time_record = array();
	    	$time_sheet = array();


    		foreach($_test as $key => $test)
			{
				$extest 	= preg_split("/[\t]/", $test);
				$emp_no 	= $extest[0];
				$date_time 	= $extest[1];

				$temp_record['employee_number'] = (string)$emp_no;
	    		$temp_record['time']			= date('H:i:s', strtotime($date_time));
	    		$temp_record['date']			= date('Y-m-d', strtotime($date_time));
	    		array_push($time_sheet, $temp_record);
			}

			$_date_collect = collect($time_sheet)->groupBy('employee_number','date');

			foreach($_date_collect as $key => $date_collect)
	    	{
	    		$_date = collect($date_collect)->groupBy('date');
	    		$temp = '';
	    		foreach($_date as $date)
	    		{
	    			$start 	= $date[0];
	    			$end 	= $date[count($date) - 1];
	    			if(Self::check_employee_number($start['employee_number']))
	    			{
	    				
		    			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($start['employee_number']), $start['date']);

		    			$temp_array['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
		    			$temp_array['payroll_time_sheet_in'] 		= $start['time'];
		    			$temp_array['payroll_time_sheet_out'] 		= $end['time'];
		    			$temp_array['payroll_time_sheet_origin'] 	= 'ZKTeco TX628';
		    			$temp_array['payroll_company_id']			= Self::getemployeeId($start['employee_number'],'payroll_employee_company_id');

		    			if($company != '' || $company != 0 || $company != null)
		    			{
		    				$temp_array['payroll_company_id'] = $company;
		    			}

		    			$count_record = Tbl_payroll_time_sheet_record::wherearray($temp_array)->count();

		    			if($count_record == 0)
		    			{
		    				array_push($insert_time_record, $temp_array);
		    			}
	    			}
	    		}
	    	}

	    	$message = '<center><span class="color-gray">Nothing to insert</span></center>';

	    	if(!empty($insert_time_record))
	    	{
	    		Tbl_payroll_time_sheet_record::insert($insert_time_record);
	    		$count_inserted = count($insert_time_record);
	    		$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
	    		AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using zkteco TX628   Template.', "", "" ,"");
	    		$message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';
	    	}
    	}

    	return $message;
    }

    public function import_ZKTime_5_0($file, $company)
    {
    	
    	$message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';

    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('no','datetime'));
    	if(isset($_time[0]['no']) && isset($_time[0]['datetime']))
    	{
	    	$success_count = 0;
	    	$temp_date = '';
	    	$insert_time_record = array();
	    	$time_sheet = array();

	    	foreach($_time as $key => $time)
	    	{
	    		$temp_record['employee_number'] = (string)$time['no'];
	    		$temp_record['time']			= date('H:i:s', strtotime($time['datetime']));
	    		$temp_record['date']			= date('Y-m-d', strtotime($time['datetime']));
	    		if ($temp_record['employee_number'] != "" || $temp_record['employee_number'] != null) 
	    		{
	    			array_push($time_sheet, $temp_record);
	    		}
	    	}
	    	
	    	$_date_collect = collect($time_sheet)->groupBy('employee_number','date');

	    	foreach($_date_collect as $key => $date_collect)
	    	{
	    		$_date = collect($date_collect)->groupBy('date');
	    		$temp = '';
	    		foreach($_date as $date)
	    		{
	    			$start 	= $date[0];
	    			$end 	= $date[count($date) - 1];
	    			
	    			if(Self::check_employee_number($start['employee_number']))
	    			{
		    			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($start['employee_number']), $start['date']);
		    			
		    			$temp_array['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
		    			$temp_array['payroll_time_sheet_in'] 		= $start['time'];
		    			$temp_array['payroll_time_sheet_out'] 		= $end['time'];
		    			$temp_array['payroll_time_sheet_origin'] 	= 'ZKTime 5.0';
		    			$temp_array['payroll_company_id']			= Self::getemployeeId($start['employee_number'],'payroll_employee_company_id');
		    			$temp_array['report']['date']				= $date[0]["date"];
		    			$temp_array['report']['employee_number']	= $start['employee_number'];
		    			$temp_array['report']['status']				= 'Inserted';
		    			if($company != '' || $company != 0 || $company != null)
		    			{
		    				$temp_array['payroll_company_id'] = $company;
		    			}
		    			
		    			/*$count_record = Tbl_payroll_time_sheet_record::wherearray($temp_array)->count();
		    			if($count_record == 0)
		    			{*/
		    				array_push($insert_time_record, $temp_array);
		    			/*}*/
		    			/* delete all 0000-00-00 date value */
		    			Self::delete_blank($payroll_time_sheet_id);
	    			}
	    		}
	    	}

	    	return Self::insert_record($insert_time_record, $company);
    	}

    	return $message;
    }

    public function insert_record($insert_time_record, $company)
    {
    	/*START remove importation if time_sheet is already approved*/
    	$data['_report'] = array();
    	$success 		 = 0;
    	$failed  		 = 0;
    	$overwritten 	 = 0;

    	foreach ($insert_time_record as $key => $value) 
    	{
    		$_time_sheet = Tbl_payroll_time_sheet::where('payroll_time_sheet_id',$value["payroll_time_sheet_id"])->first();
    		//if approve do nothing
    		if ($_time_sheet->time_keeping_approved == 1) 
    		{
    			$insert_time_record[$key]["report"]["status"] = "Failed, Timesheet already approved";
    			array_push($data['_report'], $insert_time_record[$key]);
    			unset($insert_time_record[$key]);
    			$failed++;
    		}
    		else
    		{
    			$_time_sheet_record = Tbl_payroll_time_sheet_record::where('payroll_time_sheet_id',$value["payroll_time_sheet_id"])->get();
    			foreach ($_time_sheet_record as $key2 => $time_sheet_record) 
    			{
    				$time_in_record = $time_sheet_record->payroll_time_sheet_in;
    				$time_out_record = $time_sheet_record->payroll_time_sheet_out;

    				if(($time_in_record > $value["payroll_time_sheet_in"] && $value["payroll_time_sheet_out"]  < $time_out_record)
    				|| ($time_in_record > $value["payroll_time_sheet_in"] && $value["payroll_time_sheet_out"]  < $time_out_record) 
    				|| ($time_in_record == $value["payroll_time_sheet_in"])
    				|| ($time_out_record == $value["payroll_time_sheet_out"]))
    				{
    					Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id',$time_sheet_record->payroll_time_sheet_record_id)->delete();
    					$insert_time_record[$key]["report"]["status"] = "Overwritten";
    					array_push($data['_report'], $insert_time_record[$key]);
    					$overwritten++;
    				}
    			}
    			/*unset the report of inserted timesheet*/
    			if (isset($insert_time_record[$key]["report"])) 
    			{
    				if ($insert_time_record[$key]["report"]["status"] == "Inserted")
    				{
    					array_push($data['_report'], $insert_time_record[$key]);
    					$success++;
    				}
    				unset($insert_time_record[$key]["report"]);
    			}
    		}
    	}
    	/*END remove importation if time_sheet is already approved*/

    	$message = '<center><span class="color-gray">Nothing to insert</span></center>';
    	
    	// dd($insert_time_record);
    	$data["success"] 		= $success;
    	$data["overwritten"] 	= $overwritten;
    	$data["failed"]			= $failed;

    	if(!empty($insert_time_record))
    	{
    		Tbl_payroll_time_sheet_record::insert($insert_time_record);
    		
    		$count_inserted = count($insert_time_record);
    		if ($company != null || $company != 0) 
    		{
    			$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
    			AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using ZKTime_5_0   Template.', "", "" ,"");
    		}
    		// $message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';
    		 $message = Self::importation_table_report($data);
    	}

    	return $message;
    }

    public function importation_table_report($data)
    {
    	$html  = "<div><h4 class='text-success'>SUCCESS: ".$data["success"]."</h4><h4 class='text-primary'>OVERWRITTEN: ".$data["overwritten"]."</h4><h4 class='text-danger'>FAILED: ".$data["failed"]."</h4></div>";
    	$html .= "<table class='table'>
    				<thead>
    				      <tr>
    				        <th class='text-center'>DATE</th>
    				        <th class='text-center'>EMPLOYEE/BIOMETRIC NO.</th>
    				        <th class='text-center'>COMPANY NAME</th>
    				        <th class='text-center'>TIME IN</th>
    				        <th class='text-center'>TIME OUT</th>
    				        <th class='text-center'>BIOMETRIC NAME</th>
    				        <th class='text-center'>STATUS</th>
    				      </tr>
    				</thead>
    				<tbody>";
    	foreach ($data['_report'] as $report) 
    	{
			$html .= "<tr>
						<td class='text-center'>".$report['report']['date']."</td>
						<td class='text-center'>".$report['report']['employee_number']."</td>
						<td class='text-center'>".$report['payroll_company_id']."</td>
						<td class='text-center'>".$report['payroll_time_sheet_in']."</td>
						<td class='text-center'>".$report['payroll_time_sheet_out']."</td>
						<td class='text-center'>".$report['payroll_time_sheet_origin']."</td>
						<td class='text-center'>".$report['report']['status']."</td>
					  </tr>";
    	}
    	$html 	  .= "   </tbody>
    			  </table>";
    	return $html;
    }

    public function import_zkteco_yh803aups($file, $company)
    {
    	$message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';

    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('ac_no','time'));
    	// dd($_time);

    	if(isset($_time[0]['ac_no']) && isset($_time[0]['time']))
    	{
	    	$success_count = 0;
	    	$temp_date = '';
	    	$insert_time_record = array();
	    	$time_sheet = array();
	    	foreach($_time as $key => $time)
	    	{
	    		$temp_record['employee_number'] = (string)$time['ac_no'];
	    		$temp_record['time']			= date('H:i:s', strtotime($time['time']));
	    		$temp_record['date']			= date('Y-m-d', strtotime($time['time']));
	    		array_push($time_sheet, $temp_record);
	    	}

	    	$_date_collect = collect($time_sheet)->groupBy('employee_number','date');

	    	foreach($_date_collect as $key => $date_collect)
	    	{
	    		$_date = collect($date_collect)->groupBy('date');
	    		$temp = '';
	    		foreach($_date as $date)
	    		{
	    			$start 	= $date[0];
	    			$end 	= $date[count($date) - 1];
	    			if(Self::check_employee_number($start['employee_number']))
	    			{
		    			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($start['employee_number']), $start['date']);

		    			$temp_array['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
		    			$temp_array['payroll_time_sheet_in'] 		= $start['time'];
		    			$temp_array['payroll_time_sheet_out'] 		= $end['time'];
		    			$temp_array['payroll_time_sheet_origin'] 	= 'ZKTime 5.0';

		    			$temp_array['payroll_company_id']			= Self::getemployeeId($start['employee_number'],'payroll_employee_company_id');


		    			if($company != '' || $company != 0 || $company != null)
		    			{
		    				$temp_array['payroll_company_id'] = $company;
		    			}
		    			

		    			$count_record = Tbl_payroll_time_sheet_record::wherearray($temp_array)->count();
		    			if($count_record == 0)
		    			{
		    				array_push($insert_time_record, $temp_array);
		    			}

		    			/* delete all 00:00:00 value */
		    			Self::delete_blank($payroll_time_sheet_id);
	    			}
	    			
	    		}
	    		
	    	}
	    	$message = '<center><span class="color-gray">Nothing to insert</span></center>';
	    	if(!empty($insert_time_record))
	    	{
	    		Tbl_payroll_time_sheet_record::insert($insert_time_record);
	    		$count_inserted = count($insert_time_record);
	    		$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
	    		AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using zkteco_yh803aups   Template.', "", "" ,"");
	    		$message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';
	    	}
	    	// return $message;
    	}

    	return $message;
    }

    public function import_Digital_Persona($file, $company)
    {
    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('id_no','date','time_in','time_out'))->toArray();
    	// dd($_time);
    	$space = '        ';
    	$old_id_no = 0;
    	$old_date = '';
    	$record_array = array();

    	$message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';
    	if(isset($_time[0]['id_no']) && isset($_time[0]['date']) && isset($_time[0]['time_in']) && isset($_time[0]['time_out']))
    	{

    		foreach($_time as $time)
	    	{
	    		$time['id_no'] 		= trim((string)$time['id_no'],' ');
	    		$time['date'] 		= trim((string)$time['date'], ' ');
	    		$time['time_in'] 	= trim((string)$time['time_in'], ' ');
	    		$time['time_out'] 	= trim((string)$time['time_out'], ' ');

	    		if($time['id_no'] == '')
	    		{
	    			$time['id_no'] = $old_id_no;
	    		}
	    		if(trim((string)$time['date'], ' ') == '')
	    		{
	    			$time['date'] = $old_date;
	    		}
	    		if($old_id_no != $time['id_no'] && $time['id_no'] != "")
	    		{
	    			$old_id_no = $time['id_no'];
	    		}
	    		if(trim((string)$time['date'], ' ') != '')
	    		{
	    			$old_date = trim((string)$time['date'], ' ');
	    		}
	    		array_push($record_array, $time);
	    	}

	    	$_collect = collect($record_array)->groupBy('id_no');

	    	$insert_time_record = array();

	    	foreach($_collect as $key => $collect)
	    	{	
	    		if(Self::check_employee_number($key))
	    		{
	    			$_date_key = collect($collect)->groupBy('date');
	  				
		    		foreach($_date_key as $dk => $date_key)
		    		{

		    			$insert_record = null;
		    			$date = date('Y-m-d', strtotime($dk));

		    			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($key), $date);

		    			/* delete all 0000-00-00 date value */
		    			Self::delete_blank($payroll_time_sheet_id);

		    			if($date_key[0]['time_in'] != '')
		    			{
		    				$insert_record['payroll_time_sheet_in'] 	= date('H:i:s', strtotime($date_key[0]['time_in']));
		    				$insert_record['payroll_time_sheet_id'] 	= $payroll_time_sheet_id;
		    				$insert_record['payroll_company_id']		= Self::getemployeeId($key,'payroll_employee_company_id');
		    				

		    				if($company != '' || $company != 0 || $company != null)
			    			{
			    				$insert_record['payroll_company_id'] = $company;
			    			}
		    				$insert_record['payroll_time_sheet_origin'] = 'Digital Persona';
		    				foreach($date_key as $final_date)
			    			{
			    				if(strtotime($final_date['time_out']) != '')
			    				{
			    					$insert_record['payroll_time_sheet_out'] = date('H:i:s', strtotime($final_date['time_out']));
			    				}
			    			}
			    			$count_record = Tbl_payroll_time_sheet_record::wherearray($insert_record)->count();
			    			$insert_record['report']['date']			= $date;
		    				$insert_record['report']['employee_number']	= $date_key[0]['id_no'];
		    				$insert_record['report']['status']			= 'Inserted';
			    			/*if($count_record == 0)
			    			{*/
			    			array_push($insert_time_record, $insert_record);
			    			/*}*/
		    			}		    			

		    		}
	    		}
	    	}

	    	$message = Self::insert_record($insert_time_record, $company);
    	}
    	
    	return $message;
    }	


    public function import_c7($file, $company)
    {

		$_test = file($file, FILE_IGNORE_NEW_LINES);
		foreach($_test as $key => $test)
		{
			$extest = preg_split("/[\t]/", $test);
			// dd($extest);
		}

    }

    public function import_manual($file, $company)
    {
    	// $message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';

    	// $_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('employee_no','date','time_in','time_out'));
    	
    	// if(isset($_time[0]['employee_no']) && isset($_time[0]['date']) && isset($_time[0]['time_in']) && isset($_time[0]['time_out']))
    	// {

	    // 	$success_count = 0;
	    // 	$temp_date = '';
	    // 	$insert_time_record = array();
	    // 	$time_sheet = array();
	    // 	foreach($_time as $key => $time)
	    // 	{

	    // 		if(Self::check_employee_number($time['employee_no']))
    	// 		{
    	// 			// dd($time['date']);
	    // 			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($time['employee_no']), date('Y-m-d', strtotime($time['date'])));

	    // 			$temp_array['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
	    // 			$temp_array['payroll_time_sheet_in'] 		= date('H:i:s', strtotime($time['time_in']));
	    // 			$temp_array['payroll_time_sheet_out'] 		= date('H:i:s', strtotime($time['time_out']));
	    // 			$temp_array['payroll_time_sheet_origin'] 	= 'Manual Template';
	    // 			$temp_array['payroll_company_id']			= Self::getemployeeId($time['employee_no'],'payroll_employee_company_id');

	    // 			if($company != '' || $company != 0 || $company != null)
	    // 			{
	    // 				$temp_array['payroll_company_id'] = $company;
	    // 			}

	    // 			Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $payroll_time_sheet_id)->where('payroll_time_sheet_in', '00:00:00')->where('payroll_time_sheet_out','00:00:00')->delete();

	    // 			$count_record = Tbl_payroll_time_sheet_record::wherearray($temp_array)->count();
	    			
	    // 			if($count_record == 0)
	    // 			{

	    // 				array_push($insert_time_record, $temp_array);
	    // 			}

	    // 			/* delete all 0000-00-00 date value */
		   //  		Self::delete_blank($payroll_time_sheet_id);
    	// 		}
	    // 	}

	    // 	// dd($insert_time_record);
	    // 	$message = '<center><span class="color-gray">Nothing to insert</span></center>';
	    // 	if(!empty($insert_time_record))
	    // 	{
	    // 		Tbl_payroll_time_sheet_record::insert($insert_time_record);
	    // 		$count_inserted = count($insert_time_record);
	    // 		$message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';
	    // 	}
	    	
	    // 	// return $message;
    	// }

    	// return $message;

    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('employee_no','employee_name','date','time_in','time_out'));

    	if(!isset($_time[0]['employee_no']))
    	{
    		$error["message"] = "Error in employee no";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['employee_name']))
    	{
    		$error["message"] = "Error in employee no";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['time_in']))
    	{
    		$error["message"] = "Error in Time In";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['time_out']))
    	{
    		$error["message"] = "Error in Time Out";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['date']))
    	{
    		$error["message"] = "Error in Date";
    		$error["data"] = $_time;
    		dd($error);
    	}
    	
    	if(isset($_time[0]['employee_no']) && isset($_time[0]['employee_name']) && isset($_time[0]['date']) && isset($_time[0]['time_in']) && isset($_time[0]['time_out']))
    	{
		    	$key_employee = 0;
		    	$key_date = 0;
		    	$new_record = false;

		    	foreach($_time as $excel)
		    	{
		    		if($excel["employee_no"] != null)
		    		{
		    			$new_record = true;
		    			$key_employee++;
		    		}
		    		if($new_record == true)
		    		{
				    	$_record[$key_employee] = new stdClass();
				    	$_record[$key_employee]->employee_no 							= $excel["employee_no"];
				    	$_record[$key_employee]->employee_biometrics_name 				= $excel["employee_name"];
				    	$new_record = false;
				    	$key_date = 0;
		    		}
	    			if($company != '' || $company != 0 || $company != null)
	    			{
	    				$payroll_company_id = $company;
	    			}
	    			else
	    			{
	    				$payroll_company_id = Self::getemployeeId($excel['employee_no'], 'payroll_employee_company_id');
	    			}

		    	// dd($insert_time_record);
		    	$message = '<center><span class="color-gray">Nothing to insert</span></center>';
		    	if(!empty($insert_time_record))
		    	{
		    		Tbl_payroll_time_sheet_record::insert($insert_time_record);
		    		$count_inserted = count($insert_time_record);
		    		$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
	    			AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using import_manual   Template.', "", "" ,"");
	    		
		    		$message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';

		    		$_record[$key_employee]->employee_record[$key_date]				= new stdClass();
			    	$_record[$key_employee]->employee_record[$key_date]->date 		= date('Y-m-d', strtotime($excel['date']->toDateTimeString()));
			    	$_record[$key_employee]->employee_record[$key_date]->time_in 	= date('H:i:s', strtotime($excel['time_in']->toDateTimeString()));
			    	$_record[$key_employee]->employee_record[$key_date]->time_out 	= date('H:i:s', strtotime($excel['time_out']->toDateTimeString()));
			    	$_record[$key_employee]->employee_record[$key_date]->branch 	= "";
			    	$_record[$key_employee]->employee_record[$key_date]->status 	= "status";
			    	$key_date++; 	
		    	}
	    	}

	    	$data = Self::save_record($_record, $company, $this->user_info->shop_id, "MANUAL TEMPLATE");
		    
		    return view("member.payroll2.biometrics", $data);
	    }
    	else
    	{
    		echo "<div class='text-center'>INVALID FILE FORMAT</div>";
    	}
    }


    public function import_manual_v2($file, $company)
    {
    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('employee_no','employee_name','date','time_in','time_out'));

    	$incomplete = 0;
    	$_record = null;
    	if(isset($_time[0]['employee_no']) && isset($_time[0]['employee_name']) && isset($_time[0]['date']) && isset($_time[0]['time_in']) && isset($_time[0]['time_out']))
    	{
    	 foreach ($_time as $key => $value) 
    	 {
    	 	if ($value['date'] != null && $value['time_in'] != null && $value['time_out'] != null && $value['employee_no'] != null) 
    	 	{
    	 		$employee_number = $value["employee_no"];
    	 		if (is_object($value["date"])) 
    	 		{
    	 			$date = date('Y-m-d', strtotime($value['date']->toDateTimeString()));
    	 		}
    	 		else
    	 		{
    	 			$date = date('Y-m-d', strtotime($value['date']));
    	 		}
    	 		
    	 		if(is_object($value["time_in"]) && is_object($value["time_out"]))
	 			{
			 		$_record[$date][$employee_number]['time_in']  = date('H:i:s', strtotime($value['time_in']->toDateTimeString()));
					$_record[$date][$employee_number]['time_out'] = date('H:i:s', strtotime($value['time_out']->toDateTimeString()));	
	 			}
	 			else
	 			{
	 				$_record[$date][$employee_number]['time_in']  = date('H:i:s', strtotime($value['time_in']));
					$_record[$date][$employee_number]['time_out'] = date('H:i:s', strtotime($value['time_out']));
	 			}
    	 	}
    	 	else
    	 	{
    	 		$incomplete++;
    	 	}
    	 }
    	 
    	 $data = Self::save_time_record($_record, $company, $this->user_info->shop_id, "Manual Template");
   		 
   		 echo Self::import_report_table($data);
    	}
    	else
    	{
    		echo "<div>INVALID FILE FORMAT</div>";
    	}
    }

    public function import_mustard_seed($file, $company)
    {

    	$message = '<center><i><span class="color-red"><b>Invalid File Format</b></span></i></center>';
    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('company_code','employee_no','date','in_1','out_1','in_2','out_2','in_3','out_3','in_4','out_4','in_5','out_5','in_6','out_6'));

    	if(!isset($_time[0]['company_code']))
    	{
    		$error["message"] = "Error in company code";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['employee_no']))
    	{
    		$error["message"] = "Error in employee no";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['date']))
    	{
    		$error["message"] = "Error in employee no";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['in_1']))
    	{
    		$error["message"] = "Error in In 1";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['out_1']))
    	{
    		$error["message"] = "Error in Out 1";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['in_2']))
    	{
    		$error["message"] = "Error in In 2";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['out_2']))
    	{
    		$error["message"] = "Error in Out 2";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['in_3']))
    	{
    		$error["message"] = "Error in In 3";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(!isset($_time[0]['out_3']))
    	{
    		$error["message"] = "Error in Out 3";
    		$error["data"] = $_time;
    		dd($error);
    	}

    	if(isset($_time[0]['company_code']) && isset($_time[0]['employee_no']) && isset($_time[0]['date']) && isset($_time[0]['in_1']) && isset($_time[0]['out_1']) && isset($_time[0]['in_2']) && isset($_time[0]['out_2']) && isset($_time[0]['in_3']) && isset($_time[0]['out_3']))
    	{
    		
    		$insert_time_record = array();

    		foreach($_time as $key => $time)
    		{
    			if(Self::check_employee_number($time['employee_no']))
    			{

    				$time_in = Self::findfirstlast_mustard($time);
    				$time_out = Self::findfirstlast_mustard($time,'last');

    				// dd($time_out);

    				$company_id = Self::getemployeeId($time['employee_no'],'payroll_employee_company_id');

    				if($time['company_code'] != null)
    				{
    					$company_temp_id =  Tbl_payroll_company::selbycode(Self::shop_id(), $time['company_code'])->value('payroll_company_id');
    					if($company_temp_id != null)
    					{	
    						$company_id = $company_temp_id;
    					}
    				}


	    			$payroll_time_sheet_id = Self::getTimeSheetId(Self::getemployeeId($time['employee_no']), date('Y-m-d', strtotime($time['date'])));

	    			$temp_array['payroll_time_sheet_id'] 		= $payroll_time_sheet_id;
	    			$temp_array['payroll_time_sheet_in'] 		= date('H:i:s', strtotime($time_in));
	    			$temp_array['payroll_time_sheet_out'] 		= date('H:i:s', strtotime($time_out));
	    			$temp_array['payroll_time_sheet_origin'] 	= 'Mustard Seed';
	    			$temp_array['payroll_company_id']			= $company_id;

	    			if($company != '' || $company != 0 || $company != null)
	    			{
	    				$temp_array['payroll_company_id'] = $company;
	    			}

	    			Tbl_payroll_time_sheet_record::where('payroll_time_sheet_record_id', $payroll_time_sheet_id)->where('payroll_time_sheet_in', '00:00:00')->where('payroll_time_sheet_out','00:00:00')->delete();

	    			$count_record = Tbl_payroll_time_sheet_record::wherearray($temp_array)->count();
	    			
	    			if($count_record == 0)
	    			{
	    				array_push($insert_time_record, $temp_array);
	    			}

	    			/* delete all 0000-00-00 date value */
		    		Self::delete_blank($payroll_time_sheet_id);
    			}
    		}
    		// dd($company);
    		$message = '<center><span class="color-gray">Nothing to insert</span></center>';

	    	if(!empty($insert_time_record))
	    	{
	    		Tbl_payroll_time_sheet_record::insert($insert_time_record);
	    		$count_inserted = count($insert_time_record);
	    		$data['company_info'] = Tbl_payroll_company::where('payroll_company_id',$company)->first();
	    		AuditTrail::record_logs('INSERTED: '.$data['company_info']->payroll_company_name.' Timesheet',$count_inserted.' Files had been inserted using import_mustard_seed   Template.', "", "" ,"");
	    		
	    		$message = '<center><span class="color-green">'.$count_inserted.' new record/s inserted.</span></center>';
	    	}
    	}

    	return $message;
    }

    public function import_mustard_seed_v2($file, $company)
    {
    	$_time = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->get(array('company_code','employee_no','date','in_1','out_1','in_2','out_2','in_3','out_3','in_4','out_4','in_5','out_5','in_6','out_6'));

    	$incomplete = 0;

    	$_record = null;
    	$time_records = null;
    	if(isset($_time[0]['company_code']) && isset($_time[0]['employee_no']) && isset($_time[0]['date']) && isset($_time[0]['in_1']) && isset($_time[0]['out_1']) && isset($_time[0]['in_2']) && isset($_time[0]['out_2']) && isset($_time[0]['in_3']) && isset($_time[0]['out_3']))
    	{
    	 foreach ($_time as $key => $time) 
    	 {
    	 	// dd($time);
    	 	if ($time['date'] != null && $time['in_1'] != null && $time['out_1'] != null && $time['employee_no'] != null) 
    	 	{
    	 		$employee_number = $time["employee_no"];
    	 		$time_in  = "";
    	 		$time_out = "";
    	 		$date = null;
			 	if (is_object($time["date"])) 
    	 		{
    	 			$date = date('Y-m-d', strtotime($time['date']->toDateTimeString()));
    	 		}
    	 		else
    	 		{
    	 			$date = date('Y-m-d', strtotime($time['date']));
    	 		}

    	 		$column_in_out = array('in_1','in_2','in_3','in_4','in_5','in_6','out_1','out_2','out_3','out_4','out_5','out_6');
    			
    			foreach ($time as $key => $value) 
    			{
					if (in_array($key, $column_in_out) && $value != null) 
					{
						$time_record = null;

						if (is_object($value)) 
						{
							$time_record = date('H:i:s', strtotime($value->toDateTimeString())); 
						}
						else
						{
							$time_record = date('H:i:s', strtotime($value)); 
						}

						if (!isset($_record[$date][$employee_number]['time_in'])) 
						{
							$_record[$date][$employee_number]['time_in']   = $time_record;
							$_record[$date][$employee_number]['time_out']  = $time_record;
						}
						else
						{
							if ($_record[$date][$employee_number]['time_in']  > $time_record)
							{
								$_record[$date][$employee_number]['time_in'] = $time_record;
							}
							if ($_record[$date][$employee_number]['time_out'] < $time_record) 
							{
								$_record[$date][$employee_number]['time_out'] = $time_record;
							}
						}
					}
    			}
    	 	}
    	 	else
    	 	{
    	 		$incomplete++;
    	 	}
    	 }
    	
    	 $data =  Self::save_time_record($_record, $company, $this->user_info->shop_id, "Mustard Seed");
   		 $html =  Self::import_report_table($data);
   		 echo $html;
    	 // echo "<div><h4 class='text-success'>SUCCESS: ".$data["success"]."</h4><h4 class='text-primary'>OVERWRITTEN: ".$data["overwritten"]."</h4><h4 class='text-danger'>FAILED: ".$data["failed"]."</h4></div>";
    	}
    	else
    	{
    		echo "<div>INVALID FILE FORMAT</div>";
    	}
    }


    public function findfirstlast_mustard($_time = array(), $find = 'first')
    {
    	$time = '00:00:00';
    	$_param = ['in_1','out_1','in_2','out_2','in_3','out_3','in_4','out_4','in_5','out_5','in_6','out_6'];

    	if($find == 'last')
    	{
    		// $_param = ['in_6','out_6','in_5','out_5','in_4','out_4','in_3','out_3','in_2','out_2','in_1','out_1'];
    		$_param = ['out_6','in_6','out_5','in_5','out_4','in_4','out_3','in_3','out_2','in_2','out_1','in_1'];
    	}
  
    	foreach($_param as $param)
    	{
    		
    		if($_time[$param] != null && $_time[$param] != ' ' && $_time[$param] != '' && $_time[$param] != 'null')
    		{
    			$time = date('H:i:s', strtotime($_time[$param]));
    			break;
    		}
    	}
    	return $time;
    }

    /* TEMPLATE START */
    public function template_global()
    {
    	$biometric_name = Request::input('biometric_name');
    	if($biometric_name == 'ZKTime 5.0')
    	{
    		Self::ZKTime_template();
    	}
    	if($biometric_name == 'Digital Persona')
    	{
    		Self::Digital_Persona_template();
    	}

    	if($biometric_name == 'Manual Template')
    	{
    		Self::manual_template();
    	}

    	if($biometric_name == 'Mustard Seed')
    	{
    		Self::mustard_seed_template();
    	}
    }

    public function manual_template()
    {
    	$excels['data'][0] = ['Employee No','Employee Name', 'Date','Time In','Time Out'];
        $excels['data'][1] = ['','', '','',''];
        // dd($excels);
        return Excel::create('Timesheet Template (Manual)', function($excel) use ($excels) {

            $data = $excels['data'];
            $date = 'template';
            $excel->setTitle('Payroll');
            $excel->setCreator('Laravel')->setCompany('DIGIMA');
            $excel->setDescription('payroll file');

            $excel->sheet($date, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');
    }

    public function ZKTime_template()
    {
    	$excels['data'][0] = ['Department','Name', 'No.','Date/Time','Location ID','ID Number','VerifyCode','CardNo'];
        $excels['data'][1] = ['','', '','','','','',''];
        // dd($excels);
        return Excel::create('Timesheet Template (ZKTime 5.0)', function($excel) use ($excels) {

            $data = $excels['data'];
            $date = 'template';
            $excel->setTitle('Payroll');
            $excel->setCreator('Laravel')->setCompany('DIGIMA');
            $excel->setDescription('payroll file');

            $excel->sheet($date, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');
    }

    public function Digital_Persona_template()
    {
    	$excels['data'][0] = ['Name','Id No', 'Date','Time In','Time Out'];
        $excels['data'][1] = ['','', '','',''];

        return Excel::create('Timesheet Template (Digital Persona)', function($excel) use ($excels) {

            $data = $excels['data'];
            $date = 'template';
            $excel->setTitle('Payroll');
            $excel->setCreator('Laravel')->setCompany('DIGIMA');
            $excel->setDescription('payroll file');

            $excel->sheet($date, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');
    }

    public function mustard_seed_template()
    {

    	$excels['data'][0] = ['Company Code','Employee No.', 'Employee Name','Date','In 1','Out 1','In 2','Out 2','In 3','Out 3','In 4','Out 4','In 5','Out 5','In 6','Out 6','Hrs Required','Tot Hrs Break','Hrs Worked'];

        $excels['data'][1] = ['','', '','','','','','','','','','','','','','','','',''];

        return Excel::create('Timesheet Template (Mustard Seed)', function($excel) use ($excels) {

            $data = $excels['data'];
            $date = 'template';
            $excel->setTitle('Payroll');
            $excel->setCreator('Laravel')->setCompany('DIGIMA');
            $excel->setDescription('payroll file');

            $excel->sheet($date, function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });

        })->download('xlsx');
    }

    public function delete_blank($payroll_time_sheet_id = 0)
    {
    	Tbl_payroll_time_sheet_record::where('payroll_time_sheet_id', $payroll_time_sheet_id)
    								 ->where('payroll_time_sheet_in','00:00:00')
    								 ->where('payroll_time_sheet_out','00:00:00')
    								 ->delete();
    }

    /* BIO METRICS END */
}
