<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DOMDocument;
use Config;
use DB;
use Response;
use Input;
use App\Globals\Mail_global;
use App\Models\Tbl_user;
use App\Models\Tbl_email_template;
use App\Models\Tbl_partners;

class ShopPartnersController extends Shop
{
    public function index()
    {
        $data["page"] = "Partners";
        $data["_company_information"]  = Tbl_partners::orderBy('company_address')->where("shop_id", $this->shop_info->shop_id)->where("archived",0)->get();
        $data['locationList'] = Tbl_partners::select('company_location')->distinct()->where("archived","0")->get();
        return view("partners", $data);
    }
    public function partnerFilterByLocation()
    { 
        
        if(Request::input('locationVal')=="ALL")
        {
         $partnerResult = Tbl_partners::where("shop_id", $this->shop_info->shop_id)->where("archived","0")->get();
            $partnerResultView = view('partner-filter-result', compact('partnerResult'))->render();
            return Response::json($partnerResultView);   
        }
        else
        {
                 $partnerResult = Tbl_partners::where('company_location', '=', Request::input('locationVal'))->where("shop_id", $this->shop_info->shop_id)->where("archived","0")->get();
            $partnerResultView = view('partner-filter-result', compact('partnerResult'))->render();
            return Response::json($partnerResultView);   
        }

    }

    public function partners_views()
    {
        $id = Request::input('i');
        $data["page"] = "Partners Views";
        $data['company'] = Tbl_partners::where('company_id', $id)->where('archived',0)->where('shop_id', $this->shop_info->shop_id)->first();
        $data['_company'] = Tbl_partners::where('archived',0)->where('shop_id', $this->shop_info->shop_id)->get();
        return view("partners_views", $data);
    }
}