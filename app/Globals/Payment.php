<?php
namespace App\Globals;

use DB;
use URL;
use Redirect;
use Carbon\Carbon;
use App\Models\Tbl_category;
use App\Models\Tbl_user;
use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_customer;
use App\Models\Tbl_online_pymnt_api;
use App\Models\Tbl_item;
use App\Models\Tbl_payment_logs;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_list;
use App\Globals\Cart2;
use App\Globals\Cart;
use App\Globals\Payment;
use App\Globals\Warehouse2;
use App\Globals\Transaction;
use App\Globals\Mail_global;
// IPAY 88
use App\IPay88\RequestPayment;
// DRAGON PAY
use App\Globals\Dragonpay2\Dragon_RequestPayment;
// PAYMAYA
use App\Globals\PayMaya\PayMayaSDK;
use App\Globals\PayMaya\API\Checkout;
use App\Globals\PayMaya\API\Customization;
use App\Globals\PayMaya\API\Webhook;
use App\Globals\PayMaya\Core\CheckoutAPIManager;
use App\Globals\PayMaya\Checkout\User;
use App\Globals\PayMaya\Model\Checkout\ItemAmountDetails;
use App\Globals\PayMaya\Model\Checkout\ItemAmount;
use App\Globals\PayMaya\Core\Constants;
use Crypt;

class Payment
{
	/** Helpers **/
	public static function insert_logs($insert, $shop_id)
    {
    	$insert["shop_id"] = $shop_id;
    	Tbl_payment_logs::insert($insert);

    	return true;
    }

    public static function paymaya_number_format($price)
	{
		return (string)number_format($price, 2, '.', '');
	}

	/** Main Functions **/
	public static function get_list($shop_id)
	{
		$return = Tbl_online_pymnt_method::select("tbl_online_pymnt_method.method_id", "tbl_online_pymnt_method.method_name", "tbl_online_pymnt_link.link_reference_name")
										 ->leftJoin('tbl_online_pymnt_link', 'tbl_online_pymnt_link.link_method_id', '=', 'tbl_online_pymnt_method.method_id')
	                                     ->leftJoin('tbl_online_pymnt_other', 'tbl_online_pymnt_link.link_reference_id', '=', 'tbl_online_pymnt_other.other_id')
	                                     ->leftJoin('tbl_image', 'tbl_online_pymnt_link.link_img_id', '=', 'tbl_image.image_id')
	                                     ->where("tbl_online_pymnt_link.link_shop_id", $shop_id)
	                                     ->where("tbl_online_pymnt_link.link_is_enabled", 1)
	                                     ->get();
		
		return $return;
	}

	public static function get_info($shop_id, $method_id)
	{
		$return = Tbl_online_pymnt_method::leftJoin('tbl_online_pymnt_link', 'tbl_online_pymnt_link.link_method_id', '=', 'tbl_online_pymnt_method.method_id')
	                                     ->leftJoin('tbl_online_pymnt_other', 'tbl_online_pymnt_link.link_reference_id', '=', 'tbl_online_pymnt_other.other_id')
	                                     ->leftJoin('tbl_image', 'tbl_online_pymnt_link.link_img_id', '=', 'tbl_image.image_id')
	                                     ->where("tbl_online_pymnt_link.link_shop_id", $shop_id)
	                                     ->where("tbl_online_pymnt_link.link_is_enabled", 1)
	                                     ->where("tbl_online_pymnt_method.method_id", $method_id)
	                                     ->first();
		
		return $return;
	}

	public static function get_info_by_key($shop_id, $key)
	{
		$return = Tbl_online_pymnt_method::leftJoin('tbl_online_pymnt_link', 'tbl_online_pymnt_link.link_method_id', '=', 'tbl_online_pymnt_method.method_id')
	                                     ->leftJoin('tbl_online_pymnt_other', 'tbl_online_pymnt_link.link_reference_id', '=', 'tbl_online_pymnt_other.other_id')
	                                     ->leftJoin('tbl_image', 'tbl_online_pymnt_link.link_img_id', '=', 'tbl_image.image_id')
	                                     ->where("tbl_online_pymnt_link.link_shop_id", $shop_id)
	                                     ->where("tbl_online_pymnt_link.link_is_enabled", 1)
	                                     ->where("tbl_online_pymnt_link.link_reference_name", $key)
	                                     ->first();
		
		return $return;
	}

	public static function payment_redirect($shop_id, $key, $transaction_list_id, $success, $failed, $debug = false, $method_id = null)
	{
		/* Testing Purposes */
        if ($debug) 
        {
            Self::testing_cart($shop_id);
        }

		/* Get Cart */
		$cart = Cart2::get_cart_info();

		/* Validate Item Cart */
		if (isset($cart["_item"]) && isset($cart["_total"])) 
		{
			/* Validate Info Cart */
			if (isset($cart["info"])) 
			{
				$customer_id = isset($cart["info"]->customer_id) ? $cart["info"]->customer_id : 0;
				$customer_exist = Tbl_customer::where("customer_id", $customer_id)->where("shop_id", $shop_id)->first();
				
				/* Validate Customer Cart */
				if(isset($customer_exist))
				{
					/* Get Method Information */
					$method_information = Self::get_info_by_key($shop_id, $key);

					/* Validate Payment Method */
			        if (isset($method_information))
			        {
			        	/* API Details */
						$api = Tbl_online_pymnt_api::where('api_shop_id', $shop_id)
												   ->join("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_id", "=", "tbl_online_pymnt_api.api_gateway_id")
												   ->where("gateway_code_name", $method_information->link_reference_name)
												   ->first();

			        	/* No Refresh Messsage */
			        	echo "Please do not refresh the page and wait while we are processing your payment. This can take a few minutes.";

			            switch ($method_information->link_reference_name)
			            {
			                case 'paypal2': dd("UNDER DEVELOPMENT"); break;
			                case 'paymaya': return Self::method_paymaya($cart, $shop_id, $api, $transaction_list_id, $success, $failed); break;
			                case 'paynamics': dd("UNDER DEVELOPMENT"); break;
			                case 'dragonpay': return Self::method_dragonpay($cart, $shop_id, $api, $transaction_list_id, $success, $failed); break;
			                case 'ipay88': dd("UNDER DEVELOPMENT"); break;
			                case 'manual1': return Self::method_other($cart, $shop_id, $api, $transaction_list_id, $success, $failed, $method_id);  break;
                            case 'manual2': return Self::method_other($cart, $shop_id, $api, $transaction_list_id, $success, $failed, $method_id);  break;
			                case 'e_wallet': dd("UNDER DEVELOPMENT"); break;
			                case 'cashondelivery': dd("UNDER DEVELOPMENT"); break;
			                default: dd("UNDER DEVELOPMENT"); break;
			            }
			        }
			        else
			        {
			            return "Payment method not found";
			        }
				}
				else
				{
					return "No Customer in cart";
				}
			}
			else
			{
				return "No info in cart";
			}
		}
		else
		{
			return "No item in cart";
		}
	}
  public static function method_other($cart, $shop_id, $api, $transaction_list_id,$success, $failed, $method_id)
  {
    $list = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->where("shop_id", $shop_id)->first();
    if ($list) 
    {
        $transaction = Tbl_transaction::where("transaction_id", $list->transaction_id)->where("shop_id", $shop_id)->where("transaction_reference_table", "tbl_customer")->first();
        if ($transaction) 
        {
            $customer = Tbl_customer::where("customer_id", $transaction->transaction_reference_id)->first();
            if ($customer) 
            {
                $email_template = null;
                $email_content["subject"] = "Payment Instruction for Order Ref: " . $list->transaction_number;
                $email_content["content"] = "<h2>Dear Customer,</h2>
<p>Good day and thank you for using this mode of payment. Please click on the link below to visit the uploading of proof of payment tab:</p>
<p><a href='" . URL::to("/manual_checkout?method_id=" . $method_id . "&tid=" . Crypt::encrypt($transaction_list_id)) . "'>" . URL::to("/manual_checkout?method_id=" . $method_id . "&tid=" . Crypt::encrypt($transaction_list_id)) . "</a></p>
<p>For payment inquiries, you may call, email or chat with us.</p>";
                $email_address = $customer->email;
                Mail_global::send_email($email_template, $email_content, $shop_id, $email_address);
            }
        }
    }
    
    return redirect("/manual_checkout?method_id=" . $method_id . "&tid=" . Crypt::encrypt($transaction_list_id))->send();
  }
	/** Payment Method **/
	public static function method_dragonpay($cart, $shop_id, $api, $transaction_list_id,$success, $failed)
    {
        /* Set Summary */
        foreach ($cart["_item"] as $key => $value) 
        {
            if ($key != count($cart["_item"])) 
            {
                $product_summary = "Product #" . $value->item_name . " (x" . $value->quantity . ") - " . $value->item_price_display . "";
            }
            else
            {
                $product_summary = "Product #" . $value->item_name . " (x" . $value->quantity . ") - " . $value->item_price_display . ", ";
            }
        }
        
        /* Set API Details */
        if (get_domain() == "c9users.io") 
		{
		    $merchant_id  = "MYPHONE";
            $merchant_key = "Ez9MiNqWBS2BHuO";
		}
		else
		{
		    $merchant_id  = $api->api_client_id;
            $merchant_key = $api->api_secret_id;
		}

        /* Request Set */
        $requestpayment    = new Dragon_RequestPayment($merchant_key);
        
        $request["txnid"]  = $transaction_list_id;            // Transaction ID
        $request["amount"] = $cart["_total"]->grand_total; // Amount
        $request["ccy"]    = "PHP";                        // Currency
        $request["description"] = $product_summary;        // Summary
        $request["email"] = "";                            // Email
        
        /* Request Construct */
        $dragon_request = array(
            'merchantid'    => $requestpayment->setMerchantId($merchant_id),
            'txnid'         => $requestpayment->setTxnId($request['txnid']),
            'amount'        => $requestpayment->setAmount($request['amount']),
            'ccy'           => $requestpayment->setCcy($request['ccy']),
            'description'   => $requestpayment->setDescription($request['description']),
            'email'         => $requestpayment->setEmail($request['email']),
            'digest'        => $requestpayment->getdigest(),
            'param1'        => $success,
            'param2'        => $failed
        );
        
        
        /* Insert Logs */
        $insert["payment_log_type"] 	  = "sent";
        $insert["payment_log_method"] 	  = "dragonpay";
        $insert["payment_log_created"] 	  = Carbon::now();
        $insert["payment_log_url"] 		  = "NO URL PROVIDED";
        $insert["payment_log_data"] 	  = serialize($dragon_request);
        $insert["payment_log_ip_address"] = get_ip_address();
        $insert["transaction_list_id"]    = $transaction_list_id;
        Self::insert_logs($insert, $shop_id);
        
        Cart2::clear_cart();
        
        /* Request Transaction */
        Dragon_RequestPayment::make($merchant_key, $dragon_request); 
    }
	
	public static function method_paymaya($cart, $shop_id, $api, $transaction_list_id, $success, $failed)
	{
		/* Init Paymaya */
		if (get_domain() == "c9users.io") 
		{
		    $environment = "SANDBOX";
		    PayMayaSDK::getInstance()->initCheckout("pk-sEt9FzRUWI2PCBI2axjZ7xdBHoPiVDEEWSulD78CW9c", "sk-cJFYCGhH4stZZTS52Z3dpNbrpRyu6a9iJaBiVlcIqZ5", $environment);
		}
		else
		{
		  $environment = "PRODUCTION";
		  PayMayaSDK::getInstance()->initCheckout($api->api_client_id, $api->api_secret_id, $environment);

		    //$environment = "SANDBOX";
		    //PayMayaSDK::getInstance()->initCheckout("pk-sEt9FzRUWI2PCBI2axjZ7xdBHoPiVDEEWSulD78CW9c", "sk-cJFYCGhH4stZZTS52Z3dpNbrpRyu6a9iJaBiVlcIqZ5", $environment);
		}

        /* Customization */
        $shopCustomization = new Customization();
        $shopCustomization->logoUrl = "https://brownportal.com/images/Brown_Logo2.png";
        $shopCustomization->iconUrl = "https://paymaya.com/assets/favicon.ico";
        $shopCustomization->appleTouchIconUrl = "https://paymaya.com/assets/favicon.ico";
        $shopCustomization->customTitle = "Brown and Proud - Checkout";
        $shopCustomization->colorScheme = "#5B3425";
        $shopCustomization->set();

        /* Set Webhook */
  //       $webhook = Webhook::retrieve();

		// $webhook_success = "/payment/paymaya/webhook/success";
		// $webhook_failure = "/payment/paymaya/webhook/failure";
		// $webhook_cancel = "/payment/paymaya/webhook/cancel";

  //       if (isset($webhook) && $webhook && count($webhook) > 0) 
  //       {
  //           foreach ($webhook as $value) 
  //           {
  //               if ($value->name == "CHECKOUT_SUCCESS") 
  //               {
  //                   $updateWebhook = new Webhook();
  //                   $updateWebhook->name = Webhook::CHECKOUT_SUCCESS;
  //                   $updateWebhook->id = $value->id;
  //                   $updateWebhook->callbackUrl = URL::to($webhook_success);
  //                   $updateWebhook->delete();
  //               }
  //               elseif($value->name == "CHECKOUT_FAILURE")
  //               {
  //                   $updateWebhook = new Webhook();
  //                   $updateWebhook->name = Webhook::CHECKOUT_FAILURE;
  //                   $updateWebhook->id = $value->id;
  //                   $updateWebhook->callbackUrl = URL::to($webhook_failure);
  //                   $updateWebhook->delete();
  //               }
  //               elseif($value->name == "CHECKOUT_DROPOUT")
  //               {
  //                   $updateWebhook = new Webhook();
  //                   $updateWebhook->name = Webhook::CHECKOUT_DROPOUT;
  //                   $updateWebhook->id = $value->id;
  //                   $updateWebhook->callbackUrl = URL::to($webhook_cancel);
  //                   $updateWebhook->delete();
  //               }
  //           }
  //       }
        
  //       $successWebhook = new Webhook();
  //       $successWebhook->name = Webhook::CHECKOUT_SUCCESS;
  //       $successWebhook->callbackUrl = URL::to($webhook_success);
  //       $successWebhook->register();
        
  //       $failureWebhook = new Webhook();
  //       $failureWebhook->name = Webhook::CHECKOUT_FAILURE;
  //       $failureWebhook->callbackUrl = URL::to($webhook_failure);
  //       $failureWebhook->register();
        
  //       $cancelWebhook = new Webhook();
  //       $cancelWebhook->name = Webhook::CHECKOUT_DROPOUT;
  //       $cancelWebhook->callbackUrl = URL::to($webhook_cancel);
  //       $cancelWebhook->register();
        
        /* Set Variable */
        $itemCheckout = new Checkout();
        $user = new User();
        $itemCheckout->buyer = $user->buyerInfo();
        $totalAmount = new ItemAmount();
        $total = 0;

        /* Set Item */
        $ctr = 0;
        foreach ($cart["_item"] as $key => $value) 
        {
        	/* Get Item Details */
            $product = Tbl_item::where("item_id", $value->product_id)->first();
            
            /* Set Amount Details */
            $itemAmountDetails = new ItemAmountDetails();
            $itemAmountDetails->shippingFee = "0.00";
            $itemAmountDetails->tax = "0.00";
            $itemAmountDetails->subtotal = "0.00";

            /* Set Item Amount */
            $itemAmount = new ItemAmount();
            $itemAmount->currency = "PHP";
            $itemAmount->value = Self::paymaya_number_format($product->item_price);
            $itemAmount->details = $itemAmountDetails;

            /* Set Item Total Amount */
            $itemTotalAmount = new ItemAmount();
            $itemTotalAmount->currency = "PHP";
            $itemTotalAmount->value = Self::paymaya_number_format($product->item_price * $value->quantity);
            $itemTotalAmount->details = $itemAmountDetails;

            /* Set Total Amount */
            $totalAmount->currency = "PHP";
            $totalAmount->value = 0;
            $totalAmount->details = $itemAmountDetails;
            $total += $product->item_price * $value->quantity;

            /* Set Item Details */
            $item[$ctr] = new Item();
            $item[$ctr]->name = $product->item_name;
            $item[$ctr]->code = $product->item_sku;
            $item[$ctr]->description = $product->item_sales_information ? $product->item_sales_information : "Product #" . $product->item_id;
            $item[$ctr]->quantity = (string)$value->quantity;
            $item[$ctr]->amount = $itemAmount;
            $item[$ctr]->totalAmount = $itemTotalAmount;

            $ctr++;
        }

        /* Set Total Amount Value */
        $totalAmount->value = Self::paymaya_number_format($total);

        /* Set Item Checkout */
        $itemCheckout->items = $item;
        $itemCheckout->totalAmount = $totalAmount;
        $itemCheckout->requestReferenceNumber = (string)$transaction_list_id;

        /* Set Item Checkout URL */
        $itemCheckout->redirectUrl = array(
            "success" =>  URL::to($success),
            "failure" => URL::to($failed),
            "cancel" => URL::to($failed)
        );

        /* Insert Logs */
        $insert["payment_log_type"] 	  = "sent";
        $insert["payment_log_method"] 	  = "paymaya";
        $insert["payment_log_created"] 	  = Carbon::now();
        $insert["payment_log_url"] 		  = $environment == "SANDBOX" ? Constants::CHECKOUT_SANDBOX_URL : Constants::CHECKOUT_PRODUCTION_URL;
        $insert["payment_log_data"] 	  = serialize($itemCheckout);
        $insert["payment_log_ip_address"] = get_ip_address();
        $insert["transaction_list_id"]    = $transaction_list_id;
        Self::insert_logs($insert, $shop_id);

        $itemCheckout->execute();

        // echo $itemCheckout->id; // Checkout ID
        // echo $itemCheckout->url; // Checkout URL
        Cart2::clear_cart();
            
        return Redirect::to($itemCheckout->url)->send();
	}

	public static function method_ipay88($data, $shop_id, $delimeter)
    {
    	/* Waiting Message */
        echo "Please do not refresh the page and wait while we are processing your payment. This can take a few minutes.";
        
        /* Get API Key */
        $api = Tbl_online_pymnt_api::where('api_shop_id', $shop_id)->join("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_id", "=", "tbl_online_pymnt_api.api_gateway_id")->where("gateway_code_name", "ipay88")->first();
        
        /* Get Shop Details */
        $shop = DB::table("tbl_shop")->where("shop_id", $shop_id)->first();

        /* Get Customer Details */
        $customer = Cart::get_customer();

        /* Get Delimeter */
        switch ($delimeter) 
        {  
            /* Credit Card */
            case 1: $data["paymentId"] = 1; break;

            /* Bancnet */
            case 5: $data["paymentId"] = 5; break;

            /* Default (Credit Card) */
            default: $data["paymentId"] = $delimeter; break;
        }

        /* Set Reference Number */
        $data["refNo"] = $shop_id . time();

        /* Set Product Summary */
        $product_summary = array();
        foreach ($data['_item'] as $key => $value) 
        {
            if ($key != count($data["_item"])) 
            {
                $product_summary = "Product #" . $value["item_name"] . " (x" . $value["quantity"] . ") - " . currency("PHP", $value["item_price"]) . "";
            }
            else
            {
                $product_summary = "Product #" . $value["item_name"] . " (x" . $value["quantity"] . ") - " . currency("PHP", $value["item_price"]) . ", ";
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

        // Cart::clear_all($shop_id);
        Cart2::clear_cart();
        
        RequestPayment::make($data["merchantKey"], $ipay88request);  
    }

    /** Testing **/
    public static function testing_cart($shop_id)
    {
    	$active_cart_key = Cart2::get_cart_key();
    	if (!$active_cart_key) 
    	{
    		Cart2::set_cart_key("testing-cart");

    		$key 	= "price_level_id";
			$value 	= "0";

			Cart2::set($key, $value);

    		$key 	= "customer_id";
			$value 	= "303";

			Cart2::set($key, $value);

			$key 	= "invoice_id";
			$value 	= "0";

			Cart2::set($key, $value);

			$key 	= "receipt_id";
			$value 	= "0";

			Cart2::set($key, $value);

			$key 	= "shipping_fee";
			$value 	= "0";

			Cart2::set($key, $value);

			$key 	= "global_discount";
			$value 	= "0";

			Cart2::set($key, $value);
    	}

    	$_cart = Cart2::get_cart_info();
	    if (!$_cart) 
	    {
			$item_id	= 9;
			$quantity 	= 1; //number of items

			Cart2::add_item_to_cart($shop_id, $item_id, $quantity);
	    }
    }

    /** Logs **/
    public static function logs($shop_id, $limit)
    {
    	$_logs = Tbl_payment_logs::select("transaction_list_id","payment_log_id", "payment_log_url","payment_log_type", "payment_log_method", "payment_log_created", "payment_log_url", "payment_log_data", "payment_log_ip_address")
						       ->where("shop_id", $shop_id)
						       ->take($limit)
						       ->orderBy("payment_log_id", "desc")
						       ->get();
						       
		foreach($_logs as $key => $log)
		{
		    $tx = Tbl_transaction_list::where("transaction_list_id", $log->transaction_list_id)->value('transaction_number');
		   //dd($tx);
		    
		    $_logs[$key] = $log;
		    $_logs[$key]->display_date = date("F d, Y - h:i A", strtotime($log->payment_log_created));
		    $_logs[$key]->display_transaction_list_id = $log->transaction_list_id . "<br>" . $tx;
		}
		
		return $_logs;
    }

    public static function done($data, $from)
    {
        /* Insert Logs */
        $insert["payment_log_type"]       = "received";
        $insert["payment_log_method"]     = $from;
        $insert["payment_log_created"]    = Carbon::now();
        $insert["payment_log_url"]        = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "Unknown");
        $insert["payment_log_data"]       = serialize($data);
        $insert["payment_log_ip_address"] = get_ip_address();
        
        try
        {
            if($from == "paymaya")
            {
                $transaction_list_id            = $data["requestReferenceNumber"];
                $insert["transaction_list_id"]  = $transaction_list_id;
                $transaction_list               = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->first();
                $shop_id                        = $transaction_list->shop_id;
                
                if($data["paymentStatus"] == "PAYMENT_SUCCESS")
                {
                    $transaction_type                                   = "RECEIPT";
                    $transaction_id                                     = $transaction_list->transaction_id;
                    $transaction_date                                   = Carbon::now();
                    $source                                             = $transaction_list_id;
                    
                    Transaction::create_update_transaction_details(serialize($data));
                    $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, "+", $source);
                    Transaction::consume_in_warehouse($shop_id, $transaction_list_id);
                    
                }
                elseif($data["paymentStatus"] == "PAYMENT_FAIL" || $data["paymentStatus"] == "AUTH_FAILURE" || $data["paymentStatus"] == "PAYMENT_FAILURE" || $data["paymentStatus"] == "EXPIRED")
                {
                    $transaction_type                                   = "FAILED";
                    $transaction_id                                     = $transaction_list->transaction_id;
                    $transaction_date                                   = Carbon::now();
                    $source                                             = $transaction_list_id;
                    
                    Transaction::create_update_transaction_details(serialize($data));
                    $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, null, $source);
                }
            }
            elseif($from == "dragonpay")
            {
                $transaction_list_id            = $data["txnid"];
                $insert["transaction_list_id"]  = $transaction_list_id;
                $transaction_list               = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->first();
                $shop_id                        = $transaction_list->shop_id;
                
                if($data["status"] == "S")
                {
                    $transaction_type                                   = "RECEIPT";
                    $transaction_id                                     = $transaction_list->transaction_id;
                    $transaction_date                                   = Carbon::now();
                    $source                                             = $transaction_list_id;
                    
                    Transaction::create_update_transaction_details(serialize($data));
                    $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, "+", $source);
                    Transaction::consume_in_warehouse($shop_id, $transaction_list_id);
                }
                elseif($data["status"] == "P")
                {
                    $transaction_type                                   = "PENDING";
                    $transaction_id                                     = $transaction_list->transaction_id;
                    $transaction_date                                   = Carbon::now();
                    $source                                             = $transaction_list_id;
                    
                    Transaction::create_update_transaction_details(serialize($data));
                    $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, null, $source);
                }
                elseif($data["status"] == "F")
                {
                    $transaction_type                                   = "FAILED";
                    $transaction_id                                     = $transaction_list->transaction_id;
                    $transaction_date                                   = Carbon::now();
                    $source                                             = $transaction_list_id;
                    
                    Transaction::create_update_transaction_details(serialize($data));
                    $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, null, $source);
                }
            }
            else
            {
                $shop_id = 5;
            }
            
            Payment::insert_logs($insert, $shop_id);
        }
        catch(\Exception $e)
        {
            $insert["payment_log_type"] = "error";
            $insert["payment_log_data"] = serialize($e->getMessage());
            $shop_id                    = 5;
            Payment::insert_logs($insert, $shop_id);
        }
    }
    public static function manual_confirm_payment($shop_id, $transaction_list_id = 0)
    {
      $consume_validation = Transaction::consume_in_warehouse_validation($shop_id, $transaction_list_id);
      if(!$consume_validation)
      {
        $transaction_list                                   = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->first();
        $transaction_type                                   = "RECEIPT";
        $transaction_id                                     = $transaction_list->transaction_id;
        $transaction_date                                   = Carbon::now();
        $source                                             = $transaction_list_id;
        
        // Transaction::create_update_transaction_details(serialize($data));
        $transaction_list_id = Transaction::create($shop_id, $transaction_id, $transaction_type, $transaction_date, "+", $source);
        Transaction::consume_in_warehouse($shop_id, $transaction_list_id);
      }
      return $consume_validation;
    }
   public static function manual_reject_payment($shop_id, $transaction_id = 0)
   {
      $update['order_status'] = 'reject';
      $update['payment_status'] = 'reject';
      $data = Tbl_transaction::where('shop_id', $shop_id)->where('transaction_id', $transaction_id)->first();
      $return = 0;
      if($data)
      {
         Tbl_transaction::where('shop_id', $shop_id)->where('transaction_id', $transaction_id)->update($update);
         $return = 1;
      }

      return $return;
   }
   public static function get_payment_method($shop_id, $archived = 0)
   {
    return Tbl_payment_method::where("archived",$archived)->where("shop_id", $shop_id)->get();
   }
}