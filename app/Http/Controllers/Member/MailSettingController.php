<?php

namespace App\Http\Controllers\Member;

use Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Session;
use App\Http\Controllers\Controller;
use Redirect;

class MailSettingController extends Member
{
    public function index()
    {
    	$data["setting"] = collect(DB::table("tbl_settings")->where("shop_id", $this->user_info->shop_id)->get())->keyBy('settings_key');

        return view("member.settings.mail_setting", $data);
    }
    public function submit()
    {
    	$insert = Request::except("_token");

    	if ($insert["developer_password"] != "0SlO051O") 
    	{
    		die("Mismatched Password");
    	}
    	
    	unset($insert["developer_password"]);

    	foreach ($insert as $key => $value) 
    	{
    		$setting = DB::table("tbl_settings")->where("settings_key", $key);
    		$to["settings_key"] = $key;
    		$to["settings_value"] = $value;
    		$to["shop_id"] = $this->user_info->shop_id;
    		$to["settings_setup_done"] = 1;

    		if ($setting->first()) 
    		{
    			$setting->update($to);
    		}
    		else
    		{
    			DB::table("tbl_settings")->insert($to);
    		}
    	}

    	return Redirect::to("/member/mail_setting");
    }
}
