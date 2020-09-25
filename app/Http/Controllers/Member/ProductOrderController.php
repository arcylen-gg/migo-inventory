<?php
namespace App\Http\Controllers\Member;

use App\Globals\Ec_order;
use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Ecom_Product;
use App\Globals\Pdf_global;
use App\Globals\Cart;

use App\Models\Tbl_customer;
use App\Models\Tbl_warehousea;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_item;
use App\Models\Tbl_ec_variant;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_ec_order_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_online_pymnt_method;

use Request;
use Input;
use File;
use Response;
use Carbon\Carbon;
use Session;
use Redirect;
use PDF;
use DB;
class ProductOrderController extends Member
{
    public function index()
    {

        $data["page"]               = "Customer Invoice";
          
        $data["_customer"]          = Tbl_customer::where("tbl_customer.archived", 0)->where('shop_id',$this->user_info->shop_id)->get();
      
        $data["_payment_method"]    = Tbl_online_pymnt_method::where('method_shop_id',$this->user_info->shop_id)->get();

        //dd("1");
        $data['_product']           = Ecom_Product::getProductList($this->user_info->shop_id, 0, 1);
           

        // dd($data);

        $data['_um']                = UnitMeasurement::load_um_multi();
        $data["action"]             = "/member/ecommerce/product_order/create_order/create_invoice";


        $data['view_invoice']       = Ec_order::get_settings();
        $id = Request::input('id');
        if($id)
        {
            $data["inv"]            = Tbl_ec_order::where("ec_order_id", $id)->first();
            $data["_invline"]       = Tbl_ec_order_item::where("ec_order_id", $id)->get();
            $data["action"]         = "/member/ecommerce/product_order/create_order/update_invoice";

            $customer_data = Tbl_customer::where('customer_id',$data['inv']->customer_id)->first();
            if($customer_data)
            {
                $data['customer_full_name'] = $customer_data->first_name. " ".$customer_data->middle_name." ".$customer_data->last_name;
            }
            $mobile = DB::table("tbl_customer_other_info")->where("customer_id", $data["inv"]->customer_id)->first();
            $data["inv"]->customer_mobile = $mobile->customer_mobile or '';

            $sir                    = Tbl_ec_order::where("ec_order_id",$id)->first();

            if($sir)
            {
                if($sir->coupon_id != null)
                {
                    $data["coupon_code"]   = Tbl_coupon_code::where("coupon_code_id",$sir->coupon_id)->first()->coupon_code;
                    $data["coupon_amount"] = Tbl_coupon_code::where("coupon_code_id",$sir->coupon_id)->first()->coupon_code_amount;
                    $data["coupon_type"]   = Tbl_coupon_code::where("coupon_code_id",$sir->coupon_id)->first()->coupon_discounted;
                }
                else
                {
                    $data["coupon_code"]   = null;
                    $data["coupon_amount"] = null;
                    $data["coupon_type"]   = "fixed";
                }
 
                $data["ec_order_id"] = $sir->ec_order_id;
            }

            /* Payment Method Reference */
            foreach ($data["_payment_method"] as $key => $value) 
            {
                if ($data["inv"]->payment_method_id == $value->method_id) 
                {
                    $method_id = $value->method_id;
                    $shop_id = $this->user_info->shop_id;
                    $method_information = Cart::get_method_information($shop_id, $method_id);

                    if ( isset($method_id) && isset($method_information) )
                    {
                        switch ($method_information->link_reference_name)
                        {
                            // case 'paypal2': dd("UNDER DEVELOPMENT"); break;
                            // case 'paymaya': Cart::submit_using_paymaya($data, $shop_id, $method_information, $from); break;
                            // case 'paynamics': dd("UNDER DEVELOPMENT"); break;
                            // case 'dragonpay': return Cart::submit_using_dragonpay($data, $shop_id, $method_information, $from); break;
                            case 'ipay88': 
                                $ipay88 = DB::table("tbl_ipay88_logs")->where("order_id", $id)->first();

                                $data["log_name"] = "Ipay88 Reference Number";
                                $data["log_reference"] = isset($ipay88->log_trans_id) ? $ipay88->log_trans_id : 'Not found';
                            break;
                            // case 'other': return Cart::submit_using_proof_of_payment($shop_id, $method_information);  break;
                            // case 'e_wallet': return Cart::submit_using_ewallet($data, $shop_id); break;
                            // case 'cashondelivery': return Cart::submit_using_cash_on_delivery($shop_id, $method_information); break;
                            // default: dd("UNDER DEVELOPMENT"); break;
                        }
                    }
                }
            }
        }
       
        return view('member.product_order.product_create_order', $data);
    }
    public function order_invoice()
    {
        $order_id = Request::input("order_id");
        $data["page"] = "Invoice";
        $data["shop_info"] = $this->user_info;
        $data["order"] = Tbl_ec_order::where("tbl_ec_order.ec_order_id", $order_id)->customer()->customer_address()->where("purpose","billing")->first();

        $data["shop_theme"] = "intogadgets";
        if ($data["order"]->payment_status == 1) 
        {
            $data["_item"] = Tbl_ec_order_item::where("tbl_ec_order_item.ec_order_id", $order_id)->groupBy("tbl_ec_order_item.item_id")->item()->get();

            $data["order"]->subtotal = $data["order"]->subtotal - Cart::get_coupon_discount($data["order"]->coupon_id, $data["order"]->subtotal); 
            $data["coupon_discount"] = Cart::get_coupon_discount($data["order"]->coupon_id, $data["order"]->subtotal);
            $data['order']->vat     = $data["order"]->subtotal / 1.12 * 0.12;
            $data['order']->vatable = $data['order']->subtotal - $data['order']->vat;

            if($data["order"]->billing_address == ', , , ')
            {
                $data["order"]->billing_address = $data["order"]->customer_street.", ".$data["order"]->customer_zipcode.", ".$data["order"]->customer_city.", ".$data["order"]->customer_state;
            }
            
            return view("member.product_order.account_invoice", $data);
        }
        else
        {
            return Redirect::back();
        }

    }
    public function invoice_list()
    {
        $data["ec_order_pending"]    = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Pending")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_failed"]     = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Failed")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_processing"] = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Processing")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_shipped"]    = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Shipped")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_completed"]  = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Completed")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_on_hold"]    = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","On-hold")
                                        ->orderBy("ec_order_id", "DESC");
        $data["ec_order_cancelled"]  = Tbl_ec_order::customer()->where("tbl_ec_order.archived",0)->where("shop_id",$this->user_info->shop_id)->where("order_status","Cancelled")
                                        ->orderBy("ec_order_id", "DESC");
        $filtered_by                 = Request::input("type_chosen");                                 
        foreach($data as $key => $order)
        {
            if($filtered_by != "All" && $filtered_by != "")
            {
                $data[$key] = $data[$key]->where("payment_method_id",$filtered_by);
            }

            $data[$key] = $data[$key]->paginate(10);
        }   

        /* PUT THE DATA HERE IF IT IS NOT FROM EC_ORDER TABLE */
        $data["_filter"]             = Tbl_online_pymnt_method::where("method_shop_id",$this->user_info->shop_id)->get();



        return view("member.product_order.product_order",$data);
    }
    public function create_invoice()
    {
        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['invoice_date']       = Request::input('inv_date');
        $invoice_info['invoice_due']        = Request::input('inv_due_date');
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = Request::input('subtotal_price');
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $item_count                         = 0;

        foreach(Request::input("invline_item_id") as $request_item)
        {
            if($request_item != "")
            {
                $item_count++;
            }
        }

        if($item_count != 0)
        {
                $returned_data = Ec_order::create_ec_order(Request::input());
                if($returned_data["status"] == "error")
                {
                    $json["status"]            = "error";
                    $json["status_message"]    = $returned_data["status_message"];

                    return json_encode($json);
                }
                else
                {
                    $json["status"]         = "success-invoice";
                    $json["redirect_to"]    = "/member/ecommerce/product_order/create_order?id=".$returned_data;

                    return json_encode($json);
                }
        }
        else
        {   
            $json["status"]         = "error";
            $json["status_message"] = "No ordered item.";
            return json_encode($json);
        }
    }
    public function submit_coupon()
    {
        $return["message"] = "";
        $code              = Request::input("sent_code");
        $invoice_id        = Request::input("invoice_id");

        $coupon            = Tbl_coupon_code::where("coupon_code",$code)->where("shop_id",$this->user_info->shop_id)->first();
        if($coupon)
        {
            if($coupon->used == 1)
            {
                if($invoice_id)
                {
                    $check_invoice = Tbl_ec_order::where('ec_order_id',$invoice_id)->first();
                    if($check_invoice)
                    {
                        $check_coupon = Tbl_coupon_code::where("coupon_code_id",$check_invoice->coupon_id)->first();
                        if($check_coupon)
                        {
                            if($check_coupon->coupon_code == $code)
                            {
                                $return["amount"]  = $coupon->coupon_code_amount;

                                if($coupon->coupon_discounted == "fixed")
                                {
                                    $return["type"]  = "fixed";
                                }
                                else if($coupon->coupon_discounted == "percentage")
                                {
                                    $return["type"]  = "percent";
                                }

                                $return["message"] = "Success";
                            }
                            else
                            {
                                $return["message"] = "Coupon already used"; 
                            }
                        }
                        else
                        {
                            $return["message"] = "Coupon already used";        
                        }
                    }
                    else
                    {
                        $return["message"] = "Coupon already used"; 
                    }
                }
                else
                {
                    $return["message"] = "Coupon already used"; 
                }
            }
            else if($coupon->blocked == 1)
            {
                $return["message"] = "Coupon is blocked";
            }
            else
            {
                $return["amount"]  = $coupon->coupon_code_amount;

                if($coupon->coupon_discounted == "fixed")
                {
                    $return["type"]  = "fixed";
                }
                else if($coupon->coupon_discounted == "percentage")
                {
                    $return["type"]  = "percent";
                }

                $return["message"] = "Success";
            }
        }
        else
        {
            $return["message"] = "Coupon does not exist";
        }




        return json_encode($return);
    }
    public function update_invoice()
    {   
        $data["ec_order_id"]    = Request::input("ec_order_id");
        $data["order_status"]   = Request::input("order_status");
        $data["payment_status"] = Request::input("payment_status");

        $data["tracking_no"] = Request::input("tracking_no");

        $response                           = Ec_order::update_ec_order($data);
        if(isset($response["status"]))
        {
            if($response["status"] == "error")
            {
                $return["status"]                   = "error";
                $return["status_message"]           = $response["status_message"];
                return json_encode($return);
            }
            else
            {
                $return["status"]                   = "success-update-invoice";

                Tbl_ec_order::where("ec_order_id",$data["ec_order_id"])->update(["manual_inv_number" => Request::input("manual_inv_number")]);
                
                $return["redirect_to"]              = "/member/ecommerce/product_order/create_order?id=".$data["ec_order_id"];
                return json_encode($return);
            }
        }
        else
        {
            $return["status"]                   = "success-update-invoice";
            $return["redirect_to"]              = "/member/ecommerce/product_order/create_order?id=".$data["ec_order_id"];
            return json_encode($return);
        }
    }

    public function submit_payment_upload()
    {
        $shop_id    = $this->user_info->shop_id;
        $shop_key   = $this->user_info->shop_key;
        $order_id   = Request::input('ec_order_id');

        /* SAVE THE IMAGE IN THE FOLDER */
        $file               = Input::file('file');

        if($file)
        {
            $extension          = $file->getClientOriginalExtension();
            //$filename         = $file->getClientOriginalName();
            $filename           = str_random(15).".".$extension;
            $destinationPath    = 'uploads/'.$shop_key."-".$shop_id.'/ecommerce-upload';

            if(!File::exists($destinationPath)) 
            {
                $create_result = File::makeDirectory(public_path($destinationPath), 0775, true, true);
            }

            $upload_success    = Input::file('file')->move($destinationPath, $filename);

            /* SAVE THE IMAGE PATH IN THE DATABASE */
            $image_path = $destinationPath."/".$filename;

            $update["payment_upload"] = "/" . $image_path;
            $image_id = Tbl_ec_order::where("ec_order_id", $order_id)->update($update);

            if( $upload_success) 
            {
               return Response::json('success', 200);
            } 
            else 
            {
               return Response::json('error', 400);
            }
        }
        else
        {
            return Response::json('success', 200);
        }

    }

    public function invoice_view($invoice_id)
    {
        $data["invoice_id"] = $invoice_id;

        return view("member.customer_invoice.invoice_view",$data);
    }
    public function invoice_view_pdf($inv_id)
    {
        $data["invoice"] = Tbl_customer_invoice::customer()->where("inv_id",$inv_id)->first();

        $data["invoice_item"] = Tbl_customer_invoice_line::invoice_item()->um()->where("invline_inv_id",$inv_id)->get();
        foreach($data["invoice_item"] as $key => $value) 
        {
            $total_qty = $value->invline_qty * $value->unit_qty;
            $data["invoice_item"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }

          $pdf = view('member.customer_invoice.invoice_pdf', $data);
          return Pdf_global::show_pdf($pdf);
    }
}