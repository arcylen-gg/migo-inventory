<?php
namespace App\Globals;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_variant;
use App\Models\Tbl_user;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_inventory_history;
use App\Models\Tbl_inventory_history_items;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_price_level;
use App\Models\Tbl_price_level_item;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_settings;
use App\Models\Tbl_customer;
use App\Models\Tbl_item_token;
use App\Models\Tbl_item_token_log;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_item_pricing_history;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_monitoring_inventory;
use App\Models\Tbl_item_average_cost_per_warehouse;
use App\Models\Tbl_quantity_monitoring;

use App\Globals\Inventory;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Tablet_global;
use App\Globals\Currency;
use App\Globals\AccountingTransaction;
use Session;
use DB;
use Carbon\carbon;
use App\Globals\Merchant;
use Validator;
use stdClass;
class Warehouse2
{  
    public static function get_other_warehouse($shop_id)
    {
        return Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('main_warehouse', 0)->where('archived',0)->get();
    }
    public static function get_user_warehouse_access($shop_id, $user_id)
    {
        $return = Tbl_warehouse::where("warehouse_shop_id", $shop_id)->where("archived",0)->where("warehouse_parent_id",0)->get();

        $user = Tbl_user_warehouse_access::user()->where("tbl_user.user_id", $user_id)->first();

        if($user)
        {
            if($user->user_level != 1)
            {
                $return = Tbl_user_warehouse_access::user()->warehouse()->where("tbl_user.user_id", $user_id)->get();
            }            
        }
        return $return;
    }
    public static function update_remaining_qty($sales_order_id, $item_id, $shop_id)
    {
        $received_qty = 0;
        $quantity_monitoring = Tbl_quantity_monitoring::where('qty_ref_id', $sales_order_id)->where('qty_ref_name', 'sales_order')->where('qty_item_id', $item_id)->where('qty_shop_id', $shop_id)->get();
        if($quantity_monitoring)
        {
            foreach ($quantity_monitoring as $key_quantity_monitoring => $value_quantity_monitoring) 
            {
                $received_qty += $value_quantity_monitoring->qty_new; 
            }
        }
        
        return $received_qty;
    }

    public static function warehouse_access_array($shop_id, $user_id)
    {
        $return = array();
        $get_warehouse = Self::get_user_warehouse_access($shop_id, $user_id)->toArray();
        foreach ($get_warehouse as $key => $value) 
        {
            $return[$key] = $value['warehouse_id'];
        }
        return $return;
    }
    public static function get_branches($shop_id)
    {
        $arr = array();
        $_get = Tbl_warehouse::where("warehouse_shop_id",$shop_id)->where('archived',0)->whereIn("warehouse_type",['warehouse','branches'])->get();
        foreach ($_get as $key => $value) 
        {
            $arr[$key] = $value->warehouse_id;
        }
        return $arr;
    }
    public static function get_all_warehouse($shop_id, $warehouse_id = '', $archived = 0, $search_keyword = null, $level = '')
    {
        $data = Tbl_warehouse::where('warehouse_shop_id',$shop_id)->where('archived',$archived);
        if($warehouse_id)
        {
            $data = $data->where('warehouse_id',$warehouse_id);
        }
        if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {
                $q->orWhere('warehouse_name', "LIKE", "%" . $search_keyword . "%");
                $q->orWhere('warehouse_address', "LIKE", "%" . $search_keyword . "%");
            });
        }
        if(is_numeric($level))
        {
            $data = $data->where('warehouse_level', $level);
        }
        return $data->get();
    }
	public static function get_current_warehouse($shop_id)
	{
		return session('warehouse_id_'.$shop_id);
	}
    public static function get_warehouse_type($warehouse_id)
    {
        return Tbl_warehouse::where('warehouse_id',$warehouse_id)->value('warehouse_type');
    }
	public static function get_main_warehouse($shop_id)
	{
	    return Tbl_warehouse::where('warehouse_shop_id',$shop_id)->where('main_warehouse',1)->where('archived',0)->value('warehouse_id');
	}
    public static function get_under_bin($bin_id, $return = null)
    {
        $data = Tbl_warehouse::where("warehouse_parent_id", $bin_id)->get();
        foreach ($data as $key => $value) 
        {
            $return[$value->warehouse_id] = $value->warehouse_id;
            $return = Self::get_bin($value->warehouse_id, $return);
        }
        return $return;
    }
    public static function get_bin($bin, $return = null)
    {
        $wid = Tbl_warehouse::where("warehouse_parent_id", $bin)->value("warehouse_id");
        if($wid)
        {
            $return[$wid] = $wid;             
        }
        $return = Self::get_under_bin($bin, $return);
        return $return;
    }
    public static function get_item_qty($warehouse_id, $item_id, $bin = null, $refname = null, $refid = null, $datefrom = '', $dateto = '')
    {        
        // $count = Tbl_warehouse_inventory_record_log::where("record_item_id",$item_id);
            
        // if($datefrom && $dateto)
        // {
        //     $count = $count->whereBetween("record_log_date_updated",[$datefrom, $dateto]);
        // }
        
        // if($warehouse_id)
        // {
        //     $count = $count->where("record_warehouse_id", $warehouse_id);
        // }

        // $countref = 0;
        // if($refname && $refid)
        // {
        //     $countref = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
        //                                                ->where("record_item_id",$item_id)
        //                                                ->where("record_consume_ref_name", $refname)
        //                                                ->where("record_consume_ref_id", $refid)
        //                                                ->count();
        // }
        // else
        // {
        //     $count = $count->where("record_inventory_status",0);
        // }
        // if($bin)
        // {
        //     $_bin[$bin] = (int)$bin;
        //     $get_under_bin = Self::get_under_bin($bin, $_bin);
        //     $count = $count->whereIn("record_bin_id",$get_under_bin)->count();
        // }
        // else
        // {
        //     $count = $count->count();
        // }
        $qty = Tbl_monitoring_inventory::where('invty_item_id',$item_id);
 		if($datefrom && $dateto)
        {
            $qty = $qty->whereBetween("invty_date_created",[$datefrom, $dateto]);
        }
        
        if($warehouse_id)
        {
            $qty = $qty->where('invty_warehouse_id', $warehouse_id);
        }

        $countref = 0;
        if($refname && $refid)
        {
            $countref = abs(Tbl_monitoring_inventory::where("invty_warehouse_id",$warehouse_id)
                                                       ->where("invty_item_id",$item_id)
                                                       ->where("invty_transaction_name", $refname)
                                                       ->where("invty_transaction_id", $refid)
                                                       ->sum("invty_qty"));
        }
        if($bin)
        {
            $_bin[$bin] = (int)$bin;
            $get_under_bin = Self::get_under_bin($bin, $_bin);
            $qty = $qty->whereIn("invty_warehouse_id",$get_under_bin)->sum("invty_qty");
        }
        else
        {
            $qty = $qty->sum("invty_qty");
        }
        return $qty + $countref;
    }
    public static function get_old_item_qty($warehouse_id, $item_id, $bin = null, $refname = null, $refid = null, $datefrom = '', $dateto = '')
    {        
        $count = Tbl_warehouse_inventory_record_log::where("record_item_id",$item_id);
            
        if($datefrom && $dateto)
        {
            $count = $count->whereBetween("record_log_date_updated",[$datefrom, $dateto]);
        }
        
        if($warehouse_id)
        {
            $count = $count->where("record_warehouse_id", $warehouse_id);
        }

        $countref = 0;
        if($refname && $refid)
        {
            $countref = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                       ->where("record_item_id",$item_id)
                                                       ->where("record_consume_ref_name", $refname)
                                                       ->where("record_consume_ref_id", $refid)
                                                       ->count();
        }
        else
        {
            $count = $count->where("record_inventory_status",0);
        }
        if($bin)
        {
            $_bin[$bin] = (int)$bin;
            $get_under_bin = Self::get_under_bin($bin, $_bin);
            $count = $count->whereIn("record_bin_id",$get_under_bin)->count();
        }
        else
        {
            $count = $count->count();
        }
      
        return $count + $countref;
    }
    public static function get_item_update_qty($warehouse_id, $refname ,$refid, $item_id, $bin = null)
    {
        $count = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0);
        $countref = 0;
        if($refname && $refid)
        {
            $countref = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                       ->where("record_item_id",$item_id)
                                                       ->where("record_consume_ref_name", $refname)
                                                       ->where("record_consume_ref_id", $refid)
                                                       ->count();
        }
        if($bin)
        {
            $_bin[$bin] = (int)$bin;
            $get_under_bin = Self::get_under_bin($bin, $_bin);
            $count = $count->whereIn("record_bin_id",$get_under_bin)->count();
        }
        else
        {
            $count = $count->count();
        }
        return $count + $countref;
    }
    public static function get_transaction_item($transaction_ref_name = '', $transaction_ref_id = 0)
    {
        return Tbl_warehouse_inventory_record_log::where('record_consume_ref_name',$transaction_ref_name)
                                                   ->where('record_consume_ref_id', $transaction_ref_id)
                                                   ->get(); 
    }
    public static function get_source_transaction_item($transaction_ref_name = '', $transaction_ref_id = 0)
    {
        return Tbl_warehouse_inventory_record_log::where('record_source_ref_name',$transaction_ref_name)
                                                   ->where('record_source_ref_id', $transaction_ref_id)
                                                   ->get(); 
    }
    public static function get_item_qty_transfer($warehouse_id, $item_id)
    {
        $count = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->count();
        return $count;
    }
    public static function check_warehouse_existence($shop_id, $warehouse_id = 0)
    {
        return Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();
    }
  
    public static function get_history_number($shop_id, $warehouse_id, $history_type = '')
    {
        $prefix = Tbl_settings::where("settings_key","inventory_rr_prefix")->value('settings_value');

        $history_ctr = Tbl_inventory_history::where('shop_id',$shop_id)->where('warehouse_id',$warehouse_id)->where('history_type',$history_type)->count();
        if($history_type == 'WIS')
        {
            $prefix = Tbl_settings::where("settings_key","inventory_wis_prefix")->value('settings_value');
        }

        return $prefix.sprintf("%'.05d", $history_ctr+1);

    }
    public static function transfer_validation($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial = array(), $to = array())
    {
        $return = null;

        $item_data = Item::get_item_details($item_id);
        if(Warehouse2::check_warehouse_existence($shop_id, $wh_from) && Warehouse2::check_warehouse_existence($shop_id, $wh_to))
        {
            if($item_data)
            {
                if(isset($source['name']) && isset($source['id']) && isset($to['name']) && isset($to['id']))
                {   
                    if($source['name'] == 'wis')
                    {

                        $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->first();
                        $truck_qty = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->count();
                        $truck_qty = Tbl_monitoring_inventory::where('invty_warehouse_id', $wh_from)
                                                             ->where("invty_item_id", $item_id)
                                                             ->where('invty_transaction_name', $source['name'])
                                                             ->where('invty_transaction_id',$source['id'])->sum('invty_qty');
                        if($shop_id != 81)
                        {
                            if(abs($truck_qty) < $quantity)
                            {
                                $return .= 'The quantity of '.$item_data->item_name.' is not enough to transfer <br>';
                            }
                        }
                    }
                    if(is_numeric($quantity) == false)
                    { 
                        $return .= "The quantity must be a number. <br>";
                    }
                    if($quantity < 0)
                    {
                        $return .= 'The quantity of '.$item_data->item_name.' is less than zero. <br>';                
                    }
                }
                else
                {
                    $return.= "The item number ". $item_id." doesn't exist!";            
                }
            }
        }
        else
        {
            $return .= "The warehouses does not exist!";            
        }
    }
     public static function transfer($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial = array(), $inventory_history = '', $source = array(), $to = array(), $bin_location = null, $update_count = true)
    {
        //dd($to);
        $return = Warehouse2::transfer_validation($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial);
      
        if(!$return)
        {
            $return = null;

            $insert_slip['warehouse_id']                 = $wh_to;
            $insert_slip['inventory_remarks']            = $remarks;
            $insert_slip['inventory_slip_date']          = Carbon::now();
            $insert_slip['inventory_slip_shop_id']       = $shop_id;
            $insert_slip['inventroy_source_reason']      = isset($source['name']) ? $source['name'] : '';
            $insert_slip['inventory_source_id']          = isset($source['id']) ? $source['id'] : 0;
            $insert_slip['slip_user_id']                 = Warehouse2::get_user_login();
            $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

            Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_warehouse_id',$wh_to)->where('invty_item_id',$item_id)->where('invty_transaction_name', $to['name'])->where('invty_transaction_id', $to['id'])->delete();

            $insert_inventory['invty_shop_id']          = $shop_id;
            $insert_inventory['invty_warehouse_id']     = $wh_to;
            $insert_inventory['invty_item_id']          = $item_id;
            $insert_inventory['invty_transaction_name'] = $to['name'];
            $insert_inventory['invty_transaction_id']   = $to['id'];
            $insert_inventory['invty_qty']              = $quantity;
            $insert_inventory['invty_stock_on_hand']    = 0;
            $insert_inventory['invty_date_created']     = Carbon::now();
            
            $invty_id = Tbl_monitoring_inventory::insertGetId($insert_inventory);
            
            Self::load_stock_on_hand($shop_id, $wh_to, $item_id, $invty_id);

            /*LOOPING FOR QUANTITY*/
            // for ($ctr_qty = 0; $ctr_qty < $quantity ; $ctr_qty++)
            // {                
            //     $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->first();
            //     if(count($serial) > 0)
            //     {
            //         $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_serial_number',$serial[$ctr_qty])->first();
            //     }
            //     if(isset($source['name']) && isset($source['id']) && isset($to['name']) && isset($to['id']))
            //     {   
            //         //$update['record_source_ref_name'] = $to['name'];
            //         //$update['record_source_ref_id'] = $to['id'];
            //         if($source['name'] == 'wis')
            //         {
            //             $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->first();
            //         }
            //         if(count($serial) > 0)
            //         {
            //             $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->where('record_serial_number',$serial[$ctr_qty])->first();
            //         }         
            //     }

            //     $insert[$ctr_qty]['record_shop_id']            = $shop_id;
            //     $insert[$ctr_qty]['record_item_id']            = $item_id;
            //     $insert[$ctr_qty]['record_warehouse_id']       = $wh_to;
            //     $insert[$ctr_qty]['record_item_remarks']       = $remarks;
            //     $insert[$ctr_qty]['record_warehouse_slip_id']  = $slip_id;
            //     $insert[$ctr_qty]['record_source_ref_name']    = isset($to['name']) ? $to['name'] : '';
            //     $insert[$ctr_qty]['record_source_ref_id']      = isset($to['id']) ? $to['id'] : 0;
            //     $insert[$ctr_qty]['record_log_date_updated']   = Carbon::now();
            //     $insert[$ctr_qty]['mlm_pin']                   = Warehouse2::get_mlm_pin($shop_id);
            //     $insert[$ctr_qty]['mlm_activation']            = Item::get_mlm_activation($shop_id);
            //     $insert[$ctr_qty]['ctrl_number']               = Warehouse2::get_control_number($wh_to, $shop_id, Item::get_item_type($item_id));
            //     $insert[$ctr_qty]['record_bin_id']             = $bin_location != "" ? $bin_location : null; 
                
            //     $item_data = Item::info($item_id);
            //     if($item_data)
            //     {
            //         $insert[$ctr_qty]['record_sales_price'] = $item_data->item_price;
            //         $insert[$ctr_qty]['record_cost_price'] = $item_data->item_cost;
            //     }

            //     if(count($serial) > 0)
            //     {
            //         $insert[$ctr_qty]['record_serial_number'] = $serial[$ctr_qty];
            //     }
            //     if(session('refill_offset_inventory'))
            //     {
            //         $insert[$ctr_qty]['record_count_inventory'] = 0;
            //     }
            //     Tbl_warehouse_inventory_record_log::insert($insert[$ctr_qty]);

            // }
            if(!$inventory_history)
            {
                $inventory_details['history_description'] = "Refill items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
                $inventory_details['history_remarks'] = $remarks;
                $inventory_details['history_type'] = "RR";
                $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
                $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
                $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $wh_to, $inventory_details['history_type']);

                $history_item[0]['item_id'] = $item_id;
                $history_item[0]['quantity'] = $quantity;
                $history_item[0]['item_remarks'] = $remarks;

                Warehouse2::insert_inventory_history($shop_id, $wh_to, $inventory_details, $history_item);
            }

            if($update_count == true)
            {
                Warehouse2::update_inventory_count($wh_to, $slip_id, $item_id, $quantity);
            }

            $store['refill_offset_inventory'] = null;
            $store['refill_adjust_inventory'] = null;
            session($store);

                /*
                Warehouse2::insert_item_history($get_data->record_log_id);

                $update['record_inventory_status'] = 0;
                $update['record_consume_ref_name'] = null;
                $update['record_consume_ref_id'] = 0;
                $update['record_warehouse_id'] = $wh_to;
                $update['record_item_remarks'] = $remarks;
                $update['record_log_date_updated'] = Carbon::now();
                $update['record_bin_id'] = $bin_location != "" ? $bin_location : null;
                Tbl_warehouse_inventory_record_log::where('record_log_id',$get_data->record_log_id)->update($update);

                if(!$inventory_history)
                {
                    $wh_from_name = Warehouse2::get_info($wh_from)->value('warehouse_name');
                    $wh_to_name = Warehouse2::get_info($wh_to)->value('warehouse_name');
                    $inventory_wis['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
                    $inventory_wis['history_remarks'] = $remarks;
                    $inventory_wis['history_type'] = "WIS";
                    $inventory_wis['history_number'] = Warehouse2::get_history_number($shop_id, $wh_from, $inventory_wis['history_type']);


                    $inventory_rr['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
                    $inventory_rr['history_remarks'] = $remarks;
                    $inventory_rr['history_type'] = "RR";
                    $inventory_rr['history_number'] = Warehouse2::get_history_number($shop_id, $wh_to, $inventory_rr['history_type']);


                    $history_wis[0]['item_id'] = $item_id;
                    $history_wis[0]['quantity'] = $quantity;
                    $history_wis[0]['item_remarks'] = $remarks;

                    $history_rr[0]['item_id'] = $item_id;
                    $history_rr[0]['quantity'] = $quantity;
                    $history_rr[0]['item_remarks'] = $remarks;

                    Warehouse2::insert_inventory_history($shop_id, $wh_from, $inventory_wis, $history_wis);
                    Warehouse2::insert_inventory_history($shop_id, $wh_to, $inventory_rr, $history_rr);
                }

                Warehouse2::update_inventory_count($wh_to, 0, $item_id, $quantity);*/
        }
        return $return;
    }
    public static function old_transfer_validation($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial = array(), $source = array(), $to = array())
    {
        $return = null;

        $item_data = Item::get_item_details($item_id);
        if(Warehouse2::check_warehouse_existence($shop_id, $wh_from) && Warehouse2::check_warehouse_existence($shop_id, $wh_to))
        {
            $warehouse_qty = Warehouse2::get_item_qty_transfer($wh_from, $item_id);

            if($item_data)
            {
                $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->first();

                if(isset($source['name']) && isset($source['id']) && isset($to['name']) && isset($to['id']))
                {   
                    if($source['name'] == 'wis')
                    {
                        $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->first();
                        $truck_qty = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->count();
                    }

                    if($shop_id != 81)
                    {
                        if($truck_qty < $quantity)
                        {
                            $return .= 'The quantity of '.$item_data->item_name.' is not enough to transfer <br>';
                        }
                    }
                }
                if(is_numeric($quantity) == false)
                { 
                    $return .= "The quantity must be a number. <br>";
                }
                if($quantity < 0)
                {
                    $return .= 'The quantity of '.$item_data->item_name.' is less than zero. <br>';                
                }
                // if(!$get_data)
                // {
                //     $return .= 'The item '.$item_data->item_name.' does not exist in this warehouse. <br>';                
                // }
                // if($warehouse_qty < $quantity)
                // {
                //     $return .= 'The quantity of '.$item_data->item_name.' is not enough to transfer <br>';
                // }
                $serial_qty = count($serial);
                if($serial_qty > 0)
                {
                    if($serial_qty != $quantity)
                    {
                        $return .= "The serial number are not equal from the quantity. <br> ";
                    }
                    foreach ($serial as $key => $value) 
                    {
                        $check_serial = Tbl_warehouse_inventory_record_log::where('record_serial_number',$value)->where('record_item_id',$item_id)->where('record_warehouse_id',$wh_from)->first();
                        if(!$check_serial)
                        {
                            $return .= "The serial number ".$value." does not belong to ".$item_data->item_name.". <br> ";
                        }
                    }
                }
            }
            else
            {
                $return.= "The item number ". $item_id." doesn't exist!";            
            }
        }
        else
        {
            $return .= "The warehouses does not exist!";            
        }

        return $return;
    }
    public static function old_transfer($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial = array(), $inventory_history = '', $source = array(), $to = array(), $bin_location = null, $update_count = true)
    {
        //dd($to);
        $return = Warehouse2::transfer_validation($shop_id, $wh_from, $wh_to, $item_id, $quantity, $remarks, $serial);
      
        if(!$return)
        {
            $return = null;

            $insert_slip['warehouse_id']                 = $wh_to;
            $insert_slip['inventory_remarks']            = $remarks;
            $insert_slip['inventory_slip_date']          = Carbon::now();
            $insert_slip['inventory_slip_shop_id']       = $shop_id;
            $insert_slip['inventroy_source_reason']      = isset($source['name']) ? $source['name'] : '';
            $insert_slip['inventory_source_id']          = isset($source['id']) ? $source['id'] : 0;
            $insert_slip['slip_user_id']                 = Warehouse2::get_user_login();
            $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

            Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_warehouse_id',$wh_to)->where('invty_item_id',$item_id)->where('invty_transaction_name', $to['name'])->where('invty_transaction_id', $to['id'])->delete();

            $insert_inventory['invty_shop_id']          = $shop_id;
            $insert_inventory['invty_warehouse_id']     = $wh_to;
            $insert_inventory['invty_item_id']          = $item_id;
            $insert_inventory['invty_transaction_name'] = $to['name'];
            $insert_inventory['invty_transaction_id']   = $to['id'];
            $insert_inventory['invty_qty']              = $quantity;
            $insert_inventory['invty_stock_on_hand']    = 0;
            $insert_inventory['invty_date_created']     = Carbon::now();
            
            // if($check_inventory)
            // {
            //     Tbl_monitoring_inventory::where('invty_id', $check_inventory->invty_id)->update($insert_inventory);
            //     $invty_id = $check_inventory->invty_id;
            // }
            // else
            // {
            $invty_id = Tbl_monitoring_inventory::insertGetId($insert_inventory);
            // }
            
            Self::load_stock_on_hand($shop_id, $wh_to, $item_id, $invty_id);

            /*LOOPING FOR QUANTITY*/
            for ($ctr_qty = 0; $ctr_qty < $quantity ; $ctr_qty++)
            {                
                $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->first();
                if(count($serial) > 0)
                {
                    $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_serial_number',$serial[$ctr_qty])->first();
                }
                if(isset($source['name']) && isset($source['id']) && isset($to['name']) && isset($to['id']))
                {   
                    //$update['record_source_ref_name'] = $to['name'];
                    //$update['record_source_ref_id'] = $to['id'];
                    if($source['name'] == 'wis')
                    {
                        $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->first();
                    }
                    if(count($serial) > 0)
                    {
                        $get_data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$wh_from)->where('record_item_id',$item_id)->where('record_consume_ref_name',$source['name'])->where('record_consume_ref_id',$source['id'])->where('record_serial_number',$serial[$ctr_qty])->first();
                    }         
                }

                $insert[$ctr_qty]['record_shop_id']            = $shop_id;
                $insert[$ctr_qty]['record_item_id']            = $item_id;
                $insert[$ctr_qty]['record_warehouse_id']       = $wh_to;
                $insert[$ctr_qty]['record_item_remarks']       = $remarks;
                $insert[$ctr_qty]['record_warehouse_slip_id']  = $slip_id;
                $insert[$ctr_qty]['record_source_ref_name']    = isset($to['name']) ? $to['name'] : '';
                $insert[$ctr_qty]['record_source_ref_id']      = isset($to['id']) ? $to['id'] : 0;
                $insert[$ctr_qty]['record_log_date_updated']   = Carbon::now();
                $insert[$ctr_qty]['mlm_pin']                   = Warehouse2::get_mlm_pin($shop_id);
                $insert[$ctr_qty]['mlm_activation']            = Item::get_mlm_activation($shop_id);
                $insert[$ctr_qty]['ctrl_number']               = Warehouse2::get_control_number($wh_to, $shop_id, Item::get_item_type($item_id));
                $insert[$ctr_qty]['record_bin_id']             = $bin_location != "" ? $bin_location : null; 
                
                $item_data = Item::info($item_id);
                if($item_data)
                {
                    $insert[$ctr_qty]['record_sales_price'] = $item_data->item_price;
                    $insert[$ctr_qty]['record_cost_price'] = $item_data->item_cost;
                }

                if(count($serial) > 0)
                {
                    $insert[$ctr_qty]['record_serial_number'] = $serial[$ctr_qty];
                }
                if(session('refill_offset_inventory'))
                {
                    $insert[$ctr_qty]['record_count_inventory'] = 0;
                }
                Tbl_warehouse_inventory_record_log::insert($insert[$ctr_qty]);

            }
            if(!$inventory_history)
            {
                $inventory_details['history_description'] = "Refill items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
                $inventory_details['history_remarks'] = $remarks;
                $inventory_details['history_type'] = "RR";
                $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
                $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
                $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $wh_to, $inventory_details['history_type']);

                $history_item[0]['item_id'] = $item_id;
                $history_item[0]['quantity'] = $quantity;
                $history_item[0]['item_remarks'] = $remarks;

                Warehouse2::insert_inventory_history($shop_id, $wh_to, $inventory_details, $history_item);
            }

            if($update_count == true)
            {
                Warehouse2::update_inventory_count($wh_to, $slip_id, $item_id, $quantity);
            }

            $store['refill_offset_inventory'] = null;
            $store['refill_adjust_inventory'] = null;
            session($store);

                /*
                Warehouse2::insert_item_history($get_data->record_log_id);

                $update['record_inventory_status'] = 0;
                $update['record_consume_ref_name'] = null;
                $update['record_consume_ref_id'] = 0;
                $update['record_warehouse_id'] = $wh_to;
                $update['record_item_remarks'] = $remarks;
                $update['record_log_date_updated'] = Carbon::now();
                $update['record_bin_id'] = $bin_location != "" ? $bin_location : null;
                Tbl_warehouse_inventory_record_log::where('record_log_id',$get_data->record_log_id)->update($update);

                if(!$inventory_history)
                {
                    $wh_from_name = Warehouse2::get_info($wh_from)->value('warehouse_name');
                    $wh_to_name = Warehouse2::get_info($wh_to)->value('warehouse_name');
                    $inventory_wis['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
                    $inventory_wis['history_remarks'] = $remarks;
                    $inventory_wis['history_type'] = "WIS";
                    $inventory_wis['history_number'] = Warehouse2::get_history_number($shop_id, $wh_from, $inventory_wis['history_type']);


                    $inventory_rr['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
                    $inventory_rr['history_remarks'] = $remarks;
                    $inventory_rr['history_type'] = "RR";
                    $inventory_rr['history_number'] = Warehouse2::get_history_number($shop_id, $wh_to, $inventory_rr['history_type']);


                    $history_wis[0]['item_id'] = $item_id;
                    $history_wis[0]['quantity'] = $quantity;
                    $history_wis[0]['item_remarks'] = $remarks;

                    $history_rr[0]['item_id'] = $item_id;
                    $history_rr[0]['quantity'] = $quantity;
                    $history_rr[0]['item_remarks'] = $remarks;

                    Warehouse2::insert_inventory_history($shop_id, $wh_from, $inventory_wis, $history_wis);
                    Warehouse2::insert_inventory_history($shop_id, $wh_to, $inventory_rr, $history_rr);
                }

                Warehouse2::update_inventory_count($wh_to, 0, $item_id, $quantity);*/
        }
        return $return;
    }
    public static function transfer_bulk($shop_id, $wh_from, $wh_to, $_item, $remarks = '', $source = array(),$to = array())
    {
        $validate = null;
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : array();
            $validate .= Warehouse2::transfer_validation($shop_id, $wh_from, $wh_to, $value['item_id'], $value['quantity'], $value['remarks'], $serial, $source, $to);
        }

        if(!$validate)
        {
            $wh_from_name = Warehouse2::get_info($wh_from)->value('warehouse_name');
            $wh_to_name = Warehouse2::get_info($wh_to)->value('warehouse_name');
            $inventory_wis['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
            $inventory_wis['history_remarks'] = $remarks;
            $inventory_wis['history_type'] = "WIS";
            $inventory_wis['history_number'] = Warehouse2::get_history_number($shop_id, $wh_from, $inventory_wis['history_type']);


            $inventory_rr['history_description'] = "Transfer items from ".$wh_from_name." to ".$wh_to_name;
            $inventory_rr['history_remarks'] = $remarks;
            $inventory_rr['history_type'] = "RR";
            $inventory_rr['history_number'] = Warehouse2::get_history_number($shop_id, $wh_to, $inventory_rr['history_type']);

            foreach ($_item as $key => $value)
            {
                $serial = isset($value['serial']) ? $value['serial'] : array();

                $history_wis[$key]['item_id'] = $value['item_id'];
                $history_wis[$key]['quantity'] = $value['quantity'];
                $history_wis[$key]['item_remarks'] = $value['remarks'];

                $history_rr[$key]['item_id'] = $value['item_id'];
                $history_rr[$key]['quantity'] = $value['quantity'];
                $history_rr[$key]['item_remarks'] = $value['remarks'];

                $bin_location = isset($value['bin_location']) ? $value['bin_location'] : null;
                $validate = Warehouse2::transfer($shop_id, $wh_from, $wh_to, $value['item_id'], $value['quantity'], $value['remarks'], $serial, 'inventory_history_recorded', $source, $to, $bin_location);
            }

            Warehouse2::insert_inventory_history($shop_id, $wh_from, $inventory_wis, $history_wis);
            Warehouse2::insert_inventory_history($shop_id, $wh_to, $inventory_rr, $history_rr);
        }

        return $validate;
    }
    public static function validate_warehouse($shop_id, $insert)
    {
        $check_warehouse = Tbl_warehouse::where('warehouse_name',$insert['warehouse_name'])->where('warehouse_shop_id',$shop_id)->first();
        $return = null;
        if($check_warehouse)
        {
            $return .= "The warehouse name already exist";
        }
        if($insert != 0)
        {
            $check_price_level = Tbl_price_level::where('price_level_id',$insert['sale_price_level'])->first();
            if(!$check_price_level)
            {
                $return .= "The sale_price_level does't exist";
            }
        }
        if($insert['purchase_price_level'] != 0)
        {
            $check_price_level = Tbl_price_level::where('price_level_id',$insert['purchase_price_level'])->first();
            if(!$check_price_level)
            {
                $return .= "The sale_price_level does't exist";
            }
        }

        return $return;        
    }
    public static function get_info($warehouse_id)
    {
        return Tbl_warehouse::where('warehouse_id',$warehouse_id)->first();
    }
    public static function create($shop_id, $insert)
    {
        $return = Warehouse2::validate_warehouse($shop_id, $insert);

        if(!$return['message'])
        {
            $insert["warehouse_created"] = Carbon::now();
            $insert["warehouse_shop_id"] = $shop_id;

            $warehouse_id = Tbl_warehouse::insertGetId($ins_warehouse);

            Warehouse::insert_access($id);

            $get_all_item = Tbl_item::where('shop_id',$shop_id)->get();

            foreach ($get_all_item as $key => $value) 
            {
                $check = Tbl_sub_warehouse::where("item_id",$value->item_id)->where("warehouse_id",$warehouse_id)->first();
                if($check == null)
                {
                    $ins_sub["warehouse_id"] = $warehouse_id;
                    $ins_sub["item_id"] = $value->item_id;
                    $ins_sub["item_reorder_point"] = $value->item_reorder_point;

                    Tbl_sub_warehouse::insert($ins_sub);
                }
            }     

            $return = $warehouse_id;
        }

        return $return;
    }
    public static function insert_item_history($record_log_id = 0)
    {
        $get_data = Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->first();
        if($get_data)
        {            
            $datenow = $get_data->record_log_date_updated;
            if($get_data->record_log_history)
            {
                $serialize = unserialize($get_data->record_log_history);
                $serialize[$datenow] = collect($get_data)->toArray();

                $update['record_log_history'] = serialize($serialize);
                Tbl_warehouse_inventory_record_log::where('record_log_id',$get_data->record_log_id)->update($update);
            }
            else
            {                
                $serialize[$datenow] = collect($get_data)->toArray();
                $update['record_log_history'] = serialize($serialize);
                Tbl_warehouse_inventory_record_log::where('record_log_id',$get_data->record_log_id)->update($update);
            }
        }
    }

    public static function refill_validation($shop_id, $warehouse_id, $item_id, $quantity, $remarks = '', $serial = array())
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        if(is_numeric($quantity) == false)
        { 
            $return .= "The quantity must be a number. <br>";
        }

        $serial_qty = count($serial);
        if($serial_qty != 0)
        {
            if($serial_qty != $quantity)
            {
                $item_info = Item::info($item_id);
                $return .= "The serial number of ".$item_info->item_name." are not equal from the quantity. <br> ";
            }
            foreach ($serial as $key => $value) 
            {
                $check_serial = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id)->where('record_serial_number',$value)->first();
                if($check_serial)
                {
                    $return .= "The serial number ".$value." already exist. <br>";
                }
            }
        }
        if($quantity < 0)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }

        return $return;
    }
    public static function get_offset_qty_v2($warehouse_id, $item_id)
    {
        $qty = Tbl_item::recordloginventory($warehouse_id, true)->value('offset_count');
        return $qty * -1;
    }
    public static function average_costper_item($shop_id, $item_id, $warehouse_id = '')
    {
        $data = Tbl_monitoring_inventory::where('invty_shop_id', $shop_id)->where('invty_item_id',$item_id);
        if($warehouse_id)
        {
            $data = $data->where('invty_warehouse_id', $warehouse_id);
        }
        $data = $data->get();
        $ave_cost = null;      
        if(count($data) > 0)
        {
            $qty = 0;
            $cost = null;
            foreach ($data as $key => $value)
            {
                $qty += $value->invty_qty;
                $cost += $value->invty_total_cost_price;
            }
            $ave_cost = $cost;
            if($qty != 0)
            {
                $ave_cost = $cost / $qty;
            }
        }
        return $ave_cost;
    }
    public static function refill($shop_id, $warehouse_id, $item_id = 0, $quantity = 1, $remarks = '', $source = array(), $serial = array(), $inventory_history = '', $update_count = true, $bin_location = null)
    {
        $return = null;
        if(!session('refill_adjust_inventory'))
        {
            if(Inventory::allow_out_of_stock($shop_id) == 1)
            {
                $count_offset = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id )->where('record_count_inventory','=',0)->count();
                $total_refill_qty = $quantity;
                if($count_offset > 0)
                {
                    $total_refill_qty = $quantity - $count_offset;
                }
                Self::update_offset_qty($warehouse_id, $item_id, $count_offset, $quantity);

                $quantity = $total_refill_qty;
            }
        }

        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        $serial_qty = count($serial);
        if(!$return)
        {  
            $insert_slip['warehouse_id']                 = $warehouse_id;
            $insert_slip['inventory_remarks']            = $remarks;
            $insert_slip['inventory_slip_date']          = Carbon::now();
            $insert_slip['inventory_slip_shop_id']       = $shop_id;
            $insert_slip['inventroy_source_reason']      = isset($source['name']) ? $source['name'] : 'initial_qty';
            $insert_slip['inventory_source_id']          = isset($source['id']) ? $source['id'] : $item_id;
            $insert_slip['slip_user_id']                 = Warehouse2::get_user_login();
            $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

/*            $source['name'] = isset($source['name']) ? $source['name'] : 'initial_qty';
            $source['id'] = isset($source['id']) ? $source['id'] : $item_id; */

            /* START MONITORING OF QUANTITY */
            $insert_inventory['invty_shop_id']          = $shop_id;
            $insert_inventory['invty_warehouse_id']     = $warehouse_id;
            $insert_inventory['invty_item_id']          = $item_id;
            $insert_inventory['invty_transaction_name'] = $source['name'];
            $insert_inventory['invty_transaction_id']   = $source['id'];
            $insert_inventory['invty_qty']              = $quantity;
            $insert_inventory['invty_stock_on_hand']    = 0;
            $insert_inventory['invty_date_created']     = Carbon::now();

            if($source['name'] != 'initial_qty')
            {
                $insert_inventory['invty_cost_price']       = $source['item_rate'];
                $insert_inventory['invty_total_cost_price'] = $source['item_rate'] * $quantity;
                $insert_inventory['invty_sales_price']      = 0;
                $insert_inventory['invty_total_sales_price']= 0;
            }
            else if($source['name'] == 'initial_qty')
            {

                $insert_inventory['invty_cost_price']       = $source['item_cost'];
                $insert_inventory['invty_total_cost_price'] = $source['item_cost'] * $quantity;
                $insert_inventory['invty_sales_price']      = $source['item_price'];
                $insert_inventory['invty_total_sales_price']= $source['item_price'] * $quantity;
            }
            
            $item = Tbl_item::where('item_id', $item_id)->first();
            $stock_on_hand = Self::total_stock_on_hand($shop_id, $item_id, $warehouse_id);
            $new_cost = null;
            $invty_id = Tbl_monitoring_inventory::insertGetId($insert_inventory);
            $insert_inventory['invty_sales_price'] = $item->item_price;
            /* END MONITORING OF QUANTITY */

            $new_cost = Self::average_costper_item($shop_id, $item_id, $warehouse_id);
            $item_cost = AccountingTransaction::settings($shop_id, 'item_new_cost');
            // if($item_cost == 'average_costing')
            // {
                $checkave_cost_per_whse = Tbl_item_average_cost_per_warehouse::where('iacpw_shop_id',$shop_id)->where('iacpw_warehouse_id',$warehouse_id)->where('iacpw_item_id',$item_id)->first();
                if($checkave_cost_per_whse)
                {
                    if($checkave_cost_per_whse->iacpw_ave_cost != $new_cost)
                    {
                        $insert_inventory['invty_cost_price'] = $checkave_cost_per_whse->iacpw_ave_cost;
                        Item::insert_pricing_history($insert_inventory, $insert_slip['slip_user_id'], $item_id, $shop_id);
                    }
                    /*UPDATE*/ 
                    $up_ave_cost_per_whse['iacpw_ave_cost'] = $new_cost;
                    $up_ave_cost_per_whse['iacpw_qty']      = $stock_on_hand;
                    Tbl_item_average_cost_per_warehouse::where('iacpw_id', $checkave_cost_per_whse->iacpw_id)->update($up_ave_cost_per_whse);
                }
                else
                { 
                    /*INSERT*/
                    $ave_cost_per_whse['iacpw_item_id']      = $item_id;
                    $ave_cost_per_whse['iacpw_warehouse_id'] = $warehouse_id;
                    $ave_cost_per_whse['iacpw_shop_id']      = $shop_id;
                    $ave_cost_per_whse['iacpw_ave_cost']     = $new_cost;
                    $ave_cost_per_whse['iacpw_qty']          = $quantity;
                    $ave_cost_per_whse['iacpw_date']         = Carbon::now();
                    Tbl_item_average_cost_per_warehouse::insertGetId($ave_cost_per_whse);

                }
                /*$update['item_cost'] = $insert_inventory['invty_cost_price'];
                Tbl_item::where('item_id', $item_id)->update($update);*/
                //Item::insert_pricing_history($insert_inventory, $insert_slip['slip_user_id']);
                Self::load_stock_on_hand($shop_id, $warehouse_id, $item_id, $invty_id);
            // }

            $insert = null;
            /*
			<!--------- COMMENT TEMPORARY FOR TESTING PURPOSES ----------->
            for ($ctr_qty = 0; $ctr_qty < $quantity; $ctr_qty++) 
            {
                $insert[$ctr_qty]['record_shop_id']            = $shop_id;
                $insert[$ctr_qty]['record_item_id']            = $item_id;
                $insert[$ctr_qty]['record_warehouse_id']       = $warehouse_id;
                $insert[$ctr_qty]['record_item_remarks']       = $remarks;
                $insert[$ctr_qty]['record_warehouse_slip_id']  = $slip_id;
                $insert[$ctr_qty]['record_source_ref_name']    = isset($source['name']) ? $source['name'] : '';
                $insert[$ctr_qty]['record_source_ref_id']      = isset($source['id']) ? $source['id'] : 0;
                $insert[$ctr_qty]['record_log_date_updated']   = Carbon::now();
                $insert[$ctr_qty]['mlm_pin']                   = Warehouse2::get_mlm_pin($shop_id);
                $insert[$ctr_qty]['mlm_activation']            = Item::get_mlm_activation($shop_id);
                $insert[$ctr_qty]['ctrl_number']               = Warehouse2::get_control_number($warehouse_id, $shop_id, Item::get_item_type($item_id));
                $insert[$ctr_qty]['record_bin_id']             = $bin_location != "" ? $bin_location : null; 
                if($item)
                {
                    $insert[$ctr_qty]['record_sales_price'] = $item->item_price;
                    $insert[$ctr_qty]['record_cost_price'] = $item->item_cost;
                }

                if($serial_qty > 0)
                {
                    $insert[$ctr_qty]['record_serial_number'] = $serial[$ctr_qty];
                }
                if(session('refill_offset_inventory'))
                {
                    $insert[$ctr_qty]['record_count_inventory'] = 0;
                }
                Tbl_warehouse_inventory_record_log::insert($insert[$ctr_qty]);
            }
			*/
            if(!$inventory_history)
            {
                $inventory_details['history_description'] = "Refill items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
                $inventory_details['history_remarks'] = $remarks;
                $inventory_details['history_type'] = "RR";
                $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
                $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
                $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

                $history_item[0]['item_id'] = $item_id;
                $history_item[0]['quantity'] = $quantity;
                $history_item[0]['item_remarks'] = $remarks;

                Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
            }

            if($update_count == true)
            {
                Warehouse2::update_inventory_count($warehouse_id, $slip_id, $item_id, $quantity);
            }

            $store['refill_offset_inventory'] = null;
            $store['refill_adjust_inventory'] = null;
            session($store);
        }       

        return $return;
    }
    public static function total_stock_on_hand($shop_id, $item_id, $warehouse_id)
    {
        $qty = Tbl_monitoring_inventory::where('invty_shop_id', $shop_id)->where('invty_warehouse_id', $warehouse_id)->where('invty_item_id',$item_id)->sum("invty_qty");
        // $qty = null;
        // foreach ($data as $key => $value)
        // {
        //     $qty += $value->invty_qty;
        // }
        return $qty;
    }

    public static function update_offset_qty($warehouse_id, $item_id, $count_offset, $quantity)
    {
        if($count_offset > $quantity)
        {
            $update_qty = abs($quantity);
            for ($ctr_qty = 0; $ctr_qty < $update_qty; $ctr_qty++)
            {
                $update['record_count_inventory'] = 1;
                $record_log_id = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                    ->where('record_item_id', $item_id)
                                                    ->where('record_count_inventory','=',0)->value('record_log_id');
                Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->update($update);

            }
        }
        else if($count_offset - $quantity <= 0)
        {
            $update['record_count_inventory'] = 1;
            Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                    ->where('record_item_id', $item_id)
                                                    ->where('record_count_inventory','=',0)->update($update);
        }
    }

    public static function get_control_number($warehouse_id, $shop_id, $item_type = null)
    {
        $return = 0;
        if($shop_id == 5) // SPECIAL FOR BROWN 
        {
            if($item_type == 5) // MEMBERSHIP KIT TYPE ITEM
            {
                $check = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->value('main_warehouse');
                if($check == 3)
                {
                    $count = Tbl_warehouse_inventory_record_log::item()->where('record_warehouse_id',$warehouse_id)->where('item_type_id',$item_type)->count();
                    $return = $count + 1;
                }
            }
        }
        return $return;

    }
    public static function update_inventory_count($warehouse_id, $slip_id, $item_id, $quantity)
    {
        // $update["inventory_count"] = Tbl_warehouse_inventory_record_log::where("record_warehouse_id", $warehouse_id)->where("record_item_id", $item_id)->count();
        // Tbl_warehouse_inventory::where("warehouse_id", $warehouse_id)->where("inventory_item_id", $item_id)->update($update);
        $ins['inventory_item_id'] = $item_id;
        $ins['warehouse_id'] = $warehouse_id;
        $ins['inventory_created'] = Carbon::now();
        $ins['inventory_count'] = $quantity;
        $ins['inventory_slip_id'] = $slip_id;
        Tbl_warehouse_inventory::insert($ins);
    }
    public static function get_mlm_pin($shop_id)
    {       
        $return = 0; 
        $prefix = Tbl_settings::where("settings_key","mlm_pin_prefix")->where('shop_id',$shop_id)->value('settings_value');
        
        if($prefix)
        {
            $ctr_item = Tbl_warehouse_inventory_record_log::where('record_shop_id',$shop_id)->count() + 1;
            $return = $prefix.sprintf("%'.05d",$ctr_item);
        }

        return $return;
    }
    /**           
        $_item[0]['item_id'] = 15;
        $_item[0]['quantity'] = 1;
        $_item[0]['remarks'] = 'test';
        $_item[0]['serial'] = array("1SERIAL001");;

        $_item[1]['item_id'] = 17;
        $_item[1]['quantity'] = 1;
        $_item[1]['remarks'] = 'test_consume';
        $_item[1]['serial'] = array("1SERIAL004");;

        $ret = Warehouse2::refill_bulk($this->user_info->shop_id, 6, 'refill_bulk_test', 20 , 'test refill', $_item);
        
        $ret = Warehouse2::transfer_bulk($this->user_info->shop_id, 29, 6, $_item, "test transfer 1");

        $ret = Warehouse2::consume_bulk($this->user_info->shop_id, 6, 'consumebulk_test', 1 , 'test consume', $_item);

        dd($ret);
    */
    public static function refill_bulk($shop_id, $warehouse_id, $reference_name = '', $reference_id = 0 , $remarks = '', $_item = array())
    {
        $validate = null;
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : array();
            $validate .= Warehouse2::refill_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial);
        }

        if(!$validate)
        {
            if($reference_name != 'import_adjust_inventory' && $reference_name != 'adjust_inventory')
            {
                Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)
                                    ->where('invty_warehouse_id',$warehouse_id)
                                    ->where('invty_transaction_name', $reference_name)
                                    ->where('invty_transaction_id', $reference_id)
                                    ->delete();
            }
            $inventory_details['history_description'] = "Refill items from ".$reference_name." #".$reference_id;
            $inventory_details['history_remarks'] = $remarks;
            $inventory_details['history_type'] = "RR";
            $inventory_details['history_reference'] = $reference_name;
            $inventory_details['history_reference_id'] = $reference_id;
            $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

            foreach ($_item as $key => $value) 
            {
                $serial = isset($value['serial']) ? $value['serial'] : array();

                $rate = isset($value['item_rate']) ? $value['item_rate'] : 0;
                $source['name'] = $reference_name;
                $source['id'] = $reference_id;
                $source['item_rate'] =  $rate;
                //Tbl_item::where('item_id', $value['item_id'])->value('item_cost');

                $history_item[$key]['item_id'] = $value['item_id'];
                $history_item[$key]['quantity'] = $value['quantity'];
                $history_item[$key]['item_remarks'] = $value['remarks'];

                $bin_location = isset($value['bin_location']) ? $value['bin_location'] : null;

                $validate = Warehouse2::refill($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $source, $serial, 'inventory_history_recorded', true, $bin_location);
            
            }
            Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
        }

        return $validate;
    }
    public static function non_posting_validation($insert_item, $shop_id)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        $return = null;
        if($insert_item)
        {
            foreach ($insert_item as $key_insert_item => $value_insert_item)
            {
                $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $value_insert_item['item_id'], $value_insert_item['bin_location'], $value_insert_item['item_refname'], $value_insert_item['item_refid']);
            
                if($value_insert_item['item_qty'] > $inventory_qty)
                {
                    $con_msg = "";
                    if($value_insert_item['bin_location'])
                    {
                        $w_name = Tbl_warehouse::where("warehouse_id", $value_insert_item['bin_location'])->value("warehouse_name");
                        $con_msg = " Please choose other than ". $w_name;
                    }
                    $return .= "The quantity of <b>".Item::info($value_insert_item['item_id'])->item_name."</b> is not enough to consume. ".$con_msg."<br>";     
                }
            }
        }
        return $return;
    }
    public static function consume_validation($shop_id, $warehouse_id, $item_id, $quantity, $remarks = '', $serial = array(), $ref_name = null, $bin_location = null, $refname = null, $refid = null, $qty_validation = false)
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        $serial_qty = count($serial);
        if($serial_qty != 0)
        {
            if($serial_qty != $quantity)
            {
                $return .= "The serial number are not equal from the quantity. <br> ";
            }

            foreach ($serial as $key => $value) 
            {
                $check_serial = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id)->where('record_serial_number',$value)->first();
                if(!$check_serial)
                {
                    $return .= "The serial number ".$value." does not exist in this warehouse. <br>";
                }
            }
        }
        if(is_numeric($quantity) == false)
        { 
            $return .= "The quantity must be a number. <br>";
        }
        $check_monthly_budget = AccountingTransaction::settings($shop_id, "monthly_budget");
        if($quantity < 1 && !$check_monthly_budget)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }

        $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $item_id, $bin_location, $refname, $refid);
        
        $settings_qty = 0;
        if($ref_name != 'customer_wis' && $ref_name != 'wis')
        {
            $settings_qty = Inventory::allow_out_of_stock($shop_id);
        }
        if($ref_name == 'adjust_inventory' || $ref_name == 'import_adjust_inventory')
        {
            $settings_qty = 1;
        }
        if($quantity > $inventory_qty && $settings_qty == 0)
        {
            $con_msg = "";
            if($bin_location)
            {
                $w_name = Tbl_warehouse::where("warehouse_id", $bin_location)->value("warehouse_name");
                $con_msg = " Please choose other than ". $w_name;
            }
            if($qty_validation == false)
            {
                $return .= Self::string_replace_for_url("The quantity of <b>".Item::info($item_id)->item_name."</b> is not enough to consume. ".$con_msg."<br>");
            /*$return .= "The quantity of <b>".str_replace("&", "and", str_replace("&", "and", Item::info($item_id)->item_name))."</b> is not enough to consume. ".$con_msg."<br>";*/
            }
            else
            {
                $return .= Item::info($item_id)->item_id;
            }
        }
        return $return;
    }

    public static function string_replace_for_url($str)
    {
        $str = str_replace("#", "No.", $str);
        $str = str_replace("&", "and", $str);

        return $str;
    }
    /*public static function check_qty_per_item($shop_id, $warehouse_id, $item_id, $quantity, $remarks = '', $serial = array(), $ref_name = null, $bin_location = null, $refname = null, $refid = null)
    {
        $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $item_id, $bin_location, $refname, $refid);
        
        $settings_qty = 0;
        if($ref_name != 'customer_wis' && $ref_name != 'wis')
        {
            $settings_qty = Inventory::allow_out_of_stock($shop_id);
        }
        if($quantity > $inventory_qty && $settings_qty == 0)
        {
            $con_msg = "";
            if($bin_location)
            {
                $w_name = Tbl_warehouse::where("warehouse_id", $bin_location)->value("warehouse_name");
                $con_msg = " Please choose other than ". $w_name;
            }
            $return .= "The quantity of <b>".Item::info($item_id)->item_name."</b> is not enough to consume. ".$con_msg."<br>";
        }
    }*/
    public static function consume_update($ref_name, $ref_id, $item_id, $quantity)
    {
        $data = Tbl_warehouse_inventory_record_log::where("record_consume_ref_name",$ref_name)->where("record_consume_ref_id",$ref_id)->get();
        
        for ($ctr_qty = 0; $ctr_qty < $quantity ; $ctr_qty ++) 
        { 
            $update['record_consume_ref_name'] = '';
            $update['record_consume_ref_id'] = 0;
            $update['record_inventory_status'] = 0;
            $update['record_item_remarks'] = 'Disassembled Item FROM Membership kit#'.$ref_id;

            Tbl_warehouse_inventory_record_log::where("record_consume_ref_name",$ref_name)->where("record_item_id",$item_id)->where("record_consume_ref_id",$ref_id)->update($update);
        }
    }
    public static function implode_replace($value_array)
    {
        $implode = null;
        if($value_array)
        {
            $replace = str_replace(" ","_",($value_array));
            $implode = implode (",", $replace);
        }
        return $implode;
    }
    public static function load_stock_on_hand($shop_id, $warehouse_id, $item_id, $invty_id)
    {
        $get = Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_warehouse_id',$warehouse_id)->where('invty_item_id',$item_id)->get();
        foreach ($get as $key => $value)
        {
            $data = Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_id', $invty_id)->first();

            $current_qty = Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)->where('invty_warehouse_id',$warehouse_id)->where('invty_item_id',$value->invty_item_id)->orderBy('invty_date_created','DESC')->skip(1)->take(1)->value('invty_stock_on_hand');
            $update_soh['invty_stock_on_hand']    = $current_qty == 0 ? $data->invty_qty : $current_qty + $data->invty_qty;
            Tbl_monitoring_inventory::where('invty_id', $data->invty_id)->update($update_soh);
        }
    }
    public static function consume($shop_id, $warehouse_id, $item_id = 0, $quantity = 1, $remarks = '', $consume = array(), $serial = array(), $inventory_history = '', $bin_location = null)
    {
        $return = null;
        $id = null;
        $insert_slip['warehouse_id']                 = $warehouse_id;
        $insert_slip['inventory_remarks']            = $remarks;
        $insert_slip['inventory_slip_date']          = Carbon::now();
        $insert_slip['inventory_slip_shop_id']       = $shop_id;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $insert_slip['inventroy_source_reason']      = isset($consume['name']) ? $consume['name'] : '';
        $insert_slip['inventory_source_id']          = isset($consume['id']) ? $consume['id'] : 0;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);
       
        /* START FOR MONITORING OF QUANTITY */
        $insert_inventory['invty_shop_id']          = $shop_id;
        $insert_inventory['invty_warehouse_id']     = $warehouse_id;
        $insert_inventory['invty_item_id']          = $item_id;
        $insert_inventory['invty_transaction_name'] = $consume['name'];
        $insert_inventory['invty_transaction_id']   = $consume['id'];
        $insert_inventory['invty_stock_on_hand']    = 0;
        $insert_inventory['invty_qty']              = $quantity * -1;
        $insert_inventory['invty_sales_price']       = str_replace(',', '', $consume['item_rate']);
        $insert_inventory['invty_total_sales_price'] = str_replace(',', '', $consume['item_rate']) * ($quantity * -1);
        $insert_inventory['invty_date_created']     = Carbon::now();
        $invty_id = Tbl_monitoring_inventory::insertGetId($insert_inventory);
        /* END FOR MONITORING OF QUANTITY */

        Self::load_stock_on_hand($shop_id, $warehouse_id, $item_id, $invty_id);
        $serial_qty = count($serial);

        /*
        <!------------ COMMENT TEMPORARY FOR TESTING PUSPOSES --------------->
        for ($ctr_qty = 0; $ctr_qty < $quantity; $ctr_qty++) 
        {
            $insert['record_shop_id']            = $shop_id;
            $insert['record_item_id']            = $item_id;
            $insert['record_warehouse_id']       = $warehouse_id;
            $insert['record_item_remarks']       = $remarks;
            $insert['record_warehouse_slip_id']  = $slip_id;
            $insert['record_consume_ref_name']   = isset($consume['name']) ? $consume['name'] : '';
            $insert['record_consume_ref_id']     = isset($consume['id']) ? $consume['id'] : 0;
            $insert['record_inventory_status']   = 1;
            $insert['record_log_date_updated']   = Carbon::now();
            $insert['record_count_inventory']    = 1;

            $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("item_in_use",'unused')
                                                   ->value('record_log_id');
            if($bin_location)
            {
                $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                       ->where("record_item_id",$item_id)
                                                       ->where("record_bin_id", $bin_location)
                                                       ->where("record_inventory_status",0)
                                                       ->where("item_in_use",'unused')
                                                       ->value('record_log_id');
                if(!$id)
                {
                    $get_under_bin = Warehouse2::get_under_bin($bin_location);
                    if(count($get_under_bin) > 0)
                    {
                        $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                               ->where("record_item_id",$item_id)
                                                               ->whereIn("record_bin_id", $get_under_bin)
                                                               ->where("record_inventory_status",0)
                                                               ->where("item_in_use",'unused')
                                                               ->value('record_log_id');                        
                    }
                }
            }
            if($serial_qty > 0)
            {
                $insert['record_serial_number'] = $serial[$ctr_qty];

                $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_bin_id", $bin_location)
                                                   ->where("record_inventory_status",0)
                                                   ->where("record_serial_number",$serial[$ctr_qty])
                                                   ->where("item_in_use",'unused')
                                                   ->value('record_log_id');
            }
            if(session('consume_offset_inventory'))
            {
                $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_count_inventory",0)
                                                   ->where("item_in_use",'unused')
                                                   ->value('record_log_id');
            }
            if($id)
            {     
                Warehouse2::insert_item_history($id);
                Tbl_warehouse_inventory_record_log::where('record_log_id',$id)->update($insert);
                session(['consume_offset_inventory' => null]);
            }
            else
            {
                if(Inventory::allow_out_of_stock($shop_id) == 1)
                {
                    $item_data = Item::info($item_id);
                    if($item_data)
                    {
                        $insert['record_sales_price'] = $item_data->item_price;
                        $insert['record_cost_price'] = $item_data->item_cost;
                    }
                    $insert['record_count_inventory'] = 0;
                    $insert['record_source_ref_name'] = $insert['record_consume_ref_name'];
                    $insert['record_source_ref_id'] = $insert['record_consume_ref_id'];
                    $id = Tbl_warehouse_inventory_record_log::insertGetId($insert);
                }
            }
        }
        */

        if(!$inventory_history)
        {
            $inventory_details['history_description'] = "Consume items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
            $inventory_details['history_remarks'] = $remarks;
            $inventory_details['history_type'] = "WIS";
            $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
            $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
            $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

            $history_item[0]['item_id'] = $item_id;
            $history_item[0]['quantity'] = $quantity;
            $history_item[0]['item_remarks'] = $remarks;

            Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
        }

        Warehouse2::update_inventory_count($warehouse_id, $slip_id, $item_id, -($quantity));

        return $return;
    }
    public static function sold_kit($shop_id, $warehouse_id, $item_id = 0, $quantity = 1, $remarks = '', $sold = array())
    {
        $ctr_inventory = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                               ->where("record_item_id",$item_id)
                                               ->where("record_consume_ref_id",0)
                                               ->where("record_inventory_status",0)
                                               ->count('record_log_id');
        $return = null;
        if($ctr_inventory > 0)
        {
            $insert['record_shop_id']            = $shop_id;
            $insert['record_item_id']            = $item_id;
            $insert['record_warehouse_id']       = $warehouse_id;
            $insert['record_item_remarks']       = $remarks;
            $insert['record_consume_ref_name']   = isset($sold['name']) ? $sold['name'] : '';
            $insert['record_consume_ref_id']     = isset($sold['id']) ? $sold['id'] : 0;
            $insert['record_log_date_updated']   = Carbon::now();
    
            $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->value('record_log_id');
            
            Tbl_warehouse_inventory_record_log::where('record_log_id',$id)->update($insert);
            
            Warehouse2::insert_item_history($id);
            
            return $id;
        }
    }  
    public static function consume_bulk($shop_id, $warehouse_id, $reference_name = '', $reference_id = 0 , $remarks = '', $_item)
    {
        $validate = null;
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : null;
            $validate .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial, $reference_name, null, $reference_name, $reference_id);
        }
        if(!$validate)
        { 
            if($reference_name != 'import_adjust_inventory' && $reference_name != 'adjust_inventory')
            {
                Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)
                                        ->where('invty_warehouse_id',$warehouse_id)
                                        ->where('invty_transaction_name', $reference_name)
                                        ->where('invty_transaction_id', $reference_id)
                                        ->delete();
            }
            foreach ($_item as $key => $value) 
            {          
                $serial = isset($value['serial']) ? $value['serial'] : null;
                $consume['name'] = $reference_name;
                $consume['id'] = $reference_id;
                $consume['item_rate'] = $value['item_rate'];
                $bin_location = isset($value['bin_location']) ? $value['bin_location'] : null;
                $record_log_id = isset($value['record_log_id']) ? $value['record_log_id'] : null;
                $validate = Warehouse2::consume($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $consume, $serial, 'inventory_history_recorded', $bin_location);
            }
        }

        return $validate;
    }

    public static function refill_bundling_item($shop_id, $warehouse_id, $item_id, $item_rate = 0, $quantity, $ref_name = '', $ref_id = 0, $bin_location = null)
    {
        $item_list = Item::get_item_in_bundle($item_id);
        $_item = [];
        foreach ($item_list as $key => $value) 
        {
            $_item[$key]['item_id'] = $value->bundle_item_id;
            $_item[$key]['quantity'] = $value->bundle_qty * $quantity;
            $_item[$key]['item_rate'] = Tbl_item::where('item_id', $value->bundle_item_id)->value('item_cost');
            $_item[$key]['remarks'] = 'consume item upon assembling item';
        }

        $validate_consume = Warehouse2::consume_bulk_src_ref($shop_id, $warehouse_id, 'bundling_item-'.$ref_name.'-'.$ref_id, $item_id, 'Consume Item upon bundling Item#'.$item_id, $_item, $ref_name, $ref_id);

        if(!$validate_consume)
        {
            $source['name'] = $ref_name;
            $source['id'] = $ref_id;
            $source['item_rate'] = Tbl_item::where('item_id', $item_id)->value('item_cost');
            $validate_consume .= Warehouse2::refill($shop_id, $warehouse_id, $item_id, $quantity, 'Refill Item upon bundling Item#'.$item_id, $source);    
        }
        return $validate_consume;
    }
    public static function consume_bundling_item($shop_id, $warehouse_id, $item_id, $quantity, $ref_name = '', $ref_id = 0, $bin_location = null)
    {
        $source['name'] = 'bundling_item-'.$ref_name.'-'.$ref_id;
        $source['id'] = $item_id;
        $source['item_rate'] = Tbl_item::where('item_id', $item_id)->value('item_cost');
        $qty = $quantity;
        $validate_consume = Warehouse2::consume($shop_id, $warehouse_id, $item_id, $quantity, 'Consume Item upon bundling Item#'.$item_id, $source);            

        return $validate_consume;
    }

    public static function inventory_delete_inventory_refill($shop_id, $warehouse_id, $ref_name, $ref_id, $item_info)
    {
        /* DELETE ALL INVENTORY */
        $get = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                 ->where("record_source_ref_name", $ref_name)
                                                 ->where("record_source_ref_id", $ref_id)
                                                 ->get();                                   
        $del = null;
        foreach ($get as $key => $value) 
        {
            if($value->record_consume_ref_name)
            {
                $explode = explode('-', $value->record_consume_ref_name);
                if(isset($explode[2]))
                {
                    $del = $value->record_consume_ref_name;
                }
            }
        }
        Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                 ->where("record_source_ref_name", $ref_name)
                                                 ->where("record_source_ref_id", $ref_id)
                                                 ->delete();
        if($del)
        {
            Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                              ->where('record_source_ref_name', $del)
                                              ->delete();
        }

    }

    public static function inventory_get_consume_data($shop_id, $warehouse_id, $insert_item, $ref_name, $ref_id, $remarks)
    {
        /*DELETE RECORD WHERE NOT CONSUMED YET*/
        // $delete = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
        //                                          ->where("record_source_ref_name", $ref_name)
        //                                          ->where("record_source_ref_id", $ref_id)
        //                                          ->whereNull("record_consume_ref_name")
        //                                          ->where("record_consume_ref_id", 0)
        //                                          ->get(); 

        // foreach ($delete as $key_delete => $value_delete)
        // {
        //     Tbl_warehouse_inventory_record_log::where('record_log_id', $value_delete->record_log_id)->delete();
        // }
        /*INSERT UPDATED REFILL*/
        AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, $ref_name, $ref_id, $remarks); 

        /*UPDATE CONSUMED FROM DELETED RECORD*/
        $get = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                 ->where("record_source_ref_name", $ref_name)
                                                 ->where("record_source_ref_id", $ref_id)
                                                 ->get();             

        $record = null;
        foreach ($get as $key => $value) 
        {
            if($value->record_consume_ref_name && $value->record_consume_ref_id)
            {
                $record[$key]['item_refname'] = $value->record_consume_ref_name;
                $record[$key]['item_refid']   = $value->record_consume_ref_id;
                $record[$key]['item_id'] = $value->record_item_id;
                $record[$key]['item_description'] = $value->record_item_remarks;
                $record[$key]['item_sub_warehouse'] = $value->record_bin_id;
                $record[$key]['item_qty'] = 1;
                $record[$key]['item_um'] = 0;
                $record[$key]['item_rate'] = $value->item_rate;
                $record[$key]['record_log_id'] = $value->record_log_id;

                AccountingTransaction::consume_inventory($shop_id, $warehouse_id, $record, $value->record_consume_ref_name, $value->record_consume_ref_id, 'Consume upon updating '.$ref_name);
                Tbl_warehouse_inventory_record_log::where('record_log_id', $value->record_log_id)->delete();
            }
        }
    }
    public static function update_inventory_consume($shop_id, $warehouse_id = null, $ref_name, $ref_id)
    {
        $get = Tbl_warehouse_inventory_record_log::where("record_consume_ref_name", $ref_name)
                                                 ->where("record_consume_ref_id", $ref_id)
                                                 ->where("record_count_inventory", 1);

        if($warehouse_id)
        {
            $get = $get->where('record_warehouse_id', $warehouse_id);
        }
        $get = $get->get();
        if(count($get) > 0)
        {
            $update = null;
            foreach ($get as $key => $value) 
            {
                Warehouse2::insert_item_history($value->record_log_id);
            }

            $update['record_inventory_status'] = 0;
            $update['record_consume_ref_name'] = null;    
            $update['record_consume_ref_id'] = 0;
            
            Tbl_warehouse_inventory_record_log::where("record_consume_ref_name", $ref_name)
                                                  ->where("record_consume_ref_id", $ref_id)
                                                  ->update($update);
        }
        $del = Tbl_warehouse_inventory_record_log::where("record_consume_ref_name", $ref_name)
                                                 ->where("record_consume_ref_id", $ref_id)
                                                 ->where("record_count_inventory", 0);
        if($warehouse_id)
        {
            $del = $del->where('record_warehouse_id', $warehouse_id);
        }
        $del->delete();
    }
    public static function consume_bulk_src_ref($shop_id, $warehouse_id, $reference_name = '', $reference_id = 0 , $remarks = '', $_item, $ref_src_name = '', $ref_src_id = 0)
    {
        $validate = null;
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : null;
            $validate .= Warehouse2::consume_validation_ref_num($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial, $ref_src_name, $ref_src_id);
        }
        if(!$validate)
        {
            foreach ($_item as $key => $value) 
            {                
                $serial = isset($value['serial']) ? $value['serial'] : null;

                $consume['name'] = $reference_name;
                $consume['id'] = $reference_id;
                $consume['item_rate'] = Tbl_item::where('item_id', $value['item_id'])->value('item_cost');
                $validate = Warehouse2::consume_src_ref($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $consume, $serial, 'inventory_history_recorded', $ref_src_name, $ref_src_id);
            }
        }

        return $validate;

    }
    public static function consume_src_ref($shop_id, $warehouse_id, $item_id = 0, $quantity = 1, $remarks = '', $consume = array(), $serial = array(), $inventory_history = '', $ref_src_name = '', $ref_src_id = 0)
    {
        $return = null;

        $insert_slip['warehouse_id']                 = $warehouse_id;
        $insert_slip['inventory_remarks']            = $remarks;
        $insert_slip['inventory_slip_date']          = Carbon::now();
        $insert_slip['inventory_slip_shop_id']       = $shop_id;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $insert_slip['inventroy_source_reason']      = isset($consume['name']) ? $consume['name'] : '';
        $insert_slip['inventory_source_id']          = isset($consume['id']) ? $consume['id'] : 0;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

        $serial_qty = count($serial);
        for ($ctr_qty = 0; $ctr_qty < $quantity; $ctr_qty++) 
        {
            $insert['record_shop_id']            = $shop_id;
            $insert['record_item_id']            = $item_id;
            $insert['record_warehouse_id']       = $warehouse_id;
            $insert['record_item_remarks']       = $remarks;
            $insert['record_warehouse_slip_id']  = $slip_id;
            $insert['record_consume_ref_name']   = isset($consume['name']) ? $consume['name'] : '';
            $insert['record_consume_ref_id']     = isset($consume['id']) ? $consume['id'] : 0;
            $insert['record_inventory_status']   = 1;
            $insert['record_log_date_updated']   = Carbon::now();

            $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("item_in_use",'unused')
                                                   ->where("record_source_ref_name", $ref_src_name)
                                                   ->where("record_source_ref_id", $ref_src_id)
                                                   ->value('record_log_id');
            if($serial_qty > 0)
            {
                $insert['record_serial_number'] = $serial[$ctr_qty];

                $id = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("record_serial_number",$serial[$ctr_qty])
                                                   ->where("item_in_use",'unused')
                                                   ->where("record_source_ref_name", $ref_src_name)
                                                   ->where("record_source_ref_id", $ref_src_id)
                                                   ->value('record_log_id');
            }
            Warehouse2::insert_item_history($id);
            Tbl_warehouse_inventory_record_log::where('record_log_id',$id)->update($insert);
        }

        if(!$inventory_history)
        {
            $inventory_details['history_description'] = "Consume items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
            $inventory_details['history_remarks'] = $remarks;
            $inventory_details['history_type'] = "WIS";
            $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
            $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
            $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

            $history_item[0]['item_id'] = $item_id;
            $history_item[0]['quantity'] = $quantity;
            $history_item[0]['item_remarks'] = $remarks;

            Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
        }

        Warehouse2::update_inventory_count($warehouse_id, $slip_id, $item_id, -($quantity));

        return $return;
    }
    public static function consume_validation_ref_num($shop_id, $warehouse_id, $item_id, $quantity, $remarks = '', $serial = array(), $ref_src_name = '', $ref_src_id = 0)
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        $serial_qty = count($serial);
        if($serial_qty != 0)
        {
            if($serial_qty != $quantity)
            {
                $return .= "The serial number are not equal from the quantity. <br> ";
            }

            foreach ($serial as $key => $value) 
            {
                $check_serial = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id)->where('record_serial_number',$value)->first();
                if(!$check_serial)
                {
                    $return .= "The serial number ".$value." does not exist in this warehouse. <br>";
                }
            }
        }
        if(is_numeric($quantity) == false)
        { 
            $return .= "The quantity must be a number. <br>";
        }
        if($quantity < 1)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }
        $inventory_qty = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                   ->where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("record_source_ref_name", $ref_src_name)
                                                   ->where("record_source_ref_id", $ref_src_id)
                                                   ->count();
        if($quantity > $inventory_qty)
        {
            $return .= "The quantity of <b>".str_replace("&", "and", Item::info($item_id)->item_name)."</b> is not enough to consume. <br>";
        }
        return $return;
    }

    /* PARAM
        $code[0]['mlm_pin']
        $code[0]['mlm_activation']
     */
    public static function consume_bulk_product_codes($shop_id = 0, $codes = array(), $consume = array())
    {
        foreach ($code as $key => $value) 
        {
            $ret .= Warehouse2::consume_product_code_validation($shop_id, $value['mlm_pin'], $value['mlm_activation'], $consume);
        }
        if(!$ret)
        {
            foreach ($code as $key => $value) 
            {
                Warehouse2::consume_product_codes($shop_id, $value['mlm_pin'], $value['mlm_activation'], $consume);
            }            
        }

    }
    public static function consume_product_code_validation($shop_id = 0, $mlm_pin = '', $mlm_activation = '' )
    {
        $return = null;
        $val = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)
                                                 ->where('mlm_activation',$mlm_activation)
                                                 ->where('mlm_pin',$mlm_pin)
                                                 ->where('record_inventory_status',0)
                                                 ->first();
        if(!$val)
        {
            $return ="<b>". $mlm_pin."</b> and <b>".$mlm_activation ."</b> doesn't exist.<br>";
        }

        return $return;

    }
    public static function consume_product_codes($shop_id = 0, $mlm_pin = '', $mlm_activation = '', $consume = array(), $remarks = "Consume using product codes.", $code_used = 'used')
    {
        $return = null;
        $val = Tbl_warehouse_inventory_record_log::where("record_shop_id",$shop_id)
                                                 ->where('mlm_activation',$mlm_activation)
                                                 ->where('mlm_pin',$mlm_pin)
                                                 ->where('item_in_use', 'unused')
                                                 ->first();
        if($val)
        {
            if($val->record_inventory_status == 1)
            {
                $update['record_log_date_updated']   = Carbon::now();
                $update['item_in_use']               = $code_used;
               
                Warehouse2::insert_item_history($val->record_log_id);
                Tbl_warehouse_inventory_record_log::where('record_log_id',$val->record_log_id)->update($update);
            }
            else
            {
                Warehouse2::consume_record_log($shop_id, $val->record_warehouse_id, $val->record_item_id,$val->record_log_id, 1, $remarks, $consume,null, $code_used);
            }
            $return = $val->record_item_id;
        }
        else
        {
            $return = "Pin number and activation code doesn't exist.";
        }

        return $return;

    }
    public static function consume_record_log($shop_id, $warehouse_id, $item_id = 0, $recor_log_id = 0, $quantity = 1, $remarks = '', $consume = array(), $inventory_history = '', $code_used = 'used')
    {
        $return = null;

        $insert_slip['warehouse_id']                 = $warehouse_id;
        $insert_slip['inventory_remarks']            = $remarks;
        $insert_slip['inventory_slip_date']          = Carbon::now();
        $insert_slip['inventory_slip_shop_id']       = $shop_id;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $insert_slip['inventroy_source_reason']      = isset($consume['name']) ? $consume['name'] : '';
        $insert_slip['inventory_source_id']          = isset($consume['id']) ? $consume['id'] : 0;
        $insert_slip['slip_user_id']                 = Warehouse::getUserid();
        $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);
       
        $insert['record_shop_id']            = $shop_id;
        $insert['record_item_id']            = $item_id;
        $insert['record_warehouse_id']       = $warehouse_id;
        $insert['record_item_remarks']       = $remarks;
        $insert['record_warehouse_slip_id']  = $slip_id;
        $insert['record_consume_ref_name']   = isset($consume['name']) ? $consume['name'] : '';
        $insert['record_consume_ref_id']     = isset($consume['id']) ? $consume['id'] : 0;
        $insert['record_inventory_status']   = 1;
        $insert['record_log_date_updated']   = Carbon::now();
        $insert['item_in_use']               = $code_used;
       
        Warehouse2::insert_item_history($recor_log_id);
        Tbl_warehouse_inventory_record_log::where('record_log_id',$recor_log_id)->update($insert);

        if(!$inventory_history)
        {
            $inventory_details['history_description'] = "Consume items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
            $inventory_details['history_remarks'] = $remarks;
            $inventory_details['history_type'] = "WIS";
            $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
            $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
            $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

            $history_item[0]['item_id'] = $item_id;
            $history_item[0]['quantity'] = $quantity;
            $history_item[0]['item_remarks'] = $remarks;

            Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
        }

        Warehouse2::update_inventory_count($warehouse_id, $slip_id, $item_id, -($quantity));

        return $return;
    }
    public static function get_user_login()
    {
        $user_id = 0;
        $user_data = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');
        if($user_data)
        {
            $user_id = $user_data;
        }
        return $user_id;
    }
    /** example 
        WIS - For Consuming
        RR - For Reffiling    

        $inventory_details['history_description'] = "Bulk Consume 5 EACH item to Main Warehouse";
        $inventory_details['history_remarks'] = "INVOICE #14545";
        $inventory_details['history_type'] = "WIS";
        $inventory_details['history_reference'] = "invoice";
        $inventory_details['history_reference_id'] = 25;
        $inventory_details['history_number'] = 'RR00001';

        $history_item[0]['item_id'] = 500;
        $history_item[0]['quantity'] = 5;
        $history_item[0]['item_remarks'] = 'Item 1';

        $history_item[1]['item_id'] = 501;
        $history_item[1]['quantity'] = 5;
        $history_item[1]['item_remarks'] = 'Item 2';

        $history_item[2]['item_id'] = 502;
        $history_item[2]['quantity'] = 5;
        $history_item[2]['item_remarks'] = 'Item 3';

        $return = Warehouse2::insert_inventory_history($this->user_info->shop_id,Warehouse2::get_current_warehouse($this->user_info->shop_id),$inventory_details,$history_item);

    */
    public static function insert_inventory_history($shop_id, $warehouse_id, $history_details = array(), $history_item = array())
    {
        $insert_history['shop_id']              = $shop_id;
        $insert_history['warehouse_id']         = $warehouse_id;
        $insert_history['history_description']  = $history_details['history_description'];
        $insert_history['history_remarks']      = $history_details['history_remarks'];
        $insert_history['history_type']         = $history_details['history_type'];
        $insert_history['history_reference']    = isset($history_details['history_reference']) ? $history_details['history_reference'] : '';
        $insert_history['history_reference_id'] = isset($history_details['history_reference_id']) ? $history_details['history_reference_id'] : 0;
        $insert_history['history_number']       = $history_details['history_number'];
        $insert_history['history_date']         = Carbon::now();
        $insert_history['history_user']         = Warehouse2::get_user_login();

        $history_id = Tbl_inventory_history::insertGetId($insert_history);

        $return = Warehouse2::insert_inventory_history_item($history_id, $history_item);

        return $return;
    }

    public static function insert_inventory_history_item($history_id, $history_item)
    {
        $history_data = Tbl_inventory_history::where('history_id',$history_id)->first();
        foreach ($history_item as $key => $value) 
        {
            $initial_qty = Tbl_inventory_history_items::history()->where('warehouse_id',$history_data->warehouse_id)->where('item_id',$value['item_id'])->orderBy('history_item_id', 'DESC')->value('running_quantity');

            $qty = 1;
            if($history_data->history_type == 'WIS')
            {
                $qty = -1;
            }

            $insert_item['history_id'] = $history_id;
            $insert_item['item_id'] = $value['item_id'];
            $insert_item['quantity'] = $value['quantity'];
            $insert_item['item_remarks'] = $value['item_remarks'];
            $insert_item['initial_quantity'] = $initial_qty != '' ? $initial_qty : 0 ; 
            $insert_item['running_quantity'] = $insert_item['initial_quantity'] + ($value['quantity'] * $qty);

            Tbl_inventory_history_items::insert($insert_item);
        }

        return 0;
    }
    public static function update_warehouse_item($record_log_id, $update)
    {
        Warehouse2::insert_item_history($record_log_id);
        Tbl_warehouse_inventory_record_log::where('record_log_id',$record_log_id)->update($update);
    }



    public static function refill_2($shop_id, $warehouse_id, $item_id = 0, $quantity = 1, $remarks = '', $source = array(), $serial = array(), $inventory_history = '', $update_count = true, $for_out_of_stock = '')
    {

        $count_offset = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id )->where('record_count_inventory','<',0)->count();
        $total_refill_qty = $quantity;
        if($count_offset > 0)
        {
            $total_refill_qty = $quantity - $count_offset;
        }
        if(!$for_out_of_stock)
        {
            Self::update_offset_qty($warehouse_id, $item_id, $count_offset, $quantity);
        }
        $quantity = $total_refill_qty;       

        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        $return = null;

        $serial_qty = count($serial);
        if(!$return)
        {  
            $insert_slip['warehouse_id']                 = $warehouse_id;
            $insert_slip['inventory_remarks']            = $remarks;
            $insert_slip['inventory_slip_date']          = Carbon::now();
            $insert_slip['inventory_slip_shop_id']       = $shop_id;
            $insert_slip['inventroy_source_reason']      = isset($source['name']) ? $source['name'] : '';
            $insert_slip['inventory_source_id']          = isset($source['id']) ? $source['id'] : 0;
            $insert_slip['slip_user_id']                 = Warehouse2::get_user_login();
            $slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

            $insert = null;
            for ($ctr_qty = 0; $ctr_qty < $quantity; $ctr_qty++) 
            {
                $insert[$ctr_qty]['record_shop_id']            = $shop_id;
                $insert[$ctr_qty]['record_item_id']            = $item_id;
                $insert[$ctr_qty]['record_warehouse_id']       = $warehouse_id;
                $insert[$ctr_qty]['record_item_remarks']       = $remarks;
                $insert[$ctr_qty]['record_warehouse_slip_id']  = $slip_id;
                $insert[$ctr_qty]['record_source_ref_name']    = isset($source['name']) ? $source['name'] : '';
                $insert[$ctr_qty]['record_source_ref_id']      = isset($source['id']) ? $source['id'] : 0;
                $insert[$ctr_qty]['record_log_date_updated']   = Carbon::now();
                $insert[$ctr_qty]['mlm_pin']                   = Warehouse2::get_mlm_pin($shop_id);
                $insert[$ctr_qty]['mlm_activation']            = Item::get_mlm_activation($shop_id);
                $insert[$ctr_qty]['ctrl_number']               = Warehouse2::get_control_number($warehouse_id, $shop_id, Item::get_item_type($item_id));
                $item_data = Item::info($item_id);
                if($item_data)
                {
                    $insert[$ctr_qty]['record_sales_price'] = $item_data->item_price;
                    $insert[$ctr_qty]['record_cost_price'] = $item_data->item_cost;
                }
                if($for_out_of_stock)
                {
                    $insert[$ctr_qty]['record_count_inventory'] = -1;
                }

                if($serial_qty > 0)
                {
                    $insert[$ctr_qty]['record_serial_number'] = $serial[$ctr_qty];
                }
                Tbl_warehouse_inventory_record_log::insert($insert[$ctr_qty]);
            }

            if(!$inventory_history)
            {
                $inventory_details['history_description'] = "Refill items from ". $insert_slip['inventroy_source_reason']." #".$insert_slip['inventory_source_id'];
                $inventory_details['history_remarks'] = $remarks;
                $inventory_details['history_type'] = "RR";
                $inventory_details['history_reference'] = $insert_slip['inventroy_source_reason'];
                $inventory_details['history_reference_id'] = $insert_slip['inventory_source_id'];
                $inventory_details['history_number'] = Warehouse2::get_history_number($shop_id, $warehouse_id, $inventory_details['history_type']);

                $history_item[0]['item_id'] = $item_id;
                $history_item[0]['quantity'] = $quantity;
                $history_item[0]['item_remarks'] = $remarks;

                Warehouse2::insert_inventory_history($shop_id, $warehouse_id, $inventory_details, $history_item);
            }

            if($update_count == true)
            {
                Warehouse2::update_inventory_count($warehouse_id, $slip_id, $item_id, $quantity);
            }
        }    

        return $return;
    }

    public static function consume_validation_backup($shop_id, $warehouse_id, $item_id, $quantity, $remarks, $serial = array(), $allow_out_of_stock = false)
    {
        $return = null;
        $check_warehouse = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$shop_id)->first();

        $serial_qty = count($serial);
        if($serial_qty != 0)
        {
            if($serial_qty != $quantity)
            {
                $return .= "The serial number are not equal from the quantity. <br> ";
            }

            foreach ($serial as $key => $value) 
            {
                $check_serial = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',$warehouse_id)->where('record_item_id', $item_id)->where('record_serial_number',$value)->first();
                if(!$check_serial)
                {
                    $return .= "The serial number ".$value." does not exist in this warehouse. <br>";
                }
            }
        }
        if($allow_out_of_stock == false)
        {
            if(is_numeric($quantity) == false)
            {
                $return .= "The quantity must be a number. <br>";
            }
        }
        if($quantity < 1)
        {
            $return .= "The quantity is less than 1. <br> ";
        }
        if(!$check_warehouse)
        {
            $return .= "The warehouse doesn't belong to your account <br>";
        }
        $inventory_qty = Warehouse2::get_item_qty($warehouse_id, $item_id);
        if($quantity > $inventory_qty)
        {
            $return .= "The quantity of <b>".str_replace("&", "and", Item::info($item_id)->item_name)."</b> is not enough to consume. <br>";
        }
        if($allow_out_of_stock == true)
        {
            Self::inventory_allow_out_of_stock($shop_id, $warehouse_id, $item_id, $quantity);
            $return = null;
        }
        return $return;
    }
    public static function inventory_allow_out_of_stock($shop_id, $warehouse_id, $item_id, $quantity = 1)
    {
        $v1_qty = Tbl_item::inventory($warehouse_id)->where('tbl_item.item_id', $item_id)->where('tbl_item.shop_id',$shop_id)->value('inventory_count');
        $v2_qty = Tbl_item::recordloginventory($warehouse_id)->where('tbl_item.item_id', $item_id)->where('tbl_item.shop_id',$shop_id)->value('inventory_count');

        $total_to_refill = $v1_qty - $v2_qty;
        $source['name'] = 'inventory_v1';
        $source['id'] = 0;
        // if($total_to_refill > 0)
        // {
        //     Self::refill($shop_id, $warehouse_id, $item_id, $total_to_refill, 'Migrate Inventory v1 to v2', $source, null, null, false);
        // }
        if($quantity > $v2_qty)
        {
            $total_out_of_stock = $quantity - $v2_qty;
            Self::refill($shop_id, $warehouse_id, $item_id, $total_out_of_stock, 'Migrate Inventory v1 to v2', $source, null, null, false, 'for_out_of_stock');
        }
    }
    public static function get_all_warehousev2()
    {
        return Tbl_warehouse::selectRaw('warehouse_id, warehouse_shop_id')->where('archived',0)->get();        
    }
    public static function item_warehouse($warehouse_id)
    {
        return Tbl_item::inventory()->where('warehouse_id',$warehouse_id)->get();
    }
    public static function migrate_warehouse_inventory()
    {
        $all_warehouse = Tbl_warehouse::where('archived',0)->get();
        $return = null;

        $all_serial = Tbl_inventory_serial_number::selectRaw("item_id, serial_number")->where('archived',0)->where("item_consumed",0)->where("sold",0)->groupBy('serial_number')->get()->toArray();
            $_item = null;
        foreach ($all_warehouse as $key_warehouse => $value_warehouse) 
        {
            $all_item = Tbl_item::inventory()->where('warehouse_id',$value_warehouse->warehouse_id)->get();
            
            $total_inventory = 0;
            foreach ($all_item as $key => $value)
            {
                $log_count = 0;

                $get_log_count = Tbl_item::recordloginventory()
                                     ->where('item_id',$value->item_id)
                                     ->where('record_inventory_status',0)
                                     ->value('log_count');
                if($get_log_count)
                {
                    $log_count = $get_log_count;
                }

                $total_inventory = $value->inventory_count - $log_count;
                if($total_inventory > 0)
                {
                    $_item[$key]["item_id"]              = $value->item_id;
                    $_item[$key]["quantity"]             = $total_inventory;
                    $_item[$key]["remarks"]              = "Refill - Inventory Migrated from old warehouse";    
                    $_item[$key]["warehouse_shop_id"]    = $value_warehouse->warehouse_shop_id;        
                    $_item[$key]["warehouse_id"]         = $value_warehouse->warehouse_id;          
                    $_item[$key]["ref_name"]             = 'inventory_migrate';
                }
            }
            // if(count($_item) > 0)
            // {
            //     $return = Warehouse2::refill_bulk($value_warehouse->warehouse_shop_id, $value_warehouse->warehouse_id, "inventory_migrate", 0 , "Refill - Inventory Migrated from old warehouse", $_item);
            // }
        }

        return $_item;       
    }
    public static function get_codes($warehouse_id, $start_date, $end_date, $transaction_type = '', $code_type = 'membership_code')
    {
        $data = Tbl_warehouse_inventory_record_log::warehouse()->item()->slotinfo()->where("item_in_use",'used')->where("record_inventory_status",1)->where('record_warehouse_id',$warehouse_id)->whereBetween('record_log_date_updated',[$start_date, $end_date]);

        if($transaction_type != '')
        {
            if($transaction_type == 'online')
            {
                $data = $data->where('record_consume_ref_name', 'transaction_list');
            }
            if($transaction_type == 'offline')
            {
                $data = $data->where('record_consume_ref_name','!=', 'transaction_list');
            }
        }
        if($code_type == 'product_code')
        {
            $data = $data->where("item_type_id", '!=', 5);
            if($transaction_type != 'offline')
            {
                $data = $data->customerinfo_data();
            }
        }
        else
        {
            $data = $data->customerinfo();
        }

        return $data->get();
    }
    public static function load_warehouse_list($shop_id, $user_id, $parent = 0, $margin_left = 0, $archived = 0, $search_keyword = null)
    {
        $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('warehouse_parent_id', $parent)->where("archived", $archived);
        if($search_keyword)
        {
            $warehouse->where(function($q) use ($search_keyword)
            {
                $q->orWhere('warehouse_name', "LIKE", "%" . $search_keyword . "%");
                $q->orWhere('warehouse_address', "LIKE", "%" . $search_keyword . "%");
            });
        }
        $warehouse = $warehouse->get();
        $return = null;
        
        foreach ($warehouse as $key => $value) 
        {
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$user_id)->where("warehouse_id",$value->warehouse_id)->first();
            if($check_if_owned)
            {

                $data['tr_class'] = 'tr-sub-'.$value->warehouse_parent_id.' tr-parent-'.$parent.' ';
                $data['warehouse'] = $value;
                $data['margin_left'] = 'style="margin-left:'.$margin_left.'px"';

                $return .= view('member.warehousev2.warehouse_list_tr',$data)->render();

                $count = Tbl_warehouse::where("warehouse_parent_id", $value->warehouse_id)->count();
                if($count != 0)
                {
                    $return .= Self::load_warehouse_list($shop_id, $user_id, $value->warehouse_id, $margin_left + 30, $archived, $search_keyword);
                }                
            }

        }
        return $return;
    }
    public static function load_all_warehouse_select($shop_id, $user_id, $parent = 0, $warehouse_id_selected = 0, $excluded_warehouse = 0, $include_parent = 0)
    {
        $return = null;
        $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('warehouse_parent_id', $parent);
        if($excluded_warehouse)
        {
            $warehouse = $warehouse->where('warehouse_id', '!=', $excluded_warehouse);
        }
        $warehouse = $warehouse->get();
        if($include_parent)
        {
            $data['warehouse'] = Tbl_warehouse::where("warehouse_id", $include_parent)->first();
            $data['warehouse_id'] = $warehouse_id_selected;
            $return .= view('member.warehousev2.load_warehouse_v2',$data)->render();
        }
        foreach ($warehouse as $key => $value) 
        {
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$user_id)->where("warehouse_id",$value->warehouse_id)->first();
            if($check_if_owned)
            {
                $data['warehouse'] = $value;
                $data['warehouse_id'] = $warehouse_id_selected;
                $return .= view('member.warehousev2.load_warehouse_v2',$data)->render();
                $count = Tbl_warehouse::where("warehouse_parent_id", $value->warehouse_id)->count();
                if($count != 0)
                {
                    $return .= Self::load_all_warehouse_select($shop_id, $user_id, $value->warehouse_id, $warehouse_id_selected);
                } 
            }
        }
        return $return;
    }
    public static function load_sub_warehouse_select($shop_id, $user_id, $parent = 0, $warehouse_id_selected = 0, $excluded_warehouse = 0)
    {
        //dd($shop_id." ".$user_id." ".$parent." ".$warehouse_id_selected." ".$excluded_warehouse);
        $return = null;
        $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('warehouse_parent_id', $parent);
        if($excluded_warehouse)
        {
            $warehouse = $warehouse->where('warehouse_id', '!=', $excluded_warehouse);
        }
        $warehouse = $warehouse->first();

        if($warehouse)
        {
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$user_id)->where("warehouse_id",$warehouse->warehouse_id)->first();

            if($check_if_owned)
            {
                $data['warehouse'] = $warehouse;
                $data['warehouse_id'] = $warehouse_id_selected;
                //dd($data);
                $return .= view('member.warehousev2.load_warehouse_v2',$data)->render();
                $count = Tbl_warehouse::where("warehouse_parent_id", $warehouse->warehouse_id)->count();
                if($count != 0)
                {
                    $return .= Self::load_all_warehouse_select($shop_id, $user_id, $warehouse->warehouse_id, $warehouse_id_selected);
                } 
            }
        }
        return $return;
    }
   /*  public static function load_sub_warehouse_select($shop_id, $parent)
    {
        $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->get();
        foreach ($warehouse as $key => $value)
        {
            $warehouse = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('warehouse_parent_id', $parent)->get();
        }

        return $warehouse;
    }*/
    public static function consume_offset_inventory($get)
    {
        session(['consume_offset_inventory' => $get]);
    }
    public static function refill_offset_inventory($get)
    {
        session(['refill_offset_inventory' => $get]);
    }
    public static function refill_adjust_inventory($get)
    {
        session(['refill_adjust_inventory' => $get]);
    }
    public static function adjust_inventory($shop_id, $warehouse_id, $item_id, $quantity, $remarks = '', $ref = array())
    {
       
        /* ZERO OUT ALL THE INVENTORY BY USING CONSUME */
        $get_current_inventory = Tbl_warehouse_inventory_record_log::where('record_shop_id', $shop_id)
                                                   ->where("record_warehouse_id", $warehouse_id)
                                                   ->where("record_item_id", $item_id)
                                                   ->where('record_inventory_status',0)
                                                   ->where("record_count_inventory",1)
                                                   ->count();
        $get_offset_inventory = Tbl_warehouse_inventory_record_log::where('record_shop_id', $shop_id)
                                                   ->where("record_warehouse_id", $warehouse_id)
                                                   ->where("record_item_id", $item_id)
                                                   ->where('record_count_inventory',0)
                                                   ->count();

        $t_qty = $get_offset_inventory + $get_current_inventory;
        if($t_qty > 0)
        {
            if($get_offset_inventory > 0)
            {
                Self::consume_offset_inventory(true);
            }
            Self::consume($shop_id, $warehouse_id, $item_id, $t_qty, $remarks, $ref);
        }

        if($quantity > 0) /*POSITIVE*/
        {
            Self::refill_adjust_inventory(true);
            Self::refill($shop_id, $warehouse_id, $item_id, $quantity, $remarks, $ref);
        }
        else /*NEGATIVE*/
        {
            Self::refill_offset_inventory(true);
            Self::refill($shop_id, $warehouse_id, $item_id, abs($quantity), $remarks, $ref);
        }

    }
    public static function adjust_inventory_bulk($shop_id, $warehouse_id, $item_info = array(), $remarks = '', $ref = array())
    {
        foreach ($item_info as $key => $value) 
        {
            Self::adjust_inventory($shop_id, $warehouse_id, $value['item_id'], $value['item_new_qty'], $remarks, $ref);
        }
    }
    public static function adjust_inventory_update_bulk($shop_id, $warehouse_id, $item_info = array(), $remarks = '', $ref = array())
    {
        $get_consume = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name', $ref['name'])->where('record_consume_ref_id', $ref['id'])->get();
        if(count($get_consume))
        {
            foreach ($get_consume as $key => $value) 
            {
                $update = Self::get_previous_data($value->record_log_id);
                Tbl_warehouse_inventory_record_log::where('record_log_id', $value->record_log_id)->update($update);
            }            
        }
        Tbl_warehouse_inventory_record_log::where('record_source_ref_name', $ref['name'])->where('record_source_ref_id', $ref['id'])->delete();
        Self::delete_inventory_history($shop_id, $ref);
        Self::adjust_inventory_bulk($shop_id, $warehouse_id, $item_info, $remarks, $ref);
    }
    public static function get_previous_data($record_log_id)
    {
        $return = null;
        $data = Tbl_warehouse_inventory_record_log::where('record_log_id', $record_log_id)->value('record_log_history');
        if($data)
        {
            $value = unserialize($data);
            $return = end($value);
            unset($return['record_log_id']);
        }
        return $return;
    }
    public static function delete_inventory_history($shop_id, $ref = array())
    {
        Tbl_inventory_history::where('shop_id', $shop_id)->where("history_reference", $ref['name'])->where('history_reference_id',$ref['id'])->delete();
    }
    public static function item_per_transaction($shop_id, $history_reference,  $item_id)
    {
        /*if($history_reference == 'receive_inventory')
        {
            $history_reference_id = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->value('ri_id');
            $history_reference = 'receive_inventory';
        }

        $data = Tbl_inventory_history::where('shop_id', $shop_id)->where('item_id', $item_id)->where('history_reference', $history_reference)->where('history_reference_id', $history_reference_id)->get();
        dd($data);
        return $data;*/
    }
    public static function check_new_item_reorderpoint($shop_id, $warehouse_id)
    {
        $reorder_item = null;

        if(Self::reorder_settings($shop_id))
        {
            $get_old = Session::get("reorder_item_".$warehouse_id);
            if(!$get_old)
            {
                $get_old = array();
            }
            $reorder_item = Self::get_item_reorderpoint($shop_id, $warehouse_id);

            $array1 = Self::get_single_array($reorder_item,'item_id');
            $array2 = Self::get_single_array($get_old,'item_id');

            $array_diff = array_diff($array1, $array2);
            if(count($array_diff) > 0 && count($reorder_item) > 0)
            {
                Session::put("show_reorder", 'true');
                Session::put("reorder_item_".$warehouse_id, $reorder_item);
            }
            else
            {
                Session::put("show_reorder", 'false');
                Session::put("reorder_item_".$warehouse_id, $reorder_item);
            }
        }
        return $reorder_item;
    }
    public static function get_single_array($array, $index = 'item_id')
    {
        $return = array();
        if(count($array) > 0)
        {
            foreach ($array as $key => $value) 
            {
                if(isset($value[$index]))
                {
                    $return[$key] = $value[$index];
                }
            }
        }
        return $return;
    }
    public static function get_item_reorderpoint($shop_id, $warehouse_id)
    {
        $reorder_item = array();
        $item = Tbl_item::where("shop_id", $shop_id)->where("archived",0)->whereIn("item_type_id",[1,4,5])->get();
        $perwarehouse_reorder = AccountingTransaction::settings_value($shop_id, 'perwarehouse_reorder');
        if($perwarehouse_reorder == 1)
        {
            $item = Item::get_item_warehouse_reorder($shop_id ,[1] , $warehouse_id);
        }
        foreach ($item as $key => $value) 
        {
            $curr_inventory = Self::get_item_qty($warehouse_id, $value->item_id);
            $reorder_point = $perwarehouse_reorder == 1 ? $value->warehouse_reorder : $value->item_reorder_point;
            if($curr_inventory <= $reorder_point && $reorder_point != 0)
            {
                $reorder_item[$key]['item_id']         = $value->item_id;
                $reorder_item[$key]['item_barcode']    = $value->item_barcode;
                $reorder_item[$key]['item_name']       = $value->item_name;
                $reorder_item[$key]['item_reorder']    = $reorder_point;
                $reorder_item[$key]['item_qty']        = $curr_inventory;
            }
        }

        return $reorder_item;
    }
    public static function reorder_settings($shop_id)
    {
        $return = false;
        $chk = Tbl_settings::where("settings_key","reorder_item")->where("shop_id",$shop_id)->value("settings_value");
        if($chk == 1)
        {
            $return = true;
        }
        return $return;
    }
    public static function get_equipment_report($shop_id, $date_from = '', $date_to = '', $user_id = 0)
    {
        $_item = null;
        $items = Tbl_warehouse_inventory_record_log::item()->category()->where("equipment_type_category",1)
                                                    ->where("tbl_item.shop_id", $shop_id)
                                                    ->whereBetween("record_log_date_updated",[$date_from, $date_to])
                                                    ->where("tbl_item.archived",0)
                                                    ->where("record_inventory_status",1)
                                                    ->where("record_consume_ref_name","customer_wis")
                                                    ->groupBy("record_log_id")
                                                    ->get();
        foreach ($items as $key => $value) 
        {
            $wis_info = Tbl_customer_wis::customerinfo()->where("cust_wis_id", $value->record_consume_ref_id)->first();
            if($wis_info)
            {
                $status = ucwords($wis_info->cust_wis_status)." Deliver";
                if($wis_info->cust_wis_status == "delivered")
                {
                    $status = ucwords($wis_info->cust_wis_status);
                }
                $_item[$key] = $items[$key];
                $_item[$key]->issued_to = $wis_info->company != "" ? $wis_info->company : $wis_info->first_name ." ".$wis_info->middle_name ." ".$wis_info->last_name;
                $_item[$key]->issued_date = date("F d, Y",strtotime($wis_info->cust_delivery_date));
                $_item[$key]->status = $status;
                $_item[$key]->number = AccountingTransaction::get_proposal_number($shop_id, "warehouse_issuance_slip", $value->record_consume_ref_id, $value->item_id);
            }
        }

        return $_item;
    }
    public static function get_equipment_items($shop_id, $warehouse_id, $date_from = '', $date_to = '')
    {

    }

    public static function get_bin_location_name($bin)
    {
        $get_info = Self::get_info($bin);
        $bin_name = '';
        $return = null;
        if($get_info)
        {
            $return[$get_info->warehouse_level] = $bin;
            $return = Self::get_bin_under_location_name($get_info, $return);
        }
        ksort($return);
        foreach ($return as $key => $value) 
        {
            $bininfo = Self::get_info($value);
            if($bininfo)
            {
                $bin_name .= $bininfo->warehouse_name ." - ";
            }
        }
        return $bin_name;
    }
    public static function get_bin_under_location_name($bin_info, $return = null)
    {
        if($bin_info)
        {
            $data = Tbl_warehouse::where("warehouse_id", $bin_info->warehouse_parent_id)->where("warehouse_level", "!=",0)->first();
            if($data)
            {
                $return[$data->warehouse_level] = $data->warehouse_id;
                $return = Self::get_bin_under_location_name($data, $return);
            }
        }
        return $return;
    }
    public static function get_item_status($shop_id, $warehouse_id, $date_from = '', $date_to = '')
    {
        $get_item = Tbl_item::where("shop_id", $shop_id)->whereIn("item_type_id",[1,4,5])->where("archived",0)->get();

        $return = null;
        foreach ($get_item as $key => $value) 
        {
            $return[$key] = $value;
            $return[$key]->on_stock = Self::get_item_qty($warehouse_id, $value->item_id, null,null,null, $date_from, $date_to);
            $return[$key]->ordered = Self::get_item_ordered($shop_id, $value->item_id, $warehouse_id, $date_from, $date_to);
            $return[$key]->sold = Self::get_item_sold($shop_id, $value->item_id, $warehouse_id, $date_from, $date_to);
            $return[$key]->incomplete = Self::get_item_incomplete($shop_id, $value->item_id, $warehouse_id, $date_from, $date_to);
            $return[$key]->damaged = Self::get_item_damaged($shop_id, $value->item_id, $warehouse_id, $date_from, $date_to);
            $return[$key]->total_onstock = $return[$key]->on_stock + $return[$key]->incomplete + $return[$key]->damaged;
            $repair_warehouse = Self::get_repair_warehouse($shop_id);
            $return[$key]->for_repair = Self::get_item_qty($repair_warehouse, $value->item_id, null,null,null, $date_from, $date_to);;
        }
        return $return;
    }
    public static function get_repair_warehouse($shop_id)
    {
        return Tbl_warehouse::where("warehouse_shop_id", $shop_id)->where("main_warehouse",4)->value("warehouse_id");
    }
    public static function get_item_ordered($shop_id, $item_id, $warehouse_id = null, $date_from = '', $date_to = '')
    {
        $item = Tbl_item::where("item_id", $item_id)->first();
        $data = Tbl_customer_estimate::estimate_item()->acctg_trans()
                                    ->where("transaction_warehouse_id", $warehouse_id)
                                    ->where("item_id", $item_id)
                                    ->where('est_status',"accepted")
                                    ->whereBetween("est_date",[$date_from, $date_to]);

        // $data = AccountingTransaction::acctg_trans($shop_id, $data);

        $get_data = $data->get();

        $return = "";
        $qty = 0;
        foreach ($get_data as $key => $value)
        {
            $qty += UnitMeasurement::get_umqty($value->estline_um) * $value->estline_orig_qty;
        }
        $return = $qty;
        return $return;
    }
    public static function get_item_sold($shop_id, $item_id, $warehouse_id, $date_from = '', $date_to = '')
    {
        $item = Tbl_item::where("item_id", $item_id)->first();
        $data = Tbl_customer_invoice::invoice_item()->acctg_trans()
                                    ->where("transaction_warehouse_id", $warehouse_id)
                                    ->where("item_id", $item_id)
                                    ->where('inv_is_paid',1)
                                    ->whereBetween("inv_date",[$date_from, $date_to]);

        $get_data = $data->get();

        $return = "";
        $qty = 0;
        foreach ($get_data as $key => $value)
        {
            $qty += UnitMeasurement::get_umqty($value->invline_um) * $value->invline_orig_qty;
        }
        $return = $qty;
        return $return;        
    }
    public static function get_item_incomplete($shop_id, $item_id, $warehouse_id, $date_from = '', $date_to = '')
    {
        $item = Tbl_item::where("item_id", $item_id)->first();
        $data = Tbl_purchase_order::purchase_item()->acctg_trans()
                                    ->where("transaction_warehouse_id", $warehouse_id)
                                    ->where("item_id", $item_id)
                                    ->where('po_is_billed',"0")
                                    ->whereBetween("po_date",[$date_from, $date_to]);

        $get_data = $data->get();

        $return = "";
        $qty = 0;
        foreach ($get_data as $key => $value)
        {
            $qty += UnitMeasurement::get_umqty($value->poline_um) * $value->poline_qty;
        }
        $return = $qty;
        return $return;        
    }
    public static function get_item_damaged($shop_id, $item_id, $warehouse_id, $date_from = '', $date_to = '')
    {
        $item = Tbl_item::where("item_id", $item_id)->first();
        $data = Tbl_credit_memo::cm_item()->acctg_trans()
                                    ->where("transaction_warehouse_id", $warehouse_id)
                                    ->where("item_id", $item_id)
                                    ->where('cm_status',"0")
                                    ->whereBetween("cm_date",[$date_from, $date_to]);

        $get_data = $data->get();

        $return = "";
        $qty = 0;
        foreach ($get_data as $key => $value)
        {
            $qty += UnitMeasurement::get_umqty($value->cmline_um) * $value->cmline_qty;
        }
        $return = $qty;
        return $return;    
    }

    public static function delete_inventory($shop_id)
    {
        Tbl_warehouse_inventory::item()->where("shop_id", $shop_id)->delete();
        Tbl_warehouse_inventory_record_log::where("record_shop_id", $shop_id)->delete();
        Tbl_inventory_history::where("shop_id", $shop_id)->delete();
        Tbl_inventory_slip::where("inventory_slip_shop_id", $shop_id)->delete();
        Tbl_monitoring_inventory::where("invty_transaction_name","!=","initial_qty")->where("invty_shop_id", $shop_id)->delete();
        /*EXTRA TO BE DELETED BUT NOT INCLUDED IN MODULE*/
        Tbl_mlm_item_points::joinItem()->where("shop_id", $shop_id)->delete();
    }
    public static function delete_initial_inventory($shop_id)
    {
        Tbl_monitoring_inventory::where("invty_shop_id", $shop_id)->delete();
    }
    public static function delete_warehouse($shop_id)
    {
        Tbl_user_warehouse_access::warehouse()->where("warehouse_shop_id", $shop_id)->delete();
        Tbl_warehouse::where("warehouse_shop_id", $shop_id)->delete();
    }
    public static function get_qty_warehouse($shop_id, $warehouse_id)
    {
        $get = Tbl_warehouse_inventory_record_log::where("record_shop_id", $shop_id)
                                                 ->where("record_warehouse_id", $warehouse_id)
                                                 ->where("record_inventory_status",0)
                                                 ->where("record_count_inventory",1)
                                                 ->count();

        $less = Tbl_warehouse_inventory_record_log::where("record_shop_id", $shop_id)
                                                 ->where("record_warehouse_id", $warehouse_id)
                                                 ->where("record_inventory_status",0)
                                                 ->where("record_count_inventory",0)
                                                 ->count();
        return $get - $less;
    }
}