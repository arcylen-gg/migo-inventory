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
use App\Globals\MLM2;
use App\Models\Tbl_mlm_slot;

class ShopProductContent2Controller extends Shop
{
    public function index($id)
    {
    	$data["page"]        		= "Product Content";
        $data["product"]     		= Ecom_Product::getProduct($id, $this->shop_info->shop_id);

        if ($data["product"]) 
        {
            $data["category"]           = Tbl_ec_product::category()->where("eprod_category_id", $data["product"]["eprod_category_id"])->first();
            $data["breadcrumbs"]        = Ecom_Product::getProductBreadcrumbs($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
            $data["_variant"]           = Ecom_Product::getProductOption($id, ",");
            $data["_related"]           = Ecom_Product::getAllProductByCategory($data["product"]["eprod_category_id"], $this->shop_info->shop_id);
            $data["_related"]           = $this->filter_related($data["_related"], $data["product"]["eprod_id"]);
            $data["product"]["variant"] = $this->filter_variant($data["product"]["variant"]);

            if ($this->shop_theme == "3xcell") 
            {
                if (isset(Self::$customer_info->customer_id) && Self::$customer_info->customer_id) 
                {
                    foreach ($data["product"]["variant"] as $key => $value) 
                    {
                        $price_level = Tbl_mlm_slot::priceLevel($value["item_id"])->where("tbl_mlm_slot.slot_owner", Self::$customer_info->customer_id)->first();

                        $data["product"]["variant"][$key]["price_level"] = $price_level ? $price_level->custom_price : null;
                    }
                }
                
                if(isset(Self::$customer_info->customer_id))
                {
                    $slot = Tbl_mlm_slot::where("slot_owner", Self::$customer_info->customer_id)->first();
                }
                else
                {
                    $slot = null;
                }

                foreach ($data["product"]["variant"] as $key => $value) 
                {
                    if ($slot) 
                    {
                        $data["product"]["variant"][$key]["pv"] = MLM2::item_points($this->shop_info->shop_id, $value["item_id"], $slot->slot_id);
                    }
                    else
                    {
                        $data["product"]["variant"][$key]["pv"] = 0;
                    }
                }
            }

            if ($this->shop_theme == "philtech") 
            {
                if (isset($this->shop_info->shop_id) && isset(Self::$customer_info->customer_id)) 
                {
                    foreach ($data["product"]["variant"] as $key => $value) 
                    {
                        if ($value["discounted"] == "true") 
                        {
                            $data["product"]["variant"][$key]["discounted_price"] = Ecom_Product::getMembershipPrice($this->shop_info->shop_id, Self::$customer_info->customer_id, $value["evariant_item_id"], $value["evariant_price"]);
                        }
                        else
                        {
                            $data["product"]["variant"][$key]["evariant_price"] = Ecom_Product::getMembershipPrice($this->shop_info->shop_id, Self::$customer_info->customer_id, $value["evariant_item_id"], $value["evariant_price"]);
                        }
                    }
                }
            }

            //display categories in product view
            if ($this->shop_info->shop_theme == "kolorete") 
            {
                $data["_category"] = Ecom_Product::getAllCategory($this->shop_info->shop_id);
            }
            
            return view("product_content", $data);
        }
        
        else
        {
            return Redirect::back();
        }
    }

    public function filter_related($related, $eprod_id)
    {
    	foreach ($related as $key => $value) 
        {
            if ($value["eprod_id"] == $eprod_id) 
            {
                unset($related[$key]);
            }
        }

        return $related;
    }

    public function filter_variant($variant)
    {
    	foreach ($variant as $keys => $values) 
        {
            // Convert to timestamp
            $start_ts = strtotime($values['item_discount_date_start']);
            $end_ts = strtotime($values['item_discount_date_end']);
            $user_ts = strtotime(date("Y-m-d H:i:s"));

            $result = (($user_ts >= $start_ts) && ($user_ts <= $end_ts));

            if ($result)
            {
                $variant[$keys]["discounted"] = true;
                $variant[$keys]["discounted_price"] = $values["item_discount_value"];

                if (isset($variant[$keys]["mlm_discount"])) 
                {
                    foreach ($variant[$keys]["mlm_discount"] as $key0 => $value0) 
                    {
                        if ($value0["discount_type"] == 0) 
                        {
                            $variant[$keys]["mlm_discount"][$key0]["discounted_amount"] = $values["item_discount_value"] - $value0['discount_value'];
                        }
                        else
                        {
                           $variant[$keys]["mlm_discount"][$key0]["discounted_amount"] = $values["item_discount_value"] - ($value0['discount_value'] / 100) * $values["item_discount_value"];
                        }                    
                    }
                }
            }
            else
            {
                $variant[$keys]["discounted"] = false;
            }

            $variant[$keys]["variant_image"] = Ecom_Product::getVariantImage($values["evariant_id"])->toArray();
        }

        return $variant;
    }
}