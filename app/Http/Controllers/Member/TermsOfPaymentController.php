<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Models\Tbl_user;
use App\Models\Tbl_terms;

use Carbon\Carbon;
use Request;
use Response;
use Image;
use Validator;
use Redirect;
use File;
use Crypt;
use URL;
use Session;

/**
 * Terms of Payment Module - all terms of payment related module
 *
 * @author Bryan Kier Aradanas
 */

class TermsOfPaymentController extends Member
{
    public function hasAccess($page_code, $acces)
    {
        $access = Utilities::checkAccess($page_code, $acces);
        if($access == 1) return true;
        else return false;
    }

    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function getList()
    {
        $active_terms       = Tbl_terms::where("archived", 0)->where("terms_shop_id", $this->getShopId()); 
        $inactive_terms     = Tbl_terms::where("archived", 1)->where("terms_shop_id", $this->getShopId());

        /* Filter Terms By Search */
        $search = Request::input('search');
        if($search)
        {
            $active_terms   = $active_terms->where("terms_name","like","%$search%");
            $inactive_terms = $inactive_terms->where("terms_name","like","%$search%");
        }
        
        $data["active_terms"]  = $active_terms->paginate(10);
        $data["inactive_terms"]= $inactive_terms->paginate(10);

        return view('member.terms.terms', $data);
    }

    public function getTerms()
    {
        $data['name'] = "";

        $terms_id = Request::input('id');
        if($terms_id)
        {
            $data["terms"] = Tbl_terms::where("terms_id", $terms_id)->where("terms_shop_id", $this->getShopId())->first(); 
        }

        return view('member.terms.modal_create_terms', $data);
    }

    public function getLoadTerms()
    {
        $data['_terms'] = Tbl_terms::where("archived", 0)->where("terms_shop_id", $this->getShopId())->get(); 

        return view('member.load_ajax_data.load_terms', $data);
    }


    public function postTerms()
    {
        $data["terms_name"]         = Request::input("terms_name");
        $data["terms_no_of_days"]   = Request::input("terms_no_of_days");
        $terms_id                   = Request::input('terms_id');

        $rules["terms_name"]        = "required";
        $rules["terms_no_of_days"]  = "required";

        $terms_exist = Tbl_terms::where("terms_shop_id", $this->getShopId())->where("terms_name", $data["terms_name"])->first();

        $validator = Validator::make($data, $rules);
        if($validator->fails())
        {
            $json["status"] = "error";
            $json["message"]= $validator->errors()->first();
        }
        elseif($terms_exist)
        {
            $json["status"] = "error";
            $json["message"]= "Terms Name Already Exist";
        }
        else
        {
            if($terms_id)
            {
                Tbl_terms::where("terms_id", $terms_id)->update($data);
            }
            else
            {
                $data["terms_shop_id"]  = $this->getShopId();
                $data["created_at"]     = Carbon::now();
                $terms_id = Tbl_terms::insertGetId($data);
            }

            $json["status"]     = "success";
            $json["type"]       = "terms";
            $json["message"]    = "Success";
            $json["terms_id"]   = $terms_id;
        }

        return json_encode($json);
    }
	
}