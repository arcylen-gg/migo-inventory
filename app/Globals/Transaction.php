<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_temp_customer_invoice;
use App\Models\Tbl_temp_customer_invoice_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_warehouse_receiving_report_item;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_cart_payment;
use App\Models\Tbl_transaction_ref_number;

use App\Models\Tbl_cart_item_pincode;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_list;
use App\Models\Tbl_transaction_item;
use App\Models\Tbl_transaction_payment;
use App\Models\Tbl_payment_logs;
use App\Globals\AuditTrail;
use App\Globals\Tablet_global;
use App\Globals\Mlm_slot_log;

use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\Carbon;

class Transaction
{

    public static function get_transaction_item_code($transaction_list_id, $shop_id = null)
    {
        $list = Tbl_transaction_list::salesperson()->transaction()->where('transaction_list_id',$transaction_list_id)->first();
        if ($shop_id) 
        {
            $check = Tbl_warehouse_issuance_report::where("wis_shop_id", $shop_id)->where('wis_number',$list->transaction_number)->first();
        }
        else
        {
            $check = Tbl_warehouse_issuance_report::where('wis_number',$list->transaction_number)->first();
        }
        $ref_name = 'transaction_list';
        $ref_id = $transaction_list_id;
        $_item = null;
        if($check)
        {
            $ref_name = 'wis';
            $ref_id = $check->wis_id;

            $_rr = Tbl_warehouse_receiving_report::where('wis_id',$check->wis_id)->get();

            foreach ($_rr as $rrkey => $rrvalue)
            {
                $_rr_item = Warehouse2::get_source_transaction_item('rr',$rrvalue->rr_id);
                foreach ($_rr_item as $key_rr => $value_rr) 
                {
                    $_item[$value_rr->record_item_id][$rrkey.$key_rr.'rr']['item_pin'] = $value_rr->mlm_pin;
                    $_item[$value_rr->record_item_id][$rrkey.$key_rr.'rr']['item_activation'] = $value_rr->mlm_activation;
                }
            }
        }
        $items = Warehouse2::get_transaction_item($ref_name, $ref_id);
        foreach ($items as $key => $value) 
        {
            $_item[$value->record_item_id][$key]['item_pin'] = $value->mlm_pin;
            $_item[$value->record_item_id][$key]['item_activation'] = $value->mlm_activation;
        }
        
        return $_item;
    }
    public static function create_update_transaction_details($details)
    {
        $store["create_update_transaction_details"] = $details;
        session($store);
    }
    public static function create_update_proof($details)
    {
        $store["create_update_proof"] = $details;
        session($store);
    }
    public static function create_update_proof_details($details)
    {
        $store["create_update_proof_details"] = $details;
        session($store);
    }
    public static function create_set_method($method)
    {
        $store["create_set_method"] = $method;
        session($store);
    }
    public static function create_set_method_id($method)
    {
        $store['create_set_method_id'] = $method;
        session($store);
    }
    public static function create($shop_id, $transaction_id, $transaction_type, $transaction_date, $posted = false, $source = null, $transaction_number = null)
    {
        $transaction_sales_person = isset($transaction_id["transaction_sales_person"]) ? $transaction_id["transaction_sales_person"] : null;
        if($source == null)
        {
            $cart = Cart2::get_cart_info();
        }
        else
        {
            $cart = null;
        }
        
        if($cart || $source != null) //INSERT ONLY IF CART IS NOT ZERO OR THERE IS SOURCE
        {
            if(!is_numeric($transaction_id)) //CREATE NEW IF TRANSACTION ID if $transaction_id is an ARRAY
            {
                $insert_transaction["shop_id"]                      = $shop_id;
                $insert_transaction["transaction_id_shop"]          = Self::shop_increment($shop_id);  
                $insert_transaction["transaction_reference_table"]  = $transaction_id["transaction_reference_table"];
                $insert_transaction["transaction_reference_id"]     = $transaction_id["transaction_reference_id"];
                
                if(session('create_set_method'))
                {
                    $insert_transaction["payment_method"] = session("create_set_method");
                    $insert_transaction["method_id"]      = session("create_set_method_id");
                    session()->forget('create_set_method');
                    session()->forget('create_set_method_id');
                }

                $transaction_id = Tbl_transaction::insertGetId($insert_transaction);
            }
            /* INSERT NEW LIST */
            $insert_list["transaction_id"]              = $transaction_id;
            $insert_list["shop_id"]                     = $shop_id;
            $insert_list["transaction_date"]            = $transaction_date;
            $insert_list["transaction_due_date"]        = $transaction_date;
            $insert_list["transaction_date_created"]    = Carbon::now();
            $insert_list["transaction_date_updated"]    = Carbon::now();
            $insert_list["transaction_type"]            = $transaction_type;
            $insert_list["transaction_sales_person"]    = $transaction_sales_person;
            $insert_list["transaction_number"]          =  ($transaction_number ? $transaction_number :  Self::generate_transaction_number($shop_id, $transaction_type));
            

            if($source == null)
            {
                $insert_list["transaction_subtotal"]        = $cart["_total"]->total;
                $insert_list["transaction_tax"]             = 0;
                $insert_list["transaction_discount"]        = $cart["_total"]->global_discount;
                $insert_list["transaction_total"]           = $cart["_total"]->grand_total;
                $total                                      = $cart["_total"]->grand_total;
            }
            else
            {
                $source_transaction_list = Tbl_transaction_list::where("transaction_list_id", $source)->first();
                $insert_list["transaction_subtotal"]        = $source_transaction_list->transaction_subtotal;
                $insert_list["transaction_tax"]             = $source_transaction_list->transaction_tax;
                $insert_list["transaction_discount"]        = $source_transaction_list->transaction_discount;
                $insert_list["transaction_total"]           = $source_transaction_list->transaction_total;
                $total                                      = $source_transaction_list->transaction_total;
            }
            
            if($posted == "-")
            {
                $insert_list["transaction_posted"] = $total * -1;
            }
            elseif($posted == "+")
            {
                $insert_list["transaction_posted"] = $total;
            }
            else
            {
                $insert_list["transaction_posted"] = 0;
            }

            $transaction_list_id                        = Tbl_transaction_list::insertGetId($insert_list);    

            if($source == null)
            {
                /* INSERT ITEMS */
                foreach($cart["_item"] as $key => $item)
                {
                    $insert_item[$key]["transaction_list_id"]           = $transaction_list_id;
                    $insert_item[$key]["item_id"]                       = $item->item_id;
                    $insert_item[$key]["item_name"]                     = $item->item_name;
                    $insert_item[$key]["item_sku"]                      = $item->item_sku;
                    $insert_item[$key]["item_price"]                    = $item->item_price;
                    $insert_item[$key]["quantity"]                      = $item->quantity;
                    $insert_item[$key]["discount"]                      = $item->discount;
                    $insert_item[$key]["subtotal"]                      = $item->subtotal;
                }
                
                 Tbl_transaction_item::insert($insert_item);
            }
            else
            {
                $_item = Tbl_transaction_item::where("transaction_list_id", $source)->get();
                
                /* INSERT FROM OTHER TRANSACTION */
                foreach($_item as $key => $item)
                {
                    $insert_item[$key]["transaction_list_id"]           = $transaction_list_id;
                    $insert_item[$key]["item_id"]                       = $item->item_id;
                    $insert_item[$key]["item_name"]                     = $item->item_name;
                    $insert_item[$key]["item_sku"]                      = $item->item_sku;
                    $insert_item[$key]["item_price"]                    = $item->item_price;
                    $insert_item[$key]["quantity"]                      = $item->quantity;
                    $insert_item[$key]["discount"]                      = $item->discount;
                    $insert_item[$key]["subtotal"]                      = $item->subtotal;
                }
                
                Tbl_transaction_item::insert($insert_item);
            }
            
            $return = $transaction_list_id;
            Self::update_transaction_balance($transaction_id);
        }
        else
        {
            $return = "CART IS EMPTY";
        }

        return $return;
    }
    public static function insert_payment($shop_id, $transaction_id, $method = array(), $amount = array())
    {
    }
    public static function consume_in_warehouse($shop_id, $transaction_list_id, $remarks = 'Enroll kit', $get_to_warehouse = 0)
    {
        $warehouse_id = Warehouse2::get_main_warehouse($shop_id);
        if($get_to_warehouse != 0)
        {
            $warehouse_id = $get_to_warehouse;
        }
        
        $get_item = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        
        $consume['name'] = 'transaction_list';
        $consume['id'] = $transaction_list_id;
        foreach ($get_item as $key => $value) 
        {
            $item_type = Item::get_item_type($value->item_id);
            /*INVENTORY TYPE*/
            if($item_type == 1 || $item_type == 5)
            {   
                $check = Cart2::get_item_pincode($shop_id, $value->item_id);
                if(count($check) > 0)
                {
                    foreach ($check as $key_cart => $value_cart) 
                    {
                        $pincode = explode('@', $value_cart->pincode);
                        $mlm_pin = $pincode[0];
                        $mlm_activation = $pincode[1];
                        Warehouse2::consume_product_codes($shop_id, $mlm_pin, $mlm_activation, $consume,'Consume using Transaction list '.$transaction_list_id,'unused');
                    }
                }
                else
                {                    
                    Warehouse2::consume($shop_id, $warehouse_id, $value->item_id, $value->quantity, $remarks, $consume);
                }

            }
            /*NONINVENTORY TYPE*/
            if($item_type == 2)
            {
                $return = Warehouse2::refill($shop_id, $warehouse_id, $value->item_id, $value->item_price, $value->quantity, $remarks, $consume);
                if(!$return)
                {
                    Warehouse2::consume($shop_id, $warehouse_id, $value->item_id, $value->quantity, $remarks, $consume);
                }
            }
        }
    }
    public static function consume_in_warehouse_validation($shop_id, $transaction_list_id, $remarks = 'Enroll kit')
    {
        $warehouse_id = Warehouse2::get_main_warehouse($shop_id);
        
        $get_item = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        
        $return = null;
        foreach ($get_item as $key => $value) 
        {
            $return .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value->item_id, $value->quantity, $remarks);
        }

        return $return;
    }
    public static function get_transaction_item($transaction_list_id)
    {
        return Tbl_transaction_item::where('transaction_list_id', $transaction_list_id)->get();
    }
    public static function get_all_transaction_item($shop_id, $date_from = '',$date_to = '', $transaction_type = '', $payment_type = '')
    {
        $data = Tbl_transaction_item::transaction_list()->transaction()->where('tbl_transaction_list.shop_id', $shop_id);
        if($date_from && $date_to)
        {
            $data = $data->whereBetween('transaction_date_created',[$date_from,$date_to]);
        }
        if($transaction_type)
        {
            $data = $data->where('transaction_type',$transaction_type);
        }
        $data = $data->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id')->leftJoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_customer.customer_id')->where('tbl_customer_address.purpose', 'shipping')->get();
        // die(var_dump($data));
        foreach ($data as $key => $value) 
        {
            $payment_method = Transaction::getPaymentMethod($value->transaction_number, $value->transaction_list_id, is_serialized($value->transaction_details) ? unserialize($value->transaction_details) : null);
            
            $data[$key]->payment_method         = isset($payment_method->payment_method) ? $payment_method->payment_method : "None";

            $data[$key]->checkout_id            = isset($payment_method->checkout_id) ? $payment_method->checkout_id : "None";
            $data[$key]->paymaya_response       = isset($payment_method->paymaya_response) ? $payment_method->paymaya_response : "None";
            $data[$key]->paymaya_status         = isset($payment_method->paymaya_status) ? $payment_method->paymaya_status : "None";
            $data[$key]->dragonpay_response     = isset($payment_method->dragonpay_response) ? $payment_method->dragonpay_response : "None";
            /* Old Date */
            $old = DB::table("tbl_ec_order")->where("invoice_number", $value->transaction_number)->first();
            if ($old) 
            {
                $data[$key]->transaction_date_created = $old->created_date;
            }

            $slot = Transaction::getSlotId($value->transaction_number, $value->transaction_list_id);
            if ($slot) 
            {
                $tree = Tbl_tree_sponsor::where("sponsor_tree_child_id", $slot->slot_id)->first();
                if ($tree) 
                {
                    $upline_slot = Tbl_mlm_slot::where("slot_id", $tree->sponsor_tree_parent_id)->first();
                    if ($upline_slot) 
                    {
                        $data[$key]->slot_upline_no = $upline_slot->slot_no;
                    }
                    else
                    {
                        $data[$key]->slot_upline_no = "None";
                    }
                }
                else
                {
                    $data[$key]->slot_upline_no = "HEAD";
                }
                $data[$key]->slot_no = $slot->slot_no;
                $data[$key]->slot_id = $slot->slot_id;
            }
            else
            {
                $data[$key]->slot_no = "Unused";
                $data[$key]->slot_id = "none";
                $data[$key]->slot_upline_no = "Unused";
            }
        }
        if($payment_type != '')
        {
            foreach ($data as $key => $value) 
            {
                if($value->payment_method != $payment_type)
                {
                    unset($data[$key]);
                }
            }
        }
        // die(var_dump($data));

        return $data;
    }
    public static function get_data_transaction_list($transaction_list_id, $type = null)
    {
        $data = Tbl_transaction_list::transaction()->where('transaction_list_id', $transaction_list_id);

        if(session('get_transaction_customer_details_v2'))
        {
            $data->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
            $data->leftJoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_customer.customer_id');
            $data->leftJoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_customer.customer_id');
            $data->groupBy("tbl_transaction_list.transaction_list_id");
        }

        session()->forget('get_transaction_customer_details_v2');

        return $data->first();
    }
    public static function update_transaction_balance($transaction_id)
    {
        $balance = Tbl_transaction_list::where("transaction_id", $transaction_id)->groupBy("transaction_id")->sum("transaction_posted");
        
        if(session('create_update_transaction_details'))
        {
            $update["transaction_details"] = session('create_update_transaction_details');
            session()->forget('create_update_transaction_details');
        }

        if(session('create_update_proof'))
        {
            $update["transaction_payment_proof"] = session('create_update_proof');
            session()->forget('create_update_proof');
        }

        if (session('create_update_proof_details')) 
        {
            $update["payment_details"] = serialize(session('create_update_proof_details'));
            session()->forget('create_update_proof_details');
        }

        if($balance == 0)
        {
            $update["payment_status"] = "paid";
        }
        else
        {
            $update["payment_status"] = "pending";
        }
        
        $update["transaction_balance"] = $balance;
        $balance = Tbl_transaction::where("transaction_id", $transaction_id)->update($update);
    }
    public static function generate_transaction_number($shop_id, $transaction_type)
    {
        switch ($transaction_type)
        {
            case 'ORDER':
                $prefix = "ORDER-";
            break;
           
            case 'RECEIPT':
                $prefix = "";
            break;
            
            case 'FAILED':
                $prefix = "FAIL-";
            break;

            case 'PENDING':
                $prefix = "PENDING-";
            break;

            case 'PROOF':
                $prefix = "PROOF-";
            break;


            default:
                $prefix = "";
            break;
        }

        $last_transaction_number = Tbl_transaction_list::where("shop_id", $shop_id)->where("transaction_type", $transaction_type)->orderBy("transaction_list_id", "desc")->value("transaction_number");
        
        if($last_transaction_number)
        {
            $next_transaction_number = intval(str_replace($prefix, "", $last_transaction_number)) + 1;
        }
        else
        {
            $next_transaction_number = 1;
        }

        $transaction_number = $prefix . str_pad($next_transaction_number, 6, '0', STR_PAD_LEFT);

        return $transaction_number;
    }
    public static function getCustomerNameTransaction($transaction_id = 0)
    {
        $customer_name = 'No customer found!';
        if($transaction_id)
        {
            $data = Tbl_transaction::where('transaction_id',$transaction_id)->first();
            
            if($data)
            {
                if($data->transaction_reference_table == 'tbl_customer' && $data->transaction_reference_id != 0)
                {
                    $chck = Tbl_customer::where('customer_id', $data->transaction_reference_id)->first();
                    if($chck)
                    {
                        $customer_name = $chck->first_name.' '.$chck->middle_name.' '.$chck->last_name;
                    }
                }
            }
        }
        return $customer_name;
    }
    public static function getCustomerEmailTransaction($transaction_id = 0)
    {
        $customer_email = 'No Email found!';
        if($transaction_id)
        {
            $data = Tbl_transaction::where('transaction_id',$transaction_id)->first();
            
            if($data)
            {
                if($data->transaction_reference_table == 'tbl_customer' && $data->transaction_reference_id != 0)
                {
                    $chck = Tbl_customer::where('customer_id', $data->transaction_reference_id)->first();
                    if($chck)
                    {
                        $customer_email = $chck->email;
                    }
                }
            }
        }
        return $customer_email;
    }

    public static function getCustomerInfoTransaction($transaction_id = 0)
    {
        $customer_info = null;
        if($transaction_id)
        {
            $data = Tbl_transaction::where('transaction_id',$transaction_id)->first();
            
            if($data)
            {
                if($data->transaction_reference_table == 'tbl_customer' && $data->transaction_reference_id != 0)
                {
                    $chck = Tbl_customer::where('customer_id', $data->transaction_reference_id)->first();
                    if($chck)
                    {
                        $customer_info = $chck;
                    }
                }
            }
        }
        return $customer_info;
    }
    public static function getCustomerAddressTransaction($transaction_id = 0)
    {
        $customer_address = null;
        if($transaction_id)
        {
            $data = Tbl_transaction::where('transaction_id',$transaction_id)->first();
            
            if($data)
            {
                if($data->transaction_reference_table == 'tbl_customer' && $data->transaction_reference_id != 0)
                {
                    $chck = Tbl_customer_address::where('customer_id', $data->transaction_reference_id)
                                                ->where("purpose", "shipping")
                                                ->orWhere("purpose", "permanent")
                                                ->orWhere("purpose", "billing")
                                                ->first();
                    if($chck)
                    {
                        $customer_address = $chck;
                    }
                }
            }
        }
        return $customer_address;
    }
    public static function getCustomerTransaction($transaction_id = 0)
    {
        $transaction = null;
        if($transaction_id)
        {
            $transaction = Tbl_transaction::where('transaction_id',$transaction_id)->first();
        }
        return $transaction;
    }
    public static function get_transaction_filter_customer($customer_id) //filter result of transaction list by customer
    {
        $store["get_transaction_filter_customer_id"] = $customer_id;
        session($store);
    }
    public static function get_transaction_customer_details() //filter result of transaction list by customer
    {
        $store["get_transaction_customer_details"]  = true;
        session($store);
    }
    public static function get_transaction_date() //filter result of transaction list by customer
    {
        $store["get_transaction_date"]  = true;
        session($store);
    }
    public static function get_transaction_payment_method()
    {
        $store["get_transaction_payment_method"] = true;
        session($store);
    }
    public static function get_transaction_slot_id()
    {
        $store["get_transaction_slot_id"] = true;
        session($store);
    }
    public static function get_transaction_customer_details_v2()
    {
        $store["get_transaction_customer_details_v2"] = true;
        session($store);
    }
    public static function get_transaction_list($shop_id, $transaction_type = 'all', $search_keyword = '', $paginate = 5, $transaction_id = 0)
    {
        $data = Tbl_transaction_list::where('tbl_transaction_list.shop_id',$shop_id);
        if($transaction_id != 0)
        {
            $data = Tbl_transaction_list::where('tbl_transaction_list.transaction_id',$transaction_id)->where('tbl_transaction_list.shop_id',$shop_id);
        }
        
        $data->transaction(); //join table transaction
        
        if(isset($transaction_type))
        {
            if($transaction_type != 'all')
            {
                if($transaction_type == 'proof')
                {
                    if($shop_id == 47)
                    {
                        $data->where('transaction_type', $transaction_type)->where('payment_status','pending');
                        $data->groupBy("tbl_transaction_list.transaction_id");
                    }
                    else
                    {
                        $data->where('transaction_type', $transaction_type)->where('payment_status','pending');
                    }
                }
                elseif($transaction_type == 'reject')
                {
                    $data->where('transaction_type','proof')->where('payment_status','reject')->where('order_status','reject');
                }
                else
                {
                    $data->where('transaction_type', $transaction_type)->where('order_status','!=','reject');
                }
            }
        }
        if($shop_id == 1)
        {
            $data->orderBy('transaction_date_created','DESC');
        }
        else
        {
            $data->orderBy("transaction_number");
        }
        
        if(session('get_transaction_filter_customer_id'))
        {
            $data->where('transaction_reference_table', 'tbl_customer')->where('tbl_transaction.transaction_reference_id', session('get_transaction_filter_customer_id'));
            $data->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
            session()->forget('get_transaction_filter_customer_id');
        }

        if(session('get_transaction_customer_details_v2'))
        {
            $data->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
            $data->leftJoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_customer.customer_id');
            $data->leftJoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_customer.customer_id');
        }
        else
        {
            $data->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
        }

        if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {
                $q->orwhere('transaction_number', "LIKE", "%" . $search_keyword . "%");
                $q->orWhere('first_name', "LIKE", "%" . $search_keyword . "%");
                $q->orWhere('last_name', "LIKE", "%" . $search_keyword . "%");
                $q->orWhere('email', "LIKE", "%" . $search_keyword . "%");
            });
        }
        if(session('get_transaction_customer_details'))
        {
            $data->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_transaction.transaction_reference_id');
            $data->leftJoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_customer.customer_id');
            $data->leftJoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_customer.customer_id');
            $data->groupBy("tbl_customer.customer_id");
        }
        if($paginate)
        {
            $data = $data->paginate($paginate);
        }
        else
        {
            $data = $data->groupBy("tbl_transaction_list.transaction_list_id")->get();
        }
        foreach($data as $key => $value)
        {
            $data[$key]->customer_name = Transaction::getCustomerNameTransaction($value->transaction_id);
            
            if(session('get_transaction_customer_details') || session('get_transaction_customer_details_v2'))
            {
                $data[$key]->phone_number           = $value->customer_mobile or $value->contact;
            }
            
            if(session('get_transaction_date'))
            {
                $data[$key]->date_order             = Tbl_transaction_list::where('transaction_type', 'ORDER')->where("transaction_id", $value->transaction_id)->value('transaction_date');
                $data[$key]->date_paid              = Tbl_transaction_list::where('transaction_type', 'RECEIPT')->where("transaction_id", $value->transaction_id)->value('transaction_date');
                $data[$key]->date_deliver           = Tbl_transaction_list::where('transaction_type', 'WAYBILL')->where("transaction_id", $value->transaction_id)->value('transaction_date');;
                $data[$key]->display_date_order     = $data[$key]->date_order != null ? date("m/d/Y", strtotime($data[$key]->date_order)) : "-";
                $data[$key]->display_date_paid      = $data[$key]->date_paid != null ? date("m/d/Y", strtotime($data[$key]->date_paid)) : "-";
                $data[$key]->display_date_deliver   = $data[$key]->date_deliver != null ? date("m/d/Y", strtotime($data[$key]->date_deliver)) : "-";
            }

            if (session('get_transaction_payment_method')) 
            {
                $payment_method = Transaction::getPaymentMethod($value->transaction_number, $value->transaction_list_id, is_serialized($value->transaction_details) ? unserialize($value->transaction_details) : null);
                
                $data[$key]->payment_method         = isset($payment_method->payment_method) ? $payment_method->payment_method : "None";
                $data[$key]->checkout_id            = isset($payment_method->checkout_id) ? $payment_method->checkout_id : "None";
                $data[$key]->paymaya_response       = isset($payment_method->paymaya_response) ? $payment_method->paymaya_response : "None";
                $data[$key]->paymaya_status         = isset($payment_method->paymaya_status) ? $payment_method->paymaya_status : "None";
                $data[$key]->dragonpay_response     = isset($payment_method->dragonpay_response) ? $payment_method->dragonpay_response : "None";

                /* Old Date */
                $old = DB::table("tbl_ec_order")->where("invoice_number", $value->transaction_number)->first();
                if ($old) 
                {
                    $data[$key]->transaction_date_created = $old->created_date;
                }
            }

            if (session('get_transaction_slot_id')) 
            {
                $slot = Transaction::getSlotId($value->transaction_number, $value->transaction_list_id);
                if ($slot) 
                {
                    $tree = Tbl_tree_sponsor::where("sponsor_tree_child_id", $slot->slot_id)->first();
                    if ($tree) 
                    {
                        $upline_slot = Tbl_mlm_slot::where("slot_id", $tree->sponsor_tree_parent_id)->first();
                        if ($upline_slot) 
                        {
                            $data[$key]->slot_upline_no = $upline_slot->slot_no;
                        }
                        else
                        {
                            $data[$key]->slot_upline_no = "None";
                        }
                    }
                    else
                    {
                        $data[$key]->slot_upline_no = "HEAD";
                    }
                    $data[$key]->slot_no = $slot->slot_no;
                    $data[$key]->slot_id = $slot->slot_id;
                }
                else
                {
                    $data[$key]->slot_no = "Unused";
                    $data[$key]->slot_id = "none";
                    $data[$key]->slot_upline_no = "Unused";
                }
            }
        }
        
        session()->forget('get_transaction_filter_customer_id');
        session()->forget('get_transaction_customer_details');
        session()->forget('get_transaction_date');
        session()->forget('get_transaction_payment_method');
        session()->forget('get_transaction_slot_id');

        //patrick
        $emails = array();
        if($transaction_type == 'proof' && $shop_id != 47)
        {
            foreach ($data as $key => $value) 
            {
                if(in_array($value->email, $emails))
                {
                    unset($data[$key]);                
                }
                else
                {
                    array_push($emails, $value->email);
                }
            }
        }
        
        return $data;
    }
    public static function get_all_transaction_type()
    {
        $type[0] = 'order';
        $type[1] = 'receipt';
        $type[2] = 'failed';
        $type[3] = 'pending';
        return $type;
    }
    public static function shop_increment($shop_id)
    {
        $last_transaction_id = Tbl_transaction::where("shop_id", $shop_id)->orderBy("transaction_id_shop", "desc")->value("transaction_id_shop");

        if($last_transaction_id)
        {
            return $last_transaction_id + 1;
        }
        else
        {
            return 1;
        }
    }
	public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public static function check_number_existense($tbl_name, $column_name, $shop_column_name,$number, $for_tablet = false)
    {
        $shop_id = Transaction::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $data = DB::table($tbl_name)->where($column_name,$number)->where($shop_column_name,$shop_id)->count();

        return $data;
    }

    public static function get_last_number($tbl_name, $column_name, $shop_column_name,$for_tablet = false)
    {
        $shop_id = Transaction::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $data = DB::table($tbl_name)->orderBy($column_name,"DESC")->where($shop_column_name,$shop_id)->value($column_name);

        return $data + 1 ;
    }

    /**
     * Getting all the list of transaction
     *
     * @param string    $filter     (byCustomer, byAccount, byItem)
     */

    public static function getAllTransaction($filter = null)
    {

    }

    public static function getPaymentMethod($transaction_number, $transaction_list_id = null, $transaction_details = null)
    {
        $data = new \stdClass();
        $old = DB::table("tbl_ec_order")->where("invoice_number", $transaction_number)->first();
        if ($old) 
        {
            /* Old */
            $payment_method = DB::table("tbl_online_pymnt_link")->where("link_method_id", $old->payment_method_id)->first();
            if($payment_method)
            {
                $data->payment_method = $payment_method->link_reference_name;
                if($payment_method->link_reference_name == "paymaya") 
                {
                    $paymaya = DB::table("tbl_paymaya_logs")->where("order_id", $old->ec_order_id)->first();
                    $data->paymaya_status   = $paymaya->confirm_response;
                    $data->paymaya_response = $paymaya->confirm_response_information;
                    $data->checkout_id      = $paymaya->checkout_id;
                }
                elseif($payment_method->link_reference_name == "dragonpay")
                {
                    $dragonpay = DB::table("tbl_dragonpay_logs_other")->where("order_id", $old->ec_order_id)->orderBy("log_date", "desc")->first();
                    if (!$dragonpay) 
                    {
                        $dragonpay = DB::table("tbl_dragonpay_logs")->where("order_id", $old->ec_order_id)->first();
                    }
                    $unserialize_dragonpay    = is_serialized($dragonpay->response) ? unserialize($dragonpay->response) : [];

                    $data->checkout_id        = isset($unserialize_dragonpay['txnid']) ? $unserialize_dragonpay['txnid'] : null;
                    $data->dragonpay_response = isset($unserialize_dragonpay['message']) ? $unserialize_dragonpay['message'] : null;
                }
            }
        }
        else
        {
            /* New */
            $transaction = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->first();
            $transaction = Tbl_transaction_list::where("transaction_id", $transaction->transaction_id)->where("transaction_type", "ORDER")->first();
            $payment_logs = Tbl_payment_logs::where("transaction_list_id", $transaction->transaction_list_id)->first();
            
            if ($payment_logs) 
            {
                $data->payment_method = $payment_logs->payment_log_method;

                switch ($payment_logs->payment_log_method) 
                {
                    case 'paymaya':
                        if (isset($transaction_details["id"]) && isset($transaction_details["paymentDetails"]["cardType"]) && isset($transaction_details["paymentDetails"]["last4"]) && isset($transaction_details["paymentStatus"])) 
                        {
                            $data->checkout_id      = $transaction_details["id"];
                            $data->paymaya_response = "PAID with " . $transaction_details["paymentDetails"]["cardType"] . " ending in ". $transaction_details["paymentDetails"]["last4"];
                            $data->paymaya_status   = $transaction_details["paymentStatus"];
                        }
                    break;

                    case 'dragonpay':
                        if (isset($transaction_details["refno"])) 
                        {
                            $data->checkout_id        = $transaction_details["refno"];
                            $data->dragonpay_response = $transaction_details["message"];
                        }
                    break;
                    
                    default:
                        $data->checkout_id = "None";
                    break;
                }
            }
        }
        return $data;
    }

    public static function getSlotId($transaction_number, $transaction_list_id = null)
    {
        $old = DB::table("tbl_ec_order")->where("invoice_number", $transaction_number)->first();
        if ($old) 
        {
            $slot = DB::table("tbl_ec_order")->where("invoice_number", $transaction_number)
                                     // ->join("tbl_paymaya_logs", "tbl_paymaya_logs.order_id", "=", "tbl_ec_order.ec_order_id")
                                     ->leftJoin("tbl_customer", "tbl_customer.customer_id", "=", "tbl_ec_order.customer_id")
                                     ->leftJoin("tbl_ec_order_slot", "tbl_ec_order_slot.order_slot_ec_order_id", "=", "tbl_ec_order.ec_order_id")
                                     ->leftJoin("tbl_mlm_slot", "tbl_mlm_slot.slot_id", "=", "tbl_ec_order_slot.order_slot_id_c")
                                     ->leftJoin("tbl_customer_address", "tbl_customer_address.customer_id", "=", "tbl_customer.customer_id")
                                     ->leftJoin("tbl_customer_other_info", "tbl_customer_other_info.customer_id", "=", "tbl_customer.customer_id")
                                     ->groupBy("ec_order_id")
                                     ->orderBy("ec_order_id", "asc")
                                     ->first(); 
            
            // if (!isset($slot->slot_id)) 
            // {
            //     $slot_temp = DB::table("tbl_ec_order_slot")->where("order_slot_ec_order_id", $old->ec_order_id)->first();
            //     if ($slot_temp) 
            //     {
            //         $slot = DB::table("tbl_mlm_slot")->where("slot_id", $slot_temp->order_slot_id_c)->first();
            //     }
            // }                  
        }
        else
        {
            $transaction = Tbl_transaction_list::where("transaction_list_id", $transaction_list_id)->first();
            $transaction = Tbl_transaction_list::where("transaction_id", $transaction->transaction_id)->where("transaction_type", "RECEIPT")->first();
            $slot_ref = Tbl_warehouse_inventory_record_log::where("record_consume_ref_name", "transaction_list")->where("record_consume_ref_id", $transaction->transaction_list_id)->first();
            if ($slot_ref) 
            {
                $slot = Tbl_mlm_slot::where("slot_id", $slot_ref->mlm_slot_id_created)->first();
            }
            else
            {
                $slot = 0;
            }
        }

        return $slot;
    }
    public static function validate_payment($shop_id, $slot_id)
    {
        $return = null;
        $cart_key = Cart2::get_cart_key();
        $cart = Cart2::get_cart_info();
        $amount = Customer::get_points_wallet_per_slot($slot_id);
        $cart_wallet_amount = Cart2::cart_payment_amount($shop_id,'wallet');
        $cart_gc_amount = Cart2::cart_payment_amount($shop_id,'gc');
        $cart_total_amount = Cart2::cart_payment_amount($shop_id);

        if($cart_wallet_amount > $amount['total_wallet'])
        {
            $return .= 'Not enough wallet in <b>slot no'.Customer::slot_info($slot_id)->slot_no.'</b>, wallet remaining '.currency('PHP ',$amount['total_wallet']).'. <br>'; 
        }

        if($cart_gc_amount > $amount['total_gc'])
        {
            $return .= 'Not enough GC in <b>slot no'.Customer::slot_info($slot_id)->slot_no.'</b>, GC remaining '.currency('',$amount['total_gc']).' point(s). <br>'; 
        }
        if($cart)
        {
            if($cart_total_amount < $cart["_total"]->grand_total)
            {
                $return .= 'Not enough payment';
            }
        }

        return $return;
    }
    public static function consume_payment($shop_id, $transaction_list_id, $slot_id)
    {        
        $amount = Customer::get_points_wallet_per_slot($slot_id);
        $cart_wallet_amount = Cart2::cart_payment_amount($shop_id,'wallet');
        $cart_gc_amount = Cart2::cart_payment_amount($shop_id,'gc');
        $transaction_info = Tbl_transaction_list::transaction()->where('transaction_list_id', $transaction_list_id)->first();
        $transaction_date = Carbon::now();

        if($cart_wallet_amount != 0)
        {        
            $ins_wallet['shop_id'] = $shop_id;
            $wallet_log_slot = $slot_id;
            $wallet_log_slot_sponsor = $slot_id;
            $wallet_log_claimbale_on = $transaction_date;
            $wallet_log_details = 'Thank you for purchasing. '.$cart_wallet_amount.' is deducted to your wallet';
            $wallet_log_amount = $cart_wallet_amount * -1;
            $wallet_log_plan = 'REPURCHASE';
            $wallet_log_status = 'released';
            $wallet_log_remarks = 'Wallet Purchase on POS - '.$transaction_info->transaction_number;
            
            if($slot_id)
            {
                Mlm_slot_log::slot($wallet_log_slot, $wallet_log_slot_sponsor, $wallet_log_details, $wallet_log_amount, $wallet_log_plan, $wallet_log_status,   $wallet_log_claimbale_on, $wallet_log_remarks);
            }
        }
        if($cart_gc_amount != 0)
        {
            $ins_gc['points_log_complan'] = 'PURCHASE_GC';
            $ins_gc['points_log_level'] = 0;
            $ins_gc['points_log_slot'] = $slot_id;
            $ins_gc['points_log_Sponsor'] = $slot_id;
            $ins_gc['points_log_date_claimed'] = $transaction_date;
            $ins_gc['points_log_converted_date'] = $transaction_date;
            $ins_gc['points_log_type'] = 'GC';
            $ins_gc['points_log_from'] = 'GC Purchase on POS - '.$transaction_info->transaction_number;
            $ins_gc['points_log_points'] = $cart_gc_amount * -1;
            
            if($slot_id)
            {
                Mlm_slot_log::point_slot($ins_gc);
            }
        }

        $get_all_payment = Cart2::cart_payment_list($shop_id);

        $insert_payment = null;
        foreach ($get_all_payment as $key => $value) 
        {
            $insert_payment[$key]['transaction_id'] = $transaction_info->transaction_id;
            $insert_payment[$key]['transaction_payment_type'] = $value->payment_type;
            $insert_payment[$key]['transaction_payment_amount'] = $value->payment_amount;
            $insert_payment[$key]['transaction_payment_date'] = $transaction_date;
        }

        if(count($insert_payment) > 0)
        {
            Tbl_transaction_payment::insert($insert_payment);
            $return = 1;
        }

        return $return;
    }
    public static function get_payment($transaction_id)
    {
        return Tbl_transaction_payment::where('transaction_id',$transaction_id)->get();
    }    
    public static function get_transaction_reference_number($shop_id,$key)
    {

        $data = Tbl_transaction_ref_number::where('shop_id',$shop_id)->where('key',$key);
        
        return $data;
    }
}