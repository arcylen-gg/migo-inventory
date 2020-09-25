<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Models\Tbl_user;
use App\Models\Tbl_locale;

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
 * Location Module - all location related module
 *
 * @author Bryan Kier Aradanas
 */

class LocationController extends Member
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
        $search_province    = Request::input("search_province");
        $search_city        = Request::input("search_city");
        $search_barangay    = Request::input("search_barangay");


        $data["_province"]  = Tbl_locale::where("locale_parent", 0);
        if($search_province) $data["_province"]->where("locale_name","like","%" . $search_province . "%");
        $data["_province"]  = $data["_province"]->orderBy("locale_name")->get();

        $city_parent        = Request::input("city_parent") ? Request::input("city_parent") : $data["_province"][0]->locale_id; 
        $data["_city"]      = Tbl_locale::where("locale_parent", $city_parent);
        if($search_city) $data["_city"]->where("locale_name","like","%" . $search_city . "%");
        $data["_city"]      = $data["_city"]->orderBy("locale_name")->get();


        $barangay_parent    = Request::input("barangay_parent") ? Request::input("barangay_parent") : $data["_city"][0]->locale_id; 
        $data["_barangay"]  = Tbl_locale::where("locale_parent", $barangay_parent);
        if($search_barangay) $data["_barangay"]->where("locale_name","like","%" . $search_barangay . "%");
        $data["_barangay"]  = $data["_barangay"]->orderBy("locale_name")->get();

        $data["parent_city"]        = $city_parent;
        $data["parent_city_name"]   = Tbl_locale::where("locale_id", $city_parent)->value("locale_name");

        $data["parent_barangay"]    = $barangay_parent;
        $data["parent_barangay_name"]= Tbl_locale::where("locale_id", $barangay_parent)->value("locale_name");

        return view('member.location.location', $data);
    }

    public function getLocation()
    {
        $data['name']       = "";
        $data["parent_id"]  = Request::input("parent_id");
        $data["title"]      = Request::input("title");

        $location_id = Request::input('id');
        if($location_id)
        {
            $data["location"] = Tbl_locale::where("locale_id", $location_id)->first(); 
        }

        return view('member.location.modal_create_location', $data);
    }

    public function postLocation()
    {
        $data["locale_parent"]  = Request::input("parent_id");
        $data["locale_name"]    = Request::input("location_name");
        $location_id            = Request::input("location_id");

        if(!$location_id)
        {
            $location_id = Tbl_locale::insertGetId($data);
        }
        else
        {
            Tbl_locale::where("locale_id", $location_id)->update($data);
        }

        $json["status"]         = "success";
        $json["message"]        = "Success!";
        $json["location_id"]    = $location_id;

        return json_encode($json);
    }

    public function getDeleteLocation()
    {
        $data["location_id"]    = Request::input("id");
        $data["location"]       = Tbl_locale::where("locale_id", $data["location_id"])->first(); 
        $data["title"]          = Request::input("title");

        return view('member.location.modal_delete_location', $data);
    }

    public function postDeleteLocation()
    {
        $location_id            = Request::input("location_id");
        
        Tbl_locale::where("locale_id", $location_id)->delete();

        $json["status"]         = "success";
        $json["message"]        = "Success!";

        return json_encode($json);
    }
	
}