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
use App\Globals\Ec_wishlist;

class ShopProductContentController extends Shop
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

    public function index($id)
    {
        if($id == "test")
        {
            $data["page"]        = "Product Content";
            
            return view("product_content", $data);
        }
        elseif ($id) 
        {
            $this->viewed_product($id);

            $data["page"]        = "Product Content";
            $data["product"]     = Ecom_Product::getProduct($id, $this->shop_info->shop_id);

            if ($data["product"]) 
            {
                $data["category"]    = Tbl_ec_product::category()->where("eprod_category_id", $data["product"]["eprod_category_id"])->first();
                $data["breadcrumbs"] = Ecom_Product::getProductBreadcrumbs($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
                $data["_variant"]    = Ecom_Product::getProductOption($id, ",");
                $data["_related"]    = Ecom_Product::getAllProductByCategory($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
                $data["wishlist"]    = $this->wishlist_exist($id);

                if ($this->shop_theme == "3xcell") 
                {
                    $get_cat = DB::table("tbl_category")->where("type_shop", $this->shop_info->shop_id)->where("type_name", "Business Packages")->where("archived", 0)->first();
                    if (isset($get_cat->type_id)) 
                    {
                        $data["_package"] = Ecom_Product::getAllProductByCategory($get_cat->type_id, $this->shop_info->shop_id);
                    }
                    else
                    {
                        $data["_package"] = [];
                    }
                }

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
                
                return view("product_content", $data);
            }
            else
            {
                return Redirect::to("/product");
            }
        }
    }

    public function variant()
    {
        $name    = implode(",", Request::input("variation"));
        $variant = Ecom_Product::getVariant($name, Request::input("product_id"), ",");

        if ($variant)
        {
            $response["variation"] = $variant->toArray();
            $response["result"] = "success";
        }
        else
        {
            $response["result"] = "fail";
            $response["no_stock"] = "nostock";
        }

        echo json_encode($response);
    }

    public function search()
    {
        $search = Ecom_Product::searchProduct(Request::input("search"), $this->shop_info->shop_id);

        return json_encode($search);
    }
}