<?php
namespace App\Http\Controllers\Member;


use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Request;
use Redirect;
use Session;
use Excel;
use DB;
use Response;
use PDF;
use stdClass;



use App\Globals\Payroll;
use App\Globals\PayrollJournalEntries;
use App\Globals\Utilities;
use DateTime;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_shift_time;

use App\Globals\AuditTrail;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_user;
use App\Models\Tbl_shop;
use App\Globals\Accounting;


use App\Models\Tbl_payroll_biometric_record;
use App\Models\Tbl_payroll_biometric_time_sheet;
use App\Models\Tbl_payroll_employee_basic;




class PayrollBiometricsController
{
	public function save_data()
	{
		
		if (Request::input("data_input") == 'null')
		{
			$return 	= "No Data Found!";
		}
		else
		{
			$app_key 		= Request::input("appkey");
			$app_secret 	= Request::input("appsecret");
			$branch_id 		= Request::input("branchid");
			$_time_in_out 	= json_decode(Request::input("data_input"));
			$check_access 	= Tbl_shop::where('shop_api_key',$app_key)->first();
			
			$return = null;
			$employee_record = null;

			if ($check_access->shop_api_key) 
			{
				$shop_id 	= $check_access->shop_id;
				$employee_record =  $this->import_data_from_biometric($_time_in_out, $shop_id, $branch_id);
				$return 	= "Success!";
			}
			else
			{
				$return 	= "Failed!";
			}
		}
		
		
		echo $return;
		// echo json_decode($_time_in_out);
		// echo (json_encode($_time_in_out) == null ? "has value": "null");
	}


	public function import_data_from_biometric($_time_in_out, $shop_id, $branch_id)
	{	
		$employee_in_out = null;
		$insert = null;

		//first in last out rule
		foreach ($_time_in_out as $key => $value) 
		{
			$date = date("Y-m-d", strtotime($value->DateTimeRecord));
			$time = date("H:i:s", strtotime($value->DateTimeRecord));


			if (!isset($employee_in_out[$date][$value->EmployeeID])) 
			{
				$employee_in_out[$date][$value->EmployeeID]['time_in'] = $time;
				$employee_in_out[$date][$value->EmployeeID]['time_out'] = $time;
			}
			else
			{
				if ($employee_in_out[$date][$value->EmployeeID]['time_in'] > $time) 
				{
					$employee_in_out[$date][$value->EmployeeID]['time_in'] = $time;
				}

				if ($employee_in_out[$date][$value->EmployeeID]["time_out"] < $time) 
				{
					$employee_in_out[$date][$value->EmployeeID]['time_out'] = $time;
				}
			}
		}

		
		foreach ($employee_in_out as $key_date => $time_record) 
		{
			foreach ($time_record as $key => $value) 
			{
				$employee_info = Tbl_payroll_employee_basic::where('shop_id',$shop_id)->where('payroll_employee_number',$key)->first();
				
				if (!$employee_info) 
				{
					$employee_info = Tbl_payroll_employee_basic::where('shop_id',$shop_id)->where('payroll_employee_biometric_number',$key)->first();
				}

				if ($employee_info) 
				{
					$check_biometric_exist = Tbl_payroll_biometric_record::where("shop_id",$shop_id)
						->where("payroll_employee_id",$employee_info["payroll_employee_id"])
						->where("payroll_company_id",$branch_id)
						->where("payroll_time_date",$key_date)
						->first();
					// return $shop_id." ".$employee_info["payroll_employee_id"]. " " .$employee_info["payroll_employee_company_id"]. " " .$key_date;
					if ($check_biometric_exist) 
					{
						Tbl_payroll_biometric_time_sheet::where('payroll_biometric_record_id',$check_biometric_exist->payroll_biometric_record_id)->delete();
						$insert_time['payroll_biometric_record_id'] 	= $check_biometric_exist->payroll_biometric_record_id;
						$insert_time["payroll_time_in"] 				= $value["time_in"];
						$insert_time["payroll_time_out"] 				= $value["time_out"];

						Tbl_payroll_biometric_time_sheet::insert($insert_time);
					}
					else
					{
						$insert["shop_id"] 				= $shop_id;
						$insert["payroll_employee_id"] 	= $employee_info["payroll_employee_id"];
						$insert["payroll_company_id"] 	= $branch_id;
						$insert["payroll_time_date"] 	= $key_date;
						
						$insert_time["payroll_biometric_record_id"] = 	Tbl_payroll_biometric_record::insertGetId($insert);
						$insert_time["payroll_time_in"] 			= 	$value["time_in"];
						$insert_time["payroll_time_out"] 			= 	$value["time_out"];

						Tbl_payroll_biometric_time_sheet::insert($insert_time);
					}
				}
			}	
		}

	}


	public function sample()
	{
		$all_data = json_decode('[{"EmployeeID":20170013,"DateTimeRecord":"9/21/2017 7:42:40 PM","InOut":"1"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 7:46:59 PM","InOut":"1"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 9:20:29 PM","InOut":"0"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 9:20:30 PM","InOut":"0"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 9:20:31 PM","InOut":"0"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 9:20:32 PM","InOut":"0"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 9:20:36 PM","InOut":"0"},{"EmployeeID":20170013,"DateTimeRecord":"9/21/2017 9:31:37 PM","InOut":"0"},{"EmployeeID":20170013,"DateTimeRecord":"9/21/2017 9:31:40 PM","InOut":"0"},{"EmployeeID":20170013,"DateTimeRecord":"9/21/2017 9:31:44 PM","InOut":"0"},{"EmployeeID":20170017,"DateTimeRecord":"9/21/2017 9:59:14 PM","InOut":"0"},{"EmployeeID":20170017,"DateTimeRecord":"9/21/2017 9:59:21 PM","InOut":"1"},{"EmployeeID":20170014,"DateTimeRecord":"9/21/2017 10:36:48 PM","InOut":"1"},{"EmployeeID":20172,"DateTimeRecord":"9/22/2017 6:15:12 AM","InOut":"0"},{"EmployeeID":20170013,"DateTimeRecord":"9/22/2017 8:54:55 AM","InOut":"0"},{"EmployeeID":20170014,"DateTimeRecord":"9/22/2017 8:56:48 AM","InOut":"0"},{"EmployeeID":20170025,"DateTimeRecord":"9/22/2017 9:04:23 AM","InOut":"0"},{"EmployeeID":20160008,"DateTimeRecord":"9/22/2017 9:09:30 AM","InOut":"0"},{"EmployeeID":20170018,"DateTimeRecord":"9/22/2017 9:10:42 AM","InOut":"0"},{"EmployeeID":20160003,"DateTimeRecord":"9/22/2017 9:10:44 AM","InOut":"0"},{"EmployeeID":20170017,"DateTimeRecord":"9/22/2017 9:22:22 AM","InOut":"0"},{"EmployeeID":20170023,"DateTimeRecord":"9/22/2017 9:40:50 AM","InOut":"0"},{"EmployeeID":20170016,"DateTimeRecord":"9/22/2017 10:03:59 AM","InOut":"0"},{"EmployeeID":20170016,"DateTimeRecord":"9/22/2017 10:04:04 AM","InOut":"0"},{"EmployeeID":20170016,"DateTimeRecord":"9/22/2017 10:04:06 AM","InOut":"0"},{"EmployeeID":20160005,"DateTimeRecord":"9/22/2017 10:09:57 AM","InOut":"0"},{"EmployeeID":20160004,"DateTimeRecord":"9/22/2017 10:36:12 AM","InOut":"0"},{"EmployeeID":20160002,"DateTimeRecord":"9/22/2017 11:19:38 AM","InOut":"0"},{"EmployeeID":20172,"DateTimeRecord":"9/22/2017 3:27:14 PM","InOut":"1"},{"EmployeeID":20170013,"DateTimeRecord":"9/22/2017 4:00:20 PM","InOut":"1"},{"EmployeeID":20160003,"DateTimeRecord":"9/22/2017 4:58:32 PM","InOut":"1"},{"EmployeeID":20160003,"DateTimeRecord":"9/22/2017 5:33:35 PM","InOut":"1"},{"EmployeeID":20170009,"DateTimeRecord":"9/22/2017 6:09:17 PM","InOut":"1"},{"EmployeeID":20170016,"DateTimeRecord":"9/22/2017 6:16:42 PM","InOut":"1"},{"EmployeeID":20160002,"DateTimeRecord":"9/22/2017 6:41:52 PM","InOut":"1"},{"EmployeeID":20160004,"DateTimeRecord":"9/22/2017 6:48:07 PM","InOut":"1"},{"EmployeeID":20160008,"DateTimeRecord":"9/22/2017 6:50:09 PM","InOut":"1"},{"EmployeeID":20160005,"DateTimeRecord":"9/22/2017 7:18:04 PM","InOut":"1"},{"EmployeeID":20170025,"DateTimeRecord":"9/22/2017 7:20:48 PM","InOut":"1"},{"EmployeeID":20160002,"DateTimeRecord":"9/22/2017 7:46:51 PM","InOut":"1"},{"EmployeeID":20160005,"DateTimeRecord":"9/23/2017 9:03:55 AM","InOut":"0"},{"EmployeeID":20160005,"DateTimeRecord":"9/23/2017 4:06:02 PM","InOut":"0"},{"EmployeeID":20160005,"DateTimeRecord":"9/23/2017 4:06:16 PM","InOut":"1"},{"EmployeeID":20160002,"DateTimeRecord":"9/24/2017 11:46:13 AM","InOut":"0"},{"EmployeeID":20175,"DateTimeRecord":"9/25/2017 6:23:53 AM","InOut":"0"},{"EmployeeID":20176,"DateTimeRecord":"9/25/2017 8:03:57 AM","InOut":"0"},{"EmployeeID":20170016,"DateTimeRecord":"9/25/2017 8:51:28 AM","InOut":"0"},{"EmployeeID":20170025,"DateTimeRecord":"9/25/2017 8:52:03 AM","InOut":"0"},{"EmployeeID":20170017,"DateTimeRecord":"9/25/2017 9:10:21 AM","InOut":"0"},{"EmployeeID":20160003,"DateTimeRecord":"9/25/2017 9:10:31 AM","InOut":"0"},{"EmployeeID":20170013,"DateTimeRecord":"9/25/2017 9:13:04 AM","InOut":"0"},{"EmployeeID":20160008,"DateTimeRecord":"9/25/2017 9:13:20 AM","InOut":"0"},{"EmployeeID":20170009,"DateTimeRecord":"9/25/2017 9:14:40 AM","InOut":"0"},{"EmployeeID":20170023,"DateTimeRecord":"9/25/2017 9:25:11 AM","InOut":"0"},{"EmployeeID":20160002,"DateTimeRecord":"9/25/2017 10:11:17 AM","InOut":"0"},{"EmployeeID":20160004,"DateTimeRecord":"9/25/2017 10:34:53 AM","InOut":"0"},{"EmployeeID":20160005,"DateTimeRecord":"9/25/2017 12:52:54 PM","InOut":"0"},{"EmployeeID":20170018,"DateTimeRecord":"9/25/2017 1:32:47 PM","InOut":"0"},{"EmployeeID":20176,"DateTimeRecord":"9/25/2017 5:11:30 PM","InOut":"1"},{"EmployeeID":20170009,"DateTimeRecord":"9/25/2017 6:20:50 PM","InOut":"1"},{"EmployeeID":20170023,"DateTimeRecord":"9/25/2017 6:20:52 PM","InOut":"1"},{"EmployeeID":20160008,"DateTimeRecord":"9/25/2017 6:30:02 PM","InOut":"1"},{"EmployeeID":20160003,"DateTimeRecord":"9/25/2017 6:30:06 PM","InOut":"1"},{"EmployeeID":20160004,"DateTimeRecord":"9/25/2017 6:34:58 PM","InOut":"1"},{"EmployeeID":20170017,"DateTimeRecord":"9/25/2017 6:35:00 PM","InOut":"1"},{"EmployeeID":20170013,"DateTimeRecord":"9/25/2017 6:42:47 PM","InOut":"1"},{"EmployeeID":20170025,"DateTimeRecord":"9/25/2017 7:00:34 PM","InOut":"1"},{"EmployeeID":20160002,"DateTimeRecord":"9/25/2017 7:08:24 PM","InOut":"1"},{"EmployeeID":20175,"DateTimeRecord":"9/25/2017 7:34:46 PM","InOut":"1"},{"EmployeeID":20170018,"DateTimeRecord":"9/25/2017 8:09:15 PM","InOut":"1"}]');
		
		//Carbon::parse($value->DateTimeRecord)->format("Y-m-d");
		//date("H:i:s", strtotime($value->DateTimeRecord))
		$employee_in_out = null;
		// dd($all_data);


		/*for testing only*/
		// $test = null;
		// foreach ($all_data as $key => $value) 
		// {
		// 	if ($value->EmployeeID == 20160005) {
		// 		$test[] = $value;
		// 	}
			
		// }
		// dd($test);



		//first in last out rule
		foreach ($all_data as $key => $value) 
		{
			$date = date("Y-m-d", strtotime($value->DateTimeRecord));
			$time = date("H:i:s", strtotime($value->DateTimeRecord));


			if (!isset($employee_in_out[$date][$value->EmployeeID])) 
			{
				$employee_in_out[$date][$value->EmployeeID]['time_in'] = $time;
				$employee_in_out[$date][$value->EmployeeID]['time_out'] = $time;
			}
			else
			{
				if ($employee_in_out[$date][$value->EmployeeID]['time_in'] > $time) 
				{
					$employee_in_out[$date][$value->EmployeeID]['time_in'] = $time;
				}

				if ($employee_in_out[$date][$value->EmployeeID]["time_out"] < $time) 
				{
					$employee_in_out[$date][$value->EmployeeID]['time_out'] = $time;
				}
			}
		}

		// dd($employee_in_out);


		// $date = date("Y-m-d");
		$_insert[] = "";
		$_update[] = "";

		foreach ($employee_in_out as $key_date => $time_record) 
		{
			foreach ($time_record as $key => $value) 
			{
				$employee_info = Tbl_payroll_employee_basic::where('shop_id',17)->where('payroll_employee_number',$key)->first();

				$check_biometric_exist = Tbl_payroll_biometric_record::where("shop_id",17)
				->where("payroll_employee_id",$employee_info["payroll_employee_id"])
				->where("payroll_company_id",$employee_info["payroll_employee_company_id"])
				->where("payroll_time_date",$key_date)
				->first();

				if ($check_biometric_exist) 
				{
					Tbl_payroll_biometric_time_sheet::where('payroll_biometric_record_id',$check_biometric_exist->payroll_biometric_record_id)->delete();
					
					$insert_time['payroll_biometric_record_id'] 	= $check_biometric_exist->payroll_biometric_record_id;
					$insert_time["payroll_time_in"] 				= $value["time_in"];
					$insert_time["payroll_time_out"] 				= $value["time_out"];
					array_push($_update, $insert_time);
					Tbl_payroll_biometric_time_sheet::insert($insert_time);

				}
				else
				{
					$insert["shop_id"] 				= 17;
					$insert["payroll_employee_id"] 	= $employee_info["payroll_employee_id"];
					$insert["payroll_company_id"] 	= $employee_info["payroll_employee_company_id"];
					$insert["payroll_time_date"] 	= $key_date;
					

					$insert_time["payroll_biometric_record_id"] = Tbl_payroll_biometric_record::insertGetId($insert);
					$insert_time["payroll_time_in"] 		= 	$value["time_in"];
					$insert_time["payroll_time_out"] 		= $value["time_out"];
					array_push($_insert, $insert_time);
					Tbl_payroll_biometric_time_sheet::insert($insert_time);
				}
			}	
		}

		dd($_insert, $_update);
		
	}
}