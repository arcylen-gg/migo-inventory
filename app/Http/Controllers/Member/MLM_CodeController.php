<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use App\Globals\Membership_package;
use App\Globals\Membership_code;
use Validator;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_customer;
use App\Models\Tbl_warehouse;
use DB;
use App\Globals\Pdf_global;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
class MLM_CodeController extends Member
{
    public function index()
    {

        $access = Utilities::checkAccess('mlm-membership-codes', 'access_page');
        if($access == 1)
        {
            $shop_id                      = $this->user_info->shop_id;
            // return Membership_code::set_up_mail(4, $shop_id);
            $data["page"]                 = "Membership";
            $data["_membership_package"]  = Tbl_membership_package::where("membership_package_archive",0)->where('tbl_membership.membership_archive', 0)->membership()->where("shop_id",$shop_id)->get();
            
            $code_unused                  = Tbl_membership_code::where("used",0)->where("blocked",0)->where("tbl_membership_code.shop_id",$shop_id)->invoice()->package()->membership()->customer();
            $code_used                    = Tbl_membership_code::where("used",1)->where("blocked",0)->where("tbl_membership_code.shop_id",$shop_id)->invoice()->package()->membership()->customer();
            $code_blocked                 = Tbl_membership_code::where("blocked",1)->where("tbl_membership_code.shop_id",$shop_id)->invoice()->package()->membership()->customer();    
            $membership_package           = Request::input("membership_type");
            $slot_type                    = Request::input("slot_type");
            $search_name                  = Request::input("search_name");
            
            if($membership_package)
            {
                if($membership_package != "All")
                {
                    $code_unused->where("tbl_membership_code.membership_package_id",$membership_package);
                    $code_used->where("tbl_membership_code.membership_package_id",$membership_package);
                    $code_blocked->where("tbl_membership_code.membership_package_id",$membership_package);
                }
            }
            
            if($slot_type)
            {
                if($slot_type != "All")
                {
                    $code_unused->where("membership_type",$slot_type);
                    $code_used->where("membership_type",$slot_type);
                    $code_blocked->where("membership_type",$slot_type);
                }
            }
            
            if($search_name != "")
            {
                    $code_unused->where(function ($query) use($search_name) {$query->where("membership_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
                    $code_used->where(function ($query) use($search_name){$query->where("membership_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
                    $code_blocked->where(function ($query) use($search_name){$query->where("membership_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
            }
            
            $data["_code_unused"]  = $code_unused->paginate(10, ['*'], '_code_unused');
            $data["_code_used"]    = $code_used->paginate(10, ['*'], '_code_used');
            $data["_code_blocked"] = $code_blocked->paginate(10, ['*'], '_code_blocked');
            
            return view('member.mlm_code.mlm_code_list',$data);
        }
        else
        {
           return $this->show_no_access(); 
        }

        
    }
    public function block($id)
    {
        $shop_id               = $this->user_info->shop_id;
        $data["page"]          = "Membership";
        $data["code"]          = Tbl_membership_code::where("membership_code_id",$id)->where("tbl_membership_code.shop_id",$shop_id)->package()->membership()->customer()->first();
        
        if(!$data["code"])
        {
            dd("Please try again.");
        }

        return view('member.mlm_code.mlm_code_block',$data);
    }
    public function block_submit()
    {
        $shop_id    = $this->user_info->shop_id;
        $id         = Request::input("membership_code_id");
        $code       = Tbl_membership_code::where("membership_code_id",$id)->where("shop_id",$shop_id)->first();
        if($code)
        {
            if($code->used == 0 && $code->blocked == 0)
            {
               $update["blocked"] = 1;
               Tbl_membership_code::where("membership_code_id",$id)->update($update);
               $message = "success";   
            }
            else if($code->blocked == 1)
            {
               $message = "already blocked";   
            }
            else if($code->used == 1)
            {
               $message = "already used";   
            }
        }
        else
        {
          $message = "not found";  
        }

        return json_encode($message);
    }
    public function sell()
    {
        $access = Utilities::checkAccess('mlm-membership-codes', 'sell_codes');
        if($access == 1)
        {
            $shop_id                    = $this->user_info->shop_id;
            $data['invoice_number']     = invoice_generator(1);
            $data['membership_package'] = Membership_package::view_membership_dropdown(0, $shop_id);
            $data['membership_type']    = Membership_package::view_ps_cd_fs_dropdown(0);
            $data['talbe_body']         = $this->view_all_lines();
            $data["page"]               = "Sell Membership";
            $data["_customer"]          = Tbl_customer::where("archived",0)->where('shop_id', $shop_id)->get();
            // $data['warehouse'] = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->get();
            // dd($this->current_warehouse->warehouse_id);
            $data['warehouse'][0] = $this->current_warehouse;
            return view('member.mlm_code.mlm_code_sell', $data);
        }
        else
        {
           return $this->show_no_access(); 
        }

        
    }
    public function add_line()
    {
        $data['membership_package'] = Membership_package::view_membership_dropdown(0,$this->user_info->shop_id);
        $data['membership_type']= Membership_package::view_ps_cd_fs_dropdown(0);
        return view('member.mlm_code.mlm_code_modal_add_line', $data);
    }
    public function clear_line_all()
    {
        return Session::forget('sell_codes_session');
        return "Success";
    }
    public function addl_line_submit()
    {
        $validate['membership_package'] = Request::input('membership_package');
        $validate['quantiy'] = Request::input('quantiy');
        if(number_format($validate["quantiy"]) == 0)
        {
            $validate["quantiy"] = 1;
        }
        $validate['membership_type'] = Request::input('membership_type');
        
        $rules['membership_package'] = 'required';
        $rules['quantiy']            = 'required|integer|min:1';
        $rules['membership_type']    = 'required';
        $data['other_warning']       = "";
        
    	$validator = Validator::make($validate,$rules);
    	if ($validator->passes())
    	{
    	    $membership_pack = Tbl_membership_package::membership()
    	    ->where('tbl_membership_package.membership_package_id', $validate['membership_package'])
    	    ->where('tbl_membership.membership_archive', '0')->first();
    	    if(!empty($membership_pack))
    	    {
    	        $arry['membership_package_id'] =  $membership_pack->membership_package_id;
    	        $arry['quantity'] = $validate['quantiy'];
    	        $arry['membership_type'] = $validate['membership_type'];
    	        $arry['price'] = $membership_pack->membership_price;
                $arry['total'] = $arry['quantity'] * $arry['price'];
                if(Request::input("membership_edit"))
                {
                   if(Request::input("removed"))
                   {
                       $removed = Request::input("removed");
                   }
                   else
                   {
                       $removed = null;     
                   }
                   
                   if($arry["membership_type"] == "CD")
                   {
                       $arry['total'] = 0;
                       $mem_price     = 0;
                   }
                   else
                   {
                       $mem_price =$membership_pack->membership_price;
                   }
                   $returned_data = Membership_package::sell_membership_edit_to_session($arry,$removed); 
                   $data['response_status'] = "default";
                   $data['subtotal']        = currency('PHP',$arry['total']);
                   $data['total']           = currency('PHP',$returned_data["total"]);
                   $data['price']           = currency('PHP',$mem_price);
                }
                else
                {
    	           Membership_package::sell_membership_add_to_session($arry);  
    	           $data['response_status'] = "success";
                }
    	    }
    	    else
    	    {
    	        $data['other_warning']   = "Invalid Package";
    	        $data['response_status'] = "warning";
    	    }
    	    
    	}
    	else
    	{
    	    $data['response_status']   = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
        echo json_encode($data);
    }
    public function tester()
    {
        $arry['membership_package_id'] =  3;
        $arry['quantity'] = 2;
        $arry['membership_type'] = "PS"; 
        $arry['price'] = 500;
        $arry['total'] = $arry['quantity'] * $arry['price'];
        return Membership_package::sell_membership_add_to_session($arry);
    }
    public function view_all_lines()
    {
        $get_session = Session::get("sell_codes_session");
        $data[] = null;
        if(!empty($get_session))
        {
          foreach($get_session as $key => $value)
            {
                if($value['membership_type'] == "CD")
                {
                    $value["total"] = 0;
                    $value["price"] = 0;
                }

                $data['mem_array'][$key] = $value;
                $data['membership_package_d'][$key] = Tbl_membership_package::membership()
        	    ->where('tbl_membership_package.membership_package_id', $key)
        	    ->where('tbl_membership.membership_archive', '0')->first();
        	    $data['membership_package'][$key] = Membership_package::view_membership_dropdown($key, $this->user_info->shop_id,true);
                $data['membership_type'][$key]= Membership_package::view_ps_cd_fs_dropdown($value['membership_type'],true);
            }  
        }
        
        // dd($data);
        return view('member.mlm_code.mlm_code_view_line', $data);
    }
    public static function remove_one_line($id)
    {
        $get_session = Session::get("sell_codes_session");
        $c = 0;
        $d = 0;
        foreach($get_session as $key => $value)
        {
            if($key != $id)
            {
                $arry[$key] = $value;
            }
            else
            {
                $c++;
            }
            $d++;
            
        }
        if($c == 1 && $d == 1)
        {
            return Session::forget("sell_codes_session");
        }
        return Session::put('sell_codes_session', $arry);
    }
    public static function compute()
    {
        $get_session = Session::get("sell_codes_session");
        $data[] = null;
        $total = 0;
        if(!empty($get_session))
        {
          foreach($get_session as $key => $value)
            {
                if($value['membership_type'] == "CD")
                {
                    $value["total"] = 0;
                    $value["price"] = 0;
                }
                $data['mem_array'][$key] = $value;
                $data['membership_package_d'][$key] = Tbl_membership_package::membership()
        	    ->where('tbl_membership_package.membership_package_id', $key)
        	    ->where('tbl_membership.membership_archive', '0')->first();
                $total += $value['total'];
            }  
        }
        return currency('PHP', $total);
    }
    public function process()
    {
        if(Request::input())
        {
            $shop_id = $this->user_info->shop_id;
            if(isset($this->current_warehouse->warehouse_id))
            {
                // warehouse_id
                $data    = Membership_code::add_code(Request::input(),$shop_id, $this->current_warehouse->warehouse_id);
                return json_encode($data);
            }
            else
            {
                $data['warning_validator'][0] = 'Invalid Warehouse';
                Session::flash('code_error', $data["warning_validator"]);
                return redirect('/member/mlm/code/sell')->send();
            }
            
            if($data["response_status"] == "success")
            {
                Session::flash('success', "Successfully purchased a package/s");
        	    return redirect('/member/mlm/code')->send();
            }
            else
            {
                Session::flash('code_error', $data["warning_validator"]);
        	    return redirect('/member/mlm/code/sell')->send();
            }          
        }
        else
        {
            return redirect('/member/mlm/code/sell');
        }
    }
    public function receipt()
    {
        $access = Utilities::checkAccess('mlm-membership-codes', 'view_receipt');
        if($access == 1)
        {
            $shop_id          = $this->user_info->shop_id;

            $_invoice         = Tbl_membership_code_invoice::customer();
            $customer_name = Request::input('customer_name');
            if($customer_name != null)
            {
                $_invoice->where('tbl_customer.first_name', 'like', '%' . $customer_name . '%')
                ->orwhere('tbl_customer.middle_name', 'like', '%' . $customer_name . '%')
                ->orwhere('tbl_customer.last_name', 'like', '%' . $customer_name . '%');
            }
            $data["_invoice"] = $_invoice->where("tbl_membership_code_invoice.shop_id",$shop_id)->orderBy('membership_code_invoice_id', 'DESC')->paginate(10);;
            // dd($data);
            return view('member.mlm_code.mlm_code_receipt',$data); 
        }
        else
        {
           return $this->show_no_access(); 
        }
    }
    public function view_receipt($id)
    {
        $shop_id          = $this->user_info->shop_id;
        $invoice          = Tbl_membership_code_invoice::customer()->where("membership_code_invoice_id",$id)->where("tbl_membership_code_invoice.shop_id",$shop_id)->first();
        if(!$invoice)
        {
            return Redirect::to("/member/mlm/code");
        }   

        $_code                   = Tbl_membership_code::where("membership_code_invoice_id",$invoice->membership_code_invoice_id)->package()
        ->join('tbl_membership', 'tbl_membership.membership_id','=', 'tbl_membership_package.membership_id')
        ->get();
        $data["invoice"]         = $invoice;
        $data["_code"]           = $_code;
        $data["shop_address"]    = $this->user_info->shop_street_address;
        $data["shop_contact"]    = $this->user_info->shop_contact;
        $data['company_name'] = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_name')->value('value');
        $data['company_email'] = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_email')->value('value');
        $data['company_logo'] = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
        $subtotal                = Tbl_membership_code::where("membership_code_invoice_id",$invoice->membership_code_invoice_id)->package()->sum("membership_code_price");
        $discount_amount         = $invoice->membership_discount;
        $total                   = $subtotal - $discount_amount;

        $data["subtotal"]        = $subtotal;
        $data["discount_amount"] = $discount_amount;
        $data["total"]           = $total;
        // dd($data);

        if(Request::input('pdf') == 'true')
        {
            $ht = view('member.mlm_code.mlm_code_view_receipt',$data); 
            return Pdf_global::show_pdf($ht);
        }
        return view('member.mlm_code.mlm_code_view_receipt',$data);   
    }
    public function voucher_list()
    {

    }
}