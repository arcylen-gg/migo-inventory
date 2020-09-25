<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\Tbl_customer;
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
use App\Globals\Cart;

class ShopCheckoutLoginController extends Shop
{
    public function cart_exist($data)
    {
        if (!isset($data["get_cart"]['cart'])) 
        {
            return Redirect::to('/')->send();
        }
    }
    public function if_loggedin()
    {
        if(isset(Self::$customer_info->customer_id))
        {
            $customer_info["email"] = trim(Self::$customer_info->email);
            $customer_info["new_account"] = false;
            $customer_info["password"] = Crypt::decrypt(Self::$customer_info->password);
            $customer_set_info_response = Cart::customer_set_info($this->shop_info->shop_id, $customer_info, array("check_account"));
            if ($customer_set_info_response["status"] == "error") 
            {
                return Redirect::to("/checkout/login")->with("warning", $customer_set_info_response["status_message"])->send();
            }
            else
            {
                return Redirect::to('/checkout')->send();
            }
        }
    }
    public function index()
    {
        if(Request::isMethod("post"))
        {
            $customer_info["email"] = trim(Request::input("email"));
            $customer_info["new_account"] = Request::input("continue") == "on" ? true : false;
            $customer_info["password"]= (Request::input("password") != "" ? Request::input("password") : randomPassword());
            $customer_set_info_response = Cart::customer_set_info($this->shop_info->shop_id, $customer_info, array("check_account"));

            /* CHECK FOR ERROR RESPONSE */
            if($customer_set_info_response["status"] == "error")
            {
                return Redirect::back()->with('warning', $customer_set_info_response["status_message"])->withInput();
            }
            else
            {
                return Redirect::to("/checkout")->send();
            }
        }
        else
        {
            $customer_info["email"] = null;
            $customer_info["new_account"] = null;
            $customer_info["password"]= null;
            $customer_set_info_response = Cart::customer_set_info($this->shop_info->shop_id, $customer_info, array("check_account"));
            
            $data["page"]     = "Checkout - Login";
            $data["get_cart"] = Cart::get_cart($this->shop_info->shop_id);
            $total = 0;

            if (!isset($data["get_cart"]["cart"])) 
            {
                $data["get_cart"]["cart"] = [];
            }
            
            foreach ($data["get_cart"]["cart"] as $key => $value) 
            {
                $total += $value["cart_product_information"]["product_price"] * $value["quantity"];
            }
            $data["get_cart"]["sale_information"]["total_overall_price"] = $total;

            $this->cart_exist($data);
            $this->if_loggedin();
            return view("checkout_login", $data);
        }       
    }
}