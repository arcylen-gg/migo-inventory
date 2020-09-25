<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use Carbon\Carbon;
use App\Models\Tbl_product;
use App\Globals\Ecom_Product;
use App\Models\Tbl_ec_product;
use App\Globals\Cart;
use App\Globals\Ec_wishlist;

class ShopCartController extends Shop
{
    public function viewed_product($product_id)
    {
        if(Session::get('mlm_member') != null)
        {
            $session = Session::get('mlm_member');
            $customer_id = $session['customer_info']->customer_id;

            $insert["customer_id"] = $customer_id;
            $insert["product_id"]  = $product_id;
            $insert["shop_id"]     = $this->shop_info->shop_id;
            $insert["date"]        = Carbon::now();

            $exist = DB::table("tbl_ec_recently_viewed_products")->where("customer_id", $customer_id)->where("product_id", $product_id)->where("shop_id", $this->shop_info->shop_id)->first();
            if ($exist) 
            {
                DB::table("tbl_ec_recently_viewed_products")->where("customer_id", $customer_id)->where("product_id", $product_id)->where("shop_id", $this->shop_info->shop_id)->delete();
            }

            DB::table("tbl_ec_recently_viewed_products")->where("customer_id", $customer_id)->where("product_id", $product_id)->where("shop_id", $this->shop_info->shop_id)->insert($insert);
        }
    }

    public function wishlist_exist($product_id)
    {
        if(Session::get('mlm_member') != null)
        {
            $session     = Session::get('mlm_member');
            $customer_id = $session['customer_info']->customer_id;
            $shop_id     = $this->shop_info->shop_id;

            $result = Ec_wishlist::notExistProduct($product_id, $customer_id, $shop_id);

            return $result;
        }
        else
        {
            return false;
        }
    }

    public function index()
    {
        $data["page"]  = "Product Cart";
        $data["get_cart"] = Cart::get_cart($this->shop_info->shop_id);
        
        return view("cart_modal", $data);
    }

    public function quick_cart()
    {
        $id                  = Request::input("product_id");

        $this->viewed_product($id);

        $data["page"]        = "Product Quick Cart";
        $data["product"]     = Ecom_Product::getProduct($id, $this->shop_info->shop_id);
        $data["category"]    = Tbl_ec_product::category()->where("eprod_category_id", $data["product"]["eprod_category_id"])->first();
        $data["breadcrumbs"] = Ecom_Product::getProductBreadcrumbs($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
        $data["_variant"]    = Ecom_Product::getProductOption($id, ",");
        $data["_related"]    = Ecom_Product::getAllProductByCategory($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
        $data["wishlist"]    = $this->wishlist_exist($id);
      
        foreach ($data["_related"] as $key => $value) 
        {
            if ($value["eprod_id"] == $data["product"]["eprod_id"]) 
            {
                unset($data["_related"][$key]);
            }
        }

        foreach ($data["product"]["variant"] as $keys => $values) 
        {
            // Convert to timestamp
            $start_ts = strtotime($values['item_discount_date_start']);
            $end_ts = strtotime($values['item_discount_date_end']);
            $user_ts = strtotime(date("Y-m-d H:i:s"));

            $result = (($user_ts >= $start_ts) && ($user_ts <= $end_ts));

            if ($result)
            {
                $data["product"]["variant"][$keys]["discounted"] = true;
                $data["product"]["variant"][$keys]["discounted_price"] = $values["item_discount_value"];
                if (isset($data["product"]["variant"][$keys]["mlm_discount"])) 
                {
                    foreach ($data["product"]["variant"][$keys]["mlm_discount"] as $key0 => $value0) 
                    {
                        if ($value0["discount_type"] == 0) 
                        {
                            $data["product"]["variant"][$keys]["mlm_discount"][$key0]["discounted_amount"] = $values["item_discount_value"] - $value0['discount_value'];
                        }
                        else
                        {
                           $data["product"]["variant"][$keys]["mlm_discount"][$key0]["discounted_amount"] = $values["item_discount_value"] - ($value0['discount_value'] / 100) * $values["item_discount_value"];
                        }                    
                    }
                }
            }
            else
            {
                $data["product"]["variant"][$keys]["discounted"] = false;
            }

            $data["product"]["variant"][$keys]["variant_image"] = Ecom_Product::getVariantImage($values["evariant_id"])->toArray();
        }

        return view("quick_add_cart", $data);
    }

    public function mini_cart()
    {
        $data["page"] = "Mini Product Cart";
        $data["get_cart"] = Cart::get_cart($this->shop_info->shop_id);
    
        return view("mini_cart_modal", $data);
    }

    public function add_cart()
    {
        $variant_id = Request::input("variant_id");
        $quantity = Request::input("quantity");

        $result = Cart::add_to_cart($variant_id,$quantity,$this->shop_info->shop_id);

        echo json_encode($result);
    }

    public function remove_cart()
    {
        $variant_id = Request::input("variation_id");
        $result = Cart::delete_product($variant_id, $this->shop_info->shop_id);

        if (Request::input("redirect")) 
        {
            return Redirect::back();
        }
        else
        {
            echo json_encode($result);
        }
    }

    public function update_cart()
    {
        $variant_id = Request::input("variation_id");
        $quantity = Request::input("quantity");
        $result = Cart::update_cart($variant_id, $quantity, $this->shop_info->shop_id);

        echo json_encode($result);
    }

    public function clear_cart()
    {
        $result = Cart::clear_all($this->shop_info->shop_id);

        echo json_encode($result);
    }
}