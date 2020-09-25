<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tbl_product;
use App\Models\View_variant_option;
use App\Models\View_product_variant;
use App\Models\Tbl_user;
use App\Models\Tbl_shop;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_country;
use App\Models\Tbl_category;
use App\Models\Tbl_order;
use App\Models\Tbl_variant;
use App\Models\Tbl_variants;
use App\Models\Tbl_contact;
use App\Models\Tbl_order_item; 
use App\Models\Tbl_location;
use App\Models\Tbl_about_us;
use App\Models\Tbl_image;
use App\Models\Tbl_shipping;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_attachment;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_ecommercer_remittance;
use App\Models\Tbl_ecommerce_banking;
use App\Models\Tbl_ecommerce_setting;
use App\Models\Tbl_ecommerce_paypal;
use Illuminate\Http\Request as Request2;
use App;
use Crypt;
use Carbon\Carbon;
use Request;
use Response;
use Session;
use Validator;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function post(Request $request)
    {
        $value = '';
        return json_encode($value);
    }

    public function products($auth, $store)
    {
        $data = Tbl_product::geninfo()->where("shop_key", $store)->get();
        return json_encode($data);
    }
    
    public function filter_products($auth, $store, $column, $value)
    {
        $data = Tbl_product::geninfo()->where("shop_key", $store)->where($column,"like",'%'.$value.'%')->get();
        return json_encode($data);
    }


    public function product_id($auth, $store, $product_id)
    {
        $data                   = Tbl_variant::variant_info()->where("product_id", $product_id)->first();
        $data->variant_name     = View_variant_option::where("product_id",$data->product_id)->get();
        $data->product_image    = Tbl_image::where("image_reason","product")->where("image_reason_id",$product_id)->get();
        
        return json_encode($data);
    }

    public function variants($auth, $store)
    {
        $data = Tbl_variant::variant_info()->variant_view()->get();
        return json_encode($data);
    }

    // CHECK IF THE VARIANTS EXIST
    public function products_id_variants($auth, $store, $product_id)
    {
        $data = View_product_variant::where("product_id", $product_id)->get();
        return json_encode($data);
    }

    public function category($auth, $store)
    {
        $data = Tbl_category::shop($store)->get();
        return json_encode($data);
    }

    public function products_id_related($auth, $store, $id)
    {
        $product    = Tbl_product::where("product_id",$id)->first();
        $_related   = Tbl_product::geninfo()->where("product_id","<>",$id)->where("product_type",$product->product_type)->where("shop_key", $store)->take(4)->get();

        if(count($_related) < 4)
        {
            $limit      = 4 - count($_related);
            $add        = Tbl_product::geninfo()->where("product_type","<>",$product->product_type)->where("product_vendor",$product->product_vendor)->where("shop_key", $store)->take($limit)->get();
            $_related   = $_related->merge($add);
        }
        return json_encode($_related);
    }
    
    public function user_update_id($auth, $store, $id)
    {
        $update["first_name"]   = Request::input("first_name");
        $update["last_name"]    = Request::input("last_name");
        $update["email"]        = Request::input("email");
        $update["b_day"]        = Request::input("b_day");
        $update["country_id"]   = Request::input("country_id");
        
        $updateother["customer_phone"]        = Request::input("phone");
        
        $updateaddress["country_id"]   = Request::input("country_id");
        $updateaddress["customer_street"]     = Request::input("_address");
        $updateaddress["customer_city"]         = Request::input("city");
        $updateaddress["customer_state"]     = Request::input("province");
        $updateaddress["customer_zipcode"]     = Request::input("zip_code");

        if(Request::input("password") != '')
        {
            $update["password"]     = Crypt::encrypt(Request::input("password"));
        }
        
        Tbl_customer::where("customer_id", $id)->update($update);
        Tbl_customer_address::where('customer_id',$id)->update($updateaddress);
        Tbl_customer_other_info::where('customer_id',$id)->update($updateother);
        $data["status"] = 'success';
        
        return json_encode('success');
    }

    public function create_user($auth, $store)
    {
        $shop_id = Tbl_shop::where('shop_key',$store)->value('shop_id');
        
        $insert['first_name']       = Request::input('first_name');
        $insert['last_name']        = Request::input('last_name');
        $insert['email']            = Request::input('email');
        $insert['b_day']            = Request::input('b_day');
        
        $insert['password']         = Request::input('pasword');
        $insert['country_id']       = Request::input('country');
        
        $insert['shop_id']          = $shop_id;
        $insert['created_date']     = Carbon::now();
        
        $insertaddress['customer_street']   = Request::input('address');
        $insertaddress['customer_state']    = Request::input('province');
        $insertaddress['customer_city']     = Request::input('city');
        $insertaddress['customer_zipcode']  = Request::input('zip_code');
        $insertaddress['country_id']        = Request::input('country');
        $insertaddress['country_id']        = Request::input('country');
        $insertaddress['purpose']           = 'billing';
        
        $insertother['customer_phone']      = Request::input('number');
        
        
        $insertSearch['body'] = $insert['first_name'].' '.$insert['last_name'].' '.$insert['email'];
        $IsWalkin = 0;

        if(Request::input('IsWalkin'))
        {
             $insert['IsWalkin']    = Request::input('IsWalkin');
             $IsWalkin = Request::input('IsWalkin');
        }
        $insert['IsWalkin'] = $IsWalkin;
		
		if($IsWalkin == 0){
		    
		    $CheckEmail = Tbl_customer::where('shop_id',$shop_id)->where('email',$insert['email'])->count();
		    if($CheckEmail == 1){
		        $json['data']    = null;
		        $json['error'] = 'The email has already been taken.';
		        return json_encode($json);
		    }
		  //  $rules['email']		        = "unique:tbl_customer,email";	
		    
		  //  $validate = Validator::make($insert, $rules);
		  //  if($validate->fails())
    // 		{
    // 		    $json['data']    = null;
    // 		    $json['error']   = $validate->errors()->first();
    		    
    // 		    return json_encode($json);
    // 		}
		}
		$password = 0;
		if(Request::has('pasword')){
		    $password = Request::input('pasword');
		}
		$insert['password']     = Crypt::encrypt($password);
		
        $json['data'] = Tbl_customer::insertGetId($insert);
        
        $insertaddress['customer_id'] = $json['data'];
        $insertother['customer_id'] = $json['data'];
        Tbl_customer_address::insert($insertaddress);
        Tbl_customer_other_info::insert($insertother);
        $insertSearch['customer_id'] = $json['data'];
        $insertSearch['created_at'] = Carbon::now();
        Tbl_customer_search::insert($insertSearch);
        
        return json_encode($json);
    }

    public function login_user($auth, $store){
        $username = Request::input('username');
        $password = Request::input('password');
        
        $shop = Tbl_shop::where('shop_key',$store)->first();
        // dd($url);
        $user = Tbl_customer::where('shop_id',$shop->shop_id)->where('email',$username)->where('IsWalkin',0)->where('archived',0)->first();
        $data = array();
        $data['count'] = $user->count();
        $data['password'] = $password;
        if($password != Crypt::decrypt($user->password)){
            $data['data'] = 'Wrong username/password';
        }
        else{
            $data['data'] = $user->customer_id;
        }
        return $data;
    }

    public function user_info($auth, $store){
        $user_id = Request::input('user_id');
        $user = Tbl_customer::where('tbl_customer.customer_id',$user_id)->join('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')->first();
        $address = Tbl_customer_address::where('customer_id',$user_id)->join('tbl_country','tbl_country.country_id','=','tbl_customer_address.country_id')->first();
        $other = Tbl_customer_other_info::where('customer_id',$user_id)->first();
        
        $data['customer_id'] = $user->customer_id;
        $data['firstname'] = $user->first_name;
        $data['lastname'] = $user->last_name;
        $data['user_email'] = $user->email;
        $data['phone'] = $other->customer_phone;
        $data['user_shop'] = $user->shop_id;
        $data['country_id'] = $address->country_id;
        $data['country_name'] = $address->country_name;
        $data['country_code'] = $address->country_code;
        $data['province'] = $address->customer_state;
        $data['zip_code'] = $address->customer_zipcode;
        $data['city'] = $address->customer_city;
        $data['_address'] = $address->customer_street;
        $data['profile'] = $user->profile;
        $data['b_day'] = $user->b_day;
        $data['accept_marketing'] = $user->accept_marketing;
        $data['taxt_exempt'] = $user->taxt_exempt;
        $data['company'] = $user->company;
        $data['_address_cont'] = $user->_address_cont;
        $data['created_date'] = $user->created_date;
        
        return $data;
    }

    public  function country($auth, $store)
    {
        $data = Tbl_country::orderBy('country_name','asc')->get();
        return json_encode($data);
    }

    public function payment($auth, $store){
        
        $shop_id = $this->GetShopId($store);
        $_cart = Request::input('cart');
        $shippingmethod = Request::input('shippingmethod');
        $shipping_fee = Request::input('shipping_fee');
        $paymentmethod = Request::input('paymentmethod');
        $file_proof = Request::input('file_proof');
        $path = Request::input('path');
        

        $object = (object) $file_proof;
        
        
        $user_id = Request::input('user_id');
        $insert['customer_id'] = $user_id;
        $insert['shipping_name'] = $shippingmethod;
        $insert['shipping_amount'] = $shipping_fee;
        $insert['payment_stat'] = 'Pending';
        $insert['discount_var'] = 'amount';
        $insert['IsfreeShipping'] = 1;
        $insert['craeted_date'] = Carbon::now();
        $insert['status'] = 'ready';
        $insert['shop_id'] = $shop_id;
        $insert['fulfillment_status'] = 'Pending';
        $insert['proof_of_payment'] = $path;
        
        
        $id = Tbl_order::insertGetId($insert);
        foreach($_cart as $key => $cart){
            $variant = Tbl_variant::where('variant_id',$cart['variant_id'])->first();
            $insertItem['tbl_order_id'] = $id;
            $insertItem['variant_id'] = $cart['variant_id'];
            $insertItem['item_amount'] = $variant->variant_price;
            $insertItem['quantity'] = $cart['quantity'];
            $insertItem['discount_var'] = 'amount';
            $insertItem['discount'] = 0;
            $quantity = $variant->variant_inventory_count - $cart['quantity'];
            $update['variant_inventory_count'] = $quantity;
            Tbl_variant::where('variant_id', $variant->variant_id)->update($update);
            Tbl_order_item::insert($insertItem);
        }  

        // SEND EMAIL NOTIFICATION

        $toEmail = "edwardguevarra2003@gmail.com";
        $toName = "My 168 Shop";
        $fromName = "System";
        $subject = "Checkout Notification";

        $data["data"] = "Watashi no Kinimarimasu";

        Mail::send('emails.checkout_notification', $data, function($message) use ($toEmail, $toName, $fromName, $subject)
        {
            $message->to($toEmail, $toName);
            $message->from(env('MAIL_USERNAME'), $fromName);
            $message->subject($subject);
        });        

        $return['status'] = 'success';



        return $return;
        
    }
    public function GetShopId($stores = ''){
        $data = Tbl_shop::where('shop_key',$stores)->first();
        return $data->shop_id;
    }
   
    public function getShoppingHistory($auth, $store){
        $customer_id = Request::input('user_id');
        $_order = Tbl_order::join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_name')->where('tbl_order.customer_id',$customer_id)->where('tbl_order.archived',0)->orderBy('craeted_date','desc')->get();
        $data = array();
        foreach($_order as $key => $order){
            
            $data[$key]['tbl_order_id'] = $order->tbl_order_id;
            $data[$key]['customer_id'] = $order->customer_id;
            $data[$key]['discount'] = $order->discount;
            $data[$key]['discount_var'] = $order->discount_var;
            $data[$key]['discount_reason'] = $order->discount_reason;
            $data[$key]['IsfreeShipping'] = $order->IsfreeShipping;
            $data[$key]['shipping_name'] = $order->shipping_name;
            $data[$key]['shipping_amount'] = $order->shipping_amount;
            $data[$key]['isTaxExempt'] = $order->isTaxExempt;
            $data[$key]['hasTax'] = $order->hasTax;
            $data[$key]['tax_percentage'] = $order->tax_percentage;
            $data[$key]['notes'] = $order->notes;
            $data[$key]['payment_stat'] = $order->payment_stat;
            $data[$key]['payment_method'] = $order->payment_method;
            $data[$key]['IsReserve'] = $order->IsReserve;
            $data[$key]['reserve_date'] = $order->reserve_date;
            $data[$key]['craeted_date'] = $order->craeted_date;
            $data[$key]['status'] = $order->status;
            $data[$key]['shop_id'] = $order->shop_id;
            $data[$key]['archived'] = $order->archived;
            $data[$key]['fulfillment_status'] = $order->fulfillment_status;
            $data[$key]['proof_of_payment'] = $order->proof_of_payment;
            $data[$key]['date_approve_order'] = $order->date_approve_order;

            $_item = Tbl_order_item::Sel($order->tbl_order_id)->get();
            $total_quantity = 0;
            $total_amount = 0;
            $total_discount = 0;
            $total_tax = 0;

            foreach($_item as $k => $item){
                // dd($item);
                $data[$key]['order'][$k]['tbl_order_item_id'] = $item->tbl_order_item_id;
                $data[$key]['order'][$k]['variant_id'] = $item->variant_id;
                $data[$key]['order'][$k]['item_amount'] = $item->item_amount;
                $data[$key]['order'][$k]['quantity'] = $item->quantity;
                $data[$key]['order'][$k]['discount'] = $item->discount;
                $data[$key]['order'][$k]['discount'] = $item->discount_var;
                $data[$key]['order'][$k]['discount_reason'] = $item->discount_reason;
                $data[$key]['order'][$k]['variant_sku'] = $item->variant_sku;
                $data[$key]['order'][$k]['image_path'] = $item->image_path;
                $data[$key]['order'][$k]['IsfreeShipping'] = $item->IsfreeShipping;
                $data[$key]['order'][$k]['shipping_name'] = $item->shipping_name;
                $data[$key]['order'][$k]['shipping_amount'] = $item->shipping_amount;
                $data[$key]['order'][$k]['shipping_amount'] = $item->shipping_amount;
                $data[$key]['order'][$k]['hasTax'] = $item->hasTax;
                $data[$key]['order'][$k]['tax_percentage'] = $item->tax_percentage;
                $data[$key]['order'][$k]['payment_stat'] = $item->payment_stat;
                $data[$key]['order'][$k]['payment_method'] = $item->payment_method;
                $data[$key]['order'][$k]['proof_of_payment'] = $item->proof_of_payment;
                $data[$key]['order'][$k]['product_date_created'] = $item->product_date_created;
                $total_quantity += $item->quantity;
                $tempAmount = $item->quantity * $item->item_amount;
                $tempDiscount = 0;
                if($item->discount_var == 'amount'){
                    $tempDiscount = $item->discount;
                }   
                else{
                    $tempDiscount = ($item->discount / 100) * $tempAmount;
                }
                $total_amount = $tempAmount - $tempDiscount;
            }
            if($order->discount_var == 'amount'){
                $total_discount = $order->discount;
            }
            else{
                $total_discount = ($order->discount / 100) * $total_amount;
            }
            $tempTotal = $total_amount - $total_discount;
            if($order->isTaxExempt == 1){
                $total_tax = 0;
            }
            else{
                if($order->hasTax == 1){
                    $total_tax = ($order->tax_percentage / 100) * $tempTotal;
                }
                else{
                    $total_tax = 0;
                }
            }
            $data[$key]['total_quantity'] = $total_quantity;
            $data[$key]['total_amount'] = $total_amount;
            $data[$key]['total_discount'] = $total_discount;
            $data[$key]['total_tax'] = $total_tax;
            if($order->IsfreeShipping == 1){
                $tempTotal += $order->shipping_amount;
            }
            $data[$key]['total'] = $tempTotal + $total_tax;
        }
        return json_encode($data);
    }

    public function SpecificHistory($auth, $store)
    {
        $user_id = Request::input('user_id');
        $id = Request::input('id');
        
        $_item = Tbl_order_item::Sel($id)->get();
        $order = Tbl_order::where('tbl_order_id',$id)->first();
        $data = array();
        $data['tbl_order_id'] = $order->tbl_order_id; 
        $data['customer_id'] = $order->customer_id;  
        $data['discount'] = $order->discount;  
        $data['discount_var'] = $order->discount_var;  
        $data['discount_reason'] = $order->discount_reason;  
        $data['IsfreeShipping'] = $order->IsfreeShipping;  
        $data['shipping_name'] = $order->shipping_name;  
        $data['shipping_amount'] = $order->shipping_amount;  
        $data['isTaxExempt'] = $order->isTaxExempt;  
        $data['hasTax'] = $order->hasTax;  
        $data['tax_percentage'] = $order->tax_percentage;  
        $data['notes'] = $order->notes;  
        $data['payment_stat'] = $order->payment_stat;  
        $data['payment_method'] = $order->payment_method;  
        $data['IsReserve'] = $order->IsReserve;  
        $data['reserve_date'] = $order->reserve_date;  
        $data['craeted_date'] = $order->craeted_date;  
        $data['status'] = $order->status;  
        $data['shop_id'] = $order->shop_id;  
        $data['archived'] = $order->archived;  
        $data['fulfillment_status'] = $order->fulfillment_status;  
        $data['proof_of_payment'] = $order->proof_of_payment;  
        $data['date_approve_order'] = $order->date_approve_order;  
        // $data['item'];
        $total_quantity = 0;
        $total_amount = 0;
        $total_discount = 0;
        $total_tax = 0;
        $data['order'] = array();
        
        foreach($_item as $k => $item){
            $data['order'][$k]['tbl_order_item_id'] = $item->tbl_order_item_id;
            $data['order'][$k]['variant_id'] = $item->variant_id;
            $data['order'][$k]['item_amount'] = $item->item_amount;
            $data['order'][$k]['quantity'] = $item->quantity;
            $data['order'][$k]['discount'] = $item->discount;
            $data['order'][$k]['discount'] = $item->discount_var;
            $data['order'][$k]['discount_reason'] = $item->discount_reason;
            $data['order'][$k]['variant_sku'] = $item->variant_sku;
            $data['order'][$k]['image_path'] = $item->image_path;
            $data['order'][$k]['IsfreeShipping'] = $item->IsfreeShipping;
            $data['order'][$k]['shipping_name'] = $item->shipping_name;
            $data['order'][$k]['shipping_amount'] = $item->shipping_amount;
            $data['order'][$k]['shipping_amount'] = $item->shipping_amount;
            $data['order'][$k]['hasTax'] = $item->hasTax;
            $data['order'][$k]['tax_percentage'] = $item->tax_percentage;
            $data['order'][$k]['payment_stat'] = $item->payment_stat;
            $data['order'][$k]['payment_method'] = $item->payment_method;
            $data['order'][$k]['proof_of_payment'] = $item->proof_of_payment;
            $data['order'][$k]['product_date_created'] = $item->product_date_created;
            $data['order'][$k]['product_name'] = $item->product_name;
            $total_quantity += $item->quantity;
            $tempAmount = $item->quantity * $item->item_amount;
            $tempDiscount = 0;
            if($item->discount_var == 'amount'){
                $tempDiscount = $item->discount;
            }   
            else{
                $tempDiscount = ($item->discount / 100) * $tempAmount;
            }
            $total_amount = $tempAmount - $tempDiscount;
            $data['order'][$k]['tempAmount'] = $tempAmount;
        }
        if($order->discount_var == 'amount'){
            $total_discount = $order->discount;
        }
        else{
            $total_discount = ($order->discount / 100) * $total_amount;
        }
        $tempTotal = $total_amount - $total_discount;
        if($order->isTaxExempt == 1){
            $total_tax = 0;
        }
        else{
            if($order->hasTax == 1){
                $total_tax = ($order->tax_percentage / 100) * $tempTotal;
            }
            else{
                $total_tax = 0;
            }
        }
        $data['total_quantity'] = $total_quantity;
        $data['total_amount'] = $total_amount;
        $data['total_discount'] = $total_discount;
        $data['total_tax'] = $total_tax;
        if($order->IsfreeShipping == 1){
            $tempTotal += $order->shipping_amount;
        }
        $data['total'] = $tempTotal + $total_tax;
        
        return json_encode($data);
    }
    
    public function contact($auth, $store){
        $shop_id = $this->GetShopId($store);
        $data['_contact'] = Tbl_contact::where('shop_id',$shop_id)->where('archived',0)->where('primary',1)->orderBy('category','desc')->get();
        return json_encode($data);
        
    }
    public function location($auth, $store){
        $shop_id = $this->GetShopId($store);
        $location = Tbl_location::where('shop_id',$shop_id)->where('primary',1)->where('archived',0)->first();
        return json_encode($location);
    }
    
    public function about($auth, $store){
        $shop_id = $this->GetShopId($store);
        $about = Tbl_about_us::where('shop_id', $shop_id)->where('archived',0)->get();
        return json_encode($about);
    }
    
    public function shipping($auth, $store){
        $shop_id = $this->GetShopId($store);
        $shipping = Tbl_shipping::where('shop_id', $shop_id)->where('archived',0)->orderBy('shipping_name','asc')->get();
        return json_encode($shipping);
    }
    
    public function paymentsetting($auth, $store)
    {
        $shop_id = $this->GetShopId($store);
        
        // Tbl_ecommercer_remittance
        // Tbl_ecommerce_banking
        // Tbl_ecommerce_setting
        // Tbl_ecommerce_paypal
        $data['setting'] = Tbl_ecommerce_setting::where('shop_id',$shop_id)->where('ecommerce_setting_enable',1)->get();
        $data['_banking'] = Tbl_ecommerce_banking::where('shop_id',$shop_id)->where('archived',0)->get();
        $data['_remittance'] = Tbl_ecommercer_remittance::where('shop_id',$shop_id)->where('archived',0)->get();
        $data['_paypal'] = Tbl_ecommerce_paypal::where('shop_id',$shop_id)->first();
        
        return json_encode($data);
        
        
    }

    public function api(Request2 $request) 
    {
        return $request->user();
    }
}
 
