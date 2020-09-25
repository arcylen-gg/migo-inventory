<?php

namespace App\Http\Controllers\Member;

use Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Session;
use Mail;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_product;
use App\Models\Tbl_variant;
use App\Models\Tbl_category;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_shipping;
use App\Models\Tbl_product_search;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Variant;

// use App\Http\Controllers\Orders\OrderController;
class OrderController extends Member
{
    
    public static function pagelink(){
        return 'ecommerce';
    }
    
    public function orders()
    {
    	// $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        $data["page"] = $this->pagelink();
        $data['_orders'] = OrderController::get_orders_with_view($data["page"], "ready", null, null);
        return view("member.order.order", $data);
    }
    public static function get_orders($status, $payment_stat, $fulfillment_status)
    {
        $shop_id = OrderController::checkuser('user_shop');
        $_order = Tbl_order::leftjoin('tbl_customer','tbl_customer.customer_id','=','tbl_order.customer_id')
                            ->SelStatus($status)
                            ->SelPayment_stat($payment_stat)
                            ->SelFulfillment_status($fulfillment_status)
                            ->where('tbl_order.shop_id',$shop_id)
                            ->where('tbl_order.archived',0)
                            ->get();
        $orders = '';
        $on = 0;
        foreach($_order as $order){
            $orders[$on]['tbl_order_id'] = $order->tbl_order_id;
            $orders[$on]['fulfillment_status'] = $order->fulfillment_status;
            $orders[$on]['status'] = $order->status;
            $orders[$on]['payment_stat'] = $order->payment_stat;
            $item = OrderController::computeOrder($order->tbl_order_id);
            $amount_basic = $item['amount'];
            $isTaxExempt = $order->isTaxExempt;
            $hasTax = $order->hasTax;
            $tax_percentage = $order->tax_percentage;
            $tax = 0;
            if($isTaxExempt != 1 && $hasTax != 0){
                $tax = ($tax_percentage / 100) * $amount_basic;
            }
            $discount = 0;
            $discountper = $order->discount;
            $discount_var = $order->discount_var;
            if($discount_var == 'amount'){
                $discount = $discountper;
            }
            else{
                $discount = ($discountper / 100) * $amount_basic;
            }
            $totalamounts = ($amount_basic + $tax + $order->shipping_amount) - $discount;
            $orders[$on]['amount'] = number_format($totalamounts,2);
            $orders[$on]['customer_id'] = $order->customer_id;
            $orders[$on]['customer_name'] = $order->first_name.' '.$order->last_name;
            $orders[$on]['date'] = date('M d, Y', strtotime($order->date_approve_order));
            $on++;
        }
        // dd($orders);
        return $orders;
    }
    public static function get_orders_with_view($page, $status, $payment_stat, $fulfillment_status)
    {   
        // dd($status ." " . $payment_stat . $fulfillment_status);
        $data['_orders'] = OrderController::get_orders($status, $payment_stat, $fulfillment_status);
        $data["page"] = OrderController::pagelink();
        
        return view("member.order.vieworder.viewall", $data); 
    }
    public static function computeOrder($order_id = 0){
        $_order = Tbl_order_item::where('tbl_order_id', $order_id)->get();
        $totalquantity = 0;
        $total_amount = 0;
        foreach($_order as $order){
            $discount = $order->discount;
            $discount_var = $order->discount_var;
            $item_amount = $order->item_amount;
            $item_quantity = $order->quantity;
            $amount = $item_amount * $item_quantity;
            $discountAmount = 0;
            if($discount_var == 'amount'){
                $discountAmount = $discount;
            }   
            else{
                $discountAmount = ($discount / 100) * $amount;
            }
            $total_amount += $amount - $discountAmount;
            $totalquantity += $item_quantity;
            // amount

        }
        $data['quantity'] = $totalquantity;
        $data['amount'] = $total_amount;
        return $data;
    }


    public function new_order(){
    	$user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        $order_count = Tbl_order::where('shop_id',$user_info->user_shop)->where('status','new')->count();
        if($order_count == 0){
            $this->createOrder();
        }
        $data['_customer'] = Tbl_customer::where('shop_id',$user_info->user_shop)->where('archived',0)->get();
        $data["page"] = "Order";
        $data['_country'] = Tbl_country::orderBy('country_name','asc')->get();
        $order = Tbl_order::leftjoin('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_name')->where('tbl_order.shop_id',$user_info->user_shop)->where('tbl_order.status','new')->first();
        $data['_item'] = Item::breakdown(Tbl_order_item::Sel($order->tbl_order_id, $user_info->user_shop)->select(DB::raw('tbl_variant.*, tbl_order_item.*, tbl_product.*, tbl_image.*, tbl_order_item.discount as item_discount, tbl_order_item.discount_reason as item_discount_reason, tbl_order_item.discount_var as item_discount_var'))->get());
        $total = 0;
        // dd($data['_item']);
        $deftotal = $data['_item']['total'];
        $total = number_format($data['_item']['total'],2);
        if($total == 0){
            $total = '--';
        }
        $tax = '--';
        $deftax = 0;
        $taxpercent = 0;
        $strtaxpercent = '';
        $IsTaxExemp = '0';
        $hasTax = 0;
        $discount = '--';
        $discounttext = '';
        $defdiscount = 0;
        $defnumdiscount = 0;
        $discount_reason = '';
        $discount_var = 'amount';
        $discount_currency = '₱';
        $defshipping = 0;
        $shipping = '--';
        $shipping_name = '';
        $notes = '';
        $reserveDate = '';
        $reserveDateTime = '';
        $total_ordering = '--';
        $tbl_order_id = 0;
        $discountamount = 0;
        $def_total_ordering = 0;
        $customer = '';
        $customer_info = 'none';
        $customer_form = 'block';
        $customer_id = 0;

        if($order_count == 1){
            $tbl_order_id = $order->tbl_order_id;
            $taxpercent = $order->tax_percentage;
            $IsTaxExemp = $order->isTaxExempt;
            $strtaxpercent = 'VAT '.$taxpercent.' %';
            $discount_var = $order->discount_var;
            $defnumdiscount = $order->discount;
            $shipping = $order->shipping_amount;
            $shipping_name = $order->shipping_name;
            $isFreeShipping = $order->IsfreeShipping;
            $discount_reason = $order->discount_reason;
            $notes = $order->notes;
            $reserve = $order->reserve_date;
            $reserveDate = date('Y-m-d', strtotime($reserve));
            $reserveDateTime = date('H:i:s a', strtotime($reserve));
            $tax = $deftotal * ($taxpercent / 100);
            // dd($tax);
            if($order->customer_id == 0){
                $IsTaxExemp = 0;
                $hasTax = 0;
                $strtaxpercent = '';
                $tax = 0;
            }
            $discounttext = $order->discount;
            if($discount_var == 'amount'){
                $discountamount = $order->discount;
            }
            else{
                $discount_currency = '%';
                $discountamount = $deftotal * ($order->discount / 100);
            }
            $total_ordering = ($deftotal + $shipping + $tax) - $discountamount;
            $def_total_ordering = $deftotal;
            $total_ordering = number_format($total_ordering,2);
            
            
            if($IsTaxExemp == 1){
                $deftax = 0;
                $tax = '--';
            }
            else{
                $hasTax = $order->hasTax;
                
                if($hasTax == 0){
                    $tax = 0;
                }
                $deftax = $tax;
                
                if($tax == 0){
                    $tax = '--';
                }
                else{
                    $tax = number_format($tax,2);
                }
            }
            
            $defdiscount = $discountamount;

            if($discountamount == 0){
                $discountamount = '--';
            }
            else{
                $discountamount = '- '.number_format($discountamount,2);
            }
            $defshipping = $shipping;
            if($shipping == 0){
                $shipping = '--';
            }
            else{
                $shipping = number_format($shipping,2);
            }

            $customer_id = $order->customer_id;
            // dd($customer_id);
            if($customer_id != 0){
                $customer_dd = $this->customerData($customer_id);
                $customer = view('member.order.customer_info', $customer_dd);
                $customer_info = 'block';
                $customer_form = 'none';
            }
            // dd($customer);
        }
        $data['_shipping'] = Tbl_shipping::sel($user_info->user_shop)->orderBy('shipping_name','asc')->get();
        $data['tbl_order_id'] = $tbl_order_id;
        $data['tax'] = $tax;
        $data['IsTaxExemp'] = $IsTaxExemp;
        $data['deftax'] = $deftax;
        $data['hasTax'] = $hasTax;
        $data['taxpercent'] = $taxpercent;
        $data['strtaxpercent'] = $strtaxpercent;
        $data['discountamount'] = $discountamount;
        $data['defdiscount'] = $defdiscount;
        $data['discounttext'] = $discounttext;
        $data['discount_var'] = $discount_var;
        $data['discount_reason'] = $discount_reason;
        $data['defnumdiscount'] = $defnumdiscount;
        $data['discount_currency'] = $discount_currency;
        $data['shipping'] = $shipping;
        $data['defshipping'] = $defshipping;
        $data['shipping_name'] = $shipping_name;
        $data['notes'] = $notes;
        $data['reserveDate'] = $reserveDate;
        $data['reserveDateTime'] = $reserveDateTime;
        $data['total_order'] = $total;
        $data['total_ordering'] = $total_ordering;
        $data['def_total_ordering'] = $def_total_ordering;
        $data['deftotal'] = $deftotal;
        $data['customer'] = $customer;
        $data['customer_info'] = $customer_info;
        $data['customer_form'] = $customer_form;
        $data['customer_id'] = $customer_id;

        return view("member.order.create_order", $data);
    }

    public function create_customer(){
		$first_name = Request::input('first_name');
		$last_name = Request::input('last_name');
		$email = Request::input('email');
		$accepts_marketing = 0;
		$tax_exempt = 0;
		if(Request::has('accepts_marketing')){
			$accepts_marketing = Request::input('accepts_marketing');
		}
		if(Request::has('tax_exempt')){
			$tax_exempt = Request::input('tax_exempt');
		}
		$company = Request::input('company');
		$phone = Request::input('phone');
		$address = Request::input('address');
		$address_cont = Request::input('address_cont');
		$city = Request::input('city');
		$country = Request::input('country');
		$province = Request::input('province');
		$zip_code = Request::input('zip_code');

		$user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
		$shop_id = $user_info->user_shop;

		$insert['shop_id'] = $shop_id;
		$insert['first_name'] = $first_name;
		$insert['last_name'] = $last_name;
		$insert['email'] = $email;

		$insert['country_id'] = $country;

		$insert['created_date'] = date('Y-m-d');
		$insert['profile'] = '/assets/images/noavailable.png';
		$email_count = Tbl_customer::where('email',$email)->where('shop_id',$shop_id)->count();
		if($email_count == 0){
			$customer_id = Tbl_customer::insertGetId($insert);

			//forsearching table using match against
			$insertsearch['customer_id'] = $customer_id;
			$insertsearch['body'] = $first_name.' '.$last_name.' '.$email.' '.$company;
			$insertsearch['created_at'] = Carbon::now();
			Tbl_customer_search::insert($insertsearch);
			
			return 'success';
		}
		else{
			return 'email already exist.';
		}
		
    }

    public function searchscustomer(){
        $shop = $this->user_info;
        // dd($shop);
    	$str = Request::input('str');
    	$data = Customer::search($str, $shop->user_shop);
    	return view('member.order.search_customer',$data);
    }

    public function customerinfo(){
        
        $id = Request::input('content');
        
    	$data['customer'] = Tbl_customer::join('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')->where('tbl_customer.customer_id',$id)->first();
        // dd($id);
        $data['shipping'] = Tbl_customer_address::join('tbl_country','tbl_country.country_id','=','tbl_customer_address.country_id')->where('tbl_customer_address.customer_id',$id)->where('tbl_customer_address.purpose','shipping')->where('tbl_customer_address.archived',0)->first();
        $data['other'] = Tbl_customer_other_info::where('customer_id',$id)->first();
        $tax_exempt = $data['customer']->taxt_exempt;
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        $shop_id = $user_info->user_shop;
        $count = Tbl_order::where('shop_id', $shop_id)->where('status','new')->count();
        $order['customer_id'] = $id;
        $order['isTaxExempt'] = $tax_exempt;
        $order_id = 0;
        if($count == 0){
            $order['shop_id'] = $shop_id;
            $order['status'] = 'new';
            $order['craeted_date'] = Carbon::now();
            $order_id = Tbl_order::insertGetId($order);
        }
        else{
            $orderquery = Tbl_order::where('shop_id', $shop_id)->where('status','new')->first();
            Tbl_order::where('tbl_order_id',$orderquery->tbl_order_id)->update($order);
            $order_id = $orderquery->tbl_order_id;
        }
        $data['order_id'] = $order_id;
    	return view('member.order.customer_info', $data);
    }

    public function customerData($id = 0){
        $data['customer'] = Tbl_customer::join('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')->where('tbl_customer.customer_id',$id)->first();
        // dd($id);
        $tax_exempt = $data['customer']->taxt_exempt;
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        $shop_id = $user_info->user_shop;
        $count = Tbl_order::where('shop_id', $shop_id)->where('status','new')->count();
        $order['customer_id'] = $id;
        $order['isTaxExempt'] = $tax_exempt;
        $order_id = 0;
        if($count == 0){
            $order['shop_id'] = $shop_id;
            $order['status'] = 'new';
            $order['craeted_date'] = Carbon::now();
            $order_id = Tbl_order::insertGetId($order);
        }
        else{
            $orderquery = Tbl_order::where('shop_id', $shop_id)->where('status','new')->first();
            Tbl_order::where('tbl_order_id',$orderquery->tbl_order_id)->update($order);
            $order_id = $orderquery->tbl_order_id;
        }
        $data['order_id'] = $order_id;
        return $data;
    }
    public function updateEmail(){
		$id = Request::input('id');
		$email = Request::input('email');
		$update['email'] = $email;
		Tbl_customer::where('customer_id',$id)->update($update);
    }
    public function updateShipping(){
		$id = Request::input('id');
		$country = Request::input('country');
		$first = Request::input('first');
		$last = Request::input('last');
		$company = Request::input('company');
		$phone = Request::input('phone');
		$address = Request::input('address');
		$cont = Request::input('cont');
		$city = Request::input('city');
		$province = Request::input('province');
		$zip = Request::input('zip');

		$update['first_name'] = $first;
		$update['last_name'] = $last;
		$update['company'] = $company;
		$update['phone'] = $phone;
		$update['_address'] = $address;
		$update['_address_cont'] = $cont;
		$update['city'] = $city;
		$update['country_id'] = $country;
		$update['province'] = $province;
		$update['zip_code'] = $zip;
		Tbl_customer::where('customer_id',$id)->update($update);
		$data['customer'] = Tbl_customer::join('tbl_country','tbl_country.country_id','=','tbl_customer.country_id')->where('tbl_customer.customer_id',$id)->first();
    	// dd($id);
    	return view('member.order.customer_info', $data);
    }

    public function itemlist(){
    	$content = Request::input('content');
    	
    	$ex = explode(",", $content);
    	// dd($ex);
    	$user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
    	$query = '';
    	// dd($content);
    	if($ex[0] == 'All products' || $ex[0] == 'Popular products'){
    		// dd($content);
    		if($ex[0] == 'All products'){
    			$query = Tbl_product::SelectItem($user_info->user_shop,0)->get();
    		}
    		if($ex[0] == 'Popular products'){
	    		$query = Tbl_product::SelectItem($user_info->user_shop,0)->orderBy('popularity','desc')->get();
	    	}
	    	$data['_item'] = $this->itembreakdown($query);
	    	return view('member.order.itemList',$data);
	    	// dd($query);
    	}
    	if($ex[0] == 'Product types'){
    		$data['_type'] = Tbl_category::sel($user_info->user_shop)->orderBy('type_name','asc')->get();
    		$data['trigger'] = 'type';
    		return view('member.order.subcategory',$data);
    	}
    	if($ex[0] == 'Vendors'){
    		$data['_type'] = Tbl_product_vendor::sel($user_info->user_shop)->orderBy('vendor_name','asc')->get();
    		$data['trigger'] = 'vendor';
    		return view('member.order.subcategory_vendor',$data);
    	}
    	if($ex[1] == 'vendor'){
    		$query = Tbl_product::SelectItem($user_info->user_shop,0,$ex[0])->get();
    		$data['_item'] = $this->itembreakdown($query);
	    	return view('member.order.itemList',$data);
    	}
    	if($ex[1] == 'type'){
    		$query = Tbl_product::SelectItem($user_info->user_shop,0,0,$ex[0])->get();

    		$data['_item'] = $this->itembreakdown($query);
	    	return view('member.order.itemList',$data);
    	}
    }

    public function itembreakdown($query){

    	$data = '';
    	foreach($query as $key => $main){
    		$product_id = $main->product_id;
    		$data[$key]['main_item'] = $main->product_name;
    		$data[$key]['img'] = $main->image_path;
    		$data[$key]['product_id'] = $product_id;
    		
    		$variant = Tbl_variant::Variant($product_id)
    					->groupBy('tbl_variant_name.variant_id')
    					->select(DB::raw('group_concat(tbl_option_value.option_value) as variant_con, tbl_variant.*'))
    					->get();
            $variantcount = Tbl_variant::Variant($product_id)
                        ->groupBy('tbl_variant_name.variant_id')
                        ->select(DB::raw('group_concat(tbl_option_value.option_value) as variant_con, tbl_variant.*'))
                        ->count();

    		foreach($variant as $k => $var){
    			$data[$key]['variant'][$k]['variant_id'] = $var->variant_id;
    			$data[$key]['variant'][$k]['variant'] = $this->variantseparator($var->variant_con);
    			$data[$key]['variant'][$k]['price'] = number_format($var->variant_price,2);
                $data[$key]['variant'][$k]['price_def'] = $var->variant_price;
    			$variant_track_inventory = $var->variant_track_inventory;
    			$inventory = '';
    			if($variant_track_inventory == 1){
    				$inventory = $var->variant_inventory_count.'&nbsp;in stock';
    			}
    			$data[$key]['variant'][$k]['inventory'] = $inventory;

    		}
            if($variantcount == 0){
                $data[$key]['variant'] = '';
            }
    	}
        // dd($data);
    	return $data;
    }

    public function variantseparator($variant){
    	// dd($variant);
    	$color[0] = '#29bc94';
    	$color[1] = '#763eaf';
    	$color[2] = '#ff9517';
    	$color[3] = '#ff1740';
    	$color[4] = '#3217ff';
    	$color[5] = '#17ff2b';
    	$ex = explode(",", $variant);
    	$num = 0;
    	$str = '';
    	foreach($ex as $key => $x){
    		$str.='<span class="variant-option" style="color:'.$color[$num].'">'.$ex[$key].'<span>';
    		$num++;
    	}
    	return $str;
    }

    public function create_order(){
        
        
        $item_count = Request::input('item_count');
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();

        $shop_id = $user_info->user_shop;
        $order_count = Tbl_order::where('shop_id',$shop_id)->where('status','new')->count();
        $order = Tbl_order::where('shop_id',$shop_id)->where('status','new')->first();
        $insert['status'] = 'new';
        $insert['shop_id'] = $shop_id;
        $insert['craeted_date'] = Carbon::now();
        $insert['discount_var'] = 'amount';
        
        if($order_count == 0){
            $id = Tbl_order::insertGetId($insert);
        }
        else{
            $id = $order->tbl_order_id;
        }
        $insertChild = '';
        // Tbl_order_item
        $n = 0;
        $_product = Tbl_product::where('product_shop',$shop_id)->get();
        foreach($_product as $product){
			$product_id = $product->product_id;
			if(Request::has('main_check_box_'.$product_id)){
				$varq = Tbl_variant::where('variant_product_id',$product_id);
				$_variant = $varq->get();
				$count = $_variant->count();
				// dd($_variant);
				if($count == 1){
					$countitem = Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$_variant[0]->variant_id)->count();
					if($countitem == 0){
						$insertChild[$n]['tbl_order_id'] = $id;
                        $insertChild[$n]['variant_id'] = $_variant[0]->variant_id;
                        $insertChild[$n]['item_amount'] = $_variant[0]->variant_price;
                        $insertChild[$n]['quantity'] = 1;
                        $insertChild[$n]['discount'] = 0;
                        $insertChild[$n]['discount_var'] = 'amount';
                        $insertChild[$n]['IsCustom'] = 0;
                        $n++;
					}
					else{
						$getItem = Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$_variant[0]->variant_id)->first();
                        $quantity = ($getItem->quantity) + 1;
                        $updateItem['quantity'] =$quantity;
                        Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$_variant[0]->variant_id)->update($updateItem);
					}
					
				}
				
				
			}
			
		}
        for($i = 1; $i <= $item_count; $i++){
            if(Request::has('child_check_box_'.$i)){
                $variant_id = Request::input('child_check_box_'.$i);
                $countitem = Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$variant_id)->count();
                if($countitem == 0){
                    $insertChild[$n]['tbl_order_id'] = $id;
                    $insertChild[$n]['variant_id'] = $variant_id;
                    $insertChild[$n]['item_amount'] = Request::input('def_price_'.$i);
                    $insertChild[$n]['quantity'] = 1;
                    $insertChild[$n]['discount'] = 0;
                    $insertChild[$n]['discount_var'] = 'amount';
                    $insertChild[$n]['IsCustom'] = 0;
                    $n++;
                }
                else{
                    $getItem = Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$variant_id)->first();
                    $quantity = ($getItem->quantity) + 1;
                    $updateItem['quantity'] =$quantity;
                    Tbl_order_item::where('tbl_order_id',$id)->where('variant_id',$variant_id)->update($updateItem);
                }
                
            }
        }
        if($insertChild != ''){
            Tbl_order_item::insert($insertChild);

            //SEND EMAIL
            $toEmail = "edwardguevarra2003@gmail.com";
            $toName = "Edward Guevarra";
            $fromName = "System";
            $subject = "Checkout Notification";

            $data["data"] = "Watashi no Kinimarimasu";
 
            // Mail::send('emails.checkout_notification', $data, function($message) use ($toEmail, $toName, $fromName, $subject)
            // {
            //     $message->to($toEmail, $toName);
            //     $message->from(env('MAIL_USERNAME'), $fromName);
            //     $message->subject($subject);
            // });        

        }

        $data['_item'] = Item::breakdown(Tbl_order_item::Sel($id, $user_info->user_shop)->select(DB::raw('tbl_variant.*, tbl_order_item.*, tbl_product.*, tbl_image.*, tbl_order_item.discount as item_discount, tbl_order_item.discount_reason as item_discount_reason, tbl_order_item.discount_var as item_discount_var'))->get());
        // $data['_item'] = Tbl_order_item::where('tbl_order_id',$id)->get();

        return view('member.order.order_item',$data);
    }


    public function removeitemorder(){
        $id = Request::input('id');
        Tbl_order_item::where('tbl_order_item_id',$id)->delete();
    }
    public function addIndiDiscount(){
        $content = Request::input('content');
        $trigger = Request::input('trigger');
        $reason = Request::input('reason');
        $discount = Request::input('discount');
        $update['discount'] = $discount;
        $update['discount_reason'] = $reason;
        $update['discount_var'] = $trigger;
        // dd($update);
        Tbl_order_item::where('tbl_order_item_id',$content)->update($update);
    }
    public function chagequantity(){
        $id = Request::input('id');
        $quantity = Request::input('quantity');
        $update['quantity'] = $quantity;
        Tbl_order_item::where('tbl_order_item_id',$id)->update($update);
    }

    public function addMainDiscount(){
        $amount = Request::input('amount');
        $reason = Request::input('reason');
        $trigger = Request::input('trigger');
        $id = Request::input('id');
        $update['discount'] = $amount;
        $update['discount_var'] = $trigger;
        $update['discount_reason'] = $reason;
        if($id == 0 || $id == null){
             $id = Tbl_order::insertGetId($update);
        }
        else{
            Tbl_order::where('tbl_order_id',$id)->update($update);
        }
        return $id;
    }
    public  function applytax(){
        $id = Request::input('id');
        $isTax = Request::input('isTax');
        $update['hasTax'] = $isTax;
        $update['tax_percentage'] = 20;
        Tbl_order::where('tbl_order_id',$id)->update($update);
    }
    public  function removecustomer(){
        $order = Request::input('order');
        $customer = Request::input('customer');
        $update['customer_id'] = 0;
        $update['isTaxExempt'] = 0;
        Tbl_order::where('tbl_order_id',$order)->update($update);
    }

    public function addshipping(){
        $trigger = Request::input('trigger');
        $price = Request::input('price');
        $name = Request::input('name');
        $user_id = $this->checkuser('user_id');
        $shop_id = $this->checkuser('user_shop');
        $count = $this->getNewOrder($shop_id, true);
        $order_id = 0;
        $IsfreeShipping = 0;
        if($trigger == 'free'){
            $IsfreeShipping = 0;
        }
        else{
            $IsfreeShipping = 1;
        }
        $order['IsfreeShipping'] = $IsfreeShipping;
        $order['shipping_name'] = $name;
        $order['shipping_amount'] = $price;
        if($count == 0){
            $order['shop_id'] = $shop_id;
            $order['status'] = 'new';
            $order['craeted_date'] = Carbon::now();
            Tbl_order::insert($order);
        }
        else{
            $order_id = $this->getNewOrder($shop_id, false);
            Tbl_order::where('tbl_order_id',$order_id)->update($order);
        }
    }

    public static function checkuser($str = ''){
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        switch ($str) {
            case 'user_id':
                return $user_info->user_id;
                break;
            case 'user_shop':
                return $user_info->user_shop;
                break;
            default:
                return '';
                break;
        }
    }
    public function getNewOrder($shop_id = 0, $count = false, $getData = false){
        if($count){
            $order = Tbl_order::where('shop_id',$shop_id)->where('status','new')->count();
            return $order;
        }
        else if(!$count && !$getData){
            $order = Tbl_order::where('shop_id',$shop_id)->where('status','new')->first();
            return $order->tbl_order_id;
        }
        else if($getData && !$count){
            $order = Tbl_order::where('shop_id',$shop_id)->where('status','new')->first();
            return $order;
        }
 
    }
    public function createOrder(){
        $user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        $shop_id = $user_info->user_shop;
        $order_count = Tbl_order::where('shop_id',$shop_id)->where('status','new')->count();
        $order = Tbl_order::where('shop_id',$shop_id)->where('status','new')->first();
        $insert['status'] = 'new';
        $insert['shop_id'] = $shop_id;
        $insert['craeted_date'] = Carbon::now();
        $insert['discount_var'] = 'amount';
        Tbl_order::insert($insert);
    }

    public function savetodraft(){
        $shop_id = $this->checkuser('user_shop');
        $note = Request::input('note');
        $order = $this->getNewOrder($shop_id);
        $item = Tbl_order_item::where('tbl_order_id',$order)->count();
        $data = '';
        $e = 0;
        $s = 0;
        $update['notes'] = $note;
        if($item == 0){
            $data['trigger'] = 'Error';
            $data['msg'][$e] = 'No product item selected.';
            $e++;
        }
        if($item > 0){
            $data['trigger'] = 'Success';
            $data['msg'][$s] = 'Order has been save to draft.';
            $update['status'] = 'draft';
            $s++;
        }
        Tbl_order::where('tbl_order_id',$order)->update($update);
       
        return $data;
    }
    
    public function OrderStatus()
    {
        $trigger = Request::input('trigger');
        $update['payment_stat'] = $trigger;
        $update['status'] = 'ready';
        $shop_id = $this->checkuser('user_shop');
        $order_id = $this->getNewOrder($shop_id);
        $data = Tbl_order::where('tbl_order_id',$order_id)->first();
        $shipping_name = $data->shipping_name;
        $shipping = $data->shipping_amount;
        $fulfillment_status = '';
        $date_approve_order = Carbon::now();
        $update['date_approve_order'] = $date_approve_order;
        $update['fulfillment_status'] = 'Processing';
        Tbl_order::where('tbl_order_id',$order_id)->update($update);
        return $order_id;
    }

    public function search_item()
    {
        $shop_id = $this->checkuser('user_shop');
        $search = Request::input('search');
        
        $data = Variant::search($shop_id, $search);
        // $data['view'] = view("member.order.item.item_search",$data)->render();

        // dd($data);
        return view("member.order.item.item_search",$data);
    }
}
