<?php
namespace App\Globals;
use App\Globals\Item;
use App\Globals\Ecom_Product;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_cart;
use App\Models\Tbl_cart_info;
use App\Models\Tbl_transaction_item;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_cart_item_pincode;	
use App\Models\Tbl_cart_payment;	
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use Session;
use Carbon\Carbon;
use App\Globals\Currency;
use stdClass;
use Schema;
class Cart2
{
	public static function scan_item($shop_id, $id)
	{
		$_item = Tbl_item::where("shop_id", $shop_id)->active()->where("item_id", $id)->first();
		return $_item;
	}
	public static function set_cart_key($key)
	{
		Session::put("cart_key", $key);
	}
	public static function get_cart_key()
	{
		return session("cart_key");
	}
	public static function scan_payment($shop_id, $payment_type = '', $payment_amount = 0)
	{
		$return = null;
		if($payment_type != '' && $payment_amount != 0)
		{
			$cart_key = Self::get_cart_key();
			$ins_payment['shop_id'] = $shop_id;
			$ins_payment['unique_id_per_pc'] = $cart_key;
			$ins_payment['payment_type'] = $payment_type;
			$ins_payment['payment_amount'] = $payment_amount;

			$return = Tbl_cart_payment::insertGetId($ins_payment);
		}
		return $return;
	}

	public static function load_payment($shop_id)
	{
		$cart_key = Self::get_cart_key();
		return Tbl_cart_payment::where('shop_id',$shop_id)->where('unique_id_per_pc',$cart_key)->get();
	}
	public static function remove_payment($cart_payment_id = 0)
	{
		return Tbl_cart_payment::where('cart_payment_id',$cart_payment_id)->delete();
	}
	public static function set($key, $value)
	{
		$cart_key = Self::get_cart_key();

		if(Schema::hasColumn('tbl_cart_info', $key))
		{
			$check_exist 		= Tbl_cart_info::where("unique_id_per_pc", $cart_key)->first();
			$return["status"] 	= "success";

			if($check_exist)
			{
				$update[$key] 	= $value;
				Tbl_cart_info::where("unique_id_per_pc", $cart_key)->update($update);
			}
			else
			{
				$insert[$key] 	= $value;
				$insert["unique_id_per_pc"] = $cart_key;
				Tbl_cart_info::insert($insert);
			}
			
			return $return;
		}
		else
		{
			$return["status"] 	= "error";
			$return["message"] 	= "The key you are trying to use doesn't exist.";
		}

		return $return;
	}

	public static function set_payment_method($payment_method_id)
	{
	}
	public static function set_payment_method_information($payment_method_info)
	{
	}
	public static function add_item_to_cart($shop_id, $item_id, $quantity, $change_qty = false)
	{
		$cart_key = Self::get_cart_key();

		if($cart_key)
		{
			$check_cart = Tbl_cart::where("unique_id_per_pc", $cart_key)->where("product_id", $item_id)->where("status", "Not Processed")->first();

			if($check_cart) //ITEM EXIST IN CART
			{
				$update["quantity"] = $check_cart->quantity + $quantity;
				if($change_qty == true)
				{
					$update["quantity"] = $quantity;
				}
				Tbl_cart::where("cart_id", $check_cart->cart_id)->update($update);
			}
			else //ITEM DOES NOT EXIST IN CART
			{
				$insert["product_id"] = $item_id;
				$insert["quantity"] = $quantity;
				$insert["unique_id_per_pc"] = $cart_key;
				$insert["status"] = "Not Processed";
				$insert["date_added"] = Carbon::now();
				$insert["shop_id"] = $shop_id;
				Tbl_cart::insert($insert);
			}
		}
	}
	public static function scan_pin_code($shop_id, $warehouse_id, $pin_code)
	{
		$return = 0;
		$pincode = explode('@',$pin_code);
		// $pin = $pin_code;

		$pin = null;
		$code = null;
		if(isset($pincode[1]))
		{
			$pin = $pincode[0];
			$code = $pincode[1];
		}
		else
		{
			$pin = $pin_code;
		}

		$return = Cart2::search_pin_code($shop_id, $warehouse_id, $pin, $code);

		return $return;
	}
	public static function search_pin_code($shop_id, $warehouse_id, $pin = '', $code = '')
	{
		$return = null;

		$get_item = Tbl_warehouse_inventory_record_log::where('record_shop_id',$shop_id)
													  ->where('record_warehouse_id',$warehouse_id)
													  ->where('mlm_pin',$pin)->first();
		if($get_item)
		{
			if($get_item->record_inventory_status == 0 && $get_item->item_in_use == 'unused')
			{
				$cart_key = Self::get_cart_key();
				if($cart_key)
				{
					$pin_code = $get_item->mlm_pin.'@'.$get_item->mlm_activation;
					$check_cart = Tbl_cart_item_pincode::where("unique_id_per_pc", $cart_key)->where('shop_id',$shop_id)->where('pincode',$pin_code)->where("product_id",$get_item->record_item_id)->count();
					if($check_cart == 0) //ITEM DON'T EXIST IN CART
					{
						$ins['unique_id_per_pc'] = $cart_key;
						$ins['shop_id'] 		 = $shop_id;
						$ins['product_id'] 		 = $get_item->record_item_id;
						$ins['pincode'] 		 = $pin_code;
						Tbl_cart_item_pincode::insert($ins);

						$return = $get_item->record_item_id;
					}
					else
					{
						$return = "Item Already in the cart.";
					}
				}
			}
			else
			{
				$return = "Item Already consumed or used.";
			}
		}
		return $return;
	}

    public static function scan_reserved_code($shop_id, $customer_id)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        $get_reserved_item = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','reserved')->where('record_consume_ref_id',$customer_id)->where('record_warehouse_id',$warehouse_id)->where('record_shop_id',$shop_id)->get();
        $return = null;
        if(count($get_reserved_item) > 0)
        {
            foreach ($get_reserved_item as $key => $value) 
            {
                Cart2::scan_pin_code($shop_id, $warehouse_id, $value->mlm_pin);
                Cart2::add_item_to_cart($shop_id, $value->record_item_id, 1);
            }
            $return = count($get_reserved_item);
			Session::put("reserved_item", $return);
        }

        return $return;
    }
	public static function scan_ref_num($shop_id, $warehouse_id, $ref_num)
	{
		$return = null;
		$refnum = explode('-',$ref_num);
		if(isset($refnum[0]))
		{
			if($refnum[0] == 'REFNUM')
			{
				if(isset($refnum[1]))
				{
					$record_log_id = $refnum[1];

					$get_item = Tbl_warehouse_inventory_record_log::where('record_shop_id',$shop_id)
																  ->where('record_warehouse_id',$warehouse_id)
																  ->where('record_log_id',$record_log_id)
																  ->first();
					if($get_item)
					{
						if($get_item->record_inventory_status == 0 && $get_item->item_in_use == 'unused')
						{
							$cart_key = Self::get_cart_key();
							if($cart_key)
							{
								$pin_code = $get_item->mlm_pin.'@'.$get_item->mlm_activation;
								$check_cart = Tbl_cart_item_pincode::where("unique_id_per_pc", $cart_key)->where('shop_id',$shop_id)->where('pincode',$pin_code)->where("product_id",$get_item->record_item_id)->count();
								if($check_cart == 0) //ITEM DON'T EXIST IN CART
								{
									$ins['unique_id_per_pc'] = $cart_key;
									$ins['shop_id'] 		 = $shop_id;
									$ins['product_id'] 		 = $get_item->record_item_id;
									$ins['pincode'] 		 = $get_item->mlm_pin.'@'.$get_item->mlm_activation;
									Tbl_cart_item_pincode::insert($ins);

									$return = $get_item->record_item_id;
								}
								else
								{
									$return = "Item Already in the cart.";
								}
							}
						}
						else
						{
							$return = "Item Already consumed or used.";
						}
					}			
				}				
			}
		}


		return $return;
	}
	public static function get_item_pincode($shop_id, $item_id)
	{
		$cart_key = Self::get_cart_key();
		$return = null;
		if($cart_key)
		{
			$return = Tbl_cart_item_pincode::where("unique_id_per_pc", $cart_key)->where('shop_id',$shop_id)->where("product_id",$item_id)->get();
		}

		return $return;
	}
	public static function edit_item_from_cart($item_id, $quantity)
	{
		$cart_key = Self::get_cart_key();
		$update["quantity"] = $quantity;
		Tbl_cart::where("unique_id_per_pc", $cart_key)->where("product_id", $item_id)->where("status", "Not Processed")->update($update);
	}
	public static function delete_item_from_cart($item_id)
	{
		$cart_key = Self::get_cart_key();
		Tbl_cart::where("unique_id_per_pc", $cart_key)->where("product_id", $item_id)->where("status", "Not Processed")->delete();
		Tbl_cart_item_pincode::where("unique_id_per_pc", $cart_key)->where("product_id", $item_id)->delete();
	}
	public static function clear_item()
	{
	}
	public static function get_cart_info($customer_id = null)
	{
		$cart_key    = Self::get_cart_key();
		$total       = 0;
		$grand_total = 0;
		$_cart       = null;
		
		if($cart_key)
		{
			$_cart 			= Tbl_cart::where("unique_id_per_pc", $cart_key)->where("status", "Not Processed")->get();
			$cart_info 		= Tbl_cart_info::where("unique_id_per_pc", $cart_key)->first();

			if(!$cart_info)
			{
				$cart_info 					= new stdClass();
				$cart_info->price_level_id 	= 0;
				$cart_info->global_discount = 0;
				$cart_info->shipping_fee 	= 0;
			}
 		}

		if(count($_cart) < 1)
		{
			return null;
		}
		else
		{
			/* DISCARD IN CART IF NO ITEM INFO */
			foreach($_cart as $key => $cart)
			{
				$item_info 							= Item::info($cart->product_id);
				
				if(!$item_info)
				{
					unset($_cart[$key]);
				}
			}

			foreach($_cart as $key => $cart)
			{
				Item::get_apply_price_level($cart_info->price_level_id);
				
				$item_info 							= Item::info($cart->product_id);
				
				if ($item_info) 
				{
					$_cart[$key] 						= $cart;
					$_cart[$key]->item_id 				= $item_info->item_id;
					$_cart[$key]->item_name 			= $item_info->item_name;
					$_cart[$key]->item_sku 				= $item_info->item_sku;
					$_cart[$key]->item_price 			= $item_info->item_price;

					if ($customer_id) 
		            {
		            	$price_level = Tbl_mlm_slot::priceLevel($item_info->item_id)->where("tbl_mlm_slot.slot_owner", $customer_id)->first();

		            	if ($price_level) 
		            	{
		            		$_cart[$key]->item_price = $price_level->custom_price;
		            	}
		            	else
		            	{
		         			$shop_id = Tbl_customer::where("customer_id", $customer_id)->value("shop_id");

		         			if ($shop_id) 
		         			{
		         				$membership_discount = Ecom_Product::getMembershipPrice($shop_id, $customer_id, $item_info->item_id, $item_info->item_price);
			            		$_cart[$key]->item_price = $membership_discount;
		         			}
		            	}
		            }
		            
					$_cart[$key]->discount 				= 0;
					$_cart[$key]->subtotal 				= $_cart[$key]->item_price * $cart->quantity;
					$_cart[$key]->display_item_price 	= Currency::format($_cart[$key]->item_price);
					$_cart[$key]->display_subtotal 		= Currency::format($_cart[$key]->subtotal);
					$_cart[$key]->pin_code 				= null;

					$total += $_cart[$key]->subtotal;
				}
			}

			$grand_total = $total;

			if($cart_info->global_discount == 0) //global discount computation
			{
				$global_discount 			= 0;
				$discount_label 			= "";	
			}
			else
			{
				if($cart_info->global_discount < 1)
				{
					$discount_label 		= number_format($cart_info->global_discount * 100, 0)  . "% DISCOUNT";
					$global_discount 		= $total * $cart_info->global_discount;
				}
				else
				{
					$discount_label 		= "FIXED DISCOUNT";
					$global_discount 		= $cart_info->global_discount;
				}

				$grand_total 				= $grand_total - $global_discount;
			}

			$grand_total 								= $grand_total + $cart_info->shipping_fee; // Shipping Fee

			$data["_item"] 								= $_cart;
			$data["_total"] 							= new stdClass();
			$data["_total"]->total 						= $total;
			$data["_total"]->grand_total 				= $grand_total;
			$data["_total"]->global_discount			= $global_discount;
			$data["_total"]->display_discount_label		= $discount_label;

			$data["_total"]->display_total 				= Currency::format($total);
			$data["_total"]->display_global_discount	= Currency::format($global_discount);
			$data["_total"]->display_grand_total 		= Currency::format($grand_total);

			$data["info"]								= $cart_info;
			return $data;
		}
	}
	public static function get_pincode($cart_key, $item_id)
	{
		$data = Tbl_cart_item_pincode::where('unique_id_per_pc',$cart_key)->where('product_id',$item_id)->get();
		$return = null;
		foreach ($data as $key => $value) 
		{
			$pincode = explode('@', $value->pincode);
			$return[$key]['pin'] = $pincode[0];
			$return[$key]['code'] = $pincode[1];
		}
		return $return;
	}
	public static function get_cart_quantity()
	{
		$quantity = 0;
		$cart_key = Self::get_cart_key();

		if($cart_key)
		{
			$_cart = Tbl_cart::where("unique_id_per_pc", $cart_key)->where("status", "Not Processed")->get();
 		}

		if(isset($_cart) && count($_cart) > 0)
		{
			foreach($_cart as $key => $cart)
			{
				$quantity += $cart->quantity;
			}
		}
		else
		{
			$quantity = 0;
		}

		return $quantity;
	}
	public static function clear_cart()
	{
		$cart_key = Self::get_cart_key();
		Tbl_cart::where("unique_id_per_pc", $cart_key)->delete();
		Tbl_cart_item_pincode::where("unique_id_per_pc", $cart_key)->delete();
		Tbl_cart_payment::where("unique_id_per_pc", $cart_key)->delete();
		Session::forget('reserved_item');
	}
	public static function cart_payment_amount($shop_id ,$type = '')
	{
        $cart_key = Self::get_cart_key();
		$amount = Tbl_cart_payment::where('unique_id_per_pc',$cart_key)->where('shop_id',$shop_id);
		if($type != '')
		{
			$amount = $amount->where('payment_type',$type);
		}
		return $amount->sum('payment_amount');
	}
	public static function cart_payment_list($shop_id )
	{
        $cart_key = Self::get_cart_key();
		return Tbl_cart_payment::where('unique_id_per_pc',$cart_key)->where('shop_id',$shop_id)->get();
	}
	public static function validate_cart()
	{
	}
	public static function process_cart()
	{
	}
	public static function store_customer_basic($first_name, $middle_name, $last_name, $gender)
	{
	}
	public static function store_customer_account($email, $password)
	{
	}
	public static function store_customer_country($country_id)
	{
	}
	public static function store_customer_birthday($birthday)
	{
	}
	public static function store_customer_others($others)
	{
	}
	public static function save_customer($shop_id)
	{
	}
	public static function clear_customer()
	{
	}
	public static function copy_item_from_cart_transaction($shop_id, $transaction_list_id)
	{
		Self::clear_cart();
		
        $_item = Tbl_transaction_item::where("transaction_list_id", $transaction_list_id)->get();
        
        foreach($_item as $item)
        {
            Self::add_item_to_cart($shop_id, $item->item_id, $item->quantity);
        }
	}
}