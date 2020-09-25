<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use App\Models\Tbl_category;
use App\Models\Tbl_mlm_slot;
use App\Globals\Category;
use App\Globals\Ecom_Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Globals\Ec_brand;
use DB;

class ShopProductController extends Shop
{
    public function get_product($type, $brand, $search)
    {
        if ($search) 
        {
            // Get Product Searched
            $data["get_product"] = Ecom_Product::getAllProductSearch($search, $this->shop_info->shop_id);
            // Get Breadcrumbs
            $data["breadcrumbs"] = [];
        }
        elseif ($type) 
        {
            // Get Product by Category
            $data["get_product"] = Ecom_Product::getAllProductByCategory($type, $this->shop_info->shop_id);
            // Get Breadcrumbs
            $data["breadcrumbs"] = Ecom_Product::getProductBreadcrumbs($type, $this->shop_info->shop_id);
        }
        elseif($brand)
        {
            $data["get_product"] = Ec_brand::getProductBrands($brand, $this->shop_info->shop_id);
            $manufacturer = DB::table("tbl_manufacturer")->where("manufacturer_id", $brand)->first();
            $data["breadcrumbs"][0]["type_name"] = $manufacturer->manufacturer_name;
        }
        else
        {
            // Get Product
            $data["get_product"] = Ecom_Product::getAllProduct($this->shop_info->shop_id);
            // Get Breadcrumbs
            $data["breadcrumbs"] = [];
        }

        return $data;
    }
    public function filter_price($product)
    {
        if ($product) 
        {
            $max_price = array_column($product, 'max_price');
            $min_price = array_column($product, 'min_price');

            if ($max_price && $min_price) 
            {
                $data['max_price'] = max($max_price);
                $data['min_price'] = min($min_price);
            }
            else
            {
                $data['max_price'] = 0;
                $data['min_price'] = 0;
            } 
        }
        else
        {
            $data['max_price'] = 0;
            $data['min_price'] = 0;
        }

        return $data;
    }
    public function index()
    {
        $data["page"] = "Product";
        // Get Parameters
        $type   = Request::input("type");
        $brand  = Request::input("brand");
        $search = Request::input("search");
        $min = Request::input("min");
        $max = Request::input("max");
        // Get Most Searched
        $data["_most_searched"] = Ecom_Product::getMostSearched(5, $this->shop_info->shop_id);
        // Get Category
        $data["_category"] = Ecom_Product::getAllCategory($this->shop_info->shop_id);
        // Get Product
        $get_product = $this->get_product($type, $brand, $search);
        $data["breadcrumbs"] = $get_product["breadcrumbs"];
        $product             = $get_product["get_product"];
        if($brand)
        {
            foreach ($product as $key_brand => $value_brand) 
            {
                if(count($value_brand["variant"]) <= 0)
                {
                    unset($product[$key_brand]);
                }
            }
        }
        // Count total product
        $data["total_product"] = count($product);
        // Filter Price
        $filter_price = $this->filter_price($product, $min, $max);
        $data["max_price"] = $filter_price["max_price"];
        $data["min_price"] = $filter_price["min_price"];
        if ($min && $max) 
        {
            foreach ($product as $key => $value) 
            {
                if ($value['min_price'] >= $min && $value['min_price'] <= $max && $value['max_price'] >= $min && $value['max_price'] <= $max) 
                {
                    // No Unset
                }
                else
                {
                    unset($product[$key]);
                }
            }
        }
        //Sort
        $sort = Request::input("sort");
        switch ($sort) 
        {
            case 'name_asc':
                usort($product, function($a, $b) 
                {
                    if ($a['eprod_name'] == $b['eprod_name'])
                    {
                        return 0;
                    }
                     if ($a['eprod_name'] < $b['eprod_name'])
                    {
                        return -1;
                    }
                     if ($a['eprod_name'] > $b['eprod_name'])
                    {
                        return 1;
                    }
                    // return $a['eprod_name'] <=> $b['eprod_name'];
                });
            break;

            case 'name_desc':
                usort($product, function($a, $b) 
                {
                    if ($b['eprod_name'] == $a['eprod_name'])
                    {
                        return 0;
                    }
                     if ($b['eprod_name'] < $a['eprod_name'])
                    {
                        return -1;
                    }
                     if ($b['eprod_name'] > $a['eprod_name'])
                    {
                        return 1;
                    }
                    // return $b['eprod_name'] <=> $a['eprod_name'];
                });
            break;

            case 'price_asc':
                usort($product, function($a, $b) 
                {
                    if ($a['max_price'] == $b['max_price'])
                    {
                        return 0;
                    }
                     if ($a['max_price'] < $b['max_price'])
                    {
                        return -1;
                    }
                     if ($a['max_price'] > $b['max_price'])
                    {
                        return 1;
                    }
                    // return $a['max_price'] <=> $b['max_price'];
                });
            break;

            case 'price_desc':
                usort($product, function($a, $b) 
                {
                    if ($b['min_price'] == $a['min_price'])
                    {
                        return 0;
                    }
                     if ($b['min_price'] < $a['min_price'])
                    {
                        return -1;
                    }
                     if ($b['min_price'] > $a['min_price'])
                    {
                        return 1;
                    }
                    // return $b['min_price'] <=> $a['min_price'];
                });
            break;

            case 'newest':
                usort($product, function($a, $b) 
                {
                    if ($b['date_created'] == $a['date_created'])
                    {
                        return 0;
                    }
                     if ($b['date_created'] < $a['date_created'])
                    {
                        return -1;
                    }
                     if ($b['date_created'] > $a['date_created'])
                    {
                        return 1;
                    }
                    // return $b['date_created'] <=> $a['date_created'];
                });
            break;
            
            default:
                usort($product, function($a, $b) 
                {
                    if ($a['eprod_name'] == $b['eprod_name'])
                    {
                        return 0;
                    }
                     if ($a['eprod_name'] < $b['eprod_name'])
                    {
                        return -1;
                    }
                     if ($a['eprod_name'] > $b['eprod_name'])
                    {
                        return 1;
                    }
                    // return $a['eprod_name'] <=> $b['eprod_name'];
                });
            break;
        }
        // Pagination
        $perPage = 12;

        if($this->shop_theme == "intogadgets")
        {
            $perPage = 20;
        }
        elseif($this->shop_theme == "3xcell" || $this->shop_theme == "additions")
        {
            if ($type) 
            {
                $name_category = DB::table("tbl_category")->where("type_id", $type)->first();
                if ($name_category) 
                {
                    $data["category_name"] = $name_category->type_name;
                }
                else
                {
                    $data["category_name"] = "All";
                }
            }
            else
            {
                $data["category_name"] = "All";
            }
        }

        if ($this->shop_theme == "3xcell") 
        {
            if (isset(Self::$customer_info->customer_id) && Self::$customer_info->customer_id) 
            {
                foreach ($product as $key => $value) 
                {
                    $price_level = Tbl_mlm_slot::priceLevel($value["variant"][0]["item_id"])->where("tbl_mlm_slot.slot_owner", Self::$customer_info->customer_id)->first();

                    $product[$key]["min_price"] = $price_level ? $price_level->custom_price : null;
                    $product[$key]["max_price"] = $price_level ? $price_level->custom_price : null;
                }
            }
        }

        $data["_product"] = self::paginate($product, $perPage);
        $data["current_count"] = count($data["_product"]);

        /* Category Show */
        if ($this->shop_info->shop_theme == "brown")
        {
            $data["_categories"] = Ecom_Product::getAllCategory($this->shop_info->shop_id);
        }

        return view("product", $data);
    }
    public function paginate($items,$perPage)
    {
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage; 

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

    // P4ward Product Red Rice Scrub -> Carlo
    public function product2()
    {
        $data["page"] = "Product 2";
        return view("product_2", $data);
    }
}