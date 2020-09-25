<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_user;
use App\Models\Tbl_shop;
use Request;
use Carbon\Carbon;
use Validator;
use Redirect;


class SetupController extends Member
{
	public function index()
	{
		if(Request::isMethod("post"))
		{
			/* VALIDATION */
			$value["first_name"] = Request::input('first_name');
			$rules["first_name"] = ['required'];
			$value["last_name"] = Request::input('last_name');
			$rules["last_name"] = ['required'];
			$value["street_address"] = Request::input('street_address');
			$rules["street_address"] = ['required'];
			$value["city"] = Request::input('city');
			$rules["city"] = ['required'];
			$value["zip"] = Request::input('postal_code');
			$rules["zip"] = ['required'];
			$value["country"] = Request::input('country');
			$rules["country"] = ['required','exists:tbl_country,country_id'];
			$value["contact_number"] = Request::input('contact_number');
			$rules["contact_number"] = ['required','min:7'];
			$validator = Validator::make($value, $rules);

			if ($validator->fails()) //fail to login
			{
			    return Redirect::to("/member/setup")->with('message', $validator->errors()->first())->withInput();
			}
			else
			{
				/* UPDATE USER INFORMATION */
				$update_user["user_first_name"] = ucfirst($value["first_name"]);
				$update_user["user_last_name"] =  ucfirst($value["last_name"]);;
				$update_user["user_contact_number"] = $value["contact_number"];
				$update_user["user_last_active_date"] = Carbon::now();
				Tbl_user::where("user_id", $this->user_info->user_id)->update($update_user);

				/* UPDATE SHOP INFROMATION */
				$update_shop["shop_status"] = "trial";
				$update_shop["shop_country"] = $value["country"];
				$update_shop["shop_city"] = ucfirst($value["city"]);
				$update_shop["shop_zip"] = $value["zip"];
				$update_shop["shop_street_address"] = $value["street_address"];
				$update_shop["shop_contact"] = $value["contact_number"];
				Tbl_shop::where("shop_id", $this->user_info->shop_id)->update($update_shop);

				return Redirect::to("/member");
			}
		}
		else
		{
			$data["_country"] = Tbl_country::get();
			return view('member.setup.setup', $data);
		}
	}
}