<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Illuminate\Http\Request;
use View;
use Session;
use DB;
use Carbon\Carbon;
use App\Models\Tbl_product;
use App\Globals\Ecom_Product;
use App\Models\Tbl_ec_product;
use App\Globals\Cart2;
use App\Globals\Ec_wishlist;
use App\Globals\Settings;

class ShopCart2Controller extends Shop
{
    public function index(Request $request)
    {
        /* Set Cart Key */
        $this->set_cart_key($request);

        /* Get Cart */
        $data["cart"] = Cart2::get_cart_info(isset(Self::$customer_info->customer_id) ? Self::$customer_info->customer_id : null);
        
        /* Return View */
        return view("cart_modal", $data);
    }
    public function set_cart_key($request)
    {
        /* Define Cart Key and Customer ID */
        if(Self::$customer_info)
        {
            $customer_id = Self::$customer_info->customer_id;
        }
        else
        {
            $customer_id = 0;
        }

        /* Get Active Cart */
        $active_cart_key = Cart2::get_cart_key();
        $customer_cart   = preg_match('/customer/', $active_cart_key);
        
        if (!$active_cart_key || !$customer_cart) 
        {
            if ($customer_id != 0) 
            {
                $cart_key = "customer-" . Self::$customer_info->customer_id;
            }
            else
            {
                $cart_key = "customer-" . time();
            }

            /* Set Cart Key */
            Cart2::set_cart_key($cart_key);
        }

        /* Set Price Level */
        $key    = "price_level_id";
        $value  = "0";

        Cart2::set($key, $value);

        /* Set Customer ID */
        $key    = "customer_id";
        $value  = $customer_id;

        Cart2::set($key, $value);

        /* Set Invoice ID */
        $key    = "invoice_id";
        $value  = "0";

        Cart2::set($key, $value);

        /* Set Receipt ID */
        $key    = "receipt_id";
        $value  = "0";

        Cart2::set($key, $value);

        /* Set Shipping Fee */
        $key    = "shipping_fee";
        $value  = isset(Settings::get_settings_php_shop_id("shipping_fee", $this->shop_info->shop_id)["settings_value"]) ? Settings::get_settings_php_shop_id("shipping_fee", $this->shop_info->shop_id)["settings_value"] : 0;

        Cart2::set($key, $value);

        /* Set Discount */
        $key    = "global_discount";
        $value  = "0";

        Cart2::set($key, $value);
    }
    public function add_cart(Request $request)
    {
        /* Set Cart Key */
        $this->set_cart_key($request);

        /* Define Item */
        $item_id = $request->item_id;
        $quantity = $request->quantity;
        $shop_id = $this->shop_info->shop_id;
        
        /* Add to Cart */
        Cart2::add_item_to_cart($shop_id, $item_id, $quantity);

        /* Return Success */
        return response()->json("success");
    }
    public function remove_cart(Request $request)
    {
        /* Item ID */
        $item_id = $request->item_id;

        /* Delete from Cart */
        Cart2::delete_item_from_cart($item_id);

        /* Return Success */
        return response()->json("success");
    }   
    public function update_cart(Request $request)
    {
        /* Parameters */
        $item_id    = $request->item_id; //tbl_item
        $quantity   = $request->quantity; //number of items
        
        /* Edit Function */
        Cart2::edit_item_from_cart($item_id, $quantity);

        /* Return Success */
        return response()->json("success");
    }   
    public function clear_cart(Request $request)
    {
        
    }    
    public function quantity_cart(Request $request)
    {
        $quantity = Cart2::get_cart_quantity();
        
        return response()->json($quantity);
    }
    public function buy_kit_mobile(Request $request, $item_id)
    {
        /* Set Cart Key */
        $this->set_cart_key($request);
        
        /* Clear Cart */
        Cart2::clear_cart();

        /* Define Item */
        $quantity = 1;
        $shop_id = 5;
        
        /* Add to Cart */
        Cart2::add_item_to_cart($shop_id, $item_id, $quantity);
        
        return Redirect::to("/members/checkout");
    }
}