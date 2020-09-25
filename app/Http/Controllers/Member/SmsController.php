<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_sms_key;
use App\Models\Tbl_sms_template;
use App\Models\Tbl_sms_default_key;
use App\Models\Tbl_sms_logs;
use App\Models\Tbl_user;

use App\Globals\Sms;

use Carbon\Carbon;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use DB;
use Crypt;
use Session;

/**
 * Ecommerce Product Module - all product related module
 * 
 * @author Bryan Kier Aradanas
 */

class SmsController extends Member
{
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	public function getIndex()
	{
		$data["sms_key"] 	= Tbl_sms_key::where("sms_shop_id", $this->getShopId())->first();
		$data["_sms"] 		= Tbl_sms_default_key::template($this->getShopId())->get();
		$data["user"]		= $this->user_info;
		$data["sms_balance"]= Sms::getSmsBalance();

		return view('member.sms.sms', $data);
	}

	public function getSmsModal($key)
	{
		$data["sms"] = Tbl_sms_default_key::template($this->getShopId())->where("sms_default_key", $key)->first();
		return view('member.sms.sms_modal', $data);
	}

	public function postUpdate()
	{
		$data["sms_temp_shop_id"] 	= $this->getShopId();
		$data["sms_temp_key"] 		= Request::input('sms_temp_key');
		$data["sms_temp_is_on"]		= Request::input('sms_temp_is_on') ? Request::input('sms_temp_is_on') : 0;
		$data["sms_temp_content"] 	= Request::input('sms_temp_content');

		$authorization_key = Tbl_sms_key::where("sms_shop_id", $this->getShopId())->first();

		if($authorization_key)
		{
			$sms_data	= Tbl_sms_template::where("sms_temp_shop_id", $this->getShopId())->where("sms_temp_key", $data["sms_temp_key"])->first();
			if($sms_data)
			{
				Tbl_sms_template::where("sms_temp_id", $sms_data->sms_temp_id)->update($data);
			}
			else
			{
				Tbl_sms_template::insert($data);
			}
		}
		else
		{
			$json["status"] 	= "error";
			$json["type"] 		= "sms";
			$json["message"]	= "Authorization Key Required!";
			return json_encode($json);	
		}

		$json["status"] 	= "success";
		$json["type"] 		= "sms";
		$json["message"]	= "Success ";
		return json_encode($json);
	}

	public function postAuthorizationKey()
	{
		$data["sms_shop_id"] 			= $this->getShopId();
		$data["sms_authorization_key"]  = Request::input('sms_authorization_key');

		$sms_data	= Tbl_sms_key::where("sms_shop_id", $data["sms_shop_id"])->first();
		if($sms_data)
		{
			Tbl_sms_key::where("sms_id", $sms_data->sms_id)->update($data);
		}
		else
		{
			Tbl_sms_key::insert($data);
		}

		$json["status"] 	= "success";
		$json["type"] 		= "authorization_key";
		$json["message"]	= "Success ";
		return json_encode($json);
	}

	public function getLogs()
	{
		$data["_sms_system_logs"] = Tbl_sms_logs::where("sms_logs_shop_id", $this->getShopId())->orderBy("sms_logs_id", "DESC")->paginate(10);

		$data["_sms_api_logs"]	  = Sms::getSmsLogs();

		return view('member/sms/sms_logs', $data);
	}
}
 