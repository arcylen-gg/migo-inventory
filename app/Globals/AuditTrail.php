<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_item;
use App\Models\Tbl_user;
use App\Models\Tbl_customer;
use App\Models\Tbl_vendor;
use App\Models\Tbl_membership;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_voucher;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_mlm_slot_wallet_type;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_mlm_slot_wallet_log_refill_settings;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_mlm_encashment_process;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_employee;
use App\Models\Tbl_sir;
use App\Models\Tbl_um;
use App\Models\Tbl_category;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_position;
use App\Models\Tbl_truck;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_bill;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_write_check;
use App\Models\Tbl_customer_estimate;

use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use DB;
use Carbon\Carbon;
use Session;

class AuditTrail
{
    public static function getUser()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public static function record_logs($action ="", $source="", $source_id = 0 , $old_data ="" , $new_data="")
    {
        $insert_log["user_id"] = AuditTrail::getUser();
    	$insert_log["remarks"] = $action;
    	$insert_log["source"] = $source;
    	$insert_log["source_id"] = $source_id;
    	$insert_log["old_data"] = $old_data;
    	$insert_log["new_data"] = $new_data;
        $insert_log["audit_shop_id"] = AuditTrail::getShopId();
        $insert_log["created_at"] = Carbon::now();
        
        if($insert_log["user_id"])
        {
    		$id = Tbl_audit_trail::insertGetId($insert_log);
            

    		return $id;
        }
        else
        {
            return null;
        }
    }
    public static function getSearchAuditData($col, $key, $from=null, $to=null)
    {      
        $query = Tbl_audit_trail::user()->orderBy("tbl_audit_trail.created_at","DESC")->where("audit_shop_id",AuditTrail::getShopId());   

        switch ($col) {            
            case 'remarks':
               $query = $query
                        ->where("remarks", "LIKE", '%' .$key. '%');
                break;            
            case 'fullname':
               $query = $query
                        ->where(DB::raw("concat(user_first_name, ' ', user_last_name)"), "LIKE", "%" .$key. "%");
                break;
            default:            
                break;
        }

        if ($from != null && $to != null)
        {
            $from   = date($from . ' 00:00:00', time());
            $to     = date($to . ' 23:59:00', time());

            $audit_trail = $query
                        ->whereBetween("created_at", [$from, $to])
                        ->paginate(15);        
        } else {
            $audit_trail = $query->paginate(15);  
        }
        

        foreach ($audit_trail as $key => $value) 
        {            
            $transaction_date = "";
            $transaction_client = "";
            $transaction_amount = "";
            $transaction_new_id = "";

            if($value->source == "invoice")
            {
                $transaction = Tbl_customer_invoice::customer()->where("inv_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->inv_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->inv_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["inv_overall_price"];
                        $transaction_new_id = $old[$key]["new_inv_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "sales_receipt")
            {
                $transaction = Tbl_customer_invoice::customer()->where("inv_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->inv_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->inv_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["inv_overall_price"];
                        $transaction_new_id = $old[$key]["new_inv_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "bill")
            {
                $transaction = Tbl_bill::vendor()->where("bill_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->bill_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->bill_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["bill_total_amount"];
                        $transaction_new_id = $old[$key]["bill_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "write_check")
            {
                $transaction = Tbl_write_check::vendor()->where("wc_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->wc_payment_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->wc_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["wc_total_amount"];
                        $transaction_new_id = $old[$key]["wc_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "write_check_bill")
            {
                $transaction = Tbl_write_check::vendor()->where("wc_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->wc_payment_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->wc_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["wc_total_amount"];
                        $transaction_new_id = $old[$key]["wc_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "bill_payment_check")
            {
                $transaction = Tbl_write_check::vendor()->where("wc_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->wc_payment_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->wc_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["wc_total_amount"];
                        $transaction_new_id = $old[$key]["wc_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "receive_inventory")
            {
                $transaction = Tbl_bill::vendor()->where("bill_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->bill_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->bill_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["bill_total_amount"];
                        $transaction_new_id = $old[$key]["bill_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "purchase_order")
            {
                $transaction = Tbl_purchase_order::vendor()->where("po_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->po_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->po_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["po_overall_price"];
                        $transaction_new_id = $old[$key]["po_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "receive_payment")
            {
                $transaction = Tbl_receive_payment::customer()->where("rp_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->rp_date));$transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->rp_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["rp_total_amount"];
                        $transaction_new_id = $old[$key]["rp_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "bill_payment")
            {
                $transaction = Tbl_pay_bill::vendor()->where("paybill_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->paybill_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->paybill_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["paybill_total_amount"];
                        $transaction_new_id = $old[$key]["paybill_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "credit_memo")
            {
                $transaction = Tbl_credit_memo::customer()->where("cm_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->cm_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->cm_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["cm_amount"];
                        $transaction_new_id = $old[$key]["cm_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "estimate")
            {
                $transaction = Tbl_customer_estimate::customer()->where("est_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->est_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->est_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["est_overall_price"];
                        $transaction_new_id = $old[$key]["est_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "sales_order")
            {
                $transaction = Tbl_customer_estimate::customer()->where("est_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->est_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->est_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["est_overall_price"];
                        $transaction_new_id = $old[$key]["est_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "debit_memo")
            {
                $transaction = Tbl_debit_memo::vendor()->where("db_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->date_created));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->db_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["db_amount"];
                        $transaction_new_id = $old[$key]["db_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "item")
            {                
                $transaction = Tbl_item::where("item_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $item_qty = 1;
                    if(Purchasing_inventory_system::check() != 0)
                    {
                        $item_qty = UnitMeasurement::getQty($transaction->item_measurement_id);
                    }
                    $transaction_date = date("m/d/y", strtotime($transaction->item_date_created));

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_price * $item_qty;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_price"] * $item_qty;
                        //$transaction_new_id = $old[$key]["item_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "customer")
            {
                $transaction = Tbl_customer::where("customer_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["customer_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "vendor")
            {
                $transaction = Tbl_vendor::info()->where("vendor_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["vendor_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_membership")
            {
                $transaction = Tbl_membership::where("membership_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_date_created));
                    $transaction_client = $transaction->membership_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_price"];
                        $transaction_new_id = $old[$key]["membership_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "mlm_membership_code_invoice")
            {
                $transaction = Tbl_membership_code_invoice::customer()->where("membership_code_invoice_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_code_date_created));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_total;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_total"];
                        $transaction_new_id = $old[$key]["membership_code_invoice_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "mlm_membership_package")
            {                
                $transaction = Tbl_membership_package::membership()->where("membership_package_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_package_created));
                    $transaction_client = $transaction->membership_package_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_price"];
                        $transaction_new_id = $old[$key]["membership_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "voucher")
            {                
                $transaction = Tbl_voucher::customer()->where("voucher_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["voucher_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_slot")
            {                
                $transaction = Tbl_mlm_slot::customer()->where("slot_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->slot_created_date)); 
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["slot_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_wallet_type")
            {                
                $transaction = Tbl_mlm_slot_wallet_type::where("wallet_type_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->wallet_type_key;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["wallet_type_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_plan_setting")
            {                
                $transaction = Tbl_mlm_plan_setting::where("shop_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->wallet_type_key;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["shop_id"];
                    }
                    $transaction_amount = "";                    
                }
            }            
            else if($value->source == "mlm_plan")
            {                
                $transaction = Tbl_mlm_plan::where("marketing_plan_code_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->marketing_plan_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["marketing_plan_code_id"];
                    }
                    $transaction_amount = "";                    
                }
            }       
            else if($value->source == "mlm_item_points")
            {                
                $transaction = Tbl_mlm_item_points::where("item_points_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = "STAIRSTEP RANK BONUS ".$transaction->STAIRSTEP;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["item_points_id"];
                    }
                    $transaction_amount = "";                    
                }
            }    
            else if($value->source == "mlm_discount_item")
            {                
                $transaction = Tbl_mlm_item_discount::item()->where("item_discount_d",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->item_name." ".currency("PHP",$transaction->item_price);

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_discount_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_discount_price"];
                        $transaction_new_id = $old[$key]["item_discount_d"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }    
            else if($value->source == "mlm_item_code_invoice")
            {                
                $transaction = Tbl_item_code_invoice::customer()->where("item_code_invoice_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->item_code_date_created));
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_total;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_total"];
                        $transaction_new_id = $old[$key]["item_code_invoice_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }    
            else if($value->source == "mlm_slot_wallet_refill_settings")
            {                
                $transaction = Tbl_mlm_slot_wallet_log_refill_settings::where("wallet_log_refill_settings_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = "";
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["wallet_log_refill_settings_id"];
                    }
                    $transaction_amount = "";                    
                }
            }  
            else if($value->source == "mlm_wallet_log_slot")
            {                
                $transaction = Tbl_mlm_slot_wallet_log::slot()->customer()->where("wallet_log_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->wallet_log_date_created));
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->wallet_log_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["wallet_log_amount"];
                        $transaction_new_id = $old[$key]["wallet_log_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);
                }
            }
            else if($value->source == "mlm_encashment_settings")
            {                
                $transaction = Tbl_mlm_encashment_settings::where("enchasment_settings_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = "";
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["enchasment_settings_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_encash")
            {                
                $transaction = Tbl_mlm_encashment_process::where("encashment_process",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->enchasment_process_executed));
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->encashment_process_sum;
                    if(isset($old))
                    {
                        $amount = $old[$key]["encashment_process_sum"];
                        $transaction_new_id = $old[$key]["encashment_process"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_load_out_form")
            {                
                $transaction = Tbl_sir::saleagent()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_stock_issuance_report")
            {                
                $transaction = Tbl_sir::saleagent()->truck()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_incoming_load_report")
            {                
                $transaction = Tbl_sir::saleagent()->truck()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "agent_collection")
            {
                $transaction = Tbl_sir::where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($value->created_at));
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->agent_collection;
                    if(isset($old))
                    {
                        $amount = $old[$key]["agent_collection"];
                        $transaction_new_id = $old[$key]["sir_id"];
                        $transaction_client = $old[$key]["agent_collection_remarks"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "agent")
            {
                $transaction = Tbl_employee::where("employee_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["employee_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "pis_um")
            {
                $transaction = Tbl_um::where("id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($value->created_at));
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "category")
            {
                $transaction = Tbl_category::where("type_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->type_date_created));
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["type_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "warehouse_inventory")
            {
                $transaction = Tbl_inventory_slip::warehouse()->where("inventory_slip_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->inventory_slip_date));
                    $transaction_client = $transaction->inventory_remarks." in <strong>".$transaction->warehouse_name."</strong>";

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["inventory_slip_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "warehouse")
            {
                $transaction = Tbl_warehouse::where("warehouse_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->warehouse_created));
                    $transaction_client = $transaction->warehouse_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["warehouse_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "manufacturer")
            {
                $transaction = Tbl_manufacturer::where("manufacturer_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->date_created));
                    $transaction_client = $transaction->manufacturer_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["manufacturer_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "store_information")
            {
                $transaction = Tbl_shop::where("shop_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($value->created_at));
                    $transaction_client = $transaction->shop_key;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["shop_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "user")
            {
                $transaction = Tbl_user::where("user_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($value->created_at));
                    $transaction_client = $transaction->user_first_name." ".$transaction->user_last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["user_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "agent_position")
            {
                $transaction = Tbl_position::where("position_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->position_created));
                    $transaction_client = $transaction->position_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["position_id"];
                    }
                    $transaction_amount = '';                    
                }
            }
            else if($value->source == "truck")
            {
                $transaction = Tbl_truck::where("truck_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->plate_number;

                    $old[$key] = unserialize($value->new_data);
                    $amount = '';
                    if(isset($old))
                    {
                        $amount = '';
                        $transaction_new_id = $old[$key]["truck_id"];
                    }
                    $transaction_amount = '';                    
                }
            }



            $audit_trail[$key]->user = $value->user_first_name." ".$value->user_last_name;
            $audit_trail[$key]->action = $value->remarks;
            $audit_trail[$key]->transaction = "";
            $audit_trail[$key]->transaction_no = "";
            $audit_trail[$key]->transaction_txt  = "";
            $audit_trail[$key]->transaction_date  = "";
            if($value->source != null)
            {
                $audit_trail[$key]->transaction = $value->source;
                $audit_trail[$key]->transaction_no = $value->source_id;
                if($transaction_new_id != null)
                {
                    $audit_trail[$key]->transaction_txt = ucwords(str_replace("_", " ",$value->source))." No. ".$transaction_new_id;
                }
                else
                {
                    $audit_trail[$key]->action = "";
                    $audit_trail[$key]->transaction_txt = "Transaction not found.";
                }
            }
            $audit_trail[$key]->transaction_date = $transaction_date;
            $audit_trail[$key]->transaction_client = $transaction_client;
            $audit_trail[$key]->transaction_amount = $transaction_amount;
        }
        // dd($old[1]["inv_overall_price"]);
        //dd($audit_trail[$key]->user);
        return $audit_trail;
    }
    public static function get_table_data($tbl_name,$id_column_name,$id)
    {
        return collect(DB::table($tbl_name)->where($id_column_name,$id)->first())->toArray();
    }

    public static function getAudit_data()
    {        

        // old query
        // $audit_trail = Tbl_audit_trail::user()->orderBy("tbl_audit_trail.created_at","DESC")->where("audit_shop_id",AuditTrail::getShopId())->paginate(15);
        $audit_trail = Tbl_audit_trail::user()->select(DB::raw('tbl_audit_trail.created_at as audit_created_at, tbl_user.created_at as user_created_at, tbl_audit_trail.* , tbl_user.*'))->orderBy("tbl_audit_trail.created_at","DESC")->where("audit_shop_id",AuditTrail::getShopId())->paginate(15);
      

        foreach ($audit_trail as $key => $value) 
        {            
            $transaction_date = "";
            $transaction_client = "";
            $transaction_amount = "";
            $transaction_new_id = "";

            if($value->source == "invoice")
            {
                $transaction = Tbl_customer_invoice::customer()->where("inv_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->inv_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->inv_overall_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["inv_overall_price"];
                        $transaction_new_id = $old[$key]["new_inv_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "purchase_order")
            {
                $transaction = Tbl_purchase_order::vendor()->where("po_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->po_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->po_overall_price;
                    if(isset($old))
                    {
                        //$amount = $old[$key]["po_overall_price"];
                        //$transaction_new_id = $old[$key]["po_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "receive_payment")
            {
                $transaction = Tbl_receive_payment::customer()->where("rp_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->rp_date));$transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->rp_total_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["rp_total_amount"];
                        $transaction_new_id = $old[$key]["rp_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "item")
            {
                $transaction = Tbl_item::where("item_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->item_date_created));

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_price"];
                        //$transaction_new_id = $old[$key]["item_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "customer")
            {
                $transaction = Tbl_customer::where("customer_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_date));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["customer_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "vendor")
            {
                $transaction = Tbl_vendor::info()->where("vendor_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_date));
                    $transaction_client = $transaction->company != null ? $transaction->vendor_company : $transaction->vendor_title_name." ".$transaction->vendor_first_name." ".$transaction->vendor_middle_name." ".$transaction->vendor_last_name." ".$transaction->vendor_suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["vendor_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_membership")
            {
                $transaction = Tbl_membership::where("membership_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_date_created));
                    $transaction_client = $transaction->membership_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_price"];
                        $transaction_new_id = $old[$key]["membership_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "mlm_membership_code_invoice")
            {
                $transaction = Tbl_membership_code_invoice::customer()->where("membership_code_invoice_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_code_date_created));
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_total;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_total"];
                        $transaction_new_id = $old[$key]["membership_code_invoice_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "mlm_membership_package")
            {                
                $transaction = Tbl_membership_package::membership()->where("membership_package_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->membership_package_created));
                    $transaction_client = $transaction->membership_package_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->membership_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["membership_price"];
                        $transaction_new_id = $old[$key]["membership_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "voucher")
            {                
                $transaction = Tbl_voucher::customer()->where("voucher_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->company != null ? $transaction->company : $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["voucher_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_slot")
            {                
                $transaction = Tbl_mlm_slot::customer()->where("slot_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->slot_created_date)); 
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["slot_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_wallet_type")
            {                
                $transaction = Tbl_mlm_slot_wallet_type::where("wallet_type_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->wallet_type_key;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["wallet_type_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_plan_setting")
            {                
                $transaction = Tbl_mlm_plan_setting::where("shop_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->wallet_type_key;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["shop_id"];
                    }
                    $transaction_amount = "";                    
                }
            }            
            else if($value->source == "mlm_plan")
            {                
                $transaction = Tbl_mlm_plan::where("marketing_plan_code_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->marketing_plan_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["marketing_plan_code_id"];
                    }
                    $transaction_amount = "";                    
                }
            }       
            else if($value->source == "mlm_item_points")
            {                
                $transaction = Tbl_mlm_item_points::where("item_points_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = "STAIRSTEP RANK BONUS ".$transaction->STAIRSTEP;

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["item_points_id"];
                    }
                    $transaction_amount = "";                    
                }
            }    
            else if($value->source == "mlm_discount_item")
            {                
                $transaction = Tbl_mlm_item_discount::item()->where("item_discount_d",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = ""; 
                    $transaction_client = $transaction->item_name." ".currency("PHP",$transaction->item_price);

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_discount_price;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_discount_price"];
                        $transaction_new_id = $old[$key]["item_discount_d"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }    
            else if($value->source == "mlm_item_code_invoice")
            {                
                $transaction = Tbl_item_code_invoice::customer()->where("item_code_invoice_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->item_code_date_created));
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->item_total;
                    if(isset($old))
                    {
                        $amount = $old[$key]["item_total"];
                        $transaction_new_id = $old[$key]["item_code_invoice_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }    
            else if($value->source == "mlm_slot_wallet_refill_settings")
            {                
                $transaction = Tbl_mlm_slot_wallet_log_refill_settings::where("wallet_log_refill_settings_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = "";
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["wallet_log_refill_settings_id"];
                    }
                    $transaction_amount = "";                    
                }
            }  
            else if($value->source == "mlm_wallet_log_slot")
            {                
                $transaction = Tbl_mlm_slot_wallet_log::slot()->customer()->where("wallet_log_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->wallet_log_date_created));
                    $transaction_client = $transaction->title_name." ".$transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name." ".$transaction->suffix_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->wallet_log_amount;
                    if(isset($old))
                    {
                        $amount = $old[$key]["wallet_log_amount"];
                        $transaction_new_id = $old[$key]["wallet_log_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);
                }
            }
            else if($value->source == "mlm_encashment_settings")
            {                
                $transaction = Tbl_mlm_encashment_settings::where("enchasment_settings_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = "";
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = "";
                    if(isset($old))
                    {
                        $amount = "";
                        $transaction_new_id = $old[$key]["enchasment_settings_id"];
                    }
                    $transaction_amount = "";                    
                }
            }
            else if($value->source == "mlm_encash")
            {                
                $transaction = Tbl_mlm_encashment_process::where("encashment_process",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->enchasment_process_executed));
                    $transaction_client = "";

                    $old[$key] = unserialize($value->new_data);
                    $amount = $transaction->encashment_process_sum;
                    if(isset($old))
                    {
                        $amount = $old[$key]["encashment_process_sum"];
                        $transaction_new_id = $old[$key]["encashment_process"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_load_out_form")
            {                
                $transaction = Tbl_sir::saleagent()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_stock_issuance_report")
            {                
                $transaction = Tbl_sir::saleagent()->truck()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
            else if($value->source == "pis_incoming_load_report")
            {                
                $transaction = Tbl_sir::saleagent()->truck()->where("sir_id",$value->source_id)->first();
                if($transaction != null)
                {
                    $transaction_date = date("m/d/y", strtotime($transaction->created_at));
                    $transaction_client = $transaction->first_name." ".$transaction->middle_name." ".$transaction->last_name;

                    $old[$key] = unserialize($value->new_data);
                    $amount = 0;
                    if(isset($old))
                    {
                        $amount = $old[$key]["total_amount"];
                        $transaction_new_id = $old[$key]["sir_id"];
                    }
                    $transaction_amount = currency("PHP",$amount);                    
                }
            }
           

            $audit_trail[$key]->user = $value->user_first_name." ".$value->user_last_name;
            $audit_trail[$key]->action = $value->remarks;
            $audit_trail[$key]->transaction = "";
            $audit_trail[$key]->transaction_no = "";
            $audit_trail[$key]->transaction_txt  = "";
            $audit_trail[$key]->transaction_date  = "";
            if($value->source != null)
            {
                $audit_trail[$key]->transaction = $value->source;
                $audit_trail[$key]->transaction_no = $value->source_id;
                if($transaction_new_id != null)
                {
                    $audit_trail[$key]->transaction_txt = ucwords(str_replace("_", " ",$value->source))." No. ".$transaction_new_id;
                }
                else
                {
                    $audit_trail[$key]->action = "";

                    // $audit_trail[$key]->transaction_txt = "Transaction not found.";

                    $audit_trail[$key]->transaction_txt = ucwords(str_replace("_", " ",$value->remarks));
                }
            }
            $audit_trail[$key]->transaction_date = $transaction_date;
            $audit_trail[$key]->transaction_client = $transaction_client;
            $audit_trail[$key]->transaction_amount = $transaction_amount;
            
        }
        // dd($old[1]["inv_overall_price"]);
        //dd($audit_trail[$key]->user);
        return $audit_trail;
    }
    public static function delete_audit_trail($shop_id)
    {
        Tbl_audit_trail::where("audit_shop_id", $shop_id)->delete();
    }
}