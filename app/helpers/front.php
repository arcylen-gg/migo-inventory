<?php
use App\Globals\Ecom_Product;
use App\Globals\Mlm_report;
use App\Models\Tbl_post;

function get_post($shop_id, $key)
{
    $post_id = explode(",", DB::table("tbl_content")->where("key", $key)->where("shop_id", $shop_id)->first()->value);

    $post = DB::table("tbl_post")->where("archived", 0)->where("shop_id", $shop_id)->get();
    foreach ($post as $key => $value) 
    {
        foreach ($post_id as $keys => $values) 
        {
            if ($values != $value->post_id) 
            {
                unset($post[$key]);
            }
        }
    }

    return $post;
}

function get_content($data, $tab, $content, $default = "")
{
    $response = $data->$tab->$content->default;

    return $response ? $response : $default;
}

function get_collection($collection_id, $shop_id = null)
{
    $collection = Ecom_Product::getProductCollection($collection_id, $shop_id);
    foreach ($collection as $key => $value) 
    {
        if(!isset($value["product"]["variant"][0])) 
        {            
            unset($collection[$key]);
        }

    }
    return $collection;
}
function get_collection_random($collection_id, $shop_id = null)
{

    $collection = Ecom_Product::getProductCollection($collection_id, $shop_id);
    foreach ($collection as $key => $value) 
    {
        if(!isset($value["product"]["variant"][0])) 
        {            
            unset($collection[$key]);
        }

    }
    $collection = shuffle_assoc($collection);
    return $collection;
}
// function array_random($array)
// {
//     $return_array = $array;
//     foreach ($array as $key => $value) 
//     {
        
//     }
//     return $return_array;
// }
function get_collection_first_name($data)
{
    return $data['product']['eprod_name'] ? $data['product']['eprod_name'] : '';
}

function get_collection_first_image($data)
{
    if(isset($data['product']['variant'][0]))
    {
        return $data['product']['variant'][0]['image'] ? $data['product']['variant'][0]['image'][0]['image_path'] : '/assets/front/img/placeholder.png';
    }
    else
    {
        return '/assets/front/img/placeholder.png';
    }
}

function get_collection_first_price($data)
{
    if (isset($data['product']['min_price']) && isset($data['product']['max_price'])) 
    {
        return $data['product']['min_price'] == $data['product']['max_price'] ? "&#8369; " . number_format($data['product']['max_price'], 2) : "&#8369; " . number_format($data['product']['min_price'], 2) . " - " . number_format($data['product']['max_price'], 2);
    }
    else
    {
        return "&#8369; 0.00";
    }
}

function get_product_first_name($data)
{
    return isset($data['eprod_name']) ? $data['eprod_name'] : '';
}

function get_product_first_description($data)
{
    if (isset($data['variant'][0])) 
    {
        return $data['variant'][0]['evariant_description'] ? $data['variant'][0]['evariant_description'] : '';
    }
    else
    {
        return '';
    }
}

function get_product_first_price($data)
{
    if (isset($data['min_price']) && isset($data['max_price'])) 
    {
        return $data['min_price'] == $data['max_price'] ? "&#8369; " . number_format($data['max_price'], 2) : "&#8369; " . number_format($data['min_price'], 2) . " - " . number_format($data['max_price'], 2);
    }
    else
    {
        return "&#8369; 0.00";
    }
}

function get_product_first_image($data)
{
    if (isset($data['variant'][0])) 
    {
        return $data['variant'][0]['image'] ? $data['variant'][0]['image'][0]['image_path'] : '/assets/front/img/placeholder.png';
    }
    else
    {
        return '/assets/front/img/placeholder.png';
    }   
}

function loop_content_condition($data, $tab, $content)
{
    if ( is_serialized( get_content($data, $tab, $content) ) )
    {
        if ( count( unserialize( get_content($data, $tab, $content) ) ) > 0 ) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function loop_content_get($data, $tab, $content)
{
    return unserialize( get_content($data, $tab, $content) );
}

function loop_content_divide($data, $divide = 12)
{
    $current = [];
    $current_array = 0;
    $fix_data = array_values($data);
    foreach ($fix_data as $key => $value) 
    {
        $i = $key + 1;

        if ($i%$divide == 1) 
        {
            $current_array++;
        }

        $current[$current_array][$i] = $value;
    }
    
    return $current;
}

function get_front_news($shop_id)
{
    return Tbl_post::where("shop_id", $shop_id)->where("archived", 0)->orderBy("post_date", "DESC")->get();
}

function get_front_divide_string($data, $tab, $content, $count, $index)
{
    $return = explode(' ', get_content($data, $tab, $content), $count);
    if (isset($return[$index])) 
    {
        $result = $return[$index];
    }
    else
    {
        $result = '';
    }

    return $result;
}

function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function get_cart_item_id($cart)
{
    return isset($cart["cart_product_information"]["variant_id"]) ? $cart["cart_product_information"]["variant_id"] : null;
}

function get_cart_item_image($cart)
{
    return isset($cart['cart_product_information']['image_path']) ? $cart['cart_product_information']['image_path'] : '/assets/mlm/img/placeholder.jpg';
}

function get_cart_item_name($cart)
{
    if (isset($cart["cart_product_information"]["product_name"]) && isset($cart["cart_product_information"]["variant_name"])) 
    {
        $return = $cart["cart_product_information"]["product_name"]; 
        if($cart["cart_product_information"]["product_name"] != $cart["cart_product_information"]["variant_name"])
        {
           $return .= " ( ". $cart["cart_product_information"]["variant_name"] ." )"; 
        }
    }
    else
    {
        $return = "No Product Name";
    }
    
    return $return;
}

function get_cart_item_price($cart)
{
    $price = isset($cart["cart_product_information"]["product_price"]) ? $cart["cart_product_information"]["product_price"] : 0;
    return number_format($price, 2);
}

function get_cart_item_quantity($cart)
{
    return isset($cart["quantity"]) ? $cart["quantity"] : 0;
}

function get_cart_item_subtotal($cart)
{
    return isset($cart["cart_product_information"]["product_price"]) && isset($cart["quantity"]) ? number_format($cart["cart_product_information"]["product_price"] * $cart["quantity"], 2) : 0;
}

function get_cart_item_total($cart)
{
    return isset($cart["sale_information"]["total_product_price"]) ? number_format($cart["sale_information"]["total_product_price"], 2) : 0;
}

function get_cart_item_total_quantity($cart)
{
    return isset($cart["sale_information"]["total_quantity"]) ? $cart["sale_information"]["total_quantity"] : 0;
}

function get_front_sidebar_icon($name, $shop_theme)
{
    switch ($name) 
    {
        case 'DTH PRODUCTS':
            return '/themes/'. $shop_theme .'/img/sidebar/dth.png';
        break;

        case 'PREPAID CARDS':
            return '/themes/'. $shop_theme .'/img/sidebar/card.png';
        break;

        case 'GADGETS':
            return '/themes/'. $shop_theme .'/img/sidebar/gadgets.png';
        break;

        case 'ELECTRONICS':
            return '/themes/'. $shop_theme .'/img/sidebar/electronics.png';
        break;

        case 'SERVICES':
            return '/themes/'. $shop_theme .'/img/sidebar/services.png';
        break;

        case 'ENTERTAINMENT':
            return '/themes/'. $shop_theme .'/img/sidebar/entertainment.png';
        break;

        case 'APPAREL':
            return '/themes/'. $shop_theme .'/img/sidebar/apparel.png';
        break;

        case 'ACCESORIES':
            return '/themes/'. $shop_theme .'/img/sidebar/accessories.png';
        break;

        case 'HEALTH & WELLNESS':
            return '/themes/'. $shop_theme .'/img/sidebar/health.png';
        break;
        
        default:
            return '/themes/'. $shop_theme .'/img/sidebar/accessories.png';
        break;
    }
}