<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_ec_wishlist;
use App\Models\Tbl_customer;
use App\Models\Tbl_ec_product;

use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\Carbon;

use App\Globals\Ecom_Product;

class Ec_wishlist
{
    public static function getCustomerCount($shop_id)
    {
        $customer = Tbl_customer::select("*", DB::raw("COUNT(tbl_ec_wishlist.customer_id) as count"))
                                ->where("tbl_customer.shop_id", $shop_id)
                                ->leftJoin("tbl_ec_wishlist", "tbl_customer.customer_id", "=", "tbl_ec_wishlist.customer_id")
                                ->groupBy("tbl_ec_wishlist.customer_id")
                                ->orderBy("count", "DESC")
                                ->get();

        return $customer;
    }
    public static function getProductCount($shop_id)
    {
        $product = Tbl_ec_product::select("*", DB::raw("COUNT(tbl_ec_wishlist.product_id) as count"))
                                ->where("tbl_ec_product.eprod_shop_id", $shop_id)
                                ->leftJoin("tbl_ec_wishlist", "tbl_ec_product.eprod_id", "=", "tbl_ec_wishlist.product_id")
                                ->groupBy("tbl_ec_wishlist.product_id")
                                ->orderBy("count", "DESC")
                                ->get();

        return $product;
    }
    public static function getAllProduct($shop_id)
    {
        $wishlist = Tbl_ec_wishlist::where("shop_id", $shop_id)->where("archived", 0)->get();
        foreach ($wishlist as $key => $value) 
        {
            $wishlist[$key]->product = Ecom_Product::getProduct($value->product_id, $shop_id);
        }

        return $wishlist;
    }
    public static function getProduct($customer_id, $shop_id)
    {
        $wishlist = Tbl_ec_wishlist::where("customer_id", $customer_id)->where("shop_id", $shop_id)->where("archived", 0)->get();
        foreach ($wishlist as $key => $value) 
        {
            $wishlist[$key]->product = Ecom_Product::getProduct($value->product_id, $shop_id);
        }

        return $wishlist;
    }
    public static function addProduct($product_id, $customer_id, $shop_id)
    {
        $insert["customer_id"] = $customer_id;
        $insert["product_id"] = $product_id;
        $insert["shop_id"] = $shop_id;
        
        $exist = Tbl_ec_wishlist::where("customer_id", $customer_id)
                                ->where("product_id", $product_id)
                                ->where("shop_id", $shop_id)
                                ->delete();

        Tbl_ec_wishlist::insert($insert);
    }
    public static function removeProduct($wishlist_id, $shop_id)
    {
        Tbl_ec_wishlist::where("id", $wishlist_id)->where("shop_id", $shop_id)->update( [ "archived" => 1 ] );
    }
    public static function notExistProduct($product_id, $customer_id, $shop_id)
    {
        $result = Tbl_ec_wishlist::where("customer_id", $customer_id)
                                 ->where("product_id", $product_id)
                                 ->where("shop_id", $shop_id)
                                 ->where("archived", 0)
                                 ->first();
   
        if ($result) 
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}