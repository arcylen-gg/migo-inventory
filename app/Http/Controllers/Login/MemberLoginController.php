<?php
namespace App\Http\Controllers\Login;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_user;
use App\Models\Tbl_shop;
use Request;
use Redirect;
use Validator;
use Carbon\Carbon;
use Crypt;
use Session;
use DB;
use App\Globals\AuditTrail;

use App\Globals\Settings;
class MemberLoginController extends Controller
{

	public function logout()
	{
		Session::forget('user_email');
		Session::forget('user_password');
		Session::forget('product_info');
		Session::forget('customer_id');
		return Redirect::back();
	}
	public function login()
	{

		$shop_id = Settings::get_shop_id_url();
		$data['shop_id'] = $shop_id;
		if($shop_id != null)
		{
			$data['shop_info'] = Tbl_shop::where('shop_id', $shop_id)->first();
			$data['company_logo']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
		}
		Session::forget('product_info');
		// return Crypt::decrypt('eyJpdiI6InJJUjR1NFlvVURmWURPajBMdnpldXc9PSIsInZhbHVlIjoidGJPRTRmRHZkTkNKZENSU2lWZ3p2UT09IiwibWFjIjoiY2VhNTU2OTMzNTE0OTE0YzMzOGIyMzE5Y2VjY2NhZDgzMDcwNmI5ZTgyZjNmYTUwOWEwZTQ0MDA4M2ZkMGMxOCJ9');
		if(Request::isMethod("post"))
		{
			$user_info = Tbl_user::where("user_email", Request::input("email"))->first();

			/* CHECK E-MAIL EXIST */
			if($user_info)
			{

				$user_password = Crypt::decrypt($user_info->user_password);

				/* CHECK IF PASSWORD IS CORRECT */
				if(Request::input("password") == $user_password)
				{
					/* SAVE SESSION THEN REDIRECT TO MEMBER'S AREA */
					Session::put('user_email', $user_info->user_email);
					Session::put('user_password', $user_info->user_password);

					AuditTrail::record_logs("Logged In");
					
					return Redirect::to("/member");
				}
				else
				{
					return Redirect::to("/login")->with('message', "The E-Mail / Password is incorrect.")->withInput();
				}
			}
			else
			{
				return Redirect::to("/login")->with('message', "The E-Mail / Password is incorrect.")->withInput();
			}
		}
		else
		{

			return view('login.member.member_login', $data);
		}
		
	}
	public function register()
	{
		if(Request::isMethod("post"))
		{
			/* VALIDATION */
			$value["email"] = Request::input('email');
			$rules["email"] = ['required', 'min:5', 'email', 'unique:tbl_user,user_email'];
			$value["password"] = Request::input('password');
			$value["password_confirmation"] = Request::input("password_confirmation");
			$rules["password"] = ['required', 'min:5','confirmed'];
			$value["store_name"] = Request::input('store-name');
			$rules["store_name"] = ['required', 'min:3', 'unique:tbl_shop,shop_key', 'alpha_num'];
			$validator = Validator::make($value, $rules);

			if ($validator->fails()) //fail to login
			{
			    return Redirect::to("/register")->with('message', $validator->errors()->first())->withInput();
			}
			else
			{
				/* GET PH COUNTRY - IN THE FUTURE GET THE COUNTRY BASED ON IP ADDRESS */
				$country_id = Tbl_country::where("country_code", "PH")->value("country_id");

				/* INSERT SHOP INFORMATION */
				$insert_shop["shop_key"] = $value["store_name"];
				$insert_shop["shop_date_created"] = Carbon::now();
				$insert_shop["shop_date_expiration"] = Carbon::now()->addDays(31);
				$insert_shop["shop_last_active_date"] = Carbon::now();
				$insert_shop["shop_status"] = "initial";
				$insert_shop["shop_country"] = $country_id;
				$shop_id = Tbl_shop::insertGetId($insert_shop);

				/* INSERT USER INFORMATION */
				$insert_user["user_email"] = $value["email"];
				$insert_user["user_password"] = Crypt::encrypt($value["password"]);
				$insert_user["user_shop"] = $shop_id;
				$insert_user["user_date_created"] = Carbon::now();
				$insert_user["user_last_active_date"] = Carbon::now();
				$insert_user["user_level"] = "main";
				$user_id = Tbl_user::insertGetId($insert_user);

				/* SAVE SESSION THEN REDIRECT TO MEMBER'S AREA */
				$user_info = Tbl_user::where("user_id", $user_id)->first();
				Session::put('user_email', $user_info->user_email);
				Session::put('user_password', $user_info->user_password);
				return Redirect::to("/member");
			}
		}
		else
		{
			return view('login.member.member_register');
		}
	}
}