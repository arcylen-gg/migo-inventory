<?php
namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use App\Models\Tbl_shop;
use App\Models\Tbl_payroll_employee_basic;
use Request;
use Redirect;
use Validator;
use Carbon\Carbon;
use Crypt;
use Session;
use DB;
use App\Globals\AuditTrail;

use App\Globals\Settings;

class EmployeeLoginController extends Controller
{

	public function employee_logout()
	{
		Session::forget('employee_email');
		Session::forget('employee_password');
		
		return Redirect::to("/employee_login");
	}

	public function employee_login()
	{

		$shop_id = Settings::get_shop_id_url();
		$data['shop_id'] = $shop_id;


		if(Request::isMethod("post")) 
		{
			$email 		= Request::input('email');
			$password 	= Request::input('password');

			$employee_info	= Tbl_payroll_employee_basic::where('payroll_employee_email',$email)->where('payroll_employee_tin',$password)->first();
			
			if ($employee_info) 
			{
				Session::put('employee_email',$email);
				Session::put('employee_password',$password);

				return Redirect::to("/employee");
			}
			else
			{
				return Redirect::to("/employee_login")->with('message', "The E-Mail / Password is incorrect.")->withInput();
			}
		}
		else
		{
			return view('member.payroll2.employee_dashboard.employee_login', $data);
		}

		// if($shop_id != null)
		// {
		// 	$data['shop_info'] = Tbl_shop::where('shop_id', $shop_id)->first();
		// 	$data['company_logo']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
		// }
		// Session::forget('product_info');
		// return Crypt::decrypt('eyJpdiI6InJJUjR1NFlvVURmWURPajBMdnpldXc9PSIsInZhbHVlIjoidGJPRTRmRHZkTkNKZENSU2lWZ3p2UT09IiwibWFjIjoiY2VhNTU2OTMzNTE0OTE0YzMzOGIyMzE5Y2VjY2NhZDgzMDcwNmI5ZTgyZjNmYTUwOWEwZTQ0MDA4M2ZkMGMxOCJ9');
		// if(Request::isMethod("post"))
		// {
		// 	dd(Request::input("email"));
		// 	$user_info = Tbl_payroll_employee_basic::where("payroll_employee_email", Request::input("email"))->first();

		// 	/* CHECK E-MAIL EXIST */
		// 	if($user_info)
		// 	{
		// 		//$user_password = Crypt::decrypt($user_info->payroll_employee_sss);
		// 		$user_password = $user_info->payroll_employee_sss;

		// 		/* CHECK IF PASSWORD IS CORRECT */
		// 		if(Request::input("password") == $user_password)
		// 		{
		// 			/* SAVE SESSION THEN REDIRECT TO MEMBER'S AREA */
		// 			Session::put('payroll_employee_email', $user_info->payroll_employee_email);
		// 			Session::put('payroll_employee_last_name', $user_info->payroll_employee_sss);

		// 			AuditTrail::record_logs("Logged In");
					
		// 			return Redirect::to("/employee");
		// 		}
		// 		else
		// 		{
		// 			return Redirect::to("/employee_login")->with('message', "The E-Mail / Password is incorrect.")->withInput();
		// 		}
		// 	}
		// 	else
		// 	{
		// 		return Redirect::to("/employee_login")->with('message', "The E-Mail / Password is incorrect.")->withInput();
		// 	}
		// }
		// else
		// {

		// 	return view('member.payroll2.employee_dashboard.employee_login', $data);
		// }
		
	}
	
}
