<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use DB;
use Request;
use App\Models\Tbl_shop;
use App\Models\Tbl_payroll_employee_basic;

use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Globals\Payroll2;

class PayrollConnectController extends Controller
{
	public function index()
	{
		$appkey = Request::input("appkey");
		$appsecret = Request::input("appsecret");
		$email = Request::input("email");
		$password = Request::input("password");
		
	    /* VALIDATE SHOP AND GET SHOP INFORMATION */
	    $shop_return = $this->confirm_shop($appkey, $appsecret);

	    /* GET EMPLOYEE INFO IF SHOP IS VALID */
	    if(isset($shop_return["shop_info"]))
	    {
	    	$employee_return = $this->confirm_employee($email, $password, $shop_return["shop_info"]->shop_id);
	   		
	   		if(isset($employee_return["employee_info"]))
	   		{
	   			$employee_info = $employee_return["employee_info"];
	   		}
	    }
	    
		if($shop_return["status"] != "success")
		{
			$return["status"] = $shop_return["status"];
			$return["message"] = $shop_return["message"];
		}
		elseif($employee_return["status"] != "success")
		{
			$return["status"] = $employee_return["status"];
			$return["message"] = $employee_return["message"];
		}
		else
		{
			$return["status"] = "success";
		}
	    
	    /* RECORD TEST CONNECTION DATA */
	    $data["page"] = "Login Authentication";
	    $data["return"] = $return;
	    $data["input"] = Request::input();
	    $insert["test_data_serialize"] = serialize($data);
	    $insert["ip_address"] = $_SERVER['REMOTE_ADDR'];
	    DB::table("tbl_connection_test")->insert($insert);
	    echo json_encode($return);
	}
	public function confirm_shop($appkey, $appsecret)
	{
	    /* VALIDATE SHOP AND GET SHOP INFORMATION */
	    $shop_info = Tbl_shop::where("shop_api_key", $appkey)->first();

	    if($shop_info)
	    {
	    	$secret = md5($shop_info->shop_id . "-" . $shop_info->shop_api_key);
	    	
	    	if($secret == $appsecret)
	    	{
	    		$return["status"] = "success";
	    		$return["shop_info"] = $shop_info;
	    	}
	    	else
	    	{
			    $return["status"] = "Error Code 224";
			    $return["message"] = "The app you are using might be outdated or illegal. The app secret didn't match our record.";
	    	}
	    }
	    else
	    {
		    $return["status"] = "Error Code 223";
		    $return["message"] = "The app you are using might be outdated. The system can't verify the appkey that your software is using.";
	    }
	    
	    return $return;
	}
	public function confirm_employee($email, $password, $shop_id)
	{
		$employee_basic = Tbl_payroll_employee_basic::where("shop_id", $shop_id)->where("payroll_employee_email", $email)->first();
		
		if($employee_basic)
		{
		    $return["status"] = "success";
		    $return["message"] = "You have successfully logged in.";
		    $return["employee_info"] = $employee_basic;
		}
		else
		{
		    $return["status"] = "Error Code 226";
		    $return["message"] = "The account you're trying to login doesn't exist.";
		}
		
		return $return;
		
	}
	public function get_cutoff_data()
	{

		$appkey = Request::input("appkey");
		$appsecret = Request::input("appsecret");
		$email = Request::input("email");
		$password = Request::input("password");
		$time_sheet_record_id = Request::input("time_sheet_record_id");
		
	    /* VALIDATE SHOP AND GET SHOP INFORMATION */
	    $shop_return = $this->confirm_shop($appkey, $appsecret);

	    /* GET EMPLOYEE INFO IF SHOP IS VALID */
	    if(isset($shop_return["shop_info"]))
	    {
	    	$employee_return = $this->confirm_employee($email, $password, $shop_return["shop_info"]->shop_id);
	   		
	   		if(isset($employee_return["employee_info"]))
	   		{
	   			$employee_info = $employee_return["employee_info"];
	   		}
	    }
		
		if($shop_return["status"] != "success")
		{
			$return["status"] = $shop_return["status"];
			$return["message"] = $shop_return["message"];
		}
		elseif($employee_return["status"] != "success")
		{
			$return["status"] = $employee_return["status"];
			$return["message"] = $employee_return["message"];
		}
		else
		{
	    	$return["status"] = "success";
	    		
	    	/* NO TIME SHEET RECORD - CREATE NEW RECORD */
	    	if($time_sheet_record_id == 0)
	    	{
	    		$payroll_time_sheet = Tbl_payroll_time_sheet::where("payroll_employee_id", $employee_info->payroll_employee_id)->where("payroll_time_date", date("Y-m-d"))->first();
	    		
	    		/* INSERT TIME SHEET IF THERE ISN'T ANY */
	    		if(!$payroll_time_sheet)
	    		{
	    			$insert_time_sheet["payroll_employee_id"] = $employee_info->payroll_employee_id;
	    			$insert_time_sheet["payroll_time_date"] = date("Y-m-d");
	    			Tbl_payroll_time_sheet::insert($insert_time_sheet);
	    			$payroll_time_sheet = Tbl_payroll_time_sheet::where("payroll_employee_id", $employee_info->employee_id)->where("payroll_time_date", date("Y-m-d"))->first();
	    		}
	 
	    		/* CREATE RECORD */
	    		$insert_record["payroll_time_sheet_id"] = $payroll_time_sheet->payroll_time_sheet_id;
	    		$insert_record["payroll_company_id"] = $employee_info->payroll_employee_company_id;
	    		$insert_record["payroll_time_sheet_in"] = date("H:i:s");
	    		$insert_record["payroll_time_sheet_out"] = date("H:i:s");
	    		$insert_record["payroll_time_sheet_origin"] = "DG TIMER";
	    		$time_sheet_record_id = Tbl_payroll_time_sheet_record::insertGetId($insert_record);
	    		
	    		$payroll_time_sheet_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $time_sheet_record_id)->first();
	    	}
	    	else //HAS TIME SHEET RECORD - UPDATE TIME OUT
	    	{
	    		$payroll_time_sheet_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $time_sheet_record_id)->first();
	    		$payroll_time_sheet = Tbl_payroll_time_sheet::where("payroll_time_sheet_id", $payroll_time_sheet_record->payroll_time_sheet_id)->first();
	    	
	    		$update_record["payroll_time_sheet_out"] = date("H:i:s", time());
	    		Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $time_sheet_record_id)->update($update_record);

	    		$payroll_time_sheet_record = Tbl_payroll_time_sheet_record::where("payroll_time_sheet_record_id", $time_sheet_record_id)->first();
	    	}

	    	Tbl_payroll_time_sheet_record_approved::where("payroll_time_sheet_id", $payroll_time_sheet->payroll_time_sheet_id)->delete();
	    	$daily_info = Payroll2::timesheet_process_daily_info($employee_info->payroll_employee_id, date("Y-m-d"), $payroll_time_sheet, 0);
			
			$return["overtime"] = $daily_info->time_output["overtime"];
			$return["undertime"] = $daily_info->time_output["undertime"];
			$return["late"] = $daily_info->time_output["late"];
			$return["night_differential"] = $daily_info->time_output["night_differential"];
			$return["time_spent"] = $daily_info->time_output["time_spent"];
			//dd($daily_info->time_output);
			
			$return["payroll_time_sheet_record_id"] = $payroll_time_sheet_record->payroll_time_sheet_record_id;
		}
	    
	    /* RECORD TEST CONNECTION DATA */
	    $data["page"] = "Login Authentication";
	    $data["return"] = $return;
	    $data["input"] = Request::input();
	    $insert["test_data_serialize"] = serialize($data);
	    $insert["ip_address"] = $_SERVER['REMOTE_ADDR'];
	    DB::table("tbl_connection_test")->insert($insert);
	    echo json_encode($return);
	}
}