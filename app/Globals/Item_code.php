<?php
namespace App\Globals;

use App\Models\Tbl_item;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_mlm_gc;
use App\Models\Tbl_item_code_item;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_ec_order;
use App\Models\Tbl_ec_order_item;
use App\Globals\Mlm_voucher;
use App\Globals\Item;
use App\Globals\Item_code;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_gc;
use App\Globals\AuditTrail;
use App\Globals\Mlm_slot_log;
use DB;
use Session;
use Carbon\Carbon;
use Validator;
use App\Models\Tbl_email_template;
use App\Globals\EmailContent;
use App\Globals\Mlm_plan;
use App\Models\Tbl_mlm_item_points;
use App\Globals\Ec_order;
use Mail;
use App\Globals\Accounting;
use App\Globals\Merchant;
class Item_code
{
	public static function add_code($data,$shop_id, $user_id, $warehouse_id)
	{ 
        ignore_user_abort(true);
        set_time_limit(0);
        flush();
        ob_flush();
        session_write_close();
        /// CODE HERE
        $go_serial = 0;
        if(isset($data['item_serial_enable']))
        {
            if(isset($data['item_serial']))
            {
                foreach($data["item_id"] as $key => $value)
                {
                    if(isset($data['item_serial'][$value]))
                    {
                        foreach($data['item_serial'][$value] as $key2 => $value2)
                        {
                            $check_serial = Tbl_inventory_serial_number::where('serial_number', $value2)->where('item_consumed', 0)->where("archived",0)->count();
                            if($check_serial >= 1)
                            {
                                $check_serial_first = Tbl_inventory_serial_number::where('serial_number', $value2)->where('item_consumed', 0)->where("archived",0)->first();
                                // return $check_serial_first;
                                if($check_serial_first->item_id == $value)
                                {
                                    $go_serial = 1;
                                }
                                else
                                {
                                    $send['response_status']      = "warning";
                                    $send['warning_validator'][0] = "Sorry, the serial : " . $value2 . ' does not belong to the item.';
                                    return $send;
                                }
                            }
                            else
                            {
                                $send['response_status']      = "warning";
                                $send['warning_validator'][0] = "Sorry, we can't find the serial : " . $value2;
                                return $send;
                            }
                        }
                    }
                    
                }
            }
            else
            {

            }
        }
        if(!isset($data["customer_id"]))
        {
            if(!isset($data["slot_id"]))
            {
                if(!isset($data['discount_card_log_id']))
                {
                    $send['response_status']      = "warning";
                    $send['warning_validator'][0] = "Invalid Customer/Slot/Discount Card";
                    return $send;
                }
                else
                {
                    $dis = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->first();
                    if($dis->discount_card_log_issued_date == null)
                    {
                        $update_dis['discount_card_log_date_expired'] = Carbon::now()->addYear(1);
                        $update_dis['discount_card_log_issued_date'] = Carbon::now();
                        Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->update($update_dis);
                    }

                    $dis = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->first();
                    if($dis->discount_card_log_date_expired != null)
                    {
                        if($dis->discount_card_log_date_expired <= Carbon::now())
                        {
                            $update_dis['discount_card_log_is_expired'] = 1;
                            Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->update($update_dis);

                            $send['response_status']      = "warning";
                            $send['warning_validator'][0] = "Discount Card Is Already Expired a";
                            return $send; 
                        }
                    }


                    $slot = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->value('discount_card_membership');
                    $insert['slot_id'] = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->value('discount_card_slot_sponsor'); 
                    $insert['discount_card_log_id'] = $data['discount_card_log_id'];
                }
            }
            else
            {
                $slot = Tbl_mlm_slot::where('slot_id', $data["slot_id"])->value('slot_membership');
                $insert['slot_id'] = $data["slot_id"];
            }
        }
        else
        {
            if($data["slot_id"] == null)
            {
                if($data['discount_card_log_id'] == null)
                {
                    $send['response_status']      = "warning";
                    $send['warning_validator'][0] = "Invalid Customer/Slot/Discount Card";
                    return $send;
                }
                else
                {
                   

                    $dis = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->first();
                    if($dis->discount_card_log_issued_date == null)
                    {
                        $update_dis['discount_card_log_date_expired'] = Carbon::now()->addYear(1);
                        $update_dis['discount_card_log_issued_date'] = Carbon::now();
                        Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->update($update_dis);
                    }
                    $dis = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->first();
                    if($dis->discount_card_log_date_expired != null)
                    {
                        if($dis->discount_card_log_date_expired <= Carbon::now())
                        {
                            $update_dis['discount_card_log_is_expired'] = 1;
                            Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->update($update_dis);

                            $send['response_status']      = "warning";
                            $send['warning_validator'][0] = "Discount Card Is Already Expired b";
                            return $send; 
                        }
                    }

                    $slot = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->value('discount_card_membership');
                    $insert['slot_id'] = Tbl_mlm_discount_card_log::where('discount_card_log_id', $data['discount_card_log_id'])->value('discount_card_slot_sponsor'); 
                    $insert['discount_card_log_id'] = $data['discount_card_log_id'];
                }
            }
            else
            {
                $slot = Tbl_mlm_slot::where('slot_id', $data["slot_id"])->value('slot_membership');
                $insert['slot_id'] = $data["slot_id"];
            }
        }
        if(!isset($data["item_code_customer_email"]))
        {
            $send['response_status']      = "warning";
            $send['warning_validator'][0] = "Email is required";
            return $send;
        }
        $gc = 0;
        $gc_amount = 0;
        $wallet = 0;
        $wallet_amount = 0;
        $tendered = 0;
        $tendered_amount = 0;
        $data['response_status']      = "warning";

        if(isset($data['payment_type_choose']))
        {
            if($data['payment_type_choose'] == '3')
            {
                if(isset($data["slot_id"]))
                {
                    $wallet = 1;
                    $wallet_amount = Mlm_slot_log::get_sum_wallet($data["slot_id"]);
                    $tendered_amount = $wallet_amount;
                }
                else
                {
                    $send['response_status']      = "warning";
                    $send['warning_validator'][0] = "No Slot/Discount Card Selected";
                    return $send;
                }
            }
            else if($data['payment_type_choose'] == '2')
            {
                if($data['payment_value'] != null)
                {
                    $gc = Tbl_mlm_gc::where('mlm_gc_code', $data['payment_value'])->first();
                    if(isset($gc->mlm_gc_slot))
                    {
                        if($data["slot_id"] == $gc->mlm_gc_slot)
                        {
                            if($gc->mlm_gc_used == 0)
                            {
                                $update_gc['mlm_gc_used'] = 1;
                                $update_gc['mlm_gc_used_date'] = Carbon::now();
                                $gc_amount = $gc->mlm_gc_amount;
                                $gc = 1;
                                $tendered_amount = $gc_amount;
                            }
                            else
                            {
                                $send['response_status']      = "warning";
                                $send['warning_validator'][0] = "GC, Already Used.";
                                return $send;
                            }
                            
                        }
                        else
                        {
                            $send['response_status']      = "warning";
                            $send['warning_validator'][0] = "GC, does not belong to the account.";
                            return $send;
                        }
                    }
                    else
                    {
                        $send['response_status']      = "warning";
                        $send['warning_validator'][0] = "Invalid GC Code";
                        return $send;
                    }
                    
                }
                else
                {
                    $send['response_status']      = "warning";
                    $send['warning_validator'][0] = "Invalid GC Code";
                    return $send;
                }
            }
            else if($data['payment_type_choose'] == '1')
            {
                $tendered = 1;
                $tendered_amount = $data['payment_value'];
            }
            else if($data['payment_type_choose'] == 4)
            {
                $tendered = 4;
                $tendered_amount = $data['payment_value'];
            }
            else
            {
                $send['response_status']      = "warning";
                $send['warning_validator'][0] = "Invalid Payment Type";
                return $send;
            }

        }
        else
        {
            $send['response_status']      = "warning";
            $send['warning_validator'][0] = "Invalid Payment Type";
            return $send;
        }
        if($gc  ==1 && $wallet == 1)
        {
            $send['response_status']      = "warning";
            $send['warning_validator'][0] = "Can't Use Wallet and GC at the Same Time";
            return $send;
        }
        $insert['customer_id']                             = $data["customer_id"];
        $insert['item_code_customer_email']                = $data["item_code_customer_email"];
        $insert['item_code_paid']                          = $data["item_code_paid"];
        $insert['item_code_product_issued']                = $data["item_code_product_issued"];
        $insert['item_code_date_created']                  = Carbon::now();
        $insert['shop_id']                                 = $shop_id;
        // $insert['slot_id']                                 = $data["slot_id"];
        $discount_card_log_id = $data['discount_card_log_id'];

        $rules['customer_id']                              = 'required|exists:tbl_customer,customer_id|';
        $rules['item_code_customer_email']                 = 'required|email';
        $rules['item_code_paid']                           = 'required|between:0,1';
        $rules['item_code_product_issued']                 = 'required|between:0,1';   
        // $rules['slot_id']                                  = 'required|exists:tbl_mlm_slot,slot_id';

        $messages['customer_id.required']                  = 'Customer is required.';
        $messages['customer_id.exists']                    = 'Something wrong on customers list.';
        // $messages['slot_id.exists']                         = 'Slot Does not Exists';
        $messages['item_code_customer_email.required']     = 'Customer email is required.'; 
        $messages['item_code_customer_email.email']        = 'Please use a proper format for customer email.';
        $messages['item_code_paid.required']               = 'Something wrong on product code paid.';
        $messages['item_code_paid.between']                = 'Something wrong on product code paid.';
        $messages['item_code_product_issued.required']     = 'Something wrong on product code issued.';
        $messages['item_code_product_issued.between']      = 'Something wrong on product code issued.';
        
        $item_subtotal            = 0;
        $item_total               = 0;
        $item_discount            = 0;
        $item_discount_percentage = 0;

    	$validator = Validator::make($insert,$rules,$messages);
    	if ($validator->passes())
    	{
    	    if(isset($data["item_id"]) && isset($data["quantity"]))
    	    {
        	    if(count($data["item_id"]) > 0)
        	    {   
        	        $ctr = 0;
        	        $ctr_error = 0;
                    $item_discount_sum = 0;
                    $item_subtotal_sum = 0;
        	        // $slot = Tbl_mlm_slot::where('slot_id', $insert['slot_id'])->value('slot_membership');
                    if($data['warehouse_id'] == null)
                    {
                        $send['warning_validator'][$ctr_error] = "Warehouse is required. For inventory";
                        $ctr_error = 1;
                    }
        	        /* SETUP THE VARIABLES FOR INSERTING MEMBERSHIP CODE */
                    $count_on_hand = [];
        	        foreach($data["item_id"] as $key => $item)
        	        {
                        if(isset($count_on_hand[$item]))
                        {
                            $count_on_hand[$item]+= $data["quantity"][$key];
                        }
                        else
                        {
                            $count_on_hand[$item] = $data["quantity"][$key];
                        }
        	            for($generated = 0; $generated < $data["quantity"][$key]; $generated++)
        	            {
        	                
        	                /* FOR ACTIVATION KEY */
        	                $condition = false;
        	                while($condition == false)
        	                {
        	                   $activation_code  = Item_code::random_code_generator(8);
        	                   $check_activation = Tbl_item_code::where("item_activation_code",$activation_code)->first();
        	                   if(!$check_activation)
        	                   {
        	                       $condition = true;
        	                   }
        	                }
        	                
        	                $code_pin                                                = Tbl_item_code::where("item_code_pin",$shop_id)->count() + 1; 
        	                $items                                                   = Tbl_item::where("item_id",$data["item_id"][$key])->where("shop_id",$shop_id)->first();
            	            if(!$items)
            	            {
                                    $send['warning_validator'][$ctr_error] = "Item #".$data["item_id"][$key]." does not exists.";
                                    $ctr_error++;
            	            }
            	            else
            	            {  

                                $item_subtotal                                     = intval($item_subtotal) + intval($items->item_price);
                	            $item_discount                                     = Item::get_discount_only($data["item_id"][$key], $slot);
                                $item_discount_sum += $item_discount;
                                $item_subtotal_sum += $items->item_price;
                                $rel_insert[$ctr]["item_activation_code"]          = $activation_code;
                	            $rel_insert[$ctr]["customer_id"]                   = $data["customer_id"];
                	            $rel_insert[$ctr]["item_id"]                       = $data["item_id"][$key];
                	            $rel_insert[$ctr]["item_code_invoice_id"]          = null;
                	            $rel_insert[$ctr]["shop_id"]                       = $shop_id;
                                $rel_insert[$ctr]["item_code_pin"]                 = $code_pin;
                                $rel_insert[$ctr]["item_code_price"]               = $items->item_price;
                	            $rel_insert[$ctr]["item_code_price_total"]         = $items->item_price;
            	            }
            	            
            	         	$ctr++;
        	            }
        	        }
                    // $send['response_status']      = "warning";
                    // $send['warning_validator'][0] = $count_on_hand;
                    // return $send;
        	        foreach($count_on_hand as $key => $value)
                    {
                        $a = Tbl_warehouse_inventory::check_inventory_single($data['warehouse_id'], $key)->value('inventory_count');
                        
                        if(intval($value) > intval($a))
                        {
                            $item = Tbl_item::where('item_id', $key)->first();
                            if($item->item_type_id == 1)
                            {
                                $send['warning_validator'][$ctr_error] = "Item: " . $item->item_name . ' has only ' . $a . ' while ' . $value .' is the stock needed' ;
                                $ctr_error++;
                            }
                            
                        }   
                    }

        	        /* PROCEED IF NO ERROR */
        	        if($ctr_error == 0)
        	        {
                        $insert["item_subtotal"]            = $item_subtotal_sum;
                        $insert["item_total"]               = $item_subtotal_sum - $item_discount_sum;
                        $insert["item_discount"]            = $item_discount_sum;
                        if($gc == 1)
                        {
                            if($gc_amount >= $insert["item_total"])
                            {
                                $insert['item_code_paid'] = 2;
                                $gc = 2;
                            }
                            else
                            {
                                $send['response_status']      = "warning";
                                $send['warning_validator'][0] = 'Gc Amount is only ' . $gc_amount . ' while the needed amount is ' . $insert["item_total"];
                                return $send;
                            }
                        }
                        if($wallet == 1)
                        {
                            if($wallet_amount >= $insert["item_total"])
                            {
                                $insert['item_code_paid'] = 3;
                                $wallet = 2;
                            }
                            else
                            {
                                $send['response_status']      = "warning";
                                $send['warning_validator'][0] = 'Wallet Amount is only ' . $wallet_amount . ' while the needed amount is ' . $insert["item_total"];
                                return $send;
                            }
                        }
                        

                        $insert["item_discount_percentage"] = (1 - ($insert["item_total"]  / $insert["item_subtotal"])) * 100;

                        $insert['item_code_payment_type'] = $data['payment_type_choose'];
                        $insert['item_code_tendered_payment'] = $tendered_amount;
                        $insert['item_code_change'] =$tendered_amount -  $insert["item_total"];
                        $insert['warehouse_id'] = $warehouse_id;
                        $insert['user_id'] = $user_id;
                        
                        if($insert['item_code_change'] < 0)
                        {
                            $send['response_status']      = "warning";
                             $send['warning_validator'][0] = 'Tendered Amount is less than total purchase amount';
                             return $send;
                        }
        	            /* INSERTING AREA */
        	            $invoice_id = Tbl_item_code_invoice::insertGetId($insert);

        	            /* SETUP THE INVOICE ID */
        	            foreach($rel_insert as $key => $item_code_invoice_id)
        	            {
        	                $rel_insert[$key]["item_code_invoice_id"] = $invoice_id;
                            $rel_insert[$key]["slot_id"] = $insert['slot_id'];
                            $active_plan_product_repurchase = Mlm_plan::get_all_active_plan_repurchase($shop_id);
                            $item_points = Tbl_mlm_item_points::where('item_id', $rel_insert[$key]['item_id'])->where('membership_id', $slot)->first();
                            
                            foreach($active_plan_product_repurchase as $key2 => $value)
                            {
                                $code = $value->marketing_plan_code;
                                if(isset($item_points->$code))
                                {
                                    if($code == "STAIRSTEP")
                                    {
                                        $rel_insert[$key][$code]             = $item_points->$code;
                                        $rel_insert[$key]["STAIRSTEP_GROUP"] = $item_points->STAIRSTEP_GROUP;
                                    }
                                    else if($code == "RANK")
                                    {
                                        $rel_insert[$key][$code]        = $item_points->$code;
                                        $rel_insert[$key]["RANK_GROUP"] = $item_points->RANK_GROUP;
                                    }
                                    else
                                    {
                                        $rel_insert[$key][$code] = $item_points->$code;
                                    }
                                }
                                
                            }
        	            }
        	            Tbl_item_code::insert($rel_insert);
        	            
                        $items = Tbl_item_code::where('item_code_invoice_id', $invoice_id)->get();
                        foreach($items as $key => $value)
                        {
                            $insert_item_per[$value->item_id]['item_id'] =  $value->item_id;
                            $item = Tbl_item::where('item_id', $value->item_id)->first();
                            if($item)
                            {
                                $insert_item_per[$value->item_id]['item_name'] = $item->item_name;
                                $insert_item_per[$value->item_id]['item_price'] = $item->item_price;  
                                $insert_item_per[$value->item_id]['item_code_invoice_id'] = $invoice_id;
                                $insert_item_per[$value->item_id]['item_code_id'] = $value->item_code_id;

                                if($go_serial == 1)
                                {
                                     $insert_item_per[$value->item_id]['item_serial'] = null;
                                     if(isset($data['item_serial'][$value->item_id]))
                                     {
                                        foreach($data['item_serial'][$value->item_id] as $key2 => $value2)
                                        {
                                            if($insert_item_per[$value->item_id]['item_serial'] == null)
                                            {
                                                $insert_item_per[$value->item_id]['item_serial'] .= $value2;
                                            }
                                            else
                                            {
                                                $insert_item_per[$value->item_id]['item_serial'] .= ', '. $value2;
                                            }
                                            $update_serail['item_consumed'] = 1;
                                            $update_serail['sold'] = 1;
                                            Tbl_inventory_serial_number::where('serial_number', $value2)->where('item_consumed', 0)->update($update_serail);
                                        }
                                    }
                                }
                                if(isset($insert_item_per[$value->item_id]['item_quantity']))
                                {
                                    $insert_item_per[$value->item_id]['item_quantity'] += 1;
                                }
                                else
                                {
                                    $insert_item_per[$value->item_id]['item_quantity'] = 1;
                                }
                            }
                            $insert_item_per[$value->item_id]['item_membership_discount'] = Item::get_discount_only($value->item_id, $slot);
                            $insert_item_per[$value->item_id]['item_membership_discounted'] = $insert_item_per[$value->item_id]['item_price'] - $insert_item_per[$value->item_id]['item_membership_discount'];
                        
                        }
                        Tbl_item_code_item::insert($insert_item_per);
                        Mlm_voucher::give_voucher_prod_code($invoice_id);
                        if($gc == 2)
                        {

                            $update_gc['mlm_gc_used'] = 1;
                            $update_gc['mlm_gc_used_date'] = Carbon::now();
                            Tbl_mlm_gc::where('mlm_gc_code', $data['payment_value'])->update($update_gc);

                            $return = $gc_amount - ($insert["item_total"]);
                            if($return >= 1)
                            {
                                Mlm_gc::return_gc($insert['slot_id'], $data['gc_code'], $return);
                            }
                        }
                        if($wallet == 2)
                        {
                            $log = 'Thank you for purchasing. ' .$insert["item_total"]. ' is deducted to your wallet';
                            $arry_log['wallet_log_slot'] = $insert['slot_id'];
                            $arry_log['shop_id'] = $shop_id;
                            $arry_log['wallet_log_slot_sponsor'] = $insert['slot_id'];
                            $arry_log['wallet_log_details'] = $log;
                            $arry_log['wallet_log_amount'] = ($insert["item_total"]) * (-1);
                            $arry_log['wallet_log_plan'] = "REPURCHASE";
                            $arry_log['wallet_log_status'] = "released";   
                            $arry_log['wallet_log_claimbale_on'] = Carbon::now();
                            Mlm_slot_log::slot_array($arry_log);
                        }
                        if(isset($data['use_item_code_auto']))
                        {
                            if($data['use_item_code_auto'] == 1)
                            {
                                Item_code::use_item_code_all_invoice($invoice_id);
                                
                                Item_code::set_up_email($invoice_id, $shop_id);
                            }
                            else
                            {

                            }
                        }
                        foreach ($count_on_hand as $key => $value) 
                        {
                            $warehouse_consume_reason = 'Customer ' . $data["customer_id"] . ' bought a product';
                            $iteme = Tbl_item::where('item_id', $key)->first();
                            $item = [];
                            if($iteme->item_type_id == 1)
                            {
                                $s = $key;
                                $d['product_id'] = $iteme->item_id;
                                $item[0]['product_id'] = $iteme->item_id;
                                $item[0]['quantity'] = $value;
                                // dd($item);
                                // dd($data["warehouse_id"]);
                               $a = Warehouse::inventory_consume($data['warehouse_id'], 'Used for consuming of inventory in product code', $item,$data["customer_id"], $warehouse_consume_reason, 'array');
                                if($a['status'] == 'error')
                                {
                                     $send['response_status']      = "warning";
                                     $send['warning_validator'][0] = $a['status_message'];
                                     return $send;
                                }
                            }
                        }
                        // Item_code::use_item_code_all_invoice($invoice_id);
        	            /* FORGET ALL SESSION FOR PURCHASED ITEM */
        	            Session::forget("sell_item_codes_session");
                        $send["response_status"] = "success_process";    
                        $send['invoice_id'] = $invoice_id;

                        Item_code::add_journal_entry($invoice_id);
                        Merchant::item_code_merchant_mark_up($invoice_id);
                        //audit trail here
                        $item_code_invoice = Tbl_item_code_invoice::where("item_code_invoice_id",$invoice_id)->first()->toArray();
                        AuditTrail::record_logs("Added","mlm_item_code_invoice",$invoice_id,"",serialize($item_code_invoice));
        	        }
        	        else
        	        {
                	    $send['response_status']      = "warning";
        	        }
        	    }
        	    else
        	    {
        	        $send['warning_validator'][0] = "Please add a item to purchase a code.";
        	    }
    	    }
    	    else
    	    {
        	    $send['response_status']      = "warning";
        	    $send['warning_validator'][0] = "Some error ocurred please try again.";
    	    }
    	}
    	else
    	{
    	    $send['response_status']   = "warning";
    	    $send['warning_validator'] = $validator->errors()->all();
    	}
        // sleep(2);        
        return $send;
        exit;

	}
    public static function add_journal_entry($invoice_id)
    {
        $invoice = Tbl_item_code_invoice::where('item_code_invoice_id', $invoice_id)->first();
        if($invoice)
        {

            $entry["reference_module"] = 'mlm-product-repurchase';
            $entry["reference_id"] = $invoice_id;
            $entry["name_id"] = $invoice->customer_id;
            $entry["total"] = $invoice->item_total;
            // $entry["discount"] = $invoice->item_discount;
            $entry["discount"] = 0;
            $items = Tbl_item_code_item::where('item_code_invoice_id', $invoice_id)->get();
            $entry_data = [];
            foreach ($items as $key => $value) 
            {
                # code...
                $entry_data[$key]['item_id'] = $value->item_id;
                $entry_data[$key]['discount'] = $value->item_membership_discount * $value->item_quantity;
                $entry_data[$key]['entry_amount'] = $value->item_price * $value->item_quantity;
            }
            //dd($entry_data);
            //dd($invoice_id);
           //dd($entry_data);

            Accounting::postJournalEntry($entry, $entry_data);
        }
        
    }
	public static function set_up_email($invoice_id, $shop_id)
    {
        $plan_settings = Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();
        if($plan_settings->plan_settings_email_product_code == 0)
        {
            return 1;
        }

        $invoice = Tbl_item_code_invoice::where('item_code_invoice_id', $invoice_id)
        ->leftjoin('tbl_customer', 'tbl_customer.customer_id','=', 'tbl_item_code_invoice.customer_id')
        ->leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')
        ->first();
        if($invoice)
        {
            if(isset($invoice->item_code_invoice_id))
            {
                if($invoice->item_code_customer_email != null)
                {
                    $data['invoice'] = $invoice;
                    $data['item_code'] = Tbl_item_code::where('item_code_invoice_id', $invoice_id)
                    ->get();

                    $change_content[0]["txt_to_be_replace"] = "[product_list]";
                    $change_content[0]["txt_to_replace"] = '<table><tr><td>Item</td><td>Quantity</td></tr>';
                    $data['item_code_item'] = Tbl_item_code_item::where('item_code_invoice_id', $invoice_id)->get();
                    foreach($data['item_code_item'] as $key => $value)
                    {
                        $change_content[0]["txt_to_replace"] .= '<tr><td>'.$value->item_name.'</td><td>'. $value->item_quantity.'</td></tr>';
                    }
                    $change_content[0]["txt_to_replace"] .= '</table>';

                    $change_content[1]["txt_to_be_replace"] = "[product_code]";
                    $change_content[1]["txt_to_replace"] = '<table><tr><td>Product Code</td><td>Pin</td></tr>';
                    foreach($data['item_code'] as $key => $value)
                    {
                        $change_content[1]["txt_to_replace"] .= '<tr><td>'.$value->item_activation_code.' </td><td>'. $value->item_code_id.'</td></tr>';
                    }
                    $change_content[1]["txt_to_replace"] .= '</table>';
                    // dd($change_content);
                    

                    $data["template"] = Tbl_email_template::where("shop_id",$shop_id)->first();

                    $content_key = 'product_repurchase';
                    $data['body'] = EmailContent::email_txt_replace($content_key, $change_content);
                    // return view('emails.full_body', $data);
                    
                    Mail::send('emails.full_body', $data, function ($m) use ($data) {
                    $m->from(env('MAIL_USERNAME'), $_SERVER['SERVER_NAME']);

                    $m->to($data['invoice']->item_code_customer_email, env('MAIL_USERNAME'))->subject('Product Repurchase')->cc('lukeglennjordan2@gmail.com');
                    });
                }
            }
        } 
    }
	public static function random_code_generator($word_limit)
	{
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $word_limit; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
	}
    public static function use_item_code_all_invoice($invoice_id)
    {

        $invoice = Tbl_item_code_invoice::where('item_code_invoice_id', $invoice_id)->first();

        if($invoice)
        {

            $item_code = Tbl_item_code::where('item_code_invoice_id', $invoice_id)->get();

            foreach($item_code as $key => $value)
            {
                $slot_info = Mlm_compute::get_slot_info($invoice->slot_id);
                if($value->used === 0)
                {
                    $a = Item_code::use_item_code_single($value, $slot_info);
                }
            }
        } 
        
    }
    public static function use_item_code_single($item_code, $slot_info)
    {
        $update["used"]          = 1;
        $update["date_used"]     = Carbon::now();
        $update["used_on_slot"]  = $slot_info->slot_id;
        Tbl_item_code::where('item_code_id', $item_code->item_code_id)->update($update);
        $a = Mlm_compute::repurchase($slot_info, $item_code->item_code_id);
    }
    public static function use_item_code_all_ec_order($order_id)
    {
        $item_code = Tbl_item_code::where('ec_order_id', $order_id)->get();
        if($item_code)
        {
            foreach ($item_code as $key => $code) {
                $slot = Mlm_compute::get_slot_info($code->slot_id);
                if($slot)
                {
                    Item_code::use_item_code_single($code, $slot);
                }
            }
        }
    }
    public static function completed_order_action($order_id)
    {
        Item_code::give_item_code_ec_order($order_id);
        Ec_order::create_merchant_school_item($order_id);
        // Item_code::merchant_school_active_codes($order_id);
        Item_code::ec_order_slot($order_id);
    }
    public static function ec_order_slot($order_id)
    {
        $order = DB::table('tbl_ec_order')->where('ec_order_id', $order_id)->first();
        $tbl_ec_order_slot = DB::table('tbl_ec_order_slot')->where('order_slot_ec_order_id', $order_id)->first();
        if($tbl_ec_order_slot)
        {
            if($tbl_ec_order_slot->order_slot_customer_id    != 0 &&  $tbl_ec_order_slot->order_slot_used           != 1)
            {
                $table_ec_order_slot = $tbl_ec_order_slot;
                $tbl_ec_order_item = DB::table('tbl_ec_order_item')->where('ec_order_id', $order_id)
                ->get();
                if($tbl_ec_order_item)
                {
                    foreach ($tbl_ec_order_item as $key => $order_item) 
                    {
                        $tbl_ec_variant = DB::table('tbl_ec_variant')
                                            ->where('evariant_id', $order_item->item_id)
                                            ->join("tbl_ec_product","eprod_id","=","evariant_prod_id")
                                            ->get();                                
                        if($tbl_ec_variant)
                        {
                            foreach ($tbl_ec_variant as $v_key => $v_value) 
                            {
                                if($v_value->ec_product_membership != 0)
                                {
                                    $shop_id = $order->shop_id; 
                                    $insert['slot_no'] = Mlm_plan::set_slot_no($shop_id, $v_value->ec_product_membership);
                                    $insert['shop_id'] = $shop_id;
                                    $insert['slot_owner'] = $tbl_ec_order_slot->order_slot_customer_id;
                                    $insert['slot_created_date'] = Carbon::now();
                                    $insert['slot_membership'] =    $v_value->ec_product_membership;
                                    $insert['slot_status'] = 'PS';
                                    if($tbl_ec_order_slot->order_slot_sponsor != 0)
                                    {
                                         // $insert['slot_placement'] = $tbl_ec_order_slot->order_slot_sponsor;
                                    }
                                    if($tbl_ec_order_slot->order_slot_sponsor != 0)
                                    {
                                         $insert['slot_sponsor'] = $tbl_ec_order_slot->order_slot_sponsor;
                                    }
                                    $id = Tbl_mlm_slot::insertGetId($insert);
                                    $a = Mlm_compute::entry($id);

                                    $update_s['order_slot_used'] = 1;
                                    DB::table('tbl_ec_order_slot')->where('order_slot_ec_order_id', $order_id)->update($update_s);

                                    Mlm_member::add_to_session_edit($shop_id, $tbl_ec_order_slot->order_slot_customer_id, $id);
                                }
                            }
                        }                 
                    }
                }
            }
        }
    }
    public static function insert_product_merchant_school($order_id)
    {

    }
    public static function merchant_school_active_codes($order_id)
    {
        $update['merchant_item_status'] = 1;

        $merchant_school_item = DB::table('tbl_merchant_school_item')->where('merchant_item_ec_order_id', $order_id)->get();
        foreach($merchant_school_item as $key => $value)
        {
            $all_wallet = DB::table('tbl_merchant_school_wallet')->where('merchant_school_custmer_id', $value->merchant_item_customer_id)->sum('merchant_school_amount');
            $insert['merchant_school_amount'] = $value->merchant_school_i_amount;
            // $insert['merchant_school_s_id'] = 
            // $insert['merchant_school_s_name'] = 
            if($all_wallet == null)
            {
                $all_wallet = 0;
            }
            $insert['merchant_school_remarks'] = 'Top up from E-commerce order';
            $insert['merchant_school_date'] = Carbon::now();
            $insert['merchant_school_custmer_id'] = $value->merchant_item_customer_id;
            $insert['merchant_school_amount_old'] = $all_wallet;
            $insert['merchant_school_amount_new'] = $all_wallet +  $value->merchant_school_i_amount;
            $insert['merchant_school_total_cash'] = $value->merchant_school_i_amount;
            // $insert['merchant_school_slot_id'] = 
            DB::table('tbl_merchant_school_wallet')->insert($insert);
        }
        // tbl_merchant_school_wallet
        DB::table('tbl_merchant_school_item')->where('merchant_item_ec_order_id', $order_id)->update($update);
    }
    public static function give_item_code_ec_order($ec_order_id)
    {
        ini_set('xdebug.max_nesting_level', 200);
        $order  = Tbl_ec_order::where("ec_order_id",$ec_order_id)->first();
        $order_id = $ec_order_id;
        if($order)
        {
            if($order->ec_order_slot_id == null)
            {
                $slot_id = Tbl_mlm_slot::where('slot_owner', $order->customer_id)->first();
                if($slot_id)
                {
                    $order->ec_order_slot_id = $slot_id->slot_id;
                    $update_ec_order['ec_order_slot_id'] = $slot_id->slot_id;
                    Tbl_ec_order::where("ec_order_id",$ec_order_id)->update($update_ec_order);
                }
            }
            if($order->ec_order_slot_id != null)
            {
                $slot_info = Mlm_compute::get_slot_info($order->ec_order_slot_id);
                $order_item = Tbl_ec_order_item::where('ec_order_id', $ec_order_id)
                ->join('tbl_ec_variant', 'tbl_ec_variant.evariant_id', '=', 'tbl_ec_order_item.item_id')
                ->get();
                $count = Tbl_item_code::where('ec_order_id', $ec_order_id)->count();
                $active_plan_product_repurchase = Mlm_plan::get_all_active_plan_repurchase($slot_info->shop_id);
                if($count == 0)
                {
                    foreach($order_item as $key => $value)
                    {
                        $condition = false;
                        while($condition == false)
                        {
                           $activation_code  = Item_code::random_code_generator(8);
                           $check_activation = Tbl_item_code::where("item_activation_code",$activation_code)->first();
                           $code_pin         = Tbl_item_code::where("item_code_pin",$slot_info->shop_id)->count() + 1;
                           if(!$check_activation)
                           {
                               $condition = true;
                           }
                        }
                        $item_points = Tbl_mlm_item_points::where('item_id', $value->evariant_item_id)->where('membership_id', $slot_info->slot_membership)->first();
                        // dd($value->item_id);
                        foreach($active_plan_product_repurchase as $key2 => $value2)
                        {
                            $code = $value2->marketing_plan_code;
                            if(isset($item_points->$code))
                            {
                                if($code == "STAIRSTEP")
                                {
                                    $rel_insert[$key][$code] = $item_points->$code;
                                    $rel_insert[$key]["STAIRSTEP_GROUP"] = $item_points->STAIRSTEP_GROUP;
                                }
                                else if($code == "RANK")
                                {
                                    $rel_insert[$key][$code] = $item_points->$code;
                                    $rel_insert[$key]["RANK_GROUP"] = $item_points->RANK_GROUP;      
                                }
                                else
                                {
                                    $rel_insert[$key][$code] = $item_points->$code;
                                }
                            }
                        }

                        $rel_insert[$key]["item_activation_code"]          = $activation_code;
                        $rel_insert[$key]["customer_id"]                   = $slot_info->slot_owner;
                        $rel_insert[$key]["item_id"]                       = $value->evariant_item_id;
                        $rel_insert[$key]["shop_id"]                       = $slot_info->shop_id;
                        $rel_insert[$key]["item_code_pin"]                 = $code_pin;
                        $rel_insert[$key]["item_code_price"]               = $value->price;
                        $rel_insert[$key]["item_code_price_total"]         = $value->total;
                        $rel_insert[$key]["ec_order_id"]                   = $ec_order_id;
                        $rel_insert[$key]["slot_id"]                       = $slot_info->slot_id;
                    }
                    
                    Tbl_item_code::insert($rel_insert);

                    $items = Tbl_item_code::where('ec_order_id', $ec_order_id)->get();
                    foreach($items as $key => $value)
                    {
                        $insert_item_per[$value->item_id]['item_id'] =  $value->item_id;
                        $item = Tbl_item::where('item_id', $value->item_id)->first();
                        if($item)
                        {
                            $insert_item_per[$value->item_id]['item_name'] = $item->item_name;
                            $insert_item_per[$value->item_id]['item_price'] = $item->item_price;  
                            $insert_item_per[$value->item_id]['item_code_id'] = $value->item_code_id;
                            if(isset($insert_item_per[$value->item_id]['item_quantity']))
                            {
                                $insert_item_per[$value->item_id]['item_quantity'] += 1;
                            }
                            else
                            {
                                $insert_item_per[$value->item_id]['item_quantity'] = 1;
                            }
                        }
                    }
                    Tbl_item_code_item::insert($insert_item_per);

                    Item_code::use_item_code_all_ec_order($order_id);
                }
            }
        }
    }
}