<?php

namespace App\Http\Controllers\Member;

use Carbon\Carbon;
use Session;
use Redirect;
use Request;
use Response;
use Input;
use App\Models\Tbl_shop;
use App\Models\Tbl_partners;
use App\Globals\Utilities;
use App\Globals\AuditTrail;

class Page_ThemesController extends Member
{
    
    public function view_filtering($id)
    {
	    dd($id);
	    $data["_company_info"] = $_company_info = Tbl_partners::where("company_location",$id)->orderBy("company_name")->get();
		return view('member/page/partnerview',$data);
	}

    public function delete_company_info($id)
	{
		$update['archived']="1";
		$whs_data = AuditTrail::get_table_data("tbl_partners","company_id",$id);
		$part  = Tbl_partners::where("company_id", $id)->first();
		Tbl_partners::where("company_id", $id)->update($update);
		AuditTrail::record_logs("DELETED: Partners ","Partners Company Name : ".$part->company_name,$id,serialize($whs_data),"");
        Redirect::to("/member/page/partnerview")->send();
	}

    public function edit_submit($id)
    {   

    	$insert["company_logo"] = Request::input("company_logo");
        $insert["company_name"] = Request::input("company_name");
		$insert["company_owner"] = Request::input("company_owner_name");
		$insert["company_brochure"] = Request::input("company_brochure");
		$insert["company_contactnumber"] = Request::input("company_contact_number");
		$insert["company_address"] = Request::input("company_address");
		$insert["company_location"] = Request::input("company_location");
		$insert["shop_id"] = $this->user_info->shop_id;
		$whs_data = AuditTrail::get_table_data("tbl_partners","company_id",$id);
		$part  = Tbl_partners::where("company_id", $id)->first();
		Tbl_partners::where("company_id", $id)->update($insert);
		$newdata =serialize($insert);
		AuditTrail::record_logs("EDITED: Partners ","Partners Company Name : ".$part->company_name,$id,serialize($whs_data),$newdata);
        Redirect::to("/member/page/partnerview")->send();
	}

    public function partnerinsert()
    {   
        $insert["company_logo"] = Request::input("company_logo");
        $insert["company_name"] = Request::input("company_name");
		$insert["company_owner"] = Request::input("company_owner_name");
		$insert["company_brochure"] = Request::input("company_brochure");
		$insert["company_contactnumber"] = Request::input("company_contact_number");
		$insert["company_address"] = Request::input("company_address");
		$insert["company_location"] = Request::input("company_location");
		$insert["shop_id"] = $this->user_info->shop_id;
		AuditTrail::record_logs("CREATED: Partners ","Partners Company Name : ".Request::input("company_name"),"","","");
        Tbl_partners::insert($insert);
        Redirect::to("/member/page/partnerview")->send();
	}

    public function partner()
    {
    	return view('member.page.partner');
	}

	public function partnerviewedit($id)
    {
	    $data["company_info"]  = Tbl_partners::where("company_id", $id)->first();
		return view('member.page.partnerviewedit',$data);
	}

	public function partnerview()
    {
	    $data["_company_info"]  = Tbl_partners::where('archived','=','0')->get();
	    $data['locationList'] = Tbl_partners::select('company_location')->where('archived','=','0')->distinct()->get();
		return view('member/page/partnerview', $data);
	}

	public function partnerFilterByLocation()
	{
		// if($request->ajax())
		// {
			$partnerResult = Tbl_partners::where('company_location', Request::input('locationVal'))
			->where('archived','0')
			->get();
			$partnerResultView = view('member.page.partner-filter-result', compact('partnerResult'))->render();
			return Response::json($partnerResultView);
		// }
	}

        
    public function index()
    {
    	$access = Utilities::checkAccess('page-theme', 'access_page');
		if($access == 1)
		{
			$data["page"] = "Vendor List";
			$dirs = scandir("../public/themes");
			$data["theme_color"] = $this->user_info->shop_theme_color;
			foreach($dirs as $key => $dir)
			{
				if($key > 1)
				{
					$string = file_get_contents("../public/themes/" . $dir . "/details.json");
					$json_a = json_decode($string, true);
					$themes[$dir] = $json_a;
					$themes[$dir]["key"] = $dir;
					$themes[$dir]["active"] = ($this->user_info->shop_theme == $dir ? "1" : "0");
					$themes[$dir]["color_scheme"] = implode(', ', array_keys($json_a["colors"]));
				}
			}

			$data["_themes"] = $themes;
	        return view('member.page.page_themes', $data);
		}
		else
		{
			return $this->show_no_access();
		}
    }
    public function popup_activate_form($key)
    {
    	$access = Utilities::checkAccess('page-theme', 'activate_theme');
		if($access == 1)
		{
			$data["page"] = "Activate Themes Form";
			$data["theme"] = file_get_contents("../public/themes/" . $key . "/details.json");
			$data["theme"] = json_decode($data["theme"], true);
			$data["key"] = $key;
	        return view('member.page.page_activate_form', $data);
		}
		else
		{
			return $this->show_no_access_modal();
		}
    }
    public function popup_activate_submit()
    {
    	$update["shop_theme_color"] = Request::input("shop_theme_color");
    	$update["shop_theme"] = Request::input("theme");
    	Tbl_shop::where("shop_id", $this->user_info->shop_id)->update($update);
    	$response["status"] = "success";
    	echo json_encode($response);
    }





}