<?php
namespace App\Http\Controllers\Member;

use App\Globals\Transaction;
use App\Globals\Columns;
use App\Globals\Payment;
use App\Globals\Settings;
use App\Globals\Mail_global;
use App\Globals\MLM2;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;

use App\Models\Tbl_transaction_list;
use App\Models\Tbl_online_pymnt_link;
use App\Models\Tbl_online_pymnt_method;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_item;
use App\Models\Tbl_price_level;
use App\Models\Tbl_price_level_item;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership;
use App\Models\Tbl_item;
use App\Models\Tbl_mlm_slot;

use Excel;
use DB;
use Request;
use Redirect;
use URL;

class ProductOrderController2 extends Member
{
    public function index()
    {
        $shop_id            = $this->user_info->shop_id;
        $data["page"]       = "Product Orders";
        $data["shop_id"]    = $shop_id;
        $data["_method"]    = Tbl_online_pymnt_link::where("link_shop_id", $shop_id)
                                                    ->leftJoin("tbl_online_pymnt_gateway", "tbl_online_pymnt_gateway.gateway_code_name", "=", "tbl_online_pymnt_link.link_reference_name")
                                                    ->groupBy("link_reference_name")
                                                    ->get();
        
        return view('member.product_order2.product_order2', $data);
    }
    public function table()
    {
        $shop_id            = $this->user_info->shop_id;
        $dummy              = Transaction::get_transaction_customer_details_v2();
        $dummy              = Transaction::get_transaction_date();
        $active_tab         = request("_active_tab");
        $keyword            = request("keyword");
        $paginate=5; // default pagination

        // unity pagination
        if($shop_id == 55)
        {
            $paginate = 20;
        }

        if($active_tab == "paid")
        {
            $data["_raw_table"] = Transaction::get_transaction_list($shop_id, 'receipt',$keyword,$paginate);
        }
        elseif($active_tab == "unconfirmed")
        {
            $data["_raw_table"] = Transaction::get_transaction_list($shop_id, 'proof',$keyword,$paginate);
        }
        elseif($active_tab == "pending")
        {
            $data["_raw_table"] = Transaction::get_transaction_list($shop_id, 'order',$keyword,$paginate);
        }
        elseif($active_tab == "reject")
        {
            $data["_raw_table"] = Transaction::get_transaction_list($shop_id, 'reject',$keyword,$paginate);
        }
        else
        {
            $data["_raw_table"] = Transaction::get_transaction_list($shop_id, 'receipt',$keyword,$paginate);
        }

        foreach($data["_raw_table"] as $key => $raw_table)
        {
            $data["_raw_table"][$key]->action = "NO ACTION";
            $data["_raw_table"][$key]->name = $raw_table->first_name . " " . $raw_table->last_name;

            if($active_tab == "paid") 
            {
                $data["_raw_table"][$key]->action = '<a target="_blank" href="/member/ecommerce/product_order2/proof?id=' . $raw_table->transaction_list_id . '">VIEW PROOF</a>';
            }
            if($active_tab == "unconfirmed")
            {
                $data["_raw_table"][$key]->action = '<a href="javascript:" class="popup" link="/member/ecommerce/product_order2/details?id=' . $raw_table->transaction_list_id . '" size="lg">VIEW DETAILS</a> | ';
                $data["_raw_table"][$key]->action .= '<a target="_blank" href="/member/ecommerce/product_order2/proof?id=' . $raw_table->transaction_list_id . '">VIEW PROOF</a> | ';
                $data["_raw_table"][$key]->action .= '<a link="/member/ecommerce/product_order2/confirm_payment?id='. $raw_table->transaction_list_id .'" class="popup" size="md">CONFIRM</a> | ';
                $data["_raw_table"][$key]->action .= '<a link="/member/ecommerce/product_order2/reject_payment?id='. $raw_table->transaction_list_id .'" class="popup" size="md">REJECT</a>';
            }
            if($active_tab == "reject")
            { 
                $data["_raw_table"][$key]->action = '<a target="_blank" href="/member/ecommerce/product_order2/proof?id=' . $raw_table->transaction_list_id . '">VIEW PROOF</a>';
            }
        }

        $default[]          = ["REF NO.","transaction_number", true];
        $default[]          = ["CUSTOMER NAME","name", true];
        $default[]          = ["DATE ORDERED","display_date_order", true];
        $default[]          = ["DATE PAID.","display_date_paid", true];
        $default[]          = ["DATE SHIPPED.","display_date_deliver", true];
        $default[]          = ["E-MAIL","email", true];
        $default[]          = ["CONTACT","phone_number", true];
        $default[]          = ["ACTIONS","action", true];
        $data["_table"]     = Columns::filterColumns($this->user_info->shop_id, $this->user_info->user_id, "Order List V2", $data["_raw_table"], $default);
        // dd($data['_table']);
        return view('member.global_table', $data);
    }
    public function proof()
    {
        $transaction_list_id    = request("id");
        $transaction_list       = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->transaction()->first();
        $proof                  = $transaction_list->transaction_payment_proof;

        $url = $this->user_info->upload_server . $proof;
        return redirect($url);
    }
    public function details()
    {
        $transaction_list_id    = request("id");
        $transaction_list       = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->transaction()->first();
        $details                = $transaction_list->payment_details;

        $transaction_id = Tbl_transaction_list::where("transaction_list_id",$transaction_list_id)->first()->transaction_id;
        $transaction_payment_proof = Tbl_transaction::where('transaction_id',$transaction_id)->first()->transaction_payment_proof;
        $path_prefix = "http://digimaweb.solutions/uploadthirdparty/";
        $data['image_url'] = $path_prefix.$transaction_payment_proof;

    
        if (is_serialized($details)) 
        {
            $data["details"]               = unserialize($details);
        }
        else
        {
            $data["details"]               = [];
        }

        return view("member.product_order2.details", $data);
    }
    public function payref()
    {
        /* Session */
        session()->forget('get_transaction_filter_customer_id');
        session()->forget('get_transaction_customer_details');
        session()->forget('get_transaction_date');
        session()->forget('get_transaction_payment_method');
        session()->forget('get_transaction_slot_id');
        session()->forget('get_transaction_customer_details_v2');

        Transaction::get_transaction_customer_details_v2();
        Transaction::get_transaction_payment_method();
        Transaction::get_transaction_slot_id();

        $data["_transaction"] = Transaction::get_transaction_list($this->user_info->shop_id, 'receipt', '', 0);
      
        foreach ($data["_transaction"] as $key => $value) 
        {
            if ($value->payment_method != "paymaya") 
            {
                unset($data["_transaction"][$key]);
            }
        }
        Excel::create('Paymaya Report', function($excel) use ($data)
        {
            $excel->sheet('Paymaya', function($sheet) use ($data)
            {
                $sheet->loadView('member.product_order2.payment.payref', $data);
            });
        })
        ->download('xls');
    }
    public function draref()
    {
        /* Session */
        session()->forget('get_transaction_filter_customer_id');
        session()->forget('get_transaction_customer_details');
        session()->forget('get_transaction_date');
        session()->forget('get_transaction_payment_method');
        session()->forget('get_transaction_slot_id');
        session()->forget('get_transaction_customer_details_v2');
        
        Transaction::get_transaction_customer_details_v2();
        Transaction::get_transaction_payment_method();
        Transaction::get_transaction_slot_id();

        $data["_transaction"] = Transaction::get_transaction_list($this->user_info->shop_id, 'receipt', '', 0);
        foreach ($data["_transaction"] as $key => $value) 
        {
            if ($value->payment_method != "dragonpay") 
            {
                unset($data["_transaction"][$key]);
            }
        }

        Excel::create('Dragonpay Report', function($excel) use ($data)
        {
            $excel->sheet('Dragonpay', function($sheet) use ($data)
            {
                $sheet->loadView('member.product_order2.payment.draref', $data);
            });
        })
        ->download('xls');
    }

    public function export()
    {
        /* Session */
        session()->forget('get_transaction_filter_customer_id');
        session()->forget('get_transaction_customer_details');
        session()->forget('get_transaction_date');
        session()->forget('get_transaction_payment_method');
        session()->forget('get_transaction_slot_id');
        session()->forget('get_transaction_customer_details_v2');
        
        Transaction::get_transaction_customer_details_v2();

        $method = Request::input("method");
        $data["method"] = $method;
        $data["_transaction"] = Transaction::get_transaction_list($this->user_info->shop_id, 'order', '', 0);
        foreach ($data["_transaction"] as $key => $value) 
        {
            $transaction = Transaction::getCustomerTransaction($value->transaction_id);
            if ($transaction) 
            {
                if ($transaction->payment_method != $method) 
                {
                    unset($data["_transaction"][$key]);
                }
            }
        }
        
        Excel::create('Order Report', function($excel) use ($data)
        {
            $excel->sheet('Order', function($sheet) use ($data)
            {
                $sheet->loadView('member.product_order2.payment.export', $data);
            });
        })
        ->download('xls');
    }
    public function exportpayin()
    {
       
        $id = request("method_id");
        $query = Tbl_online_pymnt_method::where('method_id',$id)->first();
        $method_name = "";
        if(count($query)>0)
        {
            $method_name = $query->method_name;
        }
        $data['method'] = $method_name;
        $data["_transaction"] = Transaction::get_transaction_list($this->user_info->shop_id, 'order', '', 0);
        foreach ($data["_transaction"] as $key => $value) 
        {
            $transaction = Transaction::getCustomerTransaction($value->transaction_id);
            if ($transaction) 
            {
                if ($transaction->method_id != $id) 
                {
                    unset($data["_transaction"][$key]);
                }
            }
        }

        // details
        $date = array();
        $refnum = array();
        $sendername = array();
        $amount = array();
        foreach ($data["_transaction"] as $key => $value)
        {
            $detail = $value->payment_details;
            if (is_serialized($detail)) 
            {
                $detail               = unserialize($detail);
            }
            else
            {
                $detail               = [];
            }

            foreach ($detail as $key => $val)
            {
                switch ($key) {
                    case 'date_and_time':
                        $date[$value->transaction_id] = $val;
                        break;
                    case 'reference_number':
                        $refnum[$value->transaction_id] = $val;
                        break;
                    case 'sender_name':
                        $sendername[$value->transaction_id] = $val;
                        break;
                    case 'amount':
                        $amount[$value->transaction_id] = $val;
                        break;
                }
            }
        }
        $data['date'] = $date;
        $data['refnum'] = $refnum;
        $data['sendername'] = $sendername;
        $data['amount'] = $amount;
        $data['x'] = 0;
        Excel::create('Order Report', function($excel) use ($data)
        {
            $excel->sheet('Order', function($sheet) use ($data)
            {
                $sheet->loadView('member.product_order2.payment.exportpayin', $data);
            });
        })
        ->download('xls');
    }

    public function confirm_payment()
    {
        $shop_id            = $this->user_info->shop_id;
        $data["page"]       = "Confirm Product Orders";
        $data['title']      = "CONFIRM";
        $transaction_list_id    = request("id");
        Transaction::get_transaction_customer_details_v2();
        $data['transaction'] = Transaction::get_data_transaction_list($transaction_list_id);
        $data['action'] = '/member/ecommerce/product_order2/confirm_payment_submit';

        return view('member.product_order2.confirm_product_order2', $data);        
    }
    public function reject_payment()
    {
        $shop_id            = $this->user_info->shop_id;
        $data["page"]       = "REJECT Product Orders";
        $data['title']      = "REJECT";
        $transaction_list_id    = request("id");
        Transaction::get_transaction_customer_details_v2();
        $data['transaction'] = Transaction::get_data_transaction_list($transaction_list_id);
        $data['action'] = '/member/ecommerce/product_order2/reject_payment_submit';

        return view('member.product_order2.confirm_product_order2', $data);        
    }
    
    public function reject_payment_submit()
    {
        $val = Payment::manual_reject_payment($this->user_info->shop_id, request('transaction_id'));
        if($val)
        {
            $return['status'] = 'success';
            $return['call_function'] = 'success_confirm';            
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = "Something wen't wrong. Please try again later.";
        }

        return json_encode($return);
    }
    public function confirm_payment_submit()
    {
        $val = Payment::manual_confirm_payment($this->user_info->shop_id, request('transaction_list_id'));
        if(!$val)
        {
            $get_transaction_list = Transaction::get_data_transaction_list(request('transaction_list_id'));
            
            if ($get_transaction_list) 
            {
                $get_transaction      = Tbl_transaction::where("transaction_id", $get_transaction_list->transaction_id)->first();

                if ($get_transaction) 
                {
                    if ($this->user_info->shop_id == 47) 
                    {
                        $email_content["subject"] = "Confirmed Payment";
                        $email_content["content"] = '<img style="max-width: 100%; display: block; margin: auto;" src="'.URL::to('/themes/3xcell/img/payment-verified.jpg').'">';
                        $email_address            = Transaction::getCustomerEmailTransaction($get_transaction->transaction_id);

                        Mail_global::send_email(null, $email_content, $this->user_info->shop_id, $email_address);
                    }
                }

                if($this->user_info->shop_id == 47)
                {
                    $consume["id"] = $get_transaction_list->transaction_list_id;
                    $get_list_item = Tbl_transaction_item::where("transaction_list_id",$consume["id"])->get();

                    foreach($get_list_item as $list_item)
                    {
                        $list_item_id  = $list_item->item_id;
                        $this->check_lead_bonus($consume,$list_item_id);
                    }
                }
                
            }
            $return['status'] = 'success';
            $return['call_function'] = 'success_confirm';            
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $val;
        }

        return json_encode($return);
    }

    public function check_lead_bonus($consume,$val)
    {
        if(isset($consume["id"]) && $val)
        {
            $transaction_list = Tbl_transaction_list::where("transaction_list_id",$consume["id"])->first();
            if($transaction_list)
            {
                $transaction = Tbl_transaction::where("transaction_id",$transaction_list->transaction_id)->first();
                if($transaction)
                {
                    if($transaction->transaction_reference_table == "tbl_customer")
                    {
                        $customer = Tbl_customer::where("customer_id",$transaction->transaction_reference_id)->first();
                        if($customer)
                        {
                            $lead_slot = Tbl_mlm_slot::where("slot_id",$customer->customer_lead)->first();
                            if($lead_slot)
                            {
                                $membership  = Tbl_membership::where("membership_id",$lead_slot->slot_membership)->first();
                                $price_level = Tbl_price_level::where("price_level_id",$membership->membership_price_level)->first();
                                if($price_level)
                                {
                                   $item = Tbl_item::where("item_id",$val)->first();
                                   if($item)
                                   {
                                       if($item->item_type_id == 1)
                                       {
                                           $bonus = 0;
                                           if($price_level->price_level_type == "per-item")
                                           {
                                               $price_level_item = Tbl_price_level_item::where("price_level_id",$price_level->price_level_id)->where("item_id",$item->item_id)->first();
                                               if($price_level_item)
                                               {
                                                 $bonus = $item->item_price - $price_level_item->custom_price;   
                                               }
                                           }
                                           else if($price_level->price_level_type == "fixed-percentage")
                                           {
                                               $bonus = $item->item_price * ($price_level->fixed_percentage_value/100);
                                           }
                                           
                                           $check_if_owner_has_slot = Tbl_mlm_slot::where("slot_owner",$customer->customer_id)->first();
                                           if(!$check_if_owner_has_slot)
                                           {
                                               if($bonus > 0)
                                               {
                                                    $amount_given                        = $bonus;
                                                    $log                                 = number_format($amount_given,2)." Retail Commission from customer ".$customer->first_name." ".$customer->last_name;
                                                    $arry_log['wallet_log_slot']         = $lead_slot->slot_id;
                                                    $arry_log['shop_id']                 = $lead_slot->shop_id;
                                                    $arry_log['wallet_log_slot_sponsor'] = $lead_slot->slot_id;
                                                    $arry_log['wallet_log_details']      = $log;
                                                    $arry_log['wallet_log_amount']       = $amount_given;
                                                    $arry_log['wallet_log_plan']         = "RETAIL_COMMISSION";
                                                    $arry_log['wallet_log_status']       = "released";   
                                                    $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('RETAIL_COMMISSION', $lead_slot->shop_id); 
                                                    Mlm_slot_log::slot_array($arry_log);  
                                               }
                                           }
                                           else
                                           {
                                                MLM2::purchase($check_if_owner_has_slot->shop_id, $check_if_owner_has_slot->slot_id, $item->item_id);
                                           }
                                       }
                                   }
                                }
                                else
                                {
                                    $check_if_owner_has_slot = Tbl_mlm_slot::where("slot_owner",$customer->customer_id)->first();
                                    if($check_if_owner_has_slot)
                                    {
                                       $item = Tbl_item::where("item_id",$val)->first();
                                       if($item)
                                       {
                                           if($item->item_type_id == 1)
                                           {
                                              MLM2::purchase($check_if_owner_has_slot->shop_id, $check_if_owner_has_slot->slot_id, $item->item_id);
                                           }
                                       }
                                    }
                                }
                            }
                            else
                            {
                                $check_if_owner_has_slot = Tbl_mlm_slot::where("slot_owner",$customer->customer_id)->first();
                                if($check_if_owner_has_slot)
                                {
                                   $item = Tbl_item::where("item_id",$val)->first();
                                   if($item)
                                   {
                                       if($item->item_type_id == 1)
                                       {
                                          MLM2::purchase($check_if_owner_has_slot->shop_id, $check_if_owner_has_slot->slot_id, $item->item_id);
                                       }
                                   }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function settings()
    {
        $data["shipping_fee"] = isset(Settings::get_settings_php_shop_id("shipping_fee", $this->user_info->shop_id)["settings_value"]) ? Settings::get_settings_php_shop_id("shipping_fee", $this->user_info->shop_id)["settings_value"] : 0;

        return view("member.product_order2.settings", $data);
    }

    public function settings_submit()
    {
        $shipping_fee = Request::input("shipping_fee");

        if ($shipping_fee) 
        {
            $shipping_exist = Settings::get_settings_php_shop_id("shipping_fee", $this->user_info->shop_id);

            if ($shipping_exist["response_status"] == "success") 
            {
                $result = Settings::update_settings_shop_id("shipping_fee", $shipping_fee, $this->user_info->shop_id);
            }
            else
            {
                $result = Settings::insert_settings_shop_id("shipping_fee", $shipping_fee, $this->user_info->shop_id);
            }

            if ($result["response_status"] == "error") 
            {
                dd($data["message"]);
            }
        }

        return Redirect::back();
    }
}