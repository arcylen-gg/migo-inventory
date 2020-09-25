<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_item;
use App\Models\Tbl_ec_product;
use App\Globals\Ecom_Product;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Crypt;
use Carbon\Carbon;

class Ec_brand
{
    /**
     * Get all brands
     *
     * @return array      List of Brands
     */
    public static function getAllBrands($shop_id)
    {
        if ($shop_id) 
        {
            return Tbl_manufacturer::where("manufacturer_shop_id", $shop_id)
                                    ->leftJoin("tbl_image", "tbl_manufacturer.manufacturer_image", "=", "tbl_image.image_id")
                                    ->where("archived", 0)
                                    ->get();
        }
        else
        {
            return null;
        }
    }
    /**
     * Get product by brands
     *
     * @return array      List of Product by Brands
     */
    public static function getProductBrands($manufacturer_id, $shop_id)
    {
        if ($shop_id) 
        {
            return Ecom_Product::getAllProduct($shop_id,$manufacturer_id);
        }
        else
        {
            return null;
        }
    }
}