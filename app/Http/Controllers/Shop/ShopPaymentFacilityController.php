<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use File;
use Input;
use Validator;
use Carbon\Carbon;
use URL;
use Session;
use DB;

use App\Globals\Mlm_member;
use App\Globals\Mlm_slot_log;
use App\Globals\Item_code;
use App\Globals\Cart;
use App\Globals\Customer;
use App\Globals\Ec_order;
use App\Models\Tbl_customer;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_coupon_code_product;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_item_code;
use App\Models\Tbl_country;
use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_item;
use App\Models\Tbl_item_code_item;
use App\Models\Tbl_ec_order_item;
use App\Models\Tbl_merchant_school;
use App\Models\Tbl_locale;
use App\Models\Tbl_email_template;
use App\Globals\Mail_global;
use App\Globals\Payment;  
use App\Models\Tbl_online_pymnt_api;

class ShopPaymentFacilityController extends Shop
{
    /* Paymaya */
    public function paymaya_webhook_success()
    {
        $data = Request::input();
        Payment::done($data, "paymaya");
    }
    public function paymaya_webhook_failure()
    {
        $data = Request::input();
        Payment::done($data, "paymaya");
    }
    public function paymaya_webhook_cancel()
    {
        $data = Request::input();
        Payment::done($data, "paymaya");
    }
    /* Dragon Pay */
    public function dragonpay_return()
    {
        if (Request::input("status") == "S") 
        {
            return Redirect::to(Request::input("param1"));
        }
        else
        {
            return Redirect::to(Request::input("param2"));
        }
    }
    public function dragonpay_postback()
    {
        $data = Request::input();
        Payment::done($data, "dragonpay");

        if (Request::input("status") == "S") 
        {
            $from = Request::input('param1');
            if ($from == "checkout") 
            {
                $order_id = Request::input("param2");
                $order = DB::table('tbl_ec_order')->where('ec_order_id', $order_id)->first();

                if($order)
                {  
                    try 
                    {
                        $update['ec_order_id'] = $order_id;
                        $update['order_status'] = "Processing";
                        $update['payment_status'] = 1;
                        $order = Ec_order::update_ec_order($update);

                        $this->after_email_payment($order_id);
                    } 
                    catch (\Exception $e) 
                    {
                        $last["log_date"] = Carbon::now();
                        $last["content"]  = $e->getMessage();
                        DB::table("tbl_dragonpay_logs")->insert($last);  
                    }   
                }
            }
            elseif ($from == "register")
            {
                $order_id = Request::input("param2");
                $order = DB::table('tbl_ec_order')->where('ec_order_id', $order_id)->first();

                if($order)
                {
                    try 
                    {
                        Item_code::ec_order_slot($order_id);
                        
                        $update['ec_order_id'] = $order_id;
                        $update['order_status'] = "Processing";
                        $update['payment_status'] = 1;
                        $order = Ec_order::update_ec_order($update);

                        $this->after_email_payment($order_id);
                    } 
                    catch (\Exception $e) 
                    {
                        $last["log_date"] = Carbon::now();
                        $last["content"]  = $e->getMessage();
                        DB::table("tbl_dragonpay_logs")->insert($last);  
                    }  
                }
            }
        }
    }
    public function dragonpay_logs()
    {
        $dragonpay = DB::table("tbl_dragonpay_logs")->orderBy("id", "DESC")->first();

        if (is_serialized($dragonpay->content)) 
        {
            dd(unserialize($dragonpay->content));
        }
        else
        {
            dd($dragonpay->content);
        }
    }
}
