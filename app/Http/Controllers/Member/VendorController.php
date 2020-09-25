<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Models\Tbl_user;
use App\Models\Tbl_vendor;
use App\Models\Tbl_vendor_address;
use App\Models\Tbl_vendor_other_info;
use App\Models\Tbl_country;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_term;
use App\Models\Tbl_delivery_method;
use App\Models\Tbl_vendor_item;
use App\Models\Tbl_vendor_attachment;
use App\Models\Tbl_vendor_bank_record;

use App\Globals\Vendor;
use App\Globals\AuditTrail;
use App\Globals\Utilities;
use App\Globals\Item;
use App\Globals\Warehouse2;
use App\Globals\AccountingTransaction;
use App\Globals\Terms;
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
use Input;

/**
 * Vendor Module - all vendor related module
 *
 * @author Bryan Kier Aradanas
 */

class VendorController extends Member
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
    public function getArchived($id, $action)
    {
        $data["vendor_id"] = $id;
        $data["vendor_info"] = Tbl_vendor::where("vendor_id",$id)->first();
        $data["action"] = $action;

        return view("member.vendor.vendor_confirm",$data);
    }
    public function postArchivedsubmit()
    {
        $id = Request::input("vendor_id");
        $action = Request::input("action");

        $update["archived"] = 0;
        if($action == "inactive")
        {
            $update["archived"] = 1;
        }

        Tbl_vendor::where("vendor_id",$id)->update($update);

        $json["status"] = "success";
        $json["type"]       = "vendor";
        $json["vendor_id"]  = $id;

        $vendor_data = Tbl_vendor::info()->where("vendor_id",$id)->first()->toArray();
        $remarks = $vendor_data['archived'] == 1 ? 'Inactive' : 'Active';
        AuditTrail::record_logs($remarks,"vendor",$id,"",serialize($vendor_data));

        return json_encode($json);
    }
	public function getList()
	{
        if($this->hasAccess("vendor-list","access_page"))
        {
            $data['_vendor'] = Tbl_vendor::info()->balanceJournal()->where('vendor_shop_id', $this->getShopId())
                                        ->orderBy('created_date','DESC')
                                        ->where("tbl_vendor.archived",0)
                                        ->paginate(5);

            $data['_archived_vendor'] = Tbl_vendor::info()->where('vendor_shop_id', $this->getShopId())
                                        ->orderBy('created_date', 'DESC')
                                        ->where("tbl_vendor.archived",1)
                                        ->paginate(5);

            /* IF REQUEST TYPE IS AJAX = RETURN ONLY TABLE DATA */ 
            if(Request::ajax())
            {
             return $this->getLoadVendorTbl();
            }

    		return view('member.vendor.vendor',$data);
        }
        else
        {
            return $this->show_no_access();
        }
	}
    public function getTag($id)
    {
        $data["vendor"] = Tbl_vendor::where("vendor_id",$id)->first();
        $type[0] = 1; 
        $data["_item"] = Item::get_all_category_item($type);

        $data["vendor_item"] = Tbl_vendor_item::item()->where("tag_vendor_id",$id)->get();


        return view("member.vendor.vendor_tag_item",$data);
    }
    public function postUpdateTaggingItem()
    {
        $item_id = Request::input("item_id");
        $vendor_id = Request::input("vendor_id");
        $item_quotation = Request::input("item_qt");

        foreach ($item_id as $key => $value) 
        {
           if($value == "")
           {
                unset($item_id[$key]);
           }
        }
        if($item_id != null)
        {
            Tbl_vendor_item::where("tag_vendor_id",$vendor_id)->delete();
            foreach ($item_id as $key => $value) 
            {
                $ctr = Tbl_vendor_item::where("tag_item_id",$value)->where("tag_vendor_id", $vendor_id)->count();
                if($ctr == 0 && is_numeric($item_quotation[$key]))
                {
                    $ins["tag_vendor_id"] = $vendor_id; 
                    $ins["tag_item_id"] = $value;
                    $ins["tag_item_quotation"] = $item_quotation[$key];

                    Tbl_vendor_item::insert($ins);                    
                }
            }

            $return["status"] = "success";
        }
        else
        {            
            $return["status"] = "error";
            $return["error"] = "Please Select Item";
        }

        return json_encode($return);
    }

    public function getLoadVendorTbl()
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->getShopId());
        
        $search_keyword = Request::input('search_keyword');
        $status = Request::input('status');

        $check_settings = AccountingTransaction::settings($this->getShopId(), 'allow_transaction');

        $vendor = Tbl_vendor::info()->where('vendor_shop_id', $this->getShopId())
                                    ->orderBy('created_date', 'DESC')
                                    ->where("tbl_vendor.archived",$status);
        if($check_settings == 1)
        {
            $vendor = $vendor->whereNull('vendor_warehouse_id')->orWhere('vendor_warehouse_id', $warehouse_id);
        }
        if($search_keyword)
        {
            $vendor->where(function($q) use ($search_keyword)
            {
                $q->orWhere("vendor_company","LIKE", "%$search_keyword%");
                $q->orWhere("vendor_first_name","LIKE", "%$search_keyword%");
                $q->orWhere("vendor_middle_name","LIKE", "%$search_keyword%");
                $q->orWhere("vendor_last_name","LIKE", "%$search_keyword%");
                $q->orWhere("vendor_email","LIKE", "%$search_keyword%");
            });
        }

        $data['_vendor']  = $vendor->paginate(5);
        return view('member.vendor.load_vendor_tbl', $data);  
    }
    public function load_vendor()
    {        
        $data["_vendor"]    = Vendor::getAllVendor('active');

        return view("member.load_ajax_data.load_vendor",$data);
    }
    public function getLoadVendorTagItem()
    {
        $item_id = Request::input("item_id");
        
        $data["_vendor"]    = Vendor::getAllVendor('active', $item_id);
        return view("member.load_ajax_data.load_vendor",$data);   
    }
    public function getAdd()
    {
        if($this->hasAccess("vendor-list","add"))
        {
            $shop_id = $this->getShopId();
            $data['_country']               = Tbl_country::orderBy('country_name','asc')->get();
            $data['_def_payment_method']    = Tbl_payment_method::where("isDefault",1)->orderBy('payment_name','asc')->get();
            $data['_payment_method']        = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->orderBy('payment_name','asc')->get();
            $data["_terms"]                 = Terms::active_terms($shop_id);
            //$data['_term']                  = Tbl_term::where('shop_id',$shop_id)->where('archived',0)->orderBy('term_name','asc')->get();
            $data['_delivery_method']       = Tbl_delivery_method::where('archived',0)->get();
            //dd($data);
            return view('member.vendor.vendor_add_modal', $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function postAdd()
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->getShopId());
        $insert_vendor["vendor_shop_id"]        = $this->getShopId();
        $insert_vendor["vendor_warehouse_id"]   = $warehouse_id;
        $insert_vendor["vendor_title_name"]     = Request::input("vendor_title_name");
        $insert_vendor["vendor_first_name"]     = Request::input("vendor_first_name");
        $insert_vendor["vendor_middle_name"]    = Request::input("vendor_middle_name");
        $insert_vendor["vendor_last_name"]      = Request::input("vendor_last_name");
        $insert_vendor["vendor_suffix_name"]    = Request::input("vendor_suffix_name");
        $insert_vendor["vendor_email"]          = Request::input("vendor_email");
        $insert_vendor["vendor_company"]        = Request::input("vendor_company");
        $insert_vendor["created_date"]          = Carbon::now();


        $comfname = $insert_vendor["vendor_first_name"].$insert_vendor["vendor_middle_name"].$insert_vendor["vendor_last_name"].$insert_vendor["vendor_company"]; 
        if($comfname == '')
        {
            $json['status'] = 'error';
            $json['status_message'] = 'Please insert company or a contact person.';
            return json_encode($json);
        }
        $vendor_id = Tbl_vendor::insertGetId($insert_vendor);

        $insert_addr["ven_addr_vendor_id"]      = $vendor_id;
        $insert_addr["ven_billing_country_id"]  = Request::input("ven_billing_country_id");
        $insert_addr["ven_billing_country_id"]  = Request::input("ven_billing_country_id");
        $insert_addr["ven_billing_city"]        = Request::input("ven_billing_city");
        $insert_addr["ven_billing_zipcode"]     = Request::input("ven_billing_zipcode");
        $insert_addr["ven_billing_street"]      = Request::input("ven_billing_street");
        $insert_addr["ven_shipping_country_id"] = Request::input("ven_shipping_country_id");
        $insert_addr["ven_shipping_state"]      = Request::input("ven_shipping_state");
        $insert_addr["ven_shipping_city"]       = Request::input("ven_shipping_city");
        $insert_addr["ven_shipping_zipcode"]    = Request::input("ven_shipping_zipcode");
        $insert_addr["ven_shipping_street"]     = Request::input("ven_shipping_street");

        Tbl_vendor_address::insert($insert_addr);

        $insert_info["ven_info_vendor_id"]      = $vendor_id;
        $insert_info["ven_info_phone"]          = Request::input("ven_info_phone");
        $insert_info["ven_info_mobile"]         = Request::input("ven_info_mobile");
        $insert_info["ven_info_fax"]            = Request::input("ven_info_fax");
        $insert_info["ven_info_other_contact"]  = Request::input("ven_info_other_contact");
        $insert_info["ven_info_website"]        = Request::input("ven_info_website");
        $insert_info["ven_info_display_name"]   = Request::input("ven_info_display_name");
        $insert_info["ven_info_print_name"]     = Request::input("ven_info_print_name");
        $insert_info["ven_info_billing"]        = Request::input("ven_info_billing");
        $insert_info["ven_info_payment_method"] = Request::input("ven_info_payment_method");
        $insert_info["ven_info_delivery_method"] = Request::input("ven_info_delivery_method");
        $insert_info["ven_info_terms"]           = Request::input("ven_info_terms");
        $insert_info["ven_info_opening_balance"] = Request::input("ven_info_opening_balance");
        $insert_info["ven_info_balance_date"]    = Request::input("ven_info_balance_date");
        $insert_info["ven_info_opening_balance"] = Request::input("ven_info_opening_balance");
        $insert_info["ven_info_balance_date"]    = Request::input("ven_info_balance_date");
        $insert_info["ven_info_notes"]           = Request::input("notes");
        $insert_info["ven_info_tax_no"]          = Request::input("tax_resale_no");
        $insert_info["ven_info_tin_no"]          = Request::input("tin_number");

        Tbl_vendor_other_info::insert($insert_info);

        $bank_acct_name = Request::input("vendor_account_name");
        $bank_acct_no = Request::input("vendor_account_number");
        foreach ($bank_acct_name as $key => $value) 
        {
            if($value && $bank_acct_no[$key])
            {
                $ins_bank["vendor_id"] = $vendor_id;
                $ins_bank["vendor_account_name"] = $value;
                $ins_bank["vendor_account_number"] = $bank_acct_no[$key];
                Tbl_vendor_bank_record::insert($ins_bank);
            }
        }
        // Tbl_vendor_attachment
        if(Request::has('fileurl'))
        {
            $fileurl = Request::input('fileurl');
            $filename = Request::input("filename");
            $mimetype = Request::input('mimetype');
            $attachment = '';
            foreach($fileurl as $key => $url){
                $attachment[$key]['vendor_id'] = $vendor_id;
                $attachment[$key]['vendor_attachment_path'] = $url;
            }
            foreach($filename as $key => $name){
                $attachment[$key]['vendor_attachment_name'] = $name;
            }
            
            foreach($mimetype as $key => $mime)
            {
                $attachment[$key]['mime_type'] = $mime;
            }
            
            if($attachment != ''){
                Tbl_vendor_attachment::insert($attachment);
            }
        }
        
        $json["status"]     = "success";
        $json["type"]       = "vendor";
        $json["vendor_id"]  = $vendor_id;
        $json["call_function"]  = "success_vendor";

        $vendor_data = Tbl_vendor::info()->where("vendor_id",$vendor_id)->first()->toArray();
        AuditTrail::record_logs("Added","vendor",$vendor_id,"",serialize($vendor_data));

        return json_encode($json);
    }
    public function getEdit($vendor_id)
    {
        if($this->hasAccess("vendor-list","edit"))
        {
            $shop_id = $this->getShopId();
            $data['_country']               = Tbl_country::orderBy('country_name','asc')->get();
            $data['_def_payment_method']    = Tbl_payment_method::where("isDefault",1)->orderBy('payment_name','asc')->get();
            $data['_payment_method']        = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->orderBy('payment_name','asc')->get();
            $data["_terms"]                 = Terms::active_terms($shop_id);
            //$data['_term']                  = Tbl_term::where('shop_id',$shop_id)->where('archived',0)->orderBy('term_name','asc')->get();
            $data['_delivery_method']       = Tbl_delivery_method::where('archived',0)->get();
            $data['_attachment']            = Tbl_vendor_attachment::where("vendor_id", $vendor_id)->get();
            $data['_bank_record']           = Tbl_vendor_bank_record::where("vendor_id", $vendor_id)->get();
            $data['vendor']                 = Tbl_vendor::info()->where("vendor_id", $vendor_id)->first();
            return view('member.vendor.vendor_add_modal', $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function postEdit($vendor_id)
    {        
        $old_vendor_data = Tbl_vendor::info()->where("vendor_id",$vendor_id)->first()->toArray();
        $warehouse_id = Warehouse2::get_current_warehouse($this->getShopId());

        // dd(Request::input());
        $update_vendor["vendor_warehouse_id"]   = $warehouse_id;
        $update_vendor["vendor_title_name"]     = Request::input("vendor_title_name");
        $update_vendor["vendor_first_name"]     = Request::input("vendor_first_name");
        $update_vendor["vendor_middle_name"]    = Request::input("vendor_middle_name");
        $update_vendor["vendor_last_name"]      = Request::input("vendor_last_name");
        $update_vendor["vendor_suffix_name"]    = Request::input("vendor_suffix_name");
        $update_vendor["vendor_email"]          = Request::input("vendor_email");
        $update_vendor["vendor_company"]        = Request::input("vendor_company");

        $comfname = $update_vendor["vendor_first_name"].$update_vendor["vendor_middle_name"].$update_vendor["vendor_last_name"].$update_vendor["vendor_company"]; 
        if($comfname == '')
        {
            $json['status'] = 'error';
            $json['status_message'] = 'Please insert company or a contact person.';
            return json_encode($json);
        }

        Tbl_vendor::where("vendor_id", $vendor_id)->update($update_vendor);

        $update_addr["ven_billing_country_id"]  = Request::input("ven_billing_country_id");
        $update_addr["ven_billing_country_id"]  = Request::input("ven_billing_country_id");
        $update_addr["ven_billing_city"]        = Request::input("ven_billing_city");
        $update_addr["ven_billing_zipcode"]     = Request::input("ven_billing_zipcode");
        $update_addr["ven_billing_street"]      = Request::input("ven_billing_street");
        $update_addr["ven_shipping_country_id"] = Request::input("ven_shipping_country_id");
        $update_addr["ven_shipping_state"]      = Request::input("ven_shipping_state");
        $update_addr["ven_shipping_city"]       = Request::input("ven_shipping_city");
        $update_addr["ven_shipping_zipcode"]    = Request::input("ven_shipping_zipcode");
        $update_addr["ven_shipping_street"]     = Request::input("ven_shipping_street");

        Tbl_vendor_address::where("ven_addr_vendor_id", $vendor_id)->update($update_addr);

        $update_info["ven_info_phone"]          = Request::input("ven_info_phone");
        $update_info["ven_info_mobile"]         = Request::input("ven_info_mobile");
        $update_info["ven_info_fax"]            = Request::input("ven_info_fax");
        $update_info["ven_info_other_contact"]  = Request::input("ven_info_other_contact");
        $update_info["ven_info_website"]        = Request::input("ven_info_website");
        $update_info["ven_info_display_name"]   = Request::input("ven_info_display_name");
        $update_info["ven_info_print_name"]     = Request::input("ven_info_print_name");
        $update_info["ven_info_billing"]        = Request::input("ven_info_billing");
        $update_info["ven_info_payment_method"] = Request::input("ven_info_payment_method");
        $update_info["ven_info_delivery_method"] = Request::input("ven_info_delivery_method");
        $update_info["ven_info_terms"]           = Request::input("ven_info_terms");
        $update_info["ven_info_opening_balance"] = Request::input("ven_info_opening_balance");
        $update_info["ven_info_balance_date"]    = Request::input("ven_info_balance_date");
        $update_info["ven_info_notes"]           = Request::input("notes");
        $update_info["ven_info_tax_no"]          = Request::input("tax_resale_no");
        $update_info["ven_info_tin_no"]          = Request::input("tin_number");

        Tbl_vendor_other_info::where("ven_info_vendor_id", $vendor_id)->update($update_info);

        Tbl_vendor_bank_record::where("vendor_id", $vendor_id)->delete();
        $bank_acct_name = Request::input("vendor_account_name");
        $bank_acct_no = Request::input("vendor_account_number");
        foreach ($bank_acct_name as $key => $value) 
        {
            if($value && $bank_acct_no[$key])
            {
                $ins_bank["vendor_id"] = $vendor_id;
                $ins_bank["vendor_account_name"] = $value;
                $ins_bank["vendor_account_number"] = $bank_acct_no[$key];
                Tbl_vendor_bank_record::insert($ins_bank);
            }
        }

        // Tbl_vendor_attachment
        Tbl_vendor_attachment::where('vendor_id',$vendor_id)->delete();
        if(Request::has('fileurl')){
            $fileurl = Request::input('fileurl');
            $filename = Request::input("filename");
            $mimetype = Request::input('mimetype');
            $attachment = '';
            foreach($fileurl as $key => $url){
                $attachment[$key]['vendor_id'] = $vendor_id;
                $attachment[$key]['vendor_attachment_path'] = $url;
            }
            foreach($filename as $key => $name){
                $attachment[$key]['vendor_attachment_name'] = $name;
            }
            
            foreach($mimetype as $key => $mime)
            {
                $attachment[$key]['mime_type'] = $mime;
            }
            
            if($attachment != ''){
                Tbl_vendor_attachment::insert($attachment);
            }
        }
        

        $json["status"]     = "success";
        $json["type"]       = "vendor";
        $json["vendor_id"]  = $vendor_id;

        $new_vendor_data = Tbl_vendor::info()->where("vendor_id",$vendor_id)->first()->toArray();
        AuditTrail::record_logs("Edited","vendor",$vendor_id,serialize($old_vendor_data),serialize($new_vendor_data));

        return json_encode($json);
    }

    public function getVendorDetails($id)
    {
        $data["vendor"]       = Tbl_vendor::info()->balanceJournal()->where("vendor_id", $id)->first();
        $data["_transaction"] = Tbl_vendor::transaction($this->getShopId(), $id)->get();

        return view('member.vendor.vendor_details', $data);
    }

    public function getTest()
    {
        dd(Vendor::getAllProduct());
    } 


    public function getDownloadFile($id)
    {
        $id = Crypt::decrypt($id);
        $attachment = Tbl_vendor_attachment::where('vendor_attachment_id',$id)->first();
        $file = $attachment->vendor_attachment_path;
        $filename = $attachment->vendor_attachment_name;
        $headers = array(
          'Content-Type: application/pdf',
        );
        
        return Response::download($file, $filename, $headers);
    }
    //upload vendor file start
    public function postUploadFile()
    {
        $file = Input::file('file');
        $extension = $file->getClientOriginalExtension();
        $path = "upload/member-" . $this->user_info->shop_id.'/vendor_file';
        // dd($path);
        $filename = $this->user_info->shop_id.'-'.date('ymdhis').'.'.$extension;
        $original_name = $file->getClientOriginalName();
        $mimetype = $file->getMimeType();
        if(!file_exists($path))
        {
            mkdir($path, 0777, true);
        }
        $file->move($path,$filename);
        
        $json['url'] = $path.'/'.$filename;
        $json['original'] = $original_name;
        $json['mimetype'] = $mimetype;
        return json_encode($json);
    }
    
    public function postRemoveFile()
    {
        $path = Request::input('path');
        $value = Request::input('value');
        $json = '';
        if(!unlink($path)){
            $json['result'] = 'error';
            $json['message'] = 'Failed to remove the file.';
        }
        else{
            Tbl_vendor_attachment::where('vendor_attachment_id',$value)->where('vendor_attachment_path',$path)->delete();
            $json['value'] = $value;
            $json['result'] = 'success';
        }
        
        return json_encode($json);
    }
	
}