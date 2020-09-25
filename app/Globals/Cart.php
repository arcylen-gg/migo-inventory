<?php
namespace App\Globals;
use App\Models\Tbl_customer;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_item;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_cart;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_ec_product;
use App\Models\Tbl_coupon_code_product;
use App\Models\Tbl_user;
use App\Models\Tbl_ec_variant;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_ec_order_item;
use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_email_template;
use App\Models\Tbl_online_pymnt_api;
use App\Models\Tbl_mlm_slot;
use App\Globals\Ecom_Product;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Globals\Mlm_discount;
use App\Globals\Mlm_member;
use App\Models\Tbl_mlm_item_points;
use App\Globals\Mlm_plan;
use Crypt;
use Config;
use URL;
use App\Globals\Mlm_slot_log;
// IPAY 88
use App\IPay88\RequestPayment;
// DRAGON PAY
use App\Globals\Dragonpay2\Dragon_RequestPayment;
// PAYMAYA
use App\Globals\PayMaya\PayMayaSDK;
use App\Globals\PayMaya\API\Checkout;
use App\Globals\PayMaya\Core\CheckoutAPIManager;
use App\Globals\PayMaya\Checkout\User;
use App\Globals\PayMaya\Model\Checkout\ItemAmountDetails;
use App\Globals\PayMaya\Model\Checkout\ItemAmount;

class Cart
{
    public static function get_unique_id($shop_id)
    {
        return "cart:".get_ip_address()."_".$shop_id;
    }
    public static function get_shop_info()
    {
        $shop_info = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
        return $shop_info;
    }
    public static function add_to_cart($product_id,$quantity,$shop_id = null,$clear = false)
    {
        if (!$shop_id) 
        {
            $shop_id = Cart::get_shop_info();
        }
        
        $unique_id = Cart::get_unique_id($shop_id);
        $check     = Tbl_ec_variant::where("evariant_id",$product_id)->first();

        if(number_format($quantity) <= 0)
        {
            $message["status"]         = "error";
            $message["status_message"] = "Value is not available.";
        }
        else if(!$check)
        {
            $message["status"]         = "error";
            $message["status_message"] = "Product not found.";
        }
        else
        {
            $_cart                                            = Session::get($unique_id);

            if ($clear == true) 
            {
                unset($_cart["cart"]);
                $insert = $_cart;
            }

            $insert["cart"][$product_id]["product_id"]        = $product_id;
            $insert["cart"][$product_id]["quantity"]          = $quantity;
            $insert["cart"][$product_id]["shop_id"]           = $shop_id;
            $insert["cart"][$product_id]["unique_id_per_pc"]  = $unique_id;
            $insert["cart"][$product_id]["date_added"]        = Carbon::now();

            if($_cart && isset($_cart["cart"]))
            {
                $condition = false;

                foreach($_cart["cart"] as $key => $cart)
                {
                    if($key == $product_id)
                    {
                        $_cart["cart"][$key]["quantity"] = $_cart["cart"][$key]["quantity"] + $quantity;
                        $condition = true;

                        Tbl_cart::where("date_added",$_cart["cart"][$key]["date_added"])
                                ->where("shop_id",$shop_id)
                                ->where("product_id",$product_id)
                                ->where("unique_id_per_pc",$unique_id)
                                ->update($_cart["cart"][$key]);
                    }
                }
                
                if($condition == false)
                {
                    $_cart["cart"][$product_id] = $insert["cart"][$product_id];
                    Tbl_cart::insert($insert["cart"][$product_id]);
                    Session::put($unique_id,$_cart);
                }
                else
                {
                    $insert = $_cart;
                    Session::put($unique_id,$insert);                  
                }

                $message["status"]         = "success";
                $message["status_message"] = "Added to cart.";
            }
            else
            {
                Tbl_cart::insert($insert["cart"][$product_id]);
                Session::put($unique_id,$insert);
                $message["status"]         = "success";
                $message["status_message"] = "Added to cart.";
            }
        }

        return $message;
    }

    public static function get_cart($shop_id = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        /* INITIALIZE */
        $date_now              = Carbon::now();
        $unique_id             = Cart::get_unique_id($shop_id);
        $data                  = Session::get($unique_id);
        $customer_setings      = Cart::customer_get_settings($shop_id);

        $total_product_price   = 0;
        $total_shipping        = 0;
        $total_coupon_discount = 0;
        $total_overall_price   = 0;
        $total_quantity        = 0;
        /* CHECK IF COUPON ALREADY USED */
        if(isset($data["applied_coupon_id"]))
        {
            $coupon_code_id = $data["applied_coupon_id"];

            $check          = Tbl_coupon_code::where("coupon_code_id",$coupon_code_id)->where("shop_id",$shop_id)->first();
            if($check->used == 1 || $check->blocked == 1)
            {
                $data       = Session::get($unique_id);
                unset($data["applied_coupon_id"]);
                Session::put($unique_id,$data);
                $data       = Session::get($unique_id);
            }
        }

        if(isset($data["cart"]))
        {     
            /* SET UP CART INFORMATION */
            foreach($data["cart"] as $key => $info)
            {
                $item_discount          = "no_discount";
                $item_discounted_value  = 0;
                $item_discounted_remark = "";

                $item = Ecom_Product::getVariantInfo($info["product_id"]);
                // dd($item);
                $data["cart"][$key]["cart_product_information"]                                   = null;
                $data["cart"][$key]["cart_product_information"]["variant_id"]                     = $item->evariant_id;
                $data["cart"][$key]["cart_product_information"]["item_id"]                        = $item->item_id;
                $data["cart"][$key]["cart_product_information"]["product_name"]                   = $item->eprod_name;
                $data["cart"][$key]["cart_product_information"]["variant_name"]                   = $item->evariant_item_label;
                $data["cart"][$key]["cart_product_information"]["product_stocks"]                 = $item->inventory_count;
                $data["cart"][$key]["cart_product_information"]["product_sku"]                    = $item->item_sku;
                $data["cart"][$key]["cart_product_information"]["product_price"]                  = $item->evariant_price;
                $data["cart"][$key]["cart_product_information"]["image_path"]                     = $item->image_path;
                $data["cart"][$key]["cart_product_information"]["item_category_id"]               = $item->item_category_id;
                /* CHECK IF DISCOUNT EXISTS */
                $check_discount = Tbl_item_discount::where("discount_item_id",$item->item_id)->first();
                if($check_discount)
                {
                    if(strtotime($check_discount->item_discount_date_start) <= strtotime($date_now) && strtotime($check_discount->item_discount_date_end) >= strtotime($date_now))
                    {

                        $current_price = $check_discount->item_discount_value;
                        $item_discounted_remark    = $check_discount->item_discount_remark;
                    }
                }
                if($item->item_discount_value > 0)
                {
                    $data["cart"][$key]["cart_product_information"]["product_price"]                  = $item->item_discount_value;
                }
                if(Session::get('mlm_member') != null)
                {

                    $session = Session::get('mlm_member');
                    if($session['slot_now'])
                    {
                        $discount_membership = Mlm_discount::get_discount_single($session['slot_now']->shop_id, $item->item_id, $session['slot_now']->slot_membership);
                        if(isset($current_price))
                        {
                             $discount_a = $current_price;
                        }
                       else
                       {
                            $discount_a = $item->item_price;
                       }
                        if($discount_membership['type'] == 0)
                        {
                            $item_discounted_value = $discount_membership['value']; 
                        }
                        else
                        {
                            $item_discounted_value =   $discount_a *   ($discount_membership['value']/100); 
                        }
                        $active_plan_product_repurchase = Mlm_plan::get_all_active_plan_repurchase($session['slot_now']->shop_id);
                        $item_points = Tbl_mlm_item_points::where('item_id', $item->item_id)->where('membership_id', $session['slot_now']->slot_membership)
                        ->first();
                        if($item_points)
                        {
                           foreach($active_plan_product_repurchase as $key2 => $value2)
                            {
                                $code = $value2->marketing_plan_code;
                                if($code == 'DISCOUNT_CARD_REPURCHASE' || $code == 'UNILEVEL_REPURCHASE_POINTS' || $code == 'UNILEVEL')
                                {
                                    
                                    
                                }
                                else
                                {
                                    // dd($code);
                                    $data["cart"][$key]["cart_product_information"]["membership_points"][$value2->marketing_plan_label] = $item_points->$code * $info['quantity'];
                                }
                            } 
                        }
                    }
                }
                //u s 2  q n a  m a m a t a y i h h h h
                $current_price                                                                    = $item->evariant_price - $item_discounted_value;
                $data["cart"][$key]["cart_product_information"]["product_discounted"]             = isset($item_discounted) ? $item_discounted : null;
                $data["cart"][$key]["cart_product_information"]["product_discounted_value"]       = isset($item_discounted_value) ? $item_discounted_value : null;
                $data["cart"][$key]["cart_product_information"]["product_discounted_remark"]      = isset($item_discounted_remark) ? $item_discounted_remark : null;
                $data["cart"][$key]["cart_product_information"]["product_current_price"]          = $current_price;
                if ($item_discounted_value != 0) 
                {
                    $data["cart"][$key]["cart_product_information"]["product_price"] = $current_price;
                }

                $total_product_price = $total_product_price + ($current_price * $info['quantity']);
                $total_quantity += $info['quantity'];
            }
        }        

        /* SET OTHER PRICE INFO  */
        $data["sale_information"]                                      = null;
        $data["sale_information"]["total_product_price"]               = $total_product_price;  
        $data["sale_information"]["total_shipping"]                    = $total_shipping;
        $data["sale_information"]["total_quantity"]                    = $total_quantity;
        $data["sale_information"]["minimum_purchase"]                  = 500;

        /* APPLY COUPON DISCOUNT */
        if(isset($data["applied_coupon_id"]))
        {
            $coupon_code_id = $data["applied_coupon_id"];
            $check          = Tbl_coupon_code::where("coupon_code_id",$coupon_code_id)->where("shop_id",$shop_id)->first();
            if($check)
            {
                if($check->coupon_discounted == "fixed")
                {
                    $total_coupon_discount = $check->coupon_code_amount;
                }
                else if($check->coupon_discounted == "percentage")
                {
                    $total_coupon_discount = $total_product_price * ($check->coupon_code_amount/100);
                }             
            }
        }
        /* CHECK IF TOTAL PRICE IS NEGATIVE */
        $total_overall_price                                                = $total_product_price + $total_shipping - $total_coupon_discount;
        if($total_overall_price < 0)
        {
            $total_overall_price = 0;
        }

        /* SET OTHER PRICE INFO  */
        $data["sale_information"]["total_coupon_discount"]             = $total_coupon_discount; 
        $data["sale_information"]["total_overall_price"]               = $total_overall_price; 
        return $data;
    }

    public static function update_cart($variant_id, $quantity, $shop_id = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        $unique_id               = Cart::get_unique_id($shop_id);
        $insert                  = Session::get($unique_id);
        foreach ($insert['cart'] as $key => $value) 
        {
            if($value['product_id'] == $variant_id)
            {
                $insert['cart'][$key]["quantity"] = $quantity;
            }
        }
       
        Session::put($unique_id,$insert);

        $message["status"]         = "success";
        $message["status_message"] = "Cart updated.";

        return $message;
    }

    public static function delete_product($product_id, $shop_id = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        $unique_id  =  Cart::get_unique_id($shop_id);
        $_cart      = Session::get($unique_id);
        $condition  = false;

        if($_cart)
        {
            foreach($_cart["cart"] as $key => $cart)
            {
                if($cart["product_id"] == $product_id)
                {
                    $condition = true;

                    Tbl_cart::where("date_added",$cart["date_added"])
                            ->where("shop_id",$cart['shop_id'])
                            ->where("product_id",$cart['product_id'])
                            ->where("unique_id_per_pc",$cart['unique_id_per_pc'])
                            ->delete();

                    unset($_cart["cart"][$key]);
                }
            }

            if($condition == false)
            {
                 $message["status"]         = "error";
                 $message["status_message"] = "Product doesn't exists.";
            }
            else
            {
                 Session::put($unique_id,$_cart);

                 $message["status"]         = "success";
                 $message["status_message"] = "Successfully removed.";
            }
        }  
        else
        {
             $message["status"]         = "error";
             $message["status_message"] = "Product doesn't exists.";
        }
        
        return $message;
    }

    public static function clear_all($shop_id = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        $unique_id =  Cart::get_unique_id($shop_id);
        $_cart     = Session::get($unique_id);

        if(isset($_cart["cart"]))
        {

            foreach($_cart["cart"] as $key => $cart)
            {
                    Tbl_cart::where("date_added",$_cart["cart"][$key]["date_added"])
                            ->where("shop_id",$_cart["cart"][$key]['shop_id'])
                            ->where("product_id",$_cart["cart"][$key]['product_id'])
                            ->where("unique_id_per_pc",$_cart["cart"][$key]['unique_id_per_pc'])
                            ->delete($_cart["cart"][$key]);
            }
            Session::forget($unique_id);

            $message["status"]         = "success";
            $message["status_message"] = "Cleared all product.";
        }
        else
        {
            $message["status"]         = "error";
            $message["status_message"] = "Nothing to clear.";
        }

        return $message;
    }

    public static function customer_settings($shop_id = null,$customer_id = null,$customer_information = null,$customer_shipping_address = null,$customer_billing_address = null,$customer_payment_method = null,$customer_payment_proof = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        $unique_id =  Cart::get_unique_id($shop_id);
        if($customer_id && $customer_id != 0)
        {
            $data["customer_id"] = $customer_id;
        }
        else
        {
            $data["customer_id"] = null;
        }

        if($customer_information)
        {
            $data["customer_information"]['first_name']    = $customer_information["first_name"];
            $data["customer_information"]['middle_name']   = $customer_information["middle_name"];
            $data["customer_information"]['last_name']     = $customer_information["last_name"];
            $data["customer_information"]['email']         = $customer_information["email"];
            $data["customer_information"]['company']       = $customer_information["company"];
            $data["customer_information"]['birthday']      = $customer_information["birthday"];
        }
        else
        {
            $data["customer_information"]['first_name']    = null;
            $data["customer_information"]['middle_name']   = null;
            $data["customer_information"]['last_name']     = null;
            $data["customer_information"]['email']         = null;
            $data["customer_information"]['company']       = null;
            $data["customer_information"]['birthday']      = null;            
        }

        if($customer_shipping_address)
        {
            $data["customer_shipping_address"]['state']    = $customer_shipping_address["state"];
            $data["customer_shipping_address"]['city']     = $customer_shipping_address["city"];
            $data["customer_shipping_address"]['zip']      = $customer_shipping_address["zip"];
            $data["customer_shipping_address"]['street']   = $customer_shipping_address["street"];
            $data["customer_shipping_address"]['address']  = $customer_shipping_address["address"];
        }
        else
        {
            $data["customer_shipping_address"]['state']    = null;
            $data["customer_shipping_address"]['city']     = null;
            $data["customer_shipping_address"]['zip']      = null;
            $data["customer_shipping_address"]['street']   = null;
            $data["customer_shipping_address"]['address']  = null;
        }

        if($customer_billing_address)
        {
            $data["customer_billing_address"]['state']     = $customer_billing_address["state"];
            $data["customer_billing_address"]['city']      = $customer_billing_address["city"];
            $data["customer_billing_address"]['zip']       = $customer_billing_address["zip"];
            $data["customer_billing_address"]['street']    = $customer_billing_address["street"];
            $data["customer_billing_address"]['address']   = $customer_billing_address["address"];
        }
        else
        {
            $data["customer_billing_address"]['state']     = null;
            $data["customer_billing_address"]['city']      = null;
            $data["customer_billing_address"]['zip']       = null;
            $data["customer_billing_address"]['street']    = null;
            $data["customer_billing_address"]['address']   = null;
        }

        if($customer_payment_method)
        {
            $data["customer_payment_method"] = $customer_payment_method;
        }
        else
        {
            $data["customer_payment_method"] = null;
        }

        if($customer_payment_proof)
        {
            $data["customer_payment_proof"]  = null;
        }
        else
        {
            $data["customer_payment_proof"]  = null;
        }


        Session::put($unique_id,$data);
        $message["status"]         = "success";
        $message["status_message"] = "Settings update.";

        return $message;
    }

    public static function customer_get_settings($shop_id = null)
    {
        //get_shop_info
        if (!$shop_id) 
        {
            $shop_info = Cart::get_shop_info();
            $shop_id = $shop_info->shop_id;
        }

        $unique_id = Cart::get_unique_id($shop_id);
        $data      = Session::get($unique_id);
        if(!$data)
        {
            Cart::customer_settings($shop_id);
        }

        return $data;
    }

    public static function generate_coupon_code($word_limit, $price, $minimum_quantity = 0, $type="fixed", $coupon_product_id = null,$all_product_id = false)
    {
        //get_shop_info
        $shop_id = Cart::get_shop_info();

        if($type != "fixed" && $type != "percentage")
        {
            $message["status"]         = "error";
            $message["status_message"] = "Invalid type.";
        }
        else if($price <= 0)
        {
            $message["status"]         = "error";
            $message["status_message"] = "Invalid price.";
        }   
        else
        {
            $condition                = false;
            $generated_word           = Cart::random_code_generator($word_limit);
            while($condition == false)
            {
                $check                = Tbl_coupon_code::where("coupon_code",$generated_word)->where("shop_id",$shop_id)->first();
                if(!$check)
                {
                    $condition = true;
                }
                else
                {
                    $generated_word   = Cart::random_code_generator($word_limit);
                }
            }

            $coupon   = Tbl_coupon_code::where("shop_id",$shop_id)->orderBy("id_per_coupon","DESC")->first();
            if($coupon)
            {
                $id_per_coupon = $coupon->id_per_coupon + 1;
            }
            else
            {
                $id_per_coupon = 1;
            }

            $insert["id_per_coupon"]           =  $id_per_coupon;                
            $insert["coupon_code"]             =  $generated_word;           
            $insert["coupon_code_amount"]      =  $price;                     
            $insert["coupon_discounted"]       =  $type;                     
            $insert["shop_id"]                 =  $shop_id;
            $insert["coupon_minimum_quantity"] =  $minimum_quantity;         
            $insert["date_created"]            =  Carbon::now();  
            $coupon_code_id = Tbl_coupon_code::insertGetId($insert);

            if($all_product_id)
            {
                $get_all_product = Tbl_ec_product::variant()->where("eprod_shop_id",$shop_id)->where("tbl_ec_product.archived",0)->get();

                foreach ($get_all_product as $key => $value) 
                {
                    $ins_product["coupon_code_id"] = $coupon_code_id;
                    $ins_product["coupon_code_product_id"] = $value->evariant_id;

                    Tbl_coupon_code_product::insert($ins_product);
                }
            }
            else
            {
                foreach ($coupon_product_id as $key => $value) 
                {
                    if($value > 0)
                    {
                        $ins_product["coupon_code_id"] = $coupon_code_id;
                        $ins_product["coupon_code_product_id"] = $value;

                        Tbl_coupon_code_product::insert($ins_product);                           
                    }                     
                }

            }

            $message["status"]         = "success";
            $message["status_message"] = "Successfully generate a coupon code.";
        }  

        return $message;                              
    }   
    public static function update_coupon_code($coupon_id, $price,$coupon_product_id, $minimum_quantity = 0, $type="fixed",$all_product_id = false)
    {

        $shop_id = Cart::get_shop_info();
        if($type != "fixed" && $type != "percentage")
        {
            $message["status"]         = "error";
            $message["status_message"] = "Invalid type.";
        }
        else if($price <= 0)
        {
            $message["status"]         = "error";
            $message["status_message"] = "Invalid price.";
        }   
        else
        {
            $update["coupon_code_amount"] = $price;               
            $update["coupon_discounted"]       =  $type;  
            $update["coupon_minimum_quantity"] =  $minimum_quantity; 
            
            Tbl_coupon_code::where("coupon_code_id",$coupon_id)->update($update);

            Tbl_coupon_code_product::where("coupon_code_id",$coupon_id)->delete();

            if($all_product_id)
            {
                $get_all_product = Tbl_ec_product::variant()->where("eprod_shop_id",$shop_id)->where("tbl_ec_product.archived",0)->get();

                foreach ($get_all_product as $key => $value) 
                {
                    $ins_product["coupon_code_id"] = $coupon_id;
                    $ins_product["coupon_code_product_id"] = $value->evariant_id;

                    Tbl_coupon_code_product::insert($ins_product);
                }
            }
            else
            {
                foreach ($coupon_product_id as $key => $value) 
                {
                    if($value > 0)
                    {
                        $ins_product["coupon_code_id"] = $coupon_id;
                        $ins_product["coupon_code_product_id"] = $value;

                        Tbl_coupon_code_product::insert($ins_product);                           
                    }                 
                }

            }

            $message["status"]         = "success";
            $message["status_message"] = "Successfully generate a coupon code.";
        }
        return $message;
    }
    public static function use_coupon_code($coupon_code)
    {   
        //get_shop_info
        $shop_info = Cart::get_shop_info();
        $shop_id = $shop_info->shop_id;

        $check = Tbl_coupon_code::where("coupon_code",$coupon_code)->where("shop_id",$shop_id)->first();       
        if($check)
        {
            if($check->used == 1)
            {
                $message["status"]         = "error";
                $message["status_message"] = "This coupon code is already used";
            }
            else if($check->blocked == 1)
            {
                $message["status"]         = "error";
                $message["status_message"] = "This coupon code is blocked";
            }
            else
            {
                $unique_id                  = Cart::get_unique_id($shop_id);
                $_cart                      = Session::get($unique_id);
                $_cart["applied_coupon_id"] = $check->coupon_code_id;
                Session::put($unique_id,$_cart); 


                $message["status"]          = "success";
                $message["status_message"]  = "Coupon code applied.";
            }
        }   
        else
        {
            $message["status"]         = "error";
            $message["status_message"] = "Coupon code does not exists.";
        }     
    }

    public static function random_code_generator($word_limit)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $word_limit; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function check_product_stock($cart)
    {
        $message["status"] = "success";
        
        foreach ($cart["cart"] as $key => $value) 
        {
            $item = Ecom_Product::getVariantInfo($value["product_id"]);
            
            if ($item["item_type_id"] != 2) 
            {
                if ($value["quantity"] > $item->inventory_count) 
                {
                    $message["status"]       = "fail";
                    $message["error"][$key]  = $item->eprod_name . " exceeds the current stock (" . $item->inventory_count . ")";
                }
            }
            
        }
        
        return $message;
    }

    /* return content of SESSION */
    public static function get_info($shop_id)
    {
        $data = Session::get(Cart::get_unique_id($shop_id));
        return $data;
    }

    /*
     * TITLE: CUSTOMER SET INFO
     * 
     * Allows us to set information for customer that will be processed later on
     *
     * @param
     *    $shop_id (int)
     *    $customer_information (array)
     *      - first_name
     *      - last_name, email, password, shipping_state, shipping_city, shipping_zip, shipping_street) 
     *      - email
     *      - password
     *      - shipping_state
     *      - shipping_city
     *      - shipping_zip
     *      - shipping_street
     *    $customer_validation (array) - If on of the string is in this array - It will trigger a validation.
     *      - check_account
     *      - check_name
     *      - check_address
     *      - check_contact
     *
     * @return (array)
     *    - status (error, success)
     *    - status_message (contains message if there is an error)
     *
     * @author (Guillermo Tabligan)
     *
     */
    public static function customer_set_info($shop_id, $customer_information, $validation = array())
    {
        $unique_id = Cart::get_unique_id($shop_id);
        
        /* GET INITIAL CART DATA */
        $data = Cart::get_info($shop_id);

        /* READY INFORMATION FOR SESSION */
        $data["new_account"] = (isset($customer_information["new_account"]) ? $customer_information["new_account"] : (isset($data["new_account"]) ? $data["new_account"] : null));;
        $data["billing_equals_shipping"] = (isset($customer_information["billing_equals_shipping"]) ? $customer_information["billing_equals_shipping"] : (isset($data["billing_equals_shipping"]) ? $data["billing_equals_shipping"] : true));;

        /* SET BASIC INFORMATION */
        if (isset($customer_information["email"]) && isset($customer_information["password"])) 
        {
            $customer_exist = DB::table("tbl_customer")->where("email", $customer_information["email"])->where("shop_id", $shop_id)->first();
        }
        
        $data["tbl_customer"]['customer_id']      = isset($customer_exist->customer_id) ? $customer_exist->customer_id : Tbl_customer::max("customer_id") + 1;
        $data["tbl_customer"]['first_name']       = (isset($customer_information["first_name"]) ? $customer_information["first_name"] : (isset($data["tbl_customer"]['first_name']) ? $data["tbl_customer"]['first_name'] : null));
        $data["tbl_customer"]['last_name']        = (isset($customer_information["last_name"]) ? $customer_information["last_name"] : (isset($data["tbl_customer"]['last_name']) ? $data["tbl_customer"]['last_name'] : null));
        $data["tbl_customer"]['middle_name']      = (isset($customer_information["middle_name"]) ? $customer_information["middle_name"] : (isset($data["tbl_customer"]['middle_name']) ? $data["tbl_customer"]['middle_name'] : null));
        $data["tbl_customer"]['email']            = (isset($customer_information["email"]) ? $customer_information["email"] : (isset($data["tbl_customer"]['email']) ? $data["tbl_customer"]['email'] : null));
        $data["tbl_customer"]['password']         = isset($customer_information["password"]) ? $customer_information["password"] : randomPassword();
        $data["tbl_customer"]['shop_id']          = $shop_id;
        $data["tbl_customer"]['customer_contact'] = (isset($customer_information["customer_contact"]) ? $customer_information["customer_contact"] : (isset($data["tbl_customer"]['customer_contact']) ? $data["tbl_customer"]['customer_contact'] : null));;
        $data["tbl_customer"]['country_id']       = 420;
        $data["tbl_customer"]['tin_number']       = (isset($customer_information["tin_number"]) ? $customer_information["tin_number"] : (isset($data["tbl_customer"]['tin_number']) ? $data["tbl_customer"]['tin_number'] : null));
        $data["tbl_customer"]['mlm_username']     = (isset($customer_information["mlm_username"]) ? $customer_information["mlm_username"] : (isset($data["tbl_customer"]['mlm_username']) ? $data["tbl_customer"]['mlm_username'] : null));
        $data["tbl_customer"]['company']          = (isset($customer_information["company"]) ? $customer_information["company"] : (isset($data["tbl_customer"]['company']) ? $data["tbl_customer"]['company'] : null));
        $data["tbl_customer"]['is_corporate']     = (isset($customer_information["is_corporate"]) ? $customer_information["is_corporate"] : (isset($data["tbl_customer"]['is_corporate']) ? $data["tbl_customer"]['is_corporate'] : 0));
        // 
        $data["tbl_customer"]['middle_name']     = (isset($customer_information["middle_name"]) ? $customer_information["middle_name"] : (isset($data["tbl_customer"]['middle_name']) ? $data["tbl_customer"]['middle_name'] : null));
        $data["tbl_customer"]['customer_full_address']     = (isset($customer_information["customer_full_address"]) ? $customer_information["customer_full_address"] : (isset($data["tbl_customer"]['customer_full_address']) ? $data["tbl_customer"]['customer_full_address'] : null));
        $data["tbl_customer"]['b_day']     = (isset($customer_information["b_day"]) ? $customer_information["b_day"] : (isset($data["tbl_customer"]['b_day']) ? $data["tbl_customer"]['b_day'] : null));
        $data["tbl_customer"]['customer_gender']     = (isset($customer_information["customer_gender"]) ? $customer_information["customer_gender"] : (isset($data["tbl_customer"]['customer_gender']) ? $data["tbl_customer"]['customer_gender'] : 'Male'));
        
        $data['load_wallet']['ec_order_load']        = isset($customer_information['load_wallet']['ec_order_load']) == true ? $customer_information['load_wallet']['ec_order_load'] : 0 ;
        $data['load_wallet']['ec_order_load_number'] = isset($customer_information['load_wallet']['ec_order_load_number']) == true ? $customer_information['load_wallet']['ec_order_load_number'] : 0;

        $data['tbl_ec_order']['coupon_id'] = isset($customer_information['coupon_id']) != null ? $customer_information['coupon_id'] : null ;
        
        /* CURRENT LOGGED IN */
        if (isset($customer_information["current_user"])) 
        {
            $current = $customer_information["current_user"];
            $other_info = DB::table("tbl_customer_other_info")->where("customer_id", $current->customer_id)->first();

            /* SET IF LOGGED IN */
            $data["tbl_customer"]['customer_id']      = $current->customer_id;
            $data["tbl_customer"]['first_name']       = $current->first_name;
            $data["tbl_customer"]['last_name']        = $current->last_name;
            $data["tbl_customer"]['middle_name']      = $current->middle_name;
            $data["tbl_customer"]['email']            = $current->email;
            $data["tbl_customer"]['password']         = Crypt::decrypt($current->password);
            $data["tbl_customer"]['shop_id']          = $shop_id;
            $data["tbl_customer"]['customer_contact'] = $data["tbl_customer"]['customer_contact'] ? $data["tbl_customer"]['customer_contact'] : $other_info->customer_mobile;
            $data["tbl_customer"]['country_id']       = 420;
            $data["tbl_customer"]['tin_number']       = $current->tin_number;
            $data["tbl_customer"]['mlm_username']     = $current->mlm_username;
            $data["tbl_customer"]['company']          = $current->company;
            $data["tbl_customer"]['is_corporate']     = $current->is_corporate;
        }

        /* SET MLM SLOT */
        $data["tbl_mlm_slot"]['slot_id'] = Tbl_mlm_slot::max("slot_id") + 1;
        $data["tbl_mlm_slot"]['shop_id'] = $shop_id;
        $data["tbl_mlm_slot"]['slot_owner'] = (isset($customer_information["slot_owner"]) ? $customer_information["slot_owner"] : (isset($data["tbl_mlm_slot"]['slot_owner']) ? $data["tbl_mlm_slot"]['slot_owner'] : null));
        $data["tbl_mlm_slot"]['slot_sponsor'] = (isset($customer_information["slot_sponsor"]) ? $customer_information["slot_sponsor"] : (isset($data["tbl_mlm_slot"]['slot_sponsor']) ? $data["tbl_mlm_slot"]['slot_sponsor'] : null));
        $data["tbl_mlm_slot"]['slot_membership'] = (isset($customer_information["slot_membership"]) ? $customer_information["slot_membership"] : (isset($data["tbl_mlm_slot"]['slot_membership']) ? $data["tbl_mlm_slot"]['slot_membership'] : null));

        /* SET SHIPPINGING INFROMATION */
        $data["tbl_customer_address"]["shipping"]["country_id"] = 420;
        $data["tbl_customer_address"]["shipping"]["customer_state"] = (isset($customer_information["shipping_state"]) ? $customer_information["shipping_state"] : (isset($data["tbl_customer_address"]["shipping"]["customer_state"]) ? $data["tbl_customer_address"]["shipping"]["customer_state"] : null));
        $data["tbl_customer_address"]["shipping"]["customer_city"] = (isset($customer_information["shipping_city"]) ? $customer_information["shipping_city"] : (isset($data["tbl_customer_address"]["shipping"]["customer_city"]) ? $data["tbl_customer_address"]["shipping"]["customer_city"] : null));
        $data["tbl_customer_address"]["shipping"]["customer_zip_code"] = (isset($customer_information["shipping_zip"]) ? $customer_information["shipping_zip"] : (isset($data["tbl_customer_address"]["shipping"]["customer_zip_code"]) ? $data["tbl_customer_address"]["shipping"]["customer_zip_code"] : null));
        $data["tbl_customer_address"]["shipping"]["customer_street"] = (isset($customer_information["shipping_street"]) ? $customer_information["shipping_street"] : (isset($data["tbl_customer_address"]["shipping"]["customer_street"]) ? $data["tbl_customer_address"]["shipping"]["customer_street"] : null));
        $data["tbl_customer_address"]["shipping"]["purpose"] = "shipping";
        $data["tbl_customer_address"]["shipping"]["state_id"] = (isset($customer_information["state_id"]) ? $customer_information["state_id"] : (isset($data["tbl_customer_address"]["shipping"]["state_id"]) ? $data["tbl_customer_address"]["shipping"]["state_id"] : null));
        $data["tbl_customer_address"]["shipping"]["city_id"] = (isset($customer_information["city_id"]) ? $customer_information["city_id"] : (isset($data["tbl_customer_address"]["shipping"]["city_id"]) ? $data["tbl_customer_address"]["shipping"]["city_id"] : null));
        $data["tbl_customer_address"]["shipping"]["barangay_id"] = (isset($customer_information["barangay_id"]) ? $customer_information["barangay_id"] : (isset($data["tbl_customer_address"]["shipping"]["barangay_id"]) ? $data["tbl_customer_address"]["shipping"]["barangay_id"] : null));

        /* SET  BILLING INFORMATION */
        if($data["billing_equals_shipping"] == true)
        {
            $data["tbl_customer_address"]["billing"]["country_id"] = 420;
            $data["tbl_customer_address"]["billing"]["customer_state"] = $data["tbl_customer_address"]["shipping"]["customer_state"];
            $data["tbl_customer_address"]["billing"]["customer_city"] = $data["tbl_customer_address"]["shipping"]["customer_city"];
            $data["tbl_customer_address"]["billing"]["customer_zip_code"] = $data["tbl_customer_address"]["shipping"]["customer_zip_code"];
            $data["tbl_customer_address"]["billing"]["customer_street"] = $data["tbl_customer_address"]["shipping"]["customer_street"];
            $data["tbl_customer_address"]["billing"]["purpose"] = "billing"; 
            $data["tbl_customer_address"]["billing"]["state_id"] = $data["tbl_customer_address"]["shipping"]["state_id"];
            $data["tbl_customer_address"]["billing"]["city_id"] = $data["tbl_customer_address"]["shipping"]["city_id"];
            $data["tbl_customer_address"]["billing"]["barangay_id"] = $data["tbl_customer_address"]["shipping"]["barangay_id"];
        }
        else
        {
            $data["tbl_customer_address"]["billing"]["country_id"] = 420;
            $data["tbl_customer_address"]["billing"]["customer_state"] = (isset($customer_information["billing_state"]) ? $customer_information["billing_state"] : (isset($data["tbl_customer_address"]["billing"]["customer_state"]) ? $data["tbl_customer_address"]["billing"]["customer_state"] : null));
            $data["tbl_customer_address"]["billing"]["customer_city"] = (isset($customer_information["billing_city"]) ? $customer_information["billing_city"] : (isset($data["tbl_customer_address"]["billing"]["customer_city"]) ? $data["tbl_customer_address"]["billing"]["customer_city"] : null));
            $data["tbl_customer_address"]["billing"]["customer_zip_code"] = (isset($customer_information["billing_zip"]) ? $customer_information["billing_zip"] : (isset($data["tbl_customer_address"]["billing"]["customer_zip_code"]) ? $data["tbl_customer_address"]["billing"]["customer_zip_code"] : null));
            $data["tbl_customer_address"]["billing"]["customer_street"] = (isset($customer_information["billing_street"]) ? $customer_information["billing_street"] : (isset($data["tbl_customer_address"]["billing"]["customer_street"]) ? $data["tbl_customer_address"]["billing"]["customer_street"] : null));
            $data["tbl_customer_address"]["billing"]["purpose"] = "billing"; 
            $data["tbl_customer_address"]["billing"]["state_id"] = (isset($customer_information["billing_state_id"]) ? $customer_information["billing_state_id"] : (isset($data["tbl_customer_address"]["billing"]["customer_street"]) ? $data["tbl_customer_address"]["billing"]["customer_street"] : null));
            $data["tbl_customer_address"]["billing"]["city_id"] = (isset($customer_information["billing_city_id"]) ? $customer_information["billing_city_id"] : (isset($data["tbl_customer_address"]["billing"]["customer_street"]) ? $data["tbl_customer_address"]["billing"]["customer_street"] : null));
            $data["tbl_customer_address"]["billing"]["barangay_id"] = (isset($customer_information["billing_barangay_id"]) ? $customer_information["billing_barangay_id"] : (isset($data["tbl_customer_address"]["billing"]["customer_street"]) ? $data["tbl_customer_address"]["billing"]["customer_street"] : null));
        }
        if (isset(Self::get_cart($shop_id)["cart"])) 
        {
            $data = Cart::customer_set_info_ec_order($shop_id, $data, $customer_information);
            
            /* VALIDATIONS */
            $check_account = Cart::customer_set_info_check_account($shop_id, $data["new_account"], $data["tbl_customer"]['email'], $data["tbl_customer"]["password"]);
            
            $check_name = "success";
            $check_address = "success";
            $check_contact = "success";

            /* VALIDATIONS RETURN MESSAGE */
            if((in_array("check_account", $validation)) && $check_account != "success")
            {
                $message["status"] = "error";
                $message["status_message"] = $check_account;
            }
            elseif((in_array("check_name", $validation)) && $check_name != "success")
            {
                $message["status"] = "error";
                $message["status_message"] = $check_account;
            }
            else
            {
                Session::put($unique_id, $data);
                $message["status"]         = "success";
                $message["status_message"] = "Customer Information Successfully Updated"; 
            }
        }
        else
        {
            Session::put($unique_id, $data);
            $message["status"]         = "success";
            $message["status_message"] = "Customer Information Successfully Updated"; 
        }

        return $message;
    }
    public static function get_coupon_discount($coupon_code_id, $total_amount_purchase = 0)
    {
        $total_coupon_discount = 0;
        $check          = Tbl_coupon_code::where("coupon_code_id",$coupon_code_id)->first();
        if($check)
        {
            $coupon_type = $check->coupon_discounted;
            if($check->coupon_discounted == "fixed")
            {
                $total_coupon_discount = $check->coupon_code_amount;
            }
            else if($check->coupon_discounted == "percentage")
            {
                $total_coupon_discount = $total_amount_purchase * ($check->coupon_code_amount/100);
            }             
        }

        return $total_coupon_discount;
    }
    public static function customer_update_method_a($shop_id, $customer_info)
    {
        if(isset($customer_info['method_id']))
        {
            $old_session = $order = Cart::get_info($shop_id);
        }
    }
    public static function customer_set_info_ec_order($shop_id, $data, $customer_information)
    { 
        $data["tbl_ec_order"]["ec_order_id"] = Tbl_ec_order::max("ec_order_id") + 1;

        /* PAYMENT METHOD ID */
        $payment_method_id = (isset($customer_information["method_id"]) ? $customer_information["method_id"] : (isset($data["method_id"]) ? $data["method_id"] : null));



        /* INITIALIZE TOTALS */
        $subtotal = 0;
        $shipping_fee = 0;

        $_cart = Self::get_cart($shop_id)["cart"];
        unset($data["tbl_ec_order_item"]);
        /* ITEM ON CART */
        foreach($_cart as $key => $cart)
        {
            $data["tbl_ec_order_item"][$key]["item_id"]     = $cart["cart_product_information"]["variant_id"];
            $data["tbl_ec_order_item"][$key]["price"]       = $cart["cart_product_information"]["product_price"];
            $data["tbl_ec_order_item"][$key]["quantity"]    = $cart["quantity"];
            $data["tbl_ec_order_item"][$key]["subtotal"]    = $cart["cart_product_information"]["product_price"] * $cart["quantity"];
            $data["tbl_ec_order_item"][$key]["total"]       = $cart["cart_product_information"]["product_price"] * $cart["quantity"];
            $data["tbl_ec_order_item"][$key]["tax"]         = 0;
            $data["tbl_ec_order_item"][$key]["ec_order_id"] = $data["tbl_ec_order"]["ec_order_id"];

            $subtotal += $data["tbl_ec_order_item"][$key]["total"];
        }
       
        /* SUMMARY OF DATA FOR ORDER */
        $data["tbl_ec_order"]["customer_id"] = $data["tbl_customer"]["customer_id"];
        $data["tbl_ec_order"]["customer_email"] = $data["tbl_customer"]["email"];
        $data["tbl_ec_order"]["billing_address"] = $data["tbl_customer_address"]["shipping"]["customer_street"] . ", " . $data["tbl_customer_address"]["shipping"]["customer_zip_code"] . ", " . $data["tbl_customer_address"]["shipping"]["customer_city"] . ", " . $data["tbl_customer_address"]["shipping"]["customer_state"];

        //arcy_coupon
        $data["tbl_ec_order"]["coupon_id"] = (isset($customer_information["coupon_id"]) ? $customer_information["coupon_id"] : (isset($data["tbl_ec_order"]["coupon_id"]) ? $data["tbl_ec_order"]["coupon_id"] : null));

        /* APPLY COUPON DISCOUNT */
        $total_coupon_discount = null;
        $coupon_type = null;
        if($data["tbl_ec_order"]["coupon_id"])
        {
            $coupon_code_id = $data["tbl_ec_order"]["coupon_id"];
            $check          = Tbl_coupon_code::where("coupon_code_id",$coupon_code_id)->where("used",0)->where("blocked",0)->first();
            if($check)
            {
                $coupon_type = $check->coupon_discounted;
                if($check->coupon_discounted == "fixed")
                {
                    $total_coupon_discount = $check->coupon_code_amount;
                }
                else if($check->coupon_discounted == "percentage")
                {
                    $total_coupon_discount = $subtotal * ($check->coupon_code_amount/100);
                }             
            }
        }
        /* CHECK IF TOTAL PRICE IS NEGATIVE */

        $data["tbl_ec_order"]["discount_coupon_amount"] = $total_coupon_discount;
        $data["tbl_ec_order"]["discount_coupon_type"] = $coupon_type;
        $data["tbl_ec_order"]["subtotal"] = $subtotal;
        $data["tbl_ec_order"]["shipping_fee"] = $shipping_fee;

        /* COMPUTE SERVICE FEE */
        if($payment_method_id != null)
        {
            $service_fee = 0;

            $payment_method = Self::get_method_information($shop_id, $payment_method_id);

            if($payment_method->link_discount_percentage != 0)
            {
                $service_fee += ($payment_method->link_discount_percentage / 100) * ($subtotal + $shipping_fee);
            }

            $service_fee += $payment_method->link_discount_fixed;
        }
        else
        {
            $service_fee = 0;
        }

         $total = $subtotal + ($shipping_fee + $service_fee);


        /*  OTHER INFO WITH SERVICE FEE */
        $data['tbl_ec_order']['ec_order_load'] = intval(isset($data['load_wallet']['ec_order_load']) == true ? $data['load_wallet']['ec_order_load'] : 0);
        $data['tbl_ec_order']['ec_order_load_number'] = isset($data['load_wallet']['ec_order_load_number']) == true ? $data['load_wallet']['ec_order_load_number'] : 0 ;
        $data["tbl_ec_order"]["service_fee"] = $service_fee;
        $data["tbl_ec_order"]["total"] = $total;
        $data["tbl_ec_order"]["invoice_date"] =  Carbon::now();
        $data["tbl_ec_order"]["due_date"] =  Carbon::now();
        $data["tbl_ec_order"]["coupon_id"] = (isset($customer_information["coupon_id"]) ? $customer_information["coupon_id"] : (isset($data["tbl_ec_order"]["coupon_id"]) ? $data["tbl_ec_order"]["coupon_id"] : null));
        $data["tbl_ec_order"]["shop_id"] = $shop_id;
        $data["tbl_ec_order"]["created_date"] = Carbon::now();
        $data["tbl_ec_order"]["payment_method_id"] = $payment_method_id;
        $data["tbl_ec_order"]["shipping_group"] = null;
        $data["tbl_ec_order"]["order_status"] = "Pending";
        $data["tbl_ec_order"]["payment_status"] = 0;

        $data["applied_coupon_id"] = (isset($customer_information["coupon_id"]) ? $customer_information["coupon_id"] : (isset($data["tbl_ec_order"]["coupon_id"]) ? $data["tbl_ec_order"]["coupon_id"] : null));    
        return $data;
    }
    public static function get_method_information($shop_id, $payment_method_id)
    {
        return Tbl_online_pymnt_method::leftJoin('tbl_online_pymnt_link', 'tbl_online_pymnt_link.link_method_id', '=', 'tbl_online_pymnt_method.method_id')
                                                                      ->leftJoin('tbl_online_pymnt_other', 'tbl_online_pymnt_link.link_reference_id', '=', 'tbl_online_pymnt_other.other_id')
                                                                      ->leftJoin('tbl_image', 'tbl_online_pymnt_link.link_img_id', '=', 'tbl_image.image_id')
                                                                      ->where("tbl_online_pymnt_link.link_shop_id", $shop_id)
                                                                      ->where("tbl_online_pymnt_link.link_is_enabled", 1)
                                                                      ->where("tbl_online_pymnt_method.method_id", $payment_method_id)
                                                                      ->first();
    }
    public static function customer_set_info_check_account($shop_id, $new_account, $email, $password)
    {
        if($new_account == true) //NEW ACCOUNT VALIDATION
        {
            $check_exist = Tbl_customer::where("shop_id", $shop_id)->where("email", $email)->first();

            if($check_exist)
            {
                return "You cannot use <b>" . $email . "</b> because this belongs to one of the existing users. If you are the owner of this account then continue with password.";
            }
            else
            {
                return "success";
            }
        }
        else //ACCOUNT EXIST VALIDATION
        {
            $check_exist = Tbl_customer::where("shop_id", $shop_id)->where("email", $email)->first();
            
            if(!$check_exist)
            {
                return "The e-mail and password you entered doesn't belong to any account.";
            }
            elseif(Crypt::decrypt($check_exist["password"]) != $password)
            {
                return "The e-mail and password you entered doesn't belong to any account.";
            }
            else
            {
                $slot_session = Mlm_member::get_session_slot();
                if($slot_session)
                {
                    Mlm_member::add_to_session_edit($shop_id, $check_exist->customer_id, $slot_session->slot_id);
                }
                else
                {
                    Mlm_member::add_to_session($shop_id, $check_exist->customer_id);
                } 
                

                return "success";
            }
        }
    }

    /*
     * TITLE: SUBMIT ORDER
     * 
     * Allows us to set information for customer that will be processed later on
     *
     * @param
     *    $shop_id (int) - Current Shop ID (for validation)
     *    $payment_status (int) - 0 = not paid, 1 = paid
     *    $order_status (str) - Pending, Failed, Processing, Shipped, Completed, On-Hold, Cancelled
     *    $customer_id (int) - current logged in
     *    $notification (int) - 0 = no notif, 1 = yes notif
     *
     * @return (array)
     *    - order_id
     *
     * @author (Edward Guevarra)
     *
     */
    public static function submit_order($shop_id, $payment_status, $order_status, $customer_id = null, $notification = 1, $order = null)
    {
        if (!$order) 
        {
            $order = Cart::get_info($shop_id);
        }
        
        $order["tbl_ec_order"]["payment_status"] = $payment_status;
        $order["tbl_ec_order"]["order_status"]   = $order_status;
        $order["customer_id"]                    = $customer_id;
        $order["notification"]                   = $notification;
        return Ec_order::create_ec_order_from_cart($order);   
    }
    public static function process_payment($shop_id, $from = "checkout")
    {
        ini_set('xdebug.max_nesting_level', 200);
        
        $data = Cart::get_info($shop_id);
        if (isset($data["tbl_ec_order"]["payment_method_id"])) 
        {
            $method_id = $data["tbl_ec_order"]["payment_method_id"];
            $method_information = Self::get_method_information($shop_id, $method_id);
            if ( isset($method_id) && isset($method_information) )
            {
                switch ($method_information->link_reference_name)
                {
                    case 'paypal2': dd("UNDER DEVELOPMENT"); break;
                    case 'paymaya': Cart::submit_using_paymaya($data, $shop_id, $method_information, $from); break;
                    case 'paynamics': dd("UNDER DEVELOPMENT"); break;
                    case 'dragonpay': return Cart::submit_using_dragonpay($data, $shop_id, $method_information, $from); break;
                    case 'ipay88': return Cart::submit_using_ipay88($data, $shop_id, $method_information); break;
                    case 'other': return Cart::submit_using_proof_of_payment($shop_id, $method_information);  break;
                    case 'e_wallet': return Cart::submit_using_ewallet($data, $shop_id); break;
                    case 'cashondelivery': return Cart::submit_using_cash_on_delivery($shop_id, $method_information); break;
                    default: dd("UNDER DEVELOPMENT"); break;
                }
            }
            else
            {
                return Redirect::back()->with("error", "Please choose payment method.")->send();
            }
        }
        else
        {
            dd('An error occurred. Please try again later.');
        }
    }
    public static function submit_using_paymaya($data, $shop_id, $method_information, $from)
    {
        echo "Please do not refresh the page and wait while we are processing your payment. This can take a few minutes.";
        $api = Tbl_online_pymnt_api::where('api_shop_id', $shop_id)->join("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_id", "=", "tbl_online_pymnt_api.api_gateway_id")->where("gateway_code_name", "paymaya")->first();

        PayMayaSDK::getInstance()->initCheckout($api->api_client_id, $api->api_secret_id, "PRODUCTION");
        // PayMayaSDK::getInstance()->initCheckout($api->api_client_id, $api->api_secret_id, "SANDBOX");
        
        // Checkout
        $itemCheckout = new Checkout();
        $user = new User();
        $itemCheckout->buyer = $user->buyerInfo();

        $totalAmount = new ItemAmount();
        $total = 0;
        foreach (array_values($data["cart"]) as $key => $value) 
        {
            $product = Tbl_ec_variant::where("evariant_id", $value["product_id"])->first();
            $product_item = Tbl_item::where("item_id", $product->evariant_item_id)->first();
            
            // Item
            $itemAmountDetails = new ItemAmountDetails();
            $itemAmountDetails->shippingFee = "0.00";
            $itemAmountDetails->tax = "0.00";
            $itemAmountDetails->subtotal = "0.00";

            $itemAmount = new ItemAmount();
            $itemAmount->currency = "PHP";
            $itemAmount->value = (string)number_format($product->evariant_price, 2, '.', '');
            $itemAmount->details = $itemAmountDetails;

            $itemTotalAmount = new ItemAmount();
            $itemTotalAmount->currency = "PHP";
            $itemTotalAmount->value = (string)number_format($product->evariant_price * $value["quantity"], 2, '.', '');
            $itemTotalAmount->details = $itemAmountDetails;

            $totalAmount->currency = "PHP";
            $totalAmount->value = 0;
            $totalAmount->details = $itemAmountDetails;
            $total += $product->evariant_price * $value["quantity"];

            $item[$key] = new Item();
            $item[$key]->name = $product->evariant_item_label;
            $item[$key]->code = $product_item->item_sku;
            $item[$key]->description = $product->item_sales_information ? $product->item_sales_information : "Product #" . $product->evariant_id;
            $item[$key]->quantity = (string)$value["quantity"];
            $item[$key]->amount = $itemAmount;
            $item[$key]->totalAmount = $itemTotalAmount;
        }
   
        $payment_status = 0;
        $order_status   = "Pending";
        $customer       = Cart::get_customer();

        $order_id = Cart::submit_order($shop_id, $payment_status, $order_status, isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null, 0);
        Cart::clear_all($shop_id);

        $totalAmount->value = number_format($total, 2, '.', '');

        $itemCheckout->items = $item;
        $itemCheckout->totalAmount = $totalAmount;
        $itemCheckout->requestReferenceNumber = $shop_id . time();

        $shop = DB::table('tbl_shop')->where('shop_id', $shop_id)->first();
        $link = '/payment/paymaya/success?notify=0&';
        if($shop)
        {
            if($shop->shop_key == 'myphone')
            {
                $link = '/mlm/login?notify=1&';
            }
        }

        $itemCheckout->redirectUrl = array(
            "success" =>  URL::to("/payment/paymaya/success?order_id=" . Crypt::encrypt($order_id) . "&from=" . $from),
            "failure" => URL::to("/payment/paymaya/failure?order_id=" . Crypt::encrypt($order_id)),
            "cancel" => URL::to("/payment/paymaya/cancel?order_id=" . Crypt::encrypt($order_id))
        );

        $itemCheckout->execute();

        // echo $itemCheckout->id; // Checkout ID
        // echo $itemCheckout->url; // Checkout URL
        $logs_insert["checkout_id"] = $itemCheckout->id;
        $logs_insert["log_date"]    = Carbon::now();
        DB::table("tbl_paymaya_logs")->insert($logs_insert);
        
        return Redirect::to($itemCheckout->url)->send();
    }
    public static function submit_using_dragonpay($data, $shop_id, $method_information, $from)
    {
        $gateway = DB::table("tbl_online_pymnt_gateway")->where("tbl_online_pymnt_api.api_shop_id", $shop_id)
                                                        ->where("tbl_online_pymnt_gateway.gateway_code_name", $method_information->link_reference_name)
                                                        ->join("tbl_online_pymnt_api", "tbl_online_pymnt_api.api_gateway_id" , "=", "tbl_online_pymnt_gateway.gateway_id")
                                                        ->first();
        if ($gateway) 
        {
            foreach ($data['tbl_ec_order_item'] as $key => $value) 
            {
                if ($key != count($data["cart"])) 
                {
                    $product_summary = "Product #" . $value["item_id"] . " (x" . $value["quantity"] . ") - " . currency("PHP", $value["price"]) . "";
                }
                else
                {
                    $product_summary = "Product #" . $value["item_id"] . " (x" . $value["quantity"] . ") - " . currency("PHP", $value["price"]) . ", ";
                }
            }

            $merchant_id  = $gateway->api_client_id;
            $merchant_key = $gateway->api_secret_id;

            $requestpayment    = new Dragon_RequestPayment($merchant_key);
            $request["txnid"]  = $shop_id . time();
            $request["amount"] = $data["tbl_ec_order"]["total"];
            $request["ccy"]    = "PHP";
            $request["description"] = $product_summary;
            $request["email"] = $data["tbl_ec_order"]["customer_email"];

            $payment_status = 0;
            $order_status   = "Pending";
            $customer       = Cart::get_customer();

            $order_id = Cart::submit_order($shop_id, $payment_status, $order_status, isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null, 0);
            Cart::clear_all($shop_id);
            
            $dragon_request = array(
                'merchantid'    => $requestpayment->setMerchantId($merchant_id),
                'txnid'         => $requestpayment->setTxnId($request['txnid']),
                'amount'        => $requestpayment->setAmount($request['amount']),
                'ccy'           => $requestpayment->setCcy($request['ccy']),
                'description'   => $requestpayment->setDescription($request['description']),
                'email'         => $requestpayment->setEmail($request['email']),
                'digest'        => $requestpayment->getdigest(),
                'param1'        => $from,
                'param2'        => $order_id
            );

            Dragon_RequestPayment::make($merchant_key, $dragon_request); 
        }  
        else
        {
            dd("Some error occurred. Please contact the administrator.");
        }
    }
    public static function submit_using_ipay88($data, $shop_id, $method_information)
    {
        echo "Please do not refresh the page and wait while we are processing your payment. This can take a few minutes.";
        $api = Tbl_online_pymnt_api::where('api_shop_id', $shop_id)->join("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_id", "=", "tbl_online_pymnt_api.api_gateway_id")->where("gateway_code_name", "ipay88")->first();
        $shop = DB::table("tbl_shop")->where("shop_id", $shop_id)->first();
        $customer = Cart::get_customer();

        /* DELIMETER */
        switch ($method_information->link_delimeter) 
        {  
            /* Credit Card */
            case 1: $data["paymentId"] = 1; break;
            /* Bancnet */
            case 5: $data["paymentId"] = 5; break;
            /* Default (Credit Card) */
            default: $data["paymentId"] = $method_information->link_delimeter; break;
        }

        if (isset($data["tbl_customer"]["customer_id"]) && $data["tbl_customer"]["customer_id"]) 
        {
            $data["refNo"] = $shop_id . time() . $data["tbl_customer"]["customer_id"];
        }
        else
        {
            $data["refNo"] = $shop_id . time();
        }
        
        $data["amount"] = $data["tbl_ec_order"]["total"] - Cart::get_coupon_discount($data["tbl_ec_order"]["coupon_id"], $data["tbl_ec_order"]["total"]);

        /* REASTRUCTURE */
        $product_summary = array();
        foreach ($data['tbl_ec_order_item'] as $key => $value) 
        {
            if ($key != count($data["cart"])) 
            {
                $product_summary = "Product " . $value["item_id"];
            }
            else
            {
                $product_summary = "Product " . $value["item_id"] . " ";
            }
        }

        $data["currency"] = "PHP";
        $data["prodDesc"] = $product_summary;
        $data["userName"] = $data["tbl_customer"]["first_name"] . " " . $data["tbl_customer"]["first_name"] . "  " . $data["tbl_customer"]["last_name"];
        $data["userEmail"] = $data["tbl_ec_order"]["customer_email"];
        $data["userContact"] = $data["tbl_customer"]["customer_contact"];
        $data["remark"] = "Checkout from " . trim(ucwords($shop->shop_key));
        $data["lang"] = "UTF-8";
        $data["responseUrl"] = URL::to('/payment/ipay88/response');
        $data["backendUrl"] = URL::to('/payment/ipay88/backend');
        $data["merchantKey"] = $api->api_secret_id;
        $data["merchantCode"] = $api->api_client_id;
        $requestpayment = new RequestPayment($data["merchantKey"]);

        $ipay88request = array(
            'merchantCode'  => $requestpayment->setMerchantCode($data["merchantCode"]),
            'paymentId'     => $requestpayment->setPaymentId($data["paymentId"]),
            'refNo'         => $requestpayment->setRefNo($data["refNo"]),
            'amount'        => $requestpayment->setAmount($data["amount"]),
            'currency'      => $requestpayment->setCurrency($data["currency"]),
            'prodDesc'      => $requestpayment->setProdDesc($data["prodDesc"]),
            'userName'      => $requestpayment->setUserName($data["userName"]),
            'userEmail'     => $requestpayment->setUserEmail($data["userEmail"]),
            'userContact'   => $requestpayment->setUserContact($data["userContact"]),
            'remark'        => $requestpayment->setRemark($data["remark"]),
            'lang'          => $requestpayment->setLang($data["lang"]),
            'signature'     => $requestpayment->getSignature(),
            'responseUrl'   => $requestpayment->setResponseUrl($data["responseUrl"]),
            'backendUrl'    => $requestpayment->setBackendUrl($data["backendUrl"])
        );
        
        $temp["reference_number"] = $data["refNo"];
        $temp["shop_id"] = $shop_id;
        $temp["customer_id"] = isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null;
        $temp["date_created"] = Carbon::now();
        $temp["cart"] = serialize(Cart::get_info($shop_id));
  
        DB::table("tbl_ipay88_temp")->insert($temp);

        Cart::clear_all($shop_id);
        
        RequestPayment::make($data["merchantKey"], $ipay88request);  
    }
    public static function submit_using_proof_of_payment($shop_id, $method_information)
    {
        $payment_status = 0;
        $order_status   = "Pending";
        $customer       = Cart::get_customer();

        $order_id = Cart::submit_order($shop_id, $payment_status, $order_status, isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null);
        Cart::clear_all($shop_id);

        $tbl_order = DB::table("tbl_ec_order")->where("tbl_ec_order.ec_order_id", $order_id)->leftJoin("tbl_customer", "tbl_customer.customer_id", "=", "tbl_ec_order.customer_id")->first();
      
        $data["template"] = Tbl_email_template::where("shop_id", $shop_id)->first();
        $data['mail_to'] = $tbl_order->customer_email;
        $data['mail_username'] = Config::get('mail.username');
        $data['mail_subject'] = "Verify Payment";
        $data['payment_detail'] = $method_information->other_description;
        $data['customer_full_name'] = $tbl_order->first_name . " " . $tbl_order->middle_name . " " . $tbl_order->last_name;
        $data['order_id'] = Crypt::encrypt($tbl_order->ec_order_id);

        $result = Mail_global::payment_mail($data, $shop_id);
        
        return Redirect::to("/email_payment?email=" . $tbl_order->customer_email)->send();
    }
    public static function submit_using_ewallet($cart, $shop_id)
    {
        $sum = $cart["tbl_ec_order"]['total'];
        $result['order_id'] = $cart["tbl_ec_order"]['ec_order_id'];
        $get_cart = Cart::get_cart($shop_id);
        $slot_session = Mlm_member::get_session_slot();
        if($slot_session != null)
        {
            $check_wallet = Mlm_slot_log::get_sum_wallet($slot_session->slot_id);
            $payment_status = 0;
            $order_status   = "Pending";
            $customer       = Cart::get_customer();

            
            if($check_wallet >= $sum )
            {
                $order_id = Cart::submit_order($shop_id, $payment_status, $order_status, isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null);
                // return $check_wallet;
                $log = 'Thank you for purchasing. ' .$sum. ' is deducted to your wallet';
                $arry_log['wallet_log_slot'] = $slot_session->slot_id;
                $arry_log['shop_id'] = $slot_session->shop_id;
                $arry_log['wallet_log_slot_sponsor'] = $slot_session->slot_id;
                $arry_log['wallet_log_details'] = $log;
                $arry_log['wallet_log_amount'] = $sum * (-1);
                $arry_log['wallet_log_plan'] = "REPURCHASE";
                $arry_log['wallet_log_status'] = "released";   
                $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
                
                Mlm_slot_log::slot_array($arry_log);
                $update['ec_order_id'] = $order_id;
                $update['order_status'] = "Processing";
                $update['payment_status'] = 1;
                $order = Ec_order::update_ec_order($update);
            }
            else
            {
                $send['errors'][0] = "Your wallet only have, " . number_format($check_wallet) . ' where the needed amount is ' . number_format($sum) ;
                return Redirect::back()
                    ->withErrors($send)
                    ->withInput()
                    ->send();
            }
        }
        else
        {
            $send['errors'][0] = "Only members with slot can use the wallet option.";
            return Redirect::back()->withErrors($send)->withInput()->send();
        }
     
        Cart::clear_all($shop_id);
        $result['status'] = 'success';
        return Redirect::to('/order_placed?order=' . Crypt::encrypt(serialize($result)))->send();
    }
    public static function submit_using_cash_on_delivery($shop_id, $method_information)
    {
        $payment_status = 0;
        $order_status   = "Pending";
        $customer       = Cart::get_customer();

        $order_id = Cart::submit_order($shop_id, $payment_status, $order_status, isset($customer['customer_info']->customer_id) ? $customer['customer_info']->customer_id : null);
        Cart::clear_all($shop_id);

        $tbl_order = DB::table("tbl_ec_order")->where("tbl_ec_order.ec_order_id", $order_id)->leftJoin("tbl_customer", "tbl_customer.customer_id", "=", "tbl_ec_order.customer_id")->first();
        
        $data["template"] = Tbl_email_template::where("shop_id", $shop_id)->first();
        $data['mail_to'] = $tbl_order->customer_email;
        $data['mail_username'] = Config::get('mail.username');
        $data['mail_subject'] = "Verify Payment";
        $data['payment_detail'] = $method_information->other_description;
        $data['customer_full_name'] = $tbl_order->first_name . " " . $tbl_order->middle_name . " " . $tbl_order->last_name;
        $data['order_id'] = Crypt::encrypt($tbl_order->ec_order_id);
        $data['password'] = Crypt::decrypt($tbl_order->password);
        //email for COD
        $result = Mail_global::create_email_content($data, $shop_id, "cash_on_delivery");
        
        if($result == 0)
        {    
            // $result = Mail_global::mail($data, $shop_id, "cod");
        }

        return Redirect::to('/order_placed?order=' . Crypt::encrypt(serialize($order_id)) . '&popup=1')->send();
    }
    public static function get_customer()
    {
        if(Session::get('mlm_member') != null)
        {
            return Session::get('mlm_member');
        }
        else
        {
            return null;
        }
    }
    public static function get_userip()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }
}