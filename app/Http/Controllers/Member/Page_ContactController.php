<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use App\Models\Tbl_shop;
use App\Models\Tbl_post_category;
use App\Models\Rel_post_category;
use Validator;
use DB;
use App\Globals\Post;
use App\Models\Tbl_post;
use App\Globals\Utilities;
use Redirect;

class Page_ContactController extends Member
{
    public function getIndex()
    {
        $access = Utilities::checkAccess('page-contact', 'access_page');
        if($access == 1)
        {
            $data["page"] = "Contact Information";
            $data["setting"] = collect(DB::table("tbl_settings")->where("shop_id", $this->user_info->shop_id)->get())->keyBy("settings_key")->map(function ($settings) 
            {
                return $settings->settings_value;
            });

            return view("member.page.page_contact", $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }

    public function postIndex()
    {
        $settings = Request::except("_token");

        foreach ($settings as $key => $value) 
        {
            $exist = DB::table("tbl_settings")->where("settings_key", $key)->where("shop_id", $this->user_info->shop_id)->first();
            
            if ($exist) 
            {
                $update["settings_value"] = $value;
                DB::table("tbl_settings")->where("settings_key", $key)->where("shop_id", $this->user_info->shop_id)->update($update);   
            }
            else
            {
                $insert["settings_key"] = $key;
                $insert["settings_value"] = $value;
                $insert["settings_setup_done"] = 1;
                $insert["shop_id"] = $this->user_info->shop_id;

                DB::table("tbl_settings")->insert($insert);
            }

            return Redirect::to("/member/page/contact");
        }
    }
}