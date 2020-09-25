<?php
namespace App\Globals;

use App\Models\Tbl_customer;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_customer_wis_item_line;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_wis_item;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_settings;
use App\Models\Tbl_admin_notification;
use App\Models\Tbl_customer_wis_budget;
use App\Models\Tbl_customer_wis_budgetline;
use App\Models\Tbl_item;
use App\Models\Tbl_quantity_monitoring;
use App\Models\Tbl_acctg_transaction;

use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Tablet_global;
use App\Globals\Currency;
use Session;
use DB;
use Carbon\Carbon;
use App\Globals\Merchant;
use Validator; 
class CustomerWIS
{   
   /* public static function transactionStatus($shop_id)
    {
        $get = Tbl_customer_wis::whereNull('transaction_status')->where("cust_wis_shop_id", $shop_id)->get();
        foreach ($get as $key => $value) 
        {
            $update_status['transaction_status'] = 'posted';
            Tbl_customer_wis::where("cust_wis_shop_id", $shop_id)->where("cust_wis_id", $value->cust_wis_id)->update($update_status);
        }
    }*/
    public static function insert_notification()
    {
        $_shop = DB::table("tbl_shop")->get();

        foreach ($_shop as $keyshop => $valueshop) 
        {
            $check = AccountingTransaction::settings($valueshop->shop_id, "notification_bar");

            if($check)
            {
                $datenow = date('Y-m-d',strtotime(Carbon::now()));
                $date_three = date('Y-m-d',strtotime(Carbon::now()->addDays(3)));
                $get = Tbl_customer_wis::warehouse()
                                        ->where("tbl_customer_wis.cust_delivery_date","<=",$date_three)
                                        ->where("cust_wis_shop_id", $valueshop->shop_id)
                                        ->where("cust_wis_status","confirm")
                                        ->get();
                
                $insert = null;
                foreach ($get as $key => $value) 
                {
                    $check = Tbl_admin_notification::where("transaction_refname",'wis')
                                                   ->where("transaction_refid", $value->cust_wis_id)
                                                   ->where("notification_shop_id", $valueshop->shop_id)
                                                   ->first();
                    if($check == null)
                    {
                        $insert[$key]['notification_shop_id'] = $valueshop->shop_id;
                        $insert[$key]['warehouse_id'] = $value->cust_wis_from_warehouse;
                        $insert[$key]['notification_description'] = 'The <strong>"'.$value->warehouse_name.'"</strong> is about to send <strong>Warehouse Issuance Slip No. "'.$value->transaction_refnum.'"</strong> on '.date("F d, Y",strtotime($value->cust_delivery_date));
                        $insert[$key]['transaction_refname'] = "wis";
                        $insert[$key]['transaction_refid'] = $value->cust_wis_id;
                        $insert[$key]['transaction_status'] = "pending";
                        $insert[$key]['transaction_date'] = $value->cust_delivery_date;
                        $insert[$key]['created_date'] = Carbon::now();
                    
                    }
                }
                if(count($insert) > 0)
                {
                    Tbl_admin_notification::insert($insert);
                }
            }
        }
    }
    public static function validate_item_against_sales($shop_id, $request, $_item)
    {
        $val = null;
        $project = AccountingTransaction::settings_value($shop_id, "project_name");
        if($request->sales_id && $project == 'fieldmen')
        {
            foreach ($_item as $key => $value) 
            {
                $inv = Tbl_customer_invoice::invoice_item()
                                           ->where("inv_id", $request->sales_id)
                                           ->where("invline_item_id", $value['item_id'])
                                           ->first();
                $sales_data = Tbl_customer_invoice::invoice_item()
                                           ->where("inv_id", $request->sales_id)
                                           ->first();
                $item_data = Item::info($value['item_id']);
                if($inv)
                {
                    $sales_qty = $inv->invline_qty * UnitMeasurement::get_umqty($inv->invline_um);
                    if($value['quantity'] > $sales_qty)
                    {
                        $val .= "The item <strong>".$inv->item_name."</strong> is greater than the quantity in sales <strong>".$inv->transaction_refnum."</strong>.<br>";
                    }
                }
                else
                {
                    $val .= "The item <strong>".$item_data->item_name."</strong> is not included in sales <strong>".$sales_data->transaction_refnum."</strong>.<br>";
                }
            }
        }

        return $val;
    }
    public static function get_customer($shop_id, $customer_id = 0)
    {
        $data = Tbl_customer::where('shop_id',$shop_id)->where('archived',0);
        if($customer_id != 0)
        {
            $data = $data->where('customer_id',$customer_id);
        }
        return $data->get();
    }
    public static function get_sales_info($shop_id, $transaction_id)
    {
        $return['info'] = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_id', $transaction_id)->first();
        $return['info_item'] = Tbl_customer_invoice_line::where('invline_inv_id', $transaction_id)->get();

        return $return;
    }
    public static function get_inv($shop_id, $inv_id)
    {
        return Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('inv_id', $inv_id)->first();
    }

    public static function get_item_per_wis($shop_id, $wis_id)
    {
        $_item = Tbl_customer_wis::item()->itemdetails()->where("cust_wis_shop_id", $shop_id)->where("cust_wis_id", $wis_id)->get();

        $return = null;
        foreach ($_item as $key => $value) 
        {
            $return[$key]['item_name'] = $value->item_name;
            $return[$key]['item_sku'] = $value->item_sku;
            $return[$key]['item_orig_qty'] = $value->itemline_orig_qty;
            $return[$key]['item_um'] = $value->multi_abbrev;
        }

        return $return;
    }

    public static function get_transaction_description($wis_id)
    {
        $info = Tbl_customer_wis::where("cust_wis_id", $wis_id)->first();
        $return = "Not Found";
        if($info)
        {
            $from_warehouse = Tbl_warehouse::where("warehouse_id",$info->cust_wis_from_warehouse)->value("warehouse_name");
            $customer = Tbl_customer::where("customer_id",$info->destination_customer_id)->first();

            $to_customer = "Not Found";
            if($customer)
            {
                $to_customer = $customer->company != '' ? $customer->company : $customer->first_name." ".$customer->middle_name." ".$customer->last_name;
            }

            $return = "From ".$from_warehouse." to ".$to_customer;
        }
        return $return;
    }
    public static function get_inv_item($shop_id, $inv_id)
    {
        return Tbl_customer_invoice_line::invoice_item()->um()->invoice()->where('inv_shop_id', $shop_id)->where('invline_inv_id', $inv_id)->get();
    }
    public static function settings($shop_id)
    {
        return Tbl_settings::where('settings_key','customer_wis')->where('shop_id', $shop_id)->value('settings_value');
    }
    public static function get_consume_validation($shop_id, $warehouse_id, $item_id, $quantity, $remarks, $bin = 0)
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        if(is_numeric($quantity) == false)
        { 
            $return .= "The quantity must be a number. <br>";
        }
        if($quantity < 0)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }
        $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $item_id, $bin);
        if($quantity > $inventory_qty)
        {
            $con_msg = "";
            if($bin)
            {
                $w_name = Tbl_warehouse::where("warehouse_id", $bin)->value("warehouse_name");
                $con_msg = " Please choose other than ". $w_name;
            }
            $return .= Self::string_replace_for_url("The quantity of <b>".Item::info($item_id)->item_name."</b> is not enough to consume. ".$con_msg."<br>");
        }
        
        return $return;
    }

    public static function get_update_consume_validation($shop_id, $refname, $refid , $warehouse_id, $item_id, $quantity, $remarks, $bin = 0)
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        if(is_numeric($quantity) == false)
        { 
            $return .= "The quantity must be a number. <br>";
        }
        if($quantity < 0)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }
        $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $item_id, $bin, $refname, $refid);
        if($quantity > $inventory_qty)
        {
            $con_msg = "";
            if($bin)
            {
                $w_name = Tbl_warehouse::where("warehouse_id", $bin)->value("warehouse_name");
                $con_msg = " Please choose other than ". $w_name;
            }
            $return .= Self::string_replace_for_url("The quantity of <b>".Item::info($item_id)->item_name."</b> is not enough to consume. ".$con_msg."<br>");
        }
        
        return $return;
    }
    public static function string_replace_for_url($str)
    {
        $str = str_replace("#", "No.", $str);
        $str = str_replace("&", "and", $str);

        return $str;
    }
    public static function applied_transaction($shop_id, $transaction_id = 0, $for_update = null)
    {
        $applied_transaction = Session::get('applied_transaction_wis');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                AccountingTransaction::checkInvlineQty($key, $transaction_id, $for_update);
                // $update['item_delivered'] = 1;
                // Tbl_customer_invoice::where("inv_id", $key)->where('inv_shop_id', $shop_id)->update($update);
            }
        }
        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }

    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_customer_wis::where("cust_wis_shop_id", $shop_id)->where("cust_wis_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "warehouse_issuance_slip";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->cust_delivery_date;

            $attached_transaction_data = null;
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value) 
                {
                    $get_data = Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $key)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$key]['transaction_ref_name'] = "sales_invoice";
                        if($get_data->is_sales_receipt == 1)
                        {
                            $attached_transaction_data[$key]['transaction_ref_name'] = "sales_receipt";
                        }
                        $attached_transaction_data[$key]['transaction_ref_id'] = $key;
                        $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$key]['transaction_date'] = $get_data->inv_date;
                    }
                }
            }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }
    public static function customer_create_wis($shop_id, $remarks, $ins, $_item = array(), $insert_item = array())
    {
        $validate = null;
        $warehouse_id = $ins['cust_wis_from_warehouse'];
        // dd($_item);

        if(count($_item) <= 0)
        {
            $validate .= "Please Select item.<br>";
        }
        if(!$ins['destination_customer_id'])
        {
            $validate .= "Please Select customer.<br>";
        }
        if(!$validate)
        {
            foreach ($_item as $key => $value)
            {
                $validate .= CustomerWIS::get_consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $value['bin_location']);
            }        
        }
        
        $check = Tbl_customer_wis::where('transaction_refnum',$ins['transaction_refnum'])->where('cust_wis_shop_id',$shop_id)->first();
        //die(var_dump($check));
 
        if($check)
        {
            $validate .= 'WIS number already exist';
        }
        if(!$validate)
        {
            $wis_id = Tbl_customer_wis::insertGetId($ins);
            $reference_name = 'customer_wis';

            /* TOTAL */
            $overall_price = collect($insert_item)->sum('item_amount'); /*SALES PRICE*/
            $overall_price = Self::getamount($insert_item); /*COST PRICE*/

            /* Transaction Journal */
            $entry["reference_module"]  = 'warehouse-issuance-slip';
            $entry["reference_id"]      = $wis_id;
            $entry["name_id"]           = $ins['destination_customer_id'];
            $entry["total"]             = $overall_price;
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = '';

            $val = Self::insertline($wis_id, $insert_item, $entry, false);
            if(is_numeric($val))
            {
               /* $settings_auto_post_transaction = AccountingTransaction::settings($shop_id, 'auto_post_transaction');
                if($settings_auto_post_transaction == 1)
                {
                    $update_status['transaction_status'] = 'posted';
                    Tbl_customer_wis::where('cust_wis_shop_id', $wis_id)->update($update_status);*/
                    $return = Warehouse2::consume_bulk($shop_id, $warehouse_id, $reference_name, $wis_id ,$remarks ,$_item);

                    if(!$return)
                    {
                        $validate = $wis_id + 0;
                        $check = AccountingTransaction::settings($shop_id, "optimize_wiswt");
                        if(!$check)
                        {
                            $get_item = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','customer_wis')->where('record_consume_ref_id',$wis_id)->get();

                            $ins_customer_item = null;
                            foreach ($get_item as $key_item => $value_item)
                            {
                                $ins_customer_item[$key_item]['cust_wis_id'] = $wis_id;
                                $ins_customer_item[$key_item]['cust_wis_record_log_id'] = $value_item->record_log_id;
                            }

                            if($ins_customer_item)
                            {
                                Tbl_customer_wis_item::insert($ins_customer_item);
                            }                        
                        }
                    }
                /*}*/               
            }
        }

        return $validate;
    }
    public static function update_wis($shop_id, $wis_id, $update = array())
    {
        if(count($update) > 0)
        {
            return Tbl_customer_wis::where("cust_wis_shop_id", $shop_id)->where("cust_wis_id", $wis_id)->update($update);
        }
    }
    public static function customer_update_wis($wis_id, $shop_id, $remarks, $ins, $_item = array(), $insert_item = array())
    {
        $old = Tbl_customer_wis::where("cust_wis_id", $wis_id)->first();


        $validate = null;

        if($old)
        {
            $warehouse_id = $old->cust_wis_from_warehouse;
        }
        if(!$warehouse_id)
        {
            $validate .= "Warehouse unknown.";
        }
        if(count($_item) <= 0)
        {
            $validate .= "Please Select item.<br>";
        }
        if(!$ins['destination_customer_id'])
        {
            $validate .= "Please Select customer.<br>";
        }
        if(!$validate)
        {

            $refname = "customer_wis";
            $refid = $wis_id;
            foreach ($_item as $key => $value)
            {
                $validate .= CustomerWIS::get_update_consume_validation($shop_id,$refname, $refid, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $value['bin_location']);
            }        
        }
        
        /*$check = Tbl_customer_wis::where('transaction_refnum',$ins['transaction_refnum'])->where('cust_wis_shop_id',$shop_id)->first();
        //die(var_dump($check));
 
        if($check)
        {
            $validate .= 'WIS number already exist';
        }*/
        if(!$validate && $validate == "")
        {
            Tbl_customer_wis::where("cust_wis_id", $wis_id)->update($ins);
            //die(var_dump($wis_id));
           
            //Tbl_customer_wis::insertGetId($ins);
            $reference_name = 'customer_wis';
            //Tbl_purchase_order::where("po_id", $po_id)->update($update);

            /* TOTAL */
            $overall_price = collect($insert_item)->sum('item_amount'); /*SALES PRICE*/
            $overall_price = Self::getamount($insert_item); /*COST PRICE*/

            /* Transaction Journal */
            $entry["reference_module"]  = 'warehouse-issuance-slip';
            $entry["reference_id"]      = $wis_id;
            $entry["name_id"]           = $ins['destination_customer_id'];
            $entry["total"]             = $overall_price;
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = ''; 
            Tbl_customer_wis_item::where("cust_wis_id", $wis_id)->delete();
            Tbl_customer_wis_item_line::where("itemline_wis_id", $wis_id)->delete();
            //Self::insertLine($po_id, $insert_item); 

            $val = Self::insertline($wis_id, $insert_item, $entry, true);
            if(is_numeric($val))
            {
                $validate = $val;
                // Warehouse2::update_inventory_consume($shop_id, $warehouse_id, $reference_name, $wis_id); 
                $return = Warehouse2::consume_bulk($shop_id, $warehouse_id, $reference_name, $wis_id ,$remarks ,$_item);
                if(!$return)
                {
                    $validate = $wis_id + 0;
                    $check = AccountingTransaction::settings($shop_id, "optimize_wiswt");
                    if(!$check)
                    {
                        $get_item = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','customer_wis')->where('record_consume_ref_id',$wis_id)->where("record_warehouse_id", $warehouse_id)->get();

                        $ins_customer_item = null;
                        foreach ($get_item as $key_item => $value_item)
                        {
                            $ins_customer_item[$key_item]['cust_wis_id'] = $wis_id;
                            $ins_customer_item[$key_item]['cust_wis_record_log_id'] = $value_item->record_log_id;
                        }

                        if($ins_customer_item)
                        {
                            Tbl_customer_wis_item::insert($ins_customer_item);
                        }
                    }
                }                
            }
        }

        return $validate;
    }
    public static function insertline($cust_wis_id, $insert_item, $entry, $for_update ='', $shop_id = '')
    {
        if(count($insert_item) > 0)
        {
            $return = null;
            $id_not_delete = array();
            $itemline = null;
            foreach ($insert_item as $key => $value) 
            {
                $itemline['itemline_wis_id']      = $cust_wis_id;
                $itemline['itemline_item_id']     = $value['item_id'];
                $itemline['itemline_description'] = $value['item_description'];
                $itemline['itemline_sub_wh_id']   = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
                $itemline['itemline_qty']         = $value['item_qty'];
                $itemline['itemline_orig_qty']    = $value['item_qty'];
                $itemline['itemline_um']          = $value['item_um'];
                $itemline['itemline_rate']        = $value['item_rate'];
                $itemline['itemline_amount']      = $value['item_amount'];
                $itemline['itemline_refname']     = $value['item_refname'];
                $itemline['itemline_refid']       = $value['item_refid'];

                $wis_line_id = Tbl_customer_wis_item_line::insert($itemline);

                array_push($id_not_delete, $wis_line_id);

                if($itemline['itemline_refid'] && $itemline['itemline_refname'])
                {
                    $qty = Tbl_quantity_monitoring::where('qty_transaction_id', $cust_wis_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $shop_id)->first();

                    if($qty == null || $for_update == false)
                    {
                        $insert_qty_item['qty_item_id']              = $itemline['itemline_item_id'];
                        $insert_qty_item['qty_transaction_id']       = $cust_wis_id;
                        $insert_qty_item['qty_transaction_name']     = 'customer_wis';
                        $insert_qty_item['qty_transactionline_id']   = $wis_line_id;
                        $insert_qty_item['qty_ref_id']               = $itemline['itemline_refid'];
                        $insert_qty_item['qty_ref_name']             = $itemline['itemline_refname'];
                        $insert_qty_item['qty_old']                  = $itemline['itemline_qty'];
                        $insert_qty_item['qty_new']                  = $itemline['itemline_qty'];
                        $insert_qty_item['qty_shop_id']              = $shop_id;
                        $insert_qty_item['created_at']               = Carbon::now();
                        Tbl_quantity_monitoring::insert($insert_qty_item);
                    }
                    else
                    {
                        $insert_qty_item['qty_old'] = $qty->qty_new;
                        $insert_qty_item['qty_new'] = $value['item_qty'];
                        $insert_qty_item['qty_transactionline_id'] = $wis_line_id;
                        Tbl_quantity_monitoring::where('qty_transaction_id', $cust_wis_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
                    }
                }
                if($id_not_delete != null)
                {
                    Tbl_quantity_monitoring::where("qty_transaction_id", $cust_wis_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'customer_wis')->delete();
                }
                foreach ($insert_item as $keys => $values) 
                {
                    $item = Tbl_item::where("item_id", $values['item_id'])->first();
                    $insert_item[$keys] = $values;
                    $insert_item[$keys]['item_rate'] = isset($item->item_cost) ? $item->item_cost : $insert_item[$keys]['item_rate']; 
                    $insert_item[$keys]['item_amount'] = $insert_item[$keys]['item_rate'] *  (UnitMeasurement::get_umqty($values['item_id']) * $values['item_qty']);
                }
                $return = AccountingTransaction::entry_data($entry, $insert_item);
                $return = intval($cust_wis_id);
            }           
        }
        return $return;
    }
    public static function getamount($insert_item)
    {
        $amount = 0;
        foreach ($insert_item as $keys => $values) 
        {
            $item = Tbl_item::where("item_id", $values['item_id'])->first();
            $insert_item[$keys] = $values;
            $insert_item[$keys]['item_rate'] = isset($item->item_cost) ? $item->item_cost : $insert_item[$keys]['item_rate']; 
            $amount += $insert_item[$keys]['item_rate'] *  (UnitMeasurement::get_umqty($values['item_id']) * $values['item_qty']);
        }
        return $amount;
    }

    public static function get_all_customer_wis($shop_id = 0, $status = 'pending', $current_warehouse = 0, $from = '', $to = '', $paginate = '', $search_keyword = '')
    {
        $data = Tbl_customer_wis::CustomerInfo()->truck()->where('cust_wis_shop_id',$shop_id)->groupBy('tbl_customer_wis.cust_wis_id')->where('cust_wis_from_warehouse', $current_warehouse)->orderBy("cust_delivery_date","desc");
        
        if($status != 'all')
        {
            $data = $data->where('cust_wis_status', $status);
        }
        if($from && $to)
        {
            $data = $data->whereBetween('tbl_customer_wis.created_at', [$from, $to]);
        }

        if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");
            });
        }

        if(is_numeric($paginate))
        {
            $data = $data->paginate($paginate);
        }
        else
        {
            $data = $data->get();
        }
        foreach ($data as $key => $value) 
        {
            $data[$key]->issued_created_by = Self::issued_created_by($shop_id, $value->cust_wis_id);
        }

        //die(var_dump($data));
        return $data;

    }

    public static function issued_created_by($shop_id, $wis_id)
    {
        $get_info = Tbl_acctg_transaction::list()->user()
                                         ->where("shop_id", $shop_id)
                                         ->where("transaction_ref_name","warehouse_issuance_slip")
                                         ->where("transaction_ref_id",$wis_id)->first();
        $name = "not found";
        if($get_info)
        {
            $name = $get_info->user_first_name." ".$get_info->user_last_name;
        }
        return $name;
    }
    public static function get_customer_wis_data($cust_wis_id)
    {
        return Tbl_customer_wis::warehouse()->truck()->customerinfo()->where('cust_wis_shop_id',WarehouseTransfer::getShopId())->where('cust_wis_id',$cust_wis_id)->first();
    }

    public static function update_customer_wis($shop_id, $cust_wis_id, $update)
    {
        return Tbl_customer_wis::where('cust_wis_shop_id',$shop_id)->where('cust_wis_id',$cust_wis_id)->update($update);
    }
    public static function get_customer_wis_item($cust_wis_id)
    {
        $return_item = Tbl_warehouse_inventory_record_log::item()->inventory()->where('record_consume_ref_name','customer_wis')->where('record_consume_ref_id',$cust_wis_id)->groupBy('record_item_id')->get();

        return $return_item;
    }
    public static function get_wis_line($cust_wis_id)
    {
        return Tbl_customer_wis_item_line::um()->item()->where("itemline_wis_id", $cust_wis_id)->get();
    }
    public static function get_wis_monthly_budget($cust_wis_id)
    {
        $data['_budget'] = Tbl_customer_wis_budget::where('budget_wis_id', $cust_wis_id)->first();
        if($data['_budget'])
        {
            $data['_budgetline'] = Tbl_customer_wis_budgetline::where('budgetline_id', $data['_budget']->wis_budget_id)->get();
        }
        return $data;
    }

    public static function print_customer_wis_item($wis_id)
    {
        return Tbl_customer_wis_item::InventoryItem()->where('cust_wis_id',$wis_id)->groupBy('record_item_id')->get();
    }
    public static function customer_wis_itemline($wis_id)
    {
        $data = Tbl_customer_wis_item_line::item()->um()->binLocation()->where('itemline_wis_id',$wis_id)->get();

        foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->itemline_um);
            $total_qty = $value->itemline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->itemline_um);
            $data[$key]->bin = '';
            if($value->itemline_sub_wh_id)
            {
                $data[$key]->bin = Warehouse2::get_bin_location_name($value->itemline_sub_wh_id);
            }
        }
        return $data;
    }
    public static function countTransaction($shop_id, $customer_id)
    {
        $so = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_customer_id",$customer_id)->where("est_status","accepted")->count();
        $so = 0;
        $inv = TransactionSalesInvoice::countUndeliveredSalesInvoice($shop_id, $customer_id);
        $sr = TransactionSalesReceipt::countUndeliveredSalesReceipt($shop_id, $customer_id);
        return $inv + $sr;
    }

    public static function check_qty($inv_id, $wis_id)
    {
        $invline = Tbl_customer_invoice_line::invoice()->where('invline_inv_id', $inv_id)->get();
        $ctr = 0;
        foreach ($invline as $key => $value)
        {
            if($value)
            {
                $transaction = "sales_invoice";
                if($value->is_sales_receipt == 1)
                {
                    $transaction = "sales_receipt";
                }
                $itemline = Tbl_customer_wis_item_line::where('itemline_wis_id', $wis_id)
                                                    ->where('itemline_refname', $transaction)
                                                    ->where('itemline_item_id', $value->invline_item_id)
                                                    ->where('itemline_refid',$inv_id)
                                                    ->first();
                $update['invline_qty'] = $value->invline_qty;
                if($itemline)
                {
                    $update['invline_qty'] = $value->invline_qty - $itemline->itemline_qty;
                }
                
                Tbl_customer_invoice_line::where('invline_id', $value->invline_id)->update($update);    

                if($update['invline_qty'] <= 0)
                {
                    $ctr++;
                }
            }
        }
        if($ctr >= count($invline))
        {
            $updates["item_delivered"] = 1;
            Tbl_customer_invoice::where("inv_id",$inv_id)->update($updates);
        }
    }
    public static function get_previous_budget($shop_id, $customer_id)
    {
        $get_last = Tbl_customer_wis::where("destination_customer_id", $customer_id)
                                    ->where("cust_wis_shop_id", $shop_id)
                                    ->orderBy("cust_wis_id", "DESC")->value("cust_wis_id");
        $prev = Tbl_customer_wis_budget::wis()->selectRaw('current_budget_month_amount, tbl_customer_wis.created_at')->where("budget_wis_id", $get_last)->first();
        $return['amount'] = 0;
        $return['month'] = date('F', strtotime(Carbon::now()->subMonths(1)));
        if($prev)
        {
            $return['amount'] = $prev->current_budget_month_amount;
        }
        else
        {
            $return['amount'] = Tbl_customer::where("shop_id", $shop_id)
                                            ->where("customer_id", $customer_id)
                                            ->value("adjusted_monthly_budget");   
        }
        return $return;
    }
    public static function insert_for_budgeting($shop_id, $wis_id, $request)
    {
        $check = AccountingTransaction::settings($shop_id, "monthly_budget");
        if($check)
        {
            if($request->budget_adjusted)
            {
                $cust = Tbl_customer_wis::where("cust_wis_id", $wis_id)->value("destination_customer_id");
                $get_old = Tbl_customer_wis_budget::where("budget_shop_id", $shop_id)->where("budget_wis_id", $wis_id)->first();
                Tbl_customer_wis_budget::where("budget_shop_id", $shop_id)->where("budget_wis_id", $wis_id)->delete();
                $ins['budget_shop_id']               = $shop_id;
                $ins['budget_wis_id']                = $wis_id;
                $ins['budget_type']                  = $request->budget_type;
                $ins['budget_adjusted']              = Self::replace_special_char($request->budget_adjusted);
                $ins['current_budget_month']         = $request->current_budget_month;
                $ins['current_budget_month_amount']  = Self::replace_special_char($request->current_budget_month_amount);
                $ins['prev_budget_month']            = $request->prev_budget_month;
                $ins['prev_budget_month_amount']     = Self::replace_special_char($request->prev_budget_month_amount);
                $ins['adj_budget_month']             = $request->adj_budget_month;
                $ins['adj_budget_month_amount']      = Self::replace_special_char($request->adj_budget_month_amount);
                $ins['total_item_less_amount']       = Self::replace_special_char($request->total_item_less_amount);
                $ins['total_budget_month']           = $request->total_budget_month;
                $ins['total_budget_month_amount']    = Self::replace_special_char($request->total_budget_month_amount);

                $budget_id = Tbl_customer_wis_budget::insertGetId($ins);
                $_item = $request->budgetline_item_id;

                Tbl_customer::where("customer_id", $cust)->update(["adjusted_monthly_budget" => $ins['total_budget_month_amount']]);
                $_item_ins = null;
                if(count($_item))
                {
                    foreach ($_item as $key => $value) 
                    {
                        $_item_ins[$key]['budgetline_id'] = $budget_id;
                        $_item_ins[$key]['budgetline_item_id'] = $value;
                        $_item_ins[$key]['budgetline_item_qty'] = 1;
                        $_item_ins[$key]['budgetline_item_amount'] = Self::replace_special_char($request->budgetline_item_amount[$key]);
                    }
                }
                if(count($_item_ins) > 0)
                {
                    if($get_old)
                    {
                        Tbl_customer_wis_budgetline::where("budgetline_id", $get_old->wis_budget_id)->delete();
                    }
                    Tbl_customer_wis_budgetline::insert($_item_ins);
                }
            }
        }
    }
    public static function get_monthly_budget($shop_id, $wis_id)
    {
        return Tbl_customer_wis_budget::where("budget_shop_id", $shop_id)->where("budget_wis_id", $wis_id)->first();
    }
    public static function get_monthly_budgetline($shop_id, $wis_id)
    {
        $budget_id = isset(Self::get_monthly_budget($shop_id, $wis_id)->wis_budget_id) ? Self::get_monthly_budget($shop_id, $wis_id)->wis_budget_id : null;
        return Tbl_customer_wis_budgetline::item()->where("budgetline_id", $budget_id)->get();
    }
    public static function replace_special_char($string) /*SPECIFIC FOR ( , ) */
    {
        $return = str_replace("(", "", $string);
        $return = str_replace(")", "", $return);
        $return = str_replace(",", "", $return);
        return $return;
    }
    /*public static function check_customer_existence($shop_id, $customer_id = 0)
    {
        return Tbl_customer::where('customer_id',$customer_id)->where('shop_id',$shop_id)->first();
    }
    public static function receive_item($shop_id, $wis_id, $ins_rr, $_item = array())
    {
        $return = null;

        $wis_data = CustomerWIS::get_customer_wis_data($wis_id);

        //die(var_dump($ins_rr['cust_wis_id']));

        if($wis_data->destination_customer_id)
        {

            if($wis_data->destination_customer_id != $ins_rr['cust_wis_id'])
            {
                die(var_dump($wis_data->destination_customer_id));

                $warehouse_name = Warehouse2::check_warehouse_existence($shop_id, $ins_rr['cust_wis_id'])->warehouse_name;
                
                $return .= '<b>'.ucfirst($warehouse_name).'</b> is not supposed to received items in this WIS - ('.$wis_data->wis_number.')';
            }   
        }

        foreach ($_item as $key => $value) 
        {
            $return .= Warehouse2::transfer_validation($shop_id, $wis_data->wis_from_warehouse, $ins_rr['warehouse_id'], $value['item_id'], $value['quantity'], 'rr');
        }


        $check = Tbl_warehouse_receiving_report::where('rr_number',$ins_rr['rr_number'])->where('rr_shop_id',$shop_id)->first();
        if($check)
        {
            $return .= 'RR number already exist';
        }

        if(!$return)
        {
            $rr_id = Tbl_warehouse_receiving_report::insertGetId($ins_rr);

            $source['name'] = 'wis';
            $source['id'] = $wis_id;    

            $to['name'] = 'rr';
            $to['id'] = $rr_id; 

            $val = Warehouse2::transfer_bulk($shop_id, $wis_data->wis_from_warehouse, $ins_rr['warehouse_id'], $_item, $ins_rr['rr_remarks'], $source, $to);

            if(!$val)
            {
                $get_item = Tbl_warehouse_inventory_record_log::where('record_source_ref_name','rr')->where('record_source_ref_id',$rr_id)->get();

                $ins_report_item = null;
                foreach ($get_item as $key_item => $value_item)
                {
                    $ins_report_item[$key_item]['rr_id'] = $rr_id;
                    $ins_report_item[$key_item]['record_log_item_id'] = $value_item->record_log_id;
                }

                if($ins_report_item)
                {
                    Tbl_warehouse_receiving_report_item::insert($ins_report_item);

                    if($wis_data->wis_status == 'confirm')
                    {
                        $udpate_wis['wis_status'] = 'received';
                        WarehouseTransfer::update_wis($shop_id, $wis_id, $udpate_wis);  
                    }
                }
            }

            return $val;
        }
        else
        {
            return $return;
        }
    }*/
}