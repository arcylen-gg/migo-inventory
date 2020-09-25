<?php
namespace App\Globals;
use Mail;
use DB; 

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_package_has;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_item;
use App\Models\Tbl_email_template;
use App\Models\Tbl_mlm_plan_setting;

use App\Globals\Mlm_voucher;
use App\Globals\Membership_code;
use App\Globals\AuditTrail;

use Session;
use Carbon\Carbon;
use Validator;
use Crypt;
use App\Globals\Item;
use App\Globals\EmailContent;
use App\Globals\Mail_global;
use Config;
class Membership_code
{
	public static function add_code($data, $shop_id, $warehouse_id)
	{
        $data['warehouse_id'] =  $warehouse_id;
        // dd($data);
        $insert['customer_id']                                   = $data["customer_id"];
        $insert['membership_code_customer_email']                = $data["membership_code_customer_email"];
        $insert['membership_code_paid']                          = $data["membership_code_paid"];
        $insert['membership_code_product_issued']                = $data["membership_code_product_issued"];
        $insert['membership_code_date_created']                  = Carbon::now();
        $insert['shop_id']                                       = $shop_id;
        $insert['membership_code_invoice_f_name'] = $data['membership_code_customer_f_name'];
        $insert['membership_code_invoice_m_name'] = $data['membership_code_customer_m_name'];
        $insert['membership_code_invoice_l_name'] = $data['membership_code_customer_l_name']; 

        // $rules['customer_id']                                    = 'required|exists:tbl_customer,customer_id|';
        $rules['membership_code_customer_email']                 = 'required|email';
        $rules['membership_code_paid']                           = 'required|between:0,1';
        $rules['membership_code_product_issued']                 = 'required|between:0,1';   
        $rules['membership_code_invoice_f_name'] = "required";
        // $rules['membership_code_invoice_m_name'] = "required";
        $rules['membership_code_invoice_l_name'] = "required";    
        // $messages['customer_id.required']                        = 'Customer is required.';
        // $messages['customer_id.exists']                          = 'Something wrong on customers list.';
        $messages['membership_code_customer_email.required']     = 'Customer email is required.'; 
        $messages['membership_code_customer_email.email']        = 'Please use a proper format for customer email.';
        $messages['membership_code_paid.required']               = 'Something wrong on membership code paid.';
        $messages['membership_code_paid.between']                = 'Something wrong on membership code paid.';
        $messages['membership_code_product_issued.required']     = 'Something wrong on membership code issued.';
        $messages['membership_code_product_issued.between']      = 'Something wrong on membership code issued.';
        $messages['membership_code_invoice_f_name.required'] = "First Name Is Required";
        // $messages['membership_code_invoice_m_name'] = "Middle Name Is Required";
        $messages['membership_code_invoice_l_name.required'] = "Last Name Is Required"; 
        $membership_subtotal            = 0;
        $membership_total               = 0;
        $membership_discount            = 0;
        $membership_discount_percentage = 0;
        if($insert['membership_code_product_issued'] == 0)
        {
            if($insert['customer_id'] == null)
            {
                $send['response_status']      = "warning";
                $send['warning_validator'][0] = "Not issued products are only available to members";
                return $send;
            }
        }

    	$validator = Validator::make($insert,$rules,$messages);
    	if ($validator->passes())
    	{
    	    if(isset($data["membership_package"]) && isset($data["quantity"]) && isset($data["membership_type"]))
    	    {
        	    if(count($data["membership_package"]) > 0)
        	    {   
        	        $ctr = 0;
        	        $ctr_error = 0;
        	        if($data['warehouse_id'] == null)
                    {
                        $send['warning_validator'][$ctr_error] = "Warehouse is required.";
                        $ctr_error = 1;
                    }
        	        /* SETUP THE VARIABLES FOR INSERTING MEMBERSHIP CODE */
                    $count_on_hand = [];
        	        foreach($data["membership_package"] as $key => $membership)
        	        {
                        $membership   = Tbl_membership_package::where("membership_package_id",$data["membership_package"][$key])->membership()->first();
                        
                        if($membership)
                        {
                            if($membership->membership_package_is_gc == 0)
                            {
                                $package_has = Tbl_membership_package_has::where('membership_package_id', $membership->membership_package_id)->get();
                                
                                foreach($package_has as $key0 => $value0)
                                {
                                    foreach($package_has as $key0 => $value0)
                                    {
                                        $bundle = Item::get_item_bundle($value0->item_id);
                                        foreach($bundle['bundle'] as $key2 => $value2)
                                        {
                                            if(isset($count_on_hand[$value2['item_id']]))
                                            {
                                                $count_on_hand[$value2['item_id']] += $value2['bundle_qty']; 
                                            } 
                                            else
                                            {
                                                $count_on_hand[$value2['item_id']] = $value2['bundle_qty']; 
                                            }
                                            
                                        }
                                    }
                                }
                            }
                        }
                        
        	            for($generated = 0; $generated < $data["quantity"][$key]; $generated++)
        	            {
        	                
        	                /* FOR ACTIVATION KEY */
        	                $condition = false;
        	                while($condition == false)
        	                {
        	                   $activation_code  = Membership_code::random_code_generator(8);
        	                   $check_activation = Tbl_membership_code::where("membership_activation_code",$activation_code)->first();
        	                   if(!$check_activation)
        	                   {
        	                       $condition = true;
        	                   }
        	                }
        	                
        	                
        	                $code_pin = Tbl_membership_code::where("membership_code_pin",$shop_id)->count() + 1; 
        	                
            	            

                            if(!$membership)
            	            {
                                    $send['warning_validator'][$ctr_error] = "Membership Package #".$data["membership_package"][$key]." does not exists.";
                                    $ctr_error++;
            	            }
            	            else
            	            {
                                $membership_subtotal                                     = $membership_subtotal + $membership->membership_price;
                	            $rel_insert[$ctr]["membership_activation_code"]          = $activation_code;
                	            $rel_insert[$ctr]["customer_id"]                         = $data["customer_id"];
                	            $rel_insert[$ctr]["membership_package_id"]               = $data["membership_package"][$key];
                	            $rel_insert[$ctr]["membership_code_invoice_id"]          = null;
                	            $rel_insert[$ctr]["shop_id"]                             = $shop_id;
                	            $rel_insert[$ctr]["membership_code_pin"]                 = $code_pin;
                	            $rel_insert[$ctr]["membership_type"]                     = $data["membership_type"][$key];
                	            
                	            if($rel_insert[$ctr]["membership_type"] == "CD")
                	            {
                	                $rel_insert[$ctr]["membership_code_price"]           = 0 - $membership->membership_price;
                	            }
                	            else
                	            {
                	                $rel_insert[$ctr]["membership_code_price"]           = $membership->membership_price;
                	            }
            	            }
            	            
            	         	$ctr++;
        	            }
        	        }
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
                        foreach ($count_on_hand as $key => $value) 
                        {
                            $warehouse_consume_reason = 'Customer ' . $data["customer_id"] . ' bought a package';
                            $iteme = Tbl_item::where('item_id', $key)->first();
                            if($iteme->item_type_id == 1)
                            {
                                $item[0]['product_id'] = $key;
                                $item[0]['quantity'] = $value;
                                // dd($item);
                               $a = Warehouse::inventory_consume($data['warehouse_id'], 'Used for consuming of inventory in membership code', $item,$data["customer_id"], $warehouse_consume_reason, 'array');
                                if($a['status'] == 'error')
                                {
                                     $send['response_status']      = "warning";
                                     $send['warning_validator'][0] = $a['status_message'];
                                     return $send;
                                }
                            }
                        }
                        // Warehouse::inventory_consume($warehouse_id, $warehouse_consume_remarks, $warehouse_consume_product, $warehouse_consumer_id, $warehouse_consume_reason, $return_type);
                        $insert["membership_subtotal"]            = $membership_subtotal;
                        $insert["membership_total"]               = $membership_subtotal - $membership_discount;
                        $insert["membership_discount"]            = $membership_discount;
                        $insert["membership_discount_percentage"] = $membership_discount_percentage;
        	            /* INSERTING AREA */
        	            $invoice_id = Tbl_membership_code_invoice::insertGetId($insert);
        	            
        	            /* SETUP THE INVOICE ID */
        	            foreach($rel_insert as $key => $membership_code_invoice_id)
        	            {
        	                $rel_insert[$key]["membership_code_invoice_id"] = $invoice_id;
        	            }
                        // tbl_membership_code_item_has
                        
        	            Tbl_membership_code::insert($rel_insert);
                        $membership_code = Tbl_membership_code::where('membership_code_invoice_id', $invoice_id)->package()->get();
                        foreach ($membership_code as $key => $value) 
                        {
                            # code...
                            // $insert_item_has_code[$key]['membership_code_invoice_id'] = ;
                            // $insert_item_has_code[$key]['membership_code_invoice_id'] = ;
                            // $insert_item_has_code[$key]['shop_id'] = ;
                            // $insert_item_has_code[$key]['item_id'] = ;
                            // $insert_item_has_code[$key]['membership_code_item_has_name'] = ;
                            // $insert_item_has_code[$key]['membership_code_item_has_quantity'] = ;
                            // $insert_item_has_code[$key]['membership_code_item_has_price'] = ;
                        }
                        Mlm_voucher::give_voucher_mem_code($invoice_id);
                        Membership_code::set_up_mail($invoice_id, $shop_id);
        	            Session::forget("sell_codes_session");
                        $send["response_status"] = "success";    
                        $send['invoice_id'] = $invoice_id;

                        $invoice_code_data = Tbl_membership_code_invoice::customer()->where("membership_code_invoice_id",$invoice_id)->first()->toArray();
                        AuditTrail::record_logs("Added","mlm_membership_code_invoice",$invoice_id,"",serialize($invoice_code_data));
        	        }
        	        else
        	        {
                	    $send['response_status']      = "warning";
        	        }
        	    }
        	    else
        	    {
        	        $send['warning_validator'][0] = "Please add a membership package to purchase a code.";
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
        

        return $send;
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
    public static function inventory_consume()
    {

    }
    public static function set_up_mail($membership_code_invoice_id, $shop_id)
    {
        // return 1;
        $plan_settings = Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();
        if($plan_settings->plan_settings_email_membership_code == 0)
        {
            return 1;
        }

        $invoice = Tbl_membership_code_invoice::where('membership_code_invoice_id', $membership_code_invoice_id)
        ->leftjoin('tbl_customer', 'tbl_customer.customer_id','=', 'tbl_membership_code_invoice.customer_id')
        ->leftjoin('tbl_customer_other_info','tbl_customer_other_info.customer_id','=','tbl_customer.customer_id')
        ->first();
        if(isset($invoice->membership_code_invoice_id))
        {
            if($invoice->membership_code_customer_email != null)
            {
                $data['invoice'] = $invoice;
                $data['membership_code_invoice_id'] = Tbl_membership_code::where('membership_code_invoice_id', $membership_code_invoice_id)
                ->join('tbl_membership_package', 'tbl_membership_package.membership_package_id', '=', 'tbl_membership_code.membership_package_id')
                ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_membership_package.membership_id')
                ->get();
                // dd($data);
                // return view('member.mlm_code.mail.membership_code', $data);
                $data["template"] = Tbl_email_template::where("shop_id",$shop_id)->first();
                // $change_content[0]['membership_name'] = null;
                // $change_content[0]['membership_code'] = null;

                $change_content[0]["txt_to_be_replace"] = "[membership_count]";
                $change_content[0]["txt_to_replace"] = 0;

                $change_content[1]["txt_to_be_replace"] = "[membership_code]";
                $change_content[1]["txt_to_replace"] = null;

                foreach($data['membership_code_invoice_id'] as $key => $value)
                {
                    $change_content[0]["txt_to_replace"] += 1;
                    $change_content[1]["txt_to_replace"] .= '<p> '. $value->membership_name .' with a package of '. $value->membership_package_name .'. Your membership code is '. $value->membership_activation_code .' and membership pin '. $value->membership_code_id .'. </p><a rel="nofollow" href="'. $_SERVER['SERVER_NAME'] .'/mlm/membership_active_code/'.Crypt::encrypt($value->membership_code_id).'">Click here to activate the code.</a><br> If you cant click the link, please copy and paste this in the url <span><b>'. $_SERVER['SERVER_NAME'] .'/mlm/membership_active_code/'.Crypt::encrypt($value->membership_code_id).'</b></span>';


                    /* Sms Notification */
                    $txt[0]["txt_to_be_replace"]    = "[name]";
                    $txt[0]["txt_to_replace"]       = $invoice['first_name'];
                    $txt[1]["txt_to_be_replace"]    = "[membership_name]";
                    $txt[1]["txt_to_replace"]       = $value->membership_name;
                    //$result  = Sms::SendSms($invoice['customer_mobile'], "membership_code_purchase", $txt, $shop_id);
                    $result  = Sms::SendSms($invoice['customer_mobile'], "membership_code_purchase", $txt, $shop_id);
                } 
                // dd($change_content);
                $content_key = 'membership_code_purchase';
                $data['body'] = EmailContent::email_txt_replace($content_key, $change_content, $shop_id);
                $data['company']['email'] = DB::table('tbl_content')->where('shop_id', $shop_id)->value('value');

                // ---------------------------------------------
                $data['mail_to'] = $data['invoice']->membership_code_customer_email;
                $data['mail_subject'] = 'Membership Code Purchase';
                // ---------------------------------------------

                Mail_global::mail($data, $shop_id);
                // Mail::send('emails.full_body', $data, function ($m) use ($data) {
                //     $m->from(env('MAIL_USERNAME'), $_SERVER['SERVER_NAME']);

                //     $m->to($data['invoice']->membership_code_customer_email, env('MAIL_USERNAME'))->subject('Membership Code Purchase');
                // });

                // Mail::send('emails.full_body', $data, function ($m) use ($data) {
                //     $m->from(env('MAIL_USERNAME'), $_SERVER['SERVER_NAME']);

                //     $m->to('lukeglennjordan2@gmail.com', env('MAIL_USERNAME'))->subject('Membership Code Purchase');
                // });
            }
            else
            {
                $data['status'] = 'success';
                $data['message'] = 'Invalid Email-address';
            }
        }
        else
        {
            $data['status'] = 'success';
            $data['message'] = 'Invalid, invoice id';
        }

    }
}
