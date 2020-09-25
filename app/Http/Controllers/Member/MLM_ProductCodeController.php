<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use DB;
use App\Globals\Merchant;
use App\Models\Tbl_user;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_customer;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_item_code_item;
use App\Globals\Item;
use App\Globals\Item_code;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_shop;
use App\Globals\Pdf_global;
use App\Globals\Utilities;
use App\Models\Tbl_inventory_serial_number;
class MLM_ProductCodeController extends Member
{
    public function index()
    {
        $access = Utilities::checkAccess('mlm-product-code', 'access_page');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $shop_id 	   = $this->user_info->shop_id;
	    $data["_item"] = null;

        $code_unused                  = Tbl_item_code::where("used",0)->where("blocked",0)->where("tbl_item_code.shop_id",$shop_id)->item()->invoice()->customer();
        $code_used                    = Tbl_item_code::where("used",1)->where("blocked",0)->where("tbl_item_code.shop_id",$shop_id)->item()->invoice()->customer();
        $code_blocked                 = Tbl_item_code::where("blocked",1)->where("tbl_item_code.shop_id",$shop_id)->item()->invoice()->customer();    
        $search_name                  = Request::input("search_name");
        if($search_name != "")
        {
                $code_unused->where(function ($query) use($search_name) {$query->where("item_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
                $code_used->where(function ($query) use($search_name){$query->where("item_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
                $code_blocked->where(function ($query) use($search_name){$query->where("item_activation_code","LIKE","%".$search_name."%")->orWhere("first_name","LIKE","%".$search_name."%")->orWhere("middle_name","LIKE","%".$search_name."%")->orWhere("last_name","LIKE","%".$search_name."%");});
        }

        $data["_code_unused"]  = $code_unused->paginate(10, ['*'], '_code_unused');
        $data["_code_used"]    = $code_used->paginate(10, ['*'], '_code_used');
        $data["_code_blocked"] = $code_blocked->paginate(10, ['*'], '_code_blocked');

        $data['user_a'] = $this->user_info->user_id;
        $data['warehouse_a'] = $this->current_warehouse->warehouse_id;
        $data['ismerchant'] = Merchant::ismerchant();
        // dd($data);
        // dd($data["_code_used"]);
        return view('member.mlm_product_code.mlm_product_code', $data);
    }

    public function sell()
    {
        // dd(php_info);
        $access = Utilities::checkAccess('mlm-product-code', 'product_code_sell_codes');
        $data['_item']  = Item::get_all_category_item();
        // dd($data);
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $shop_id            = $this->user_info->shop_id;
        $data["shop_data"]  = Tbl_shop::where("shop_id",$shop_id)->first();
	    $data['_item']      = Item::get_all_category_item();
	    $data["_customer"]  = Tbl_customer::where("archived",0)
                                          ->join("tbl_mlm_slot","tbl_mlm_slot.slot_owner","=","tbl_customer.customer_id")
                                          ->where("tbl_customer.shop_id",$shop_id)->get();
        $data['ismerchant'] = Merchant::ismerchant();                                  
        $data['table_body'] = $this->view_all_lines();
        if($this->current_warehouse == null)
        {
            $data['warehouse'][0] = [];
        }
        else
        {
            $data['warehouse'][0] = $this->current_warehouse;
        }

        return view('member.mlm_product_code.mlm_product_code_sell', $data);
    }

    public function block($id)
    {
        $shop_id               = $this->user_info->shop_id;
        $data["page"]          = "Item Code";
        $data["code"]          = Tbl_item_code::where("item_code_id",$id)->where("tbl_item_code.shop_id",$shop_id)->item()->customer()->first();
        if(!$data["code"])
        {
            dd("Please try again.");
        }
        return view('member.mlm_product_code.mlm_product_code_block',$data);
    }

    public function block_submit()
    {
        $shop_id    = $this->user_info->shop_id;
        $id         = Request::input("item_code_id");
        $code       = Tbl_item_code::where("item_code_id",$id)->where("shop_id",$shop_id)->first();
        if($code)
        {
            if($code->used == 0 && $code->blocked == 0)
            {
               $update["blocked"] = 1;
               Tbl_item_code::where("item_code_id",$id)->update($update);
               $message = "success-block";   
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

    public function add_line()
    {
        $data['item_list'] = Item::view_item_dropdown($this->user_info->shop_id);

        $data['_item']  = Item::get_all_category_item();

        if(Request::input('slot_id') != null)
        {
            $data['slot_id'] = Request::input('slot_id');
        }
        else
        {
            $data['slot_id'] = 0;
        }
        return view('member.mlm_product_code.mlm_product_code_add_line', $data);
    }
    public function add_line_barcode_product()
    {
        $code = Request::input('product_barcode');
        $item = Tbl_item::where('item_barcode', $code)->first();
        if($item)
        {
            $validate['item_id']         = $item->item_id;
            $validate['quantity']        = 1;
            $validate['slot_id']         = 0;
            $arry['item_id']         = $item->item_id;
            $arry['quantity']        = 1;
            $arry['price']           = $item->item_price;
            $arry['total']           = $arry['quantity'] * $arry['price'];

            Item::sell_item_add_to_session($arry);
            $data['item'] = $item;
        }
        $data['response_status'] = 'success_a_item_barcode';
        $data['message'] = ' asda';

        return json_encode($data);
    }

    public function add_line_submit()
    {
        $shop_id                     = $this->user_info->shop_id;
        $validate['item_id']         = Request::input('item_id');
        $validate['quantity']        = Request::input('quantity');
        $validate['slot_id']         = Request::input('slot_id');

        if(number_format($validate["quantity"]) == 0)
        {
            $validate["quantity"]    = 1;
        }
        
        $rules['item_id']            = 'required|exists:tbl_item,item_id';
        $rules['quantity']           = 'required|integer|min:1';
        // $rules['slot_id']           = 'required';

        $data['other_warning']       = "";
        
        $validator = Validator::make($validate,$rules);
        if ($validator->passes())
        {
            $item = Tbl_item::where("item_id",$validate["item_id"])->where("shop_id",$shop_id)->first();
            if($item)
            {
               $removed                 = Request::input("removed");
               $arry['item_id']         = $item->item_id;
               $arry['quantity']        = $validate['quantity'];
               $arry['price']           = $item->item_price;
               $arry['total']           = $arry['quantity'] * $arry['price'];

               if(Request::input("item_edit"))
               {
                   $returned_data           = Item::sell_item_edit_to_session($arry,$removed); 
                   $data['response_status'] = "default";
                   $data['subtotal']        = currency('PHP',$arry['total']);
                   $data['total']           = currency('PHP',$returned_data["total"]);
                   $data['price']           = currency('PHP',$item->item_price);
               }
               else
               {
                   Item::sell_item_add_to_session($arry);
                   $data['response_status'] = "success";
               }
                $slot_id = Request::input('slot_id');
                $discount_card_log_id = Request::input('discount_card_log_id');
                $a = Item::fix_discount_session_w_dis($slot_id, $discount_card_log_id);
               // $fix = Item::fix_discount_session($validate['slot_id']);
            }
            else
            {
                $data['other_warning']   = "Invalid Item";
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

    public function view_all_lines()
    {
        $slot_id = Request::input('slot_id');
        $discount_card_log_id = Request::input('discount_card_log_id');
        $a = Item::fix_discount_session_w_dis($slot_id, $discount_card_log_id);
        $get_session = Session::get("sell_item_codes_session");
        $data[] = null;
        if(!empty($get_session))
        {
          foreach($get_session as $key => $value)
            {
                $data['item_array'][$key]         = $value;
        	    $data['item_list'][$key]          = Item::view_item_dropdown($this->user_info->shop_id,$key,true);
                $data['item_array'][$key]['item_serial'] = Tbl_inventory_serial_number::where('item_id', $key)->get()->toArray();
            }  
        }
        $data['_item']  = Item::get_all_category_item();
        // dd($data['item_array']);
        return view('member.mlm_product_code.mlm_product_code_view_line', $data);
    }

    public static function compute()
    {
        $get_session = Session::get("sell_item_codes_session");
        $data[]      = null;
        $total       = 0;
        if(!empty($get_session))
        {
          foreach($get_session as $key => $value)
            {
                $data['item_array'][$key] = $value;
                $data['item_id'][$key] = Tbl_item::where("item_id",$key)->first();
                $total += $value['membership_discounted_price_total'];
            }  
        }
        return currency('PHP', $total);
    }
    public function clear_line_all()
    {
        return Session::forget('sell_item_codes_session');
        return "Success";
    }
    public static function remove_one_line($id)
    {
        $get_session = Session::get("sell_item_codes_session");
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
            return Session::forget("sell_item_codes_session");
        }
        return Session::put('sell_item_codes_session', $arry);
    }

    public function process()
    {
        // return $_POST;
        if(Request::input())
        {
            if(isset($this->current_warehouse->warehouse_id))
            {
                // return $_POST;

                $shop_id = $this->user_info->shop_id;
                $user_id = $this->user_info->user_id;
                $warehouse_id = $this->current_warehouse->warehouse_id;


                $data    = Item_code::add_code(Request::input(),$shop_id, $user_id, $warehouse_id);
                if($data["response_status"] == "success")
                {
                    Session::forget("sell_codes_session");
                    return json_encode($data);
                    Session::flash('success', "Successfully purchased a product/s");
                    return redirect('/member/mlm/product_code/receipt?invoice_id='. $data['invoice_id'])->send();
                }
                else
                {
                    return json_encode($data);
                    Session::flash('code_error', $data["warning_validator"]);
                    return redirect('/member/mlm/product_code/sell')->send();
                }
            }
            else
            {
                $data["warning_validator"][0] = 'Invalid Warehouse';
                return json_encode($data);
                Session::flash('code_error', $data["warning_validator"]);
                return redirect('/member/mlm/product_code/sell')->send();
            }     
        }
        else
        {
            return redirect('/member/mlm/product_code/sell');
        }
    }
    public static function discount_get($item_id, $slot_id)
    {
        $slot_membership = Tbl_mlm_slot::where('slot_id', $slot_id)->value('slot_membership');
        return Item::get_discounted_price_mlm($item_id, $slot_membership);
    }
    public static function price_original_get($item_id)
    {
        return Item::get_original_price($item_id);
    }
    public static function fix_discount_session($slot_id)
    {
        return Item::fix_discount_session($slot_id);
    }
    public function receipt()
    {
        $access = Utilities::checkAccess('mlm-product-code', 'product_code_reciept');
        $access_all_invoice = Utilities::checkAccess('mlm-product-code', 'see_all_invoice');
        
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $shop_id          = $this->user_info->shop_id;

        if($access_all_invoice == 1)
        {
            $_invoice         = Tbl_item_code_invoice::customer()->orderBy('item_code_invoice_id', 'DESC')->where("tbl_item_code_invoice.shop_id",$shop_id);
        }
        else
        {
            $user_id = $this->user_info->user_id;
            $_invoice         = Tbl_item_code_invoice::customer()->orderBy('item_code_invoice_id', 'DESC')->where("tbl_item_code_invoice.user_id",$user_id);
        }
        
        if(Request::input('search_name'))
        {
            $search_email = Request::input('search_name');
            $_invoice  = $_invoice->where("tbl_item_code_invoice.item_code_customer_email","LIKE","%".$search_email."%");
        }

        /* FOR FILTERING */
        if(Request::input('date_filter'))
        {
            if(Request::input('date_filter') == "asc")
            {
                $_invoice  = $_invoice->orderBy("item_code_date_created","ASC");
                $date_filter = "asc";
            }
            else if(Request::input('date_filter') == "desc")
            {
                $_invoice  = $_invoice->orderBy("item_code_date_created","DESC");
                $date_filter = "desc";
            }
            else
            {
                $date_filter = "default";
            }
        }
        else
        {
            $_invoice  = $_invoice->orderBy('item_code_invoice_id', 'DESC');
            $date_filter = "default";
        }


        if(Request::input('filter_to'))
        {
            $filter_to   = date('Y-m-d 00:00:00',strtotime(Request::input('filter_to')));
            // dd($filter_to);
            $_invoice    = $_invoice->where("item_code_date_created",">=",$filter_to);
        }

        if(Request::input('filter_by'))
        {
            $filter_by   = date('Y-m-d 23:59:59',strtotime(Request::input('filter_by')));
            $_invoice    = $_invoice->where("item_code_date_created","<=",$filter_by);
        }



        $data["_invoice"]    = $_invoice->paginate(10);
        $data["date_filter"] = $date_filter;

        // dd($code);
        return view('member.mlm_product_code.mlm_product_code_receipt',$data);   
    }
    public function view_receipt($id)
    {
        $shop_id          = $this->user_info->shop_id;
        $invoice          = Tbl_item_code_invoice::customer()->where("item_code_invoice_id",$id)->where("tbl_item_code_invoice.shop_id",$shop_id)
                            ->leftjoin('tbl_user', 'tbl_user.user_id', '=', 'tbl_item_code_invoice.user_id')
                            ->first();
        if(!$invoice)
        {
            return Redirect::to("/member/mlm/code");
        }   

        $_code                   = Tbl_item_code::where("item_code_invoice_id",$invoice->item_code_invoice_id)->item()
        ->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_item_code.slot_id')
        ->get();
        $data["invoice"]         = $invoice;
        $data["_code"]           = $_code;
        $data["shop_address"]    = $this->user_info->shop_street_address;
        $data["shop_contact"]    = $this->user_info->shop_contact;
        $data['company_name']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_name')->value('value');
        $data['company_email']   = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_email')->value('value');
        $data['company_logo']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
        if($invoice->user_is_merchant == 1)
        {
            $warehouse = DB::table('tbl_user_warehouse_access')->where('user_id', $invoice->user_id)
                            ->join('tbl_warehouse', 'tbl_warehouse.warehouse_id', '=', 'tbl_user_warehouse_access.warehouse_id')
                            ->first();
            if($warehouse)
            {
                $data['company_name'] = $warehouse->warehouse_name;
                $data["shop_address"] = $warehouse->warehouse_address;
                $data['company_logo'] = $warehouse->merchant_logo;
                $data["shop_contact"] = '';
                $data['company_email']= '';
                if($warehouse->merchant_logo == '')
                {
                    $data['company_logo'] = '/assets/images/noimage.png';
                }
            }    
        }
        // dd($data);
        $data['item_list']       = Tbl_item_code_item::where('tbl_item_code_item.item_code_invoice_id', $id)->join("tbl_item_code","tbl_item_code.item_code_id","=","tbl_item_code_item.item_code_id")->get();    
        $subtotal                = Tbl_item_code::where("item_code_invoice_id",$invoice->item_code_invoice_id)->item()->sum("item_code_price");
        $discount_amount         = $invoice->item_discount;
        $total                   = $subtotal - $discount_amount;

        $data['slot'] = Tbl_mlm_slot::where('slot_id', $invoice->slot_id)->first();
        $data["subtotal"]        = $subtotal;
        $data["discount_amount"] = $discount_amount;
        $data["total"]           = $total;
        $data['pdf'] = Request::input('pdf');
        if(Request::input('pdf') == 'true')
        {
            $ht = view('member.mlm_product_code.mlm_product_code_view_receipt',$data);   
            return Pdf_global::show_pdf($ht);
        }

        return view('member.mlm_product_code.mlm_product_code_view_receipt',$data);   
    }
    public function get_customer_slot()
    {
        $customer_id   = Request::input("customer_id");
        $shop_id       = $this->user_info->shop_id;
        if($customer_id == "All")
        {
            $data["_slot"] = Tbl_mlm_slot::where("shop_id",$shop_id)->get();
        }
        else
        {
            $data["_slot"] = Tbl_mlm_slot::where("slot_owner",$customer_id)->where("shop_id",$shop_id)->get();
        }

        return view('member.mlm_product_code.mlm_product_code_customer_slot',$data); 
    }
}
