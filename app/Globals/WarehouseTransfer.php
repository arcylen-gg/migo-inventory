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
use App\Models\Tbl_price_level;
use App\Models\Tbl_price_level_item;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_settings;
use App\Models\Tbl_truck;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_issuance_report_item;
use App\Models\Tbl_warehouse_issuance_report_itemline;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_warehouse_receiving_report_item;
use App\Models\Tbl_warehouse_receiving_report_itemline;
use App\Models\Tbl_admin_notification;
use App\Models\Tbl_acctg_transaction_list;
use App\Models\Tbl_acctg_transaction;

use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\AdminNotification;
use App\Globals\Warehouse2;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Tablet_global;
use App\Globals\Currency;
use Session;
use DB;
use Carbon\Carbon;
use App\Globals\Merchant;
use Validator;
class WarehouseTransfer
{   
	public static function insert_notification()
	{
        $_shop = DB::table("tbl_settings")->where("settings_key","notification_bar")->where("settings_value",1)->get();

        foreach ($_shop as $keyshop => $valueshop) 
        {
            $check = AccountingTransaction::settings($valueshop->shop_id, "notification_bar");
            if($check)
            {
				$datenow = date('Y-m-d',strtotime(Carbon::now()->subDays(1)));
				$date_three = date('Y-m-d',strtotime(Carbon::now()->addDays(3)));
				$get = Tbl_warehouse_issuance_report::destinationWarehouse()
													->where("wis_shop_id", $valueshop->shop_id)
		                                			->where("wis_delivery_date","<=",$date_three)
													->where("wis_status","confirm")
													->get();
				$insert = null;
				foreach ($get as $key => $value) 
				{
					$check = Tbl_admin_notification::where("transaction_refname",'warehouse_transfer')
												   ->where("transaction_refid", $value->wis_id)
												   ->where("notification_shop_id", $valueshop->shop_id)
												   ->first();
					if(!$check)
					{
						$insert[$key]['notification_shop_id'] = $valueshop->shop_id;
						$insert[$key]['warehouse_id'] = $value->destination_warehouse_id;
						$insert[$key]['notification_description'] = 'The <strong>"'.$value->warehouse_name.'"</strong> is about to receive <strong>Warehouse Transfer No. "'.$value->wis_number.'"</strong> on '.date("F d, Y",strtotime($value->wis_delivery_date));
						$insert[$key]['transaction_refname'] = "warehouse_transfer";
						$insert[$key]['transaction_refid'] = $value->wis_id;
						$insert[$key]['transaction_status'] = "pending";
						$insert[$key]['transaction_date'] = $value->wis_delivery_date;
						$insert[$key]['created_date'] = Carbon::now();
						$id = Tbl_admin_notification::insertGetId($insert[$key]);
					}
				}
			}

		}
	}
	public static function get_all_wis($shop_id = 0, $status = 'pending', $current_warehouse = 0, $from = '', $to = '')
	{
		$data = Tbl_warehouse_issuance_report::truck()->selectRaw("*, sum(wt_amount) as wis_amount_total")->itemline()->destinationWarehouse()->where('wis_shop_id',$shop_id)->groupBy('tbl_warehouse_issuance_report.wis_id')->where('tbl_warehouse_issuance_report.wis_from_warehouse', $current_warehouse)->orderBy('tbl_warehouse_issuance_report.created_at', 'DESC');
		if($status != 'all')
		{
			$data = $data->where('wis_status', $status);
		}
		if($from && $to)
        {
            $data = $data->whereBetween('wis_delivery_date', [$from, $to]);
        }
        $data = $data->get();	
		foreach ($data as $key => $value) 
		{
			$count = Tbl_warehouse_receiving_report::wis()->inventory_item()->where('tbl_warehouse_receiving_report.wis_id',$value->wis_id)->count();

			$data[$key]->total_received_qty = $count;
			$data[$key]->issued_qty = 0;

			$data[$key]->issued_created_by = Self::issued_created_by($shop_id, $value->wis_id);
		}

		return $data;
	}
	public static function issued_created_by($shop_id, $wt_id)
	{
		$get_info = Tbl_acctg_transaction::list()->user()
										 ->where("shop_id", $shop_id)
										 ->where("transaction_ref_name","warehouse_transfer")
										 ->where("transaction_ref_id",$wt_id)->first();
        $name = "not found";
        if($get_info)
        {
        	$name = $get_info->user_first_name." ".$get_info->user_last_name;
        }
        return $name;
	}
	public static function get_all_rr($shop_id = 0)
	{
		return Tbl_warehouse_receiving_report::wis()->where('rr_shop_id',$shop_id)->groupBy('tbl_warehouse_receiving_report.rr_id')->orderBy('tbl_warehouse_receiving_report.rr_date_received','DESC')->get();
	}
	public static function scan_item($shop_id, $item_code)
	{
		$chk = Tbl_item::where('item_id',$item_code)->where('item_type_id',1)->where('shop_id',$shop_id)->first();
		$data['item_id'] = $item_code;
		if(!$chk)
		{
			$data = null;
			/* SEARCH FOR OTHER ITEM NUMBER HERE*/

			/* - WAREHOUSE SERIAL - */
			$a = Tbl_warehouse_inventory_record_log::where('record_warehouse_id',Warehouse2::get_current_warehouse($shop_id))->where('record_shop_id', $shop_id)->where('record_serial_number',$item_code)->value('record_item_id');
			if($a)
			{
				$data['item_id'] = $a;
				$data['item_serial'] = $item_code;
			}
		}

		return $data;
	}
	public static function add_item_to_list($shop_id, $item_id, $quantity = 1, $serial = '', $is_change_qty = 0)
	{
		$first_data = Session::get('wis_item'); 

		$data = Session::get('wis_item');

		$data[$item_id]['item_id'] = $item_id;
		$data[$item_id]['item_name'] = Item::info($item_id)->item_name;
		$data[$item_id]['item_sku'] = Item::info($item_id)->item_sku;
		$data[$item_id]['item_quantity'] = $quantity;
		$data[$item_id]['item_serial'] = null;
		if($serial != '')
		{
			$data[$item_id]['item_serial'][0] = $serial;
		}

		$check = Session::get('wis_item');
		if(count($check) > 0)
		{
			if(isset($first_data[$item_id]))
			{
				$data[$item_id]['item_id'] = $item_id;
				$data[$item_id]['item_name'] = Item::info($item_id)->item_name;
				$data[$item_id]['item_sku'] = Item::info($item_id)->item_sku;
				$data[$item_id]['item_quantity'] = $first_data[$item_id]['item_quantity'] + $quantity;
				if($is_change_qty == 1)
				{
					$data[$item_id]['item_quantity'] = $quantity;
				}

				if(count($first_data[$item_id]['item_serial']) > 0)
				{
					foreach ($first_data[$item_id]['item_serial'] as $key => $value) 
					{
						if($serial != '')
						{
							if($value != $serial)
							{
								$data[$item_id]['item_serial'][$key] = $value;
							}						
						}
					}

					$data[$item_id]['item_serial'][count($first_data[$item_id]['item_serial']) + 1] = $serial;
				}


				unset(Session::get('wis_item')[$item_id]);
			}
		}
		Session::put('wis_item', $data);
	}
	public static function delete_item_from_list($item_id)
	{
		$data = Session::get('wis_item');
		unset($data[$item_id]);

		Session::put('wis_item', $data);
	}
	public static function update_wis($shop_id, $wis_id, $up)
	{
		return Tbl_warehouse_issuance_report::where('wis_shop_id',$shop_id)->where('wis_id',$wis_id)->update($up);
	}
	public static function update_data_wis($wis_id, $shop_id, $remarks, $ins, $_item)
	{
		$validate = null;
        $warehouse_id = $ins['wis_from_warehouse'];
        // dd($_item);
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : null;
            $validate .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial, null, $value['item_sub_warehouse'], "wis", $wis_id);
        }

        $check = Tbl_warehouse_issuance_report::where('wis_number',$ins['wis_number'])->where("wis_id","!=", $wis_id)->where('wis_shop_id',$shop_id)->first();
        if($check)
        {
        	$validate .= 'WIS number already exist';
        }

        if(!$validate)
        {
        	//EDIT WAREHOUSE TRANSFER
        	$ins['wis_total_amount'] = collect($_item)->sum('item_amount');
        	Tbl_warehouse_issuance_report::where("wis_id", $wis_id)->update($ins);
        	$reference_name = 'wis';
        	$validate = $wis_id;
            Warehouse2::update_inventory_consume($shop_id, null, $reference_name, $wis_id); 
        	Tbl_warehouse_issuance_report_itemline::where("wt_wis_id", $wis_id)->delete();
        	Tbl_warehouse_issuance_report_item::where("wis_id", $wis_id)->delete();
        	$val = Self::wis_insertline($wis_id, $_item);

            if(is_numeric($val))
            {
	        	$return = Warehouse2::consume_bulk($shop_id, $warehouse_id, $reference_name, $wis_id ,$remarks ,$_item);

	        	if(!$return)
	        	{
	        		$check = AccountingTransaction::settings($shop_id, "optimize_wiswt");
                    if(!$check)
                    {
		        		$get_item = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','wis')->where('record_consume_ref_id',$wis_id)->get();

		        		$ins_report_item = null;
		        		foreach ($get_item as $key_item => $value_item)
		        		{
		        			$ins_report_item[$key_item]['wis_id'] = $wis_id;
		        			$ins_report_item[$key_item]['record_log_item_id'] = $value_item->record_log_id;
		        		}

		        		if($ins_report_item)
		        		{
		        			Tbl_warehouse_issuance_report_item::insert($ins_report_item);
		        		}
		        	}
	        	}
	        }
        }

        return $validate;

	}
	public static function create_wis($shop_id, $remarks, $ins, $_item)
	{
        $val = null;
        $warehouse_id = $ins['wis_from_warehouse'];
        // dd($_item);
        foreach ($_item as $key => $value)
        {
            $serial = isset($value['serial']) ? $value['serial'] : null;
            $val .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial,'wis', $value['item_sub_warehouse']);
        }

        $check = Tbl_warehouse_issuance_report::where('wis_number',$ins['wis_number'])->where('wis_shop_id',$shop_id)->first();
        if($check)
        {
        	$val .= 'WIS number already exist';
        }

        if(!$val)
        {
        	$ins['wis_total_amount'] = collect($_item)->sum('item_amount');
        	$wis_id = Tbl_warehouse_issuance_report::insertGetId($ins);
        	$reference_name = 'wis';
        	$val = Self::wis_insertline($wis_id, $_item);
            if(is_numeric($val))
            {
	        	$return = Warehouse2::consume_bulk($shop_id, $warehouse_id, $reference_name, $wis_id ,$remarks ,$_item);

	        	if(!$return)
	        	{
                    $check = AccountingTransaction::settings($shop_id, "optimize_wiswt");
                    if(!$check)
                    {
		        		$get_item = Tbl_warehouse_inventory_record_log::where('record_consume_ref_name','wis')->where('record_consume_ref_id',$wis_id)->get();

		        		$ins_report_item = null;
		        		foreach ($get_item as $key_item => $value_item)
		        		{
		        			$ins_report_item[$key_item]['wis_id'] = $wis_id;
		        			$ins_report_item[$key_item]['record_log_item_id'] = $value_item->record_log_id;
		        		}

		        		if($ins_report_item)
		        		{
		        			Tbl_warehouse_issuance_report_item::insert($ins_report_item);
		        		}
		        	}
	        	}
	        }
	        $val = $wis_id;
        }
        return $val;
	}

    public static function wis_insertline($wis_id, $insert_item)
    {
        $return = null;
        $itemline = null;
        foreach ($insert_item as $key => $value) 
        {
            $itemline[$key]['wt_wis_id']      	= $wis_id;
            $itemline[$key]['wt_item_id']     	= $value['item_id'];
            $itemline[$key]['wt_description'] 	= $value['item_description'];
            $itemline[$key]['wt_sub_wh_id']		= $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;;
            $itemline[$key]['wt_qty']         	= $value['item_qty'];
            $itemline[$key]['wt_orig_qty']      = $value['item_qty'];
            $itemline[$key]['wt_um']           	= $value['item_um'];
            $itemline[$key]['wt_rate']          = $value['item_rate'];
            $itemline[$key]['wt_amount']     	= $value['item_amount'];
            $itemline[$key]['wt_refname']     	= $value['item_refname'];
            $itemline[$key]['wt_refid']       	= $value['item_refid'];
        }
        if(count($itemline) > 0)
        {
            Tbl_warehouse_issuance_report_itemline::insert($itemline);
            $return = 1;
        }

        return $return;
    }
    public static function get_wtline($wt_id)
    {
    	return Tbl_warehouse_issuance_report_itemline::where('wt_wis_id', $wt_id)->get();
    }
    public static function rr_insertline($rr_id, $insert_item)
    {
        $return = null;
        $itemline = null;
        foreach ($insert_item as $key => $value) 
        {
            $itemline[$key]['rr_id']      		= $rr_id;
            $itemline[$key]['rr_item_id']     	= $value['item_id'];
            $itemline[$key]['rr_description'] 	= $value['item_description'];
            $itemline[$key]['rr_qty']         	= $value['item_qty'];
            $itemline[$key]['rr_orig_qty']      = $value['item_qty'];
            $itemline[$key]['rr_um']           	= $value['item_um'];
            $itemline[$key]['rr_rate']          = $value['item_rate'];
            $itemline[$key]['rr_amount']     	= $value['item_amount'];
            $itemline[$key]['rr_refname']     	= $value['item_refname'];
            $itemline[$key]['rr_refid']       	= $value['item_refid'];
            $itemline[$key]['rr_sub_wh_id']     = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
        }
        if(count($itemline) > 0)
        {
            Tbl_warehouse_receiving_report_itemline::insert($itemline);
            $return = 1;
        }

        return $return;
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
	public static function get_wis_data($wis_id)
	{
		return Tbl_warehouse_issuance_report::destinationWarehouse()->where('wis_shop_id',WarehouseTransfer::getShopId())->where('wis_id',$wis_id)->first();
	}
	public static function getTruck($truck_id)
    {
        return Tbl_truck::where("truck_id", $truck_id)->first();
    }
	public static function get_wis_item($wis_id)
	{
        $return_item = Tbl_warehouse_inventory_record_log::item()->inventory()->where('record_consume_ref_name','wis')->where('record_consume_ref_id',$wis_id)->groupBy('record_item_id')->get();

		return $return_item;
	}
	public static function get_wis_itemline($wis_id)
	{
		$data = Tbl_warehouse_issuance_report_itemline::item()->um()->binLocation()->where("wt_wis_id", $wis_id)->get();
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->wt_um);

            $total_qty = $value->wt_orig_qty * $qty;
            $data[$key]->int_qty = $total_qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->wt_um);

            $data[$key]->bin = '';
            if($value->wt_sub_wh_id)
            {
                $data[$key]->bin = Warehouse2::get_bin_location_name($value->wt_sub_wh_id);
            }
        }
        return $data;
	}

	public static function get_rr_itemline($rr_id)
	{
		$data = Tbl_warehouse_receiving_report_itemline::item()->um()->where("rr_id", $rr_id)->get();
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->rr_um);

            $total_qty = $value->rr_orig_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->rr_um);
            $data[$key]->rr_amount2 = $total_qty * $value->rr_rate; 
        }
        return $data;
	}
	public static function get_rr_data($rr_id)
	{
		return Tbl_warehouse_receiving_report::where('rr_id',$rr_id)->first();
	}
	public static function get_rr_item($wis_id)
	{
        $return_item = Tbl_warehouse_inventory_record_log::item()->inventory()->where('record_source_ref_name','wis')->where('record_source_ref_id',$wis_id)->groupBy('record_item_id')->get();

		return $return_item;
	}
	public static function print_wis_item($wis_id)
	{
		return Tbl_warehouse_issuance_report_item::inventory_item()->where('wis_id',$wis_id)->groupBy('record_item_id')->get();
	}
	public static function print_rr_item($rr_id)
	{
		return Tbl_warehouse_receiving_report_item::inventory_item()->where('rr_id',$rr_id)->groupBy('record_item_id')->get();
	}

	public static function get_warehouse_data($warehouse_id)
	{
        return Tbl_warehouse::shop()->where('warehouse_id',$warehouse_id)->first();
	}
	public static function get_code($shop_id)
	{
        $code = strtoupper(str_random(6));

        $ctr = Tbl_warehouse_issuance_report::where("wis_shop_id",$shop_id)->where('receiver_code',$code)->count();
        if($ctr > 0)
        {
            $code = Self::check_code($shop_id, strtoupper(str_random(6)));
        }

        return $code;
	}

    public static function check_code($shop_id, $code = '')
    {
        $ctr = Tbl_warehouse_issuance_report::where("wis_shop_id",$shop_id)->where('receiver_code',$code)->count();
        if($ctr > 0)
        {
            $code = Self::check_code($shop_id, strtoupper(str_random(6)));
        }

        return $code;
    }
    public static function check_wis($shop_id, $warehouse_id, $receiver_code)
    {
    	$check = Tbl_warehouse_issuance_report::where('wis_shop_id',$shop_id)->where('wis_status', 'confirm')->where('wis_from_warehouse', $warehouse_id)->where('receiver_code',$receiver_code)->first();

    	$return = null;
    	if($check)
    	{
    		$return = $check->wis_id;
    	}
    	else
    	{
    		$return = 'Code not found.';
    	}
    	return $return;
    }
    public static function create_rr($shop_id, $wis_id, $ins_rr, $_item = array(), $user_id = 0)
    {
    	//Self::create_wis($shop_id, $remarks, $ins, $_item)
    	$return = null;

        $wis_data = WarehouseTransfer::get_wis_data($wis_id);

        if($wis_data->destination_warehouse_id)
        {
	        if($wis_data->destination_warehouse_id != $ins_rr['warehouse_id'])
	        {
	        	$warehouse_name = Warehouse2::check_warehouse_existence($shop_id, $ins_rr['warehouse_id'])->warehouse_name;
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
   //  		$warehouse_id = Warehouse2::get_current_warehouse($shop_id);
   //  		$get_item = Item::get_item_info($shop_id, $value['item_id']);
   //  		$get_old = $get_item->item_warehouse_id;

   //  		$count_wh = 0;
   //  		if($get_old)
   //  		{
   //  			/*IF ITEM WAREHOUSE ID IS NOT NULL*/
   //  			$arr = unserialize($get_old);
   //  			/*IF WAREHOUSE ID IS ALREADY EXISTED IN ITEM WAREHOUSE ID*/
   //  			if(in_array($warehouse_id, $arr, TRUE))
			// 	{
			// 	  	//dd('123');
			// 	}
			// 	else
			// 	{
	  //   			$count_wh = count($arr);
			// 		$arr[$count_wh+1] = $warehouse_id;
			// 	  	//dd('456');
			// 	}
			// }
			// else
			// {
			// 	/*IF ITEM WAREHOUSE ID IS NULL*/
			// 	$arr[0] = $warehouse_id;
			// }
			// $update_warehouse['item_warehouse_id']  = count($warehouse_id) > 0 ? serialize($arr) : null;
			// Tbl_item::where("shop_id", $shop_id)->where("item_id", $value['item_id'])->update($update_warehouse);

        	$rr_id = Tbl_warehouse_receiving_report::insertGetId($ins_rr);

	        $source['name'] = 'wis';
	        $source['id'] = $wis_id;	

	        $to['name'] = 'rr';
	        $to['id'] = $rr_id;	
	        
	        $val = Self::rr_insertline($rr_id, $_item);
            if(is_numeric($val))
            {
	        	$val = Warehouse2::transfer_bulk($shop_id, $wis_data->wis_from_warehouse, $ins_rr['warehouse_id'], $_item, $ins_rr['rr_remarks'], $source, $to);

	        	if(!$val)
	        	{
	        		$check = AccountingTransaction::settings($shop_id, "optimize_wiswt");
                    if(!$check)
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
		        		}
		        	}

    				if($wis_data->wis_status == 'confirm')
    				{
	    				$udpate_wis['wis_status'] = 'received';
	    				WarehouseTransfer::update_wis($shop_id, $wis_id, $udpate_wis);	
	    				AdminNotification::update_notification($shop_id,'warehouse_transfer', $wis_id, $user_id);
    				}
	        		$val = $rr_id;
	        	}
	        }

        	return $val;
    	}
    	else
    	{
    		return $return;
    	}
    }
    public static function applied_transaction_wt($shop_id, $transaction_id = 0)
    {
        $applied_transaction = Session::get('applied_transaction_wt');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                // $update['item_delivered'] = 1;
                // Tbl_customer_invoice::where("inv_id", $key)->where('inv_shop_id', $shop_id)->update($update);
            }
        }
        Self::insert_acctg_transaction_wt($shop_id, $transaction_id, $applied_transaction);
    }

    public static function insert_acctg_transaction_wt($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_warehouse_issuance_report::where("wis_shop_id", $shop_id)->where("wis_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "warehouse_transfer";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->wis_number;
            $transaction_data['transaction_date'] = $get_transaction->wis_delivery_date;

            $attached_transaction_data = null;
            // if(count($applied_transaction) > 0)
            // {
            //     foreach ($applied_transaction as $key => $value) 
            //     {
            //         $get_data = Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $key)->first();
            //         if($get_data)
            //         {
            //             $attached_transaction_data[$key]['transaction_ref_name'] = "sales_invoice";
            //             if($get_data->is_sales_receipt == 1)
            //             {
            //                 $attached_transaction_data[$key]['transaction_ref_name'] = "sales_receipt";
            //             }
            //             $attached_transaction_data[$key]['transaction_ref_id'] = $key;
            //             $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
            //             $attached_transaction_data[$key]['transaction_date'] = $get_data->inv_date;
            //         }
            //     }
            // }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }

    public static function applied_transaction_rr($shop_id, $transaction_id = 0)
    {
        $applied_transaction = Session::get('applied_transaction_rr');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                // $update['item_delivered'] = 1;
                // Tbl_customer_invoice::where("inv_id", $key)->where('inv_shop_id', $shop_id)->update($update);
            }
        }
        Self::insert_acctg_transaction_rr($shop_id, $transaction_id, $applied_transaction);
    }

    public static function insert_acctg_transaction_rr($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_warehouse_receiving_report::where("rr_shop_id", $shop_id)->where("rr_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "receiving_report";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->rr_number;
            $transaction_data['transaction_date'] = $get_transaction->rr_date_received;

            $attached_transaction_data = null;
            // if(count($applied_transaction) > 0)
            // {
            //     foreach ($applied_transaction as $key => $value) 
            //     {
            //         $get_data = Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $key)->first();
            //         if($get_data)
            //         {
            //             $attached_transaction_data[$key]['transaction_ref_name'] = "sales_invoice";
            //             if($get_data->is_sales_receipt == 1)
            //             {
            //                 $attached_transaction_data[$key]['transaction_ref_name'] = "sales_receipt";
            //             }
            //             $attached_transaction_data[$key]['transaction_ref_id'] = $key;
            //             $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
            //             $attached_transaction_data[$key]['transaction_date'] = $get_data->inv_date;
            //         }
            //     }
            // }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }
    public static function get_item_per_wt($shop_id, $wis_id)
    {
    	$_item = Tbl_warehouse_issuance_report::itemline()->itemdetails()->where("wis_shop_id", $shop_id)->where("wis_id", $wis_id)->get();

    	$return = null;
    	foreach ($_item as $key => $value) 
    	{
    		$return[$key]['item_name'] = $value->item_name;
    		$return[$key]['item_sku'] = $value->item_sku;
    		$return[$key]['item_orig_qty'] = $value->wt_orig_qty;
    		$return[$key]['item_um'] = $value->multi_abbrev;

    		$return[$key]['item_receive_qty'] = 0;

	    	$_get = Tbl_warehouse_receiving_report::itemline()->where("rr_shop_id", $shop_id)->where("rr_item_id",$value->wt_item_id)->where("wis_id", $wis_id)->get();
	    	foreach ($_get as $keyrr => $valuerr) 
	    	{
	    		$qty = $valuerr->rr_qty;
	    		if($valuerr->rr_um)
	    		{
					$qty = $valuerr->rr_qty * UnitMeasurement::get_umqty($valuerr->rr_um);
	    		}
    			$return[$key]['item_receive_qty'] += $qty;
	    	}
    		$return[$key]['item_rem_qty'] = $return[$key]['item_orig_qty'] - $return[$key]['item_receive_qty'];
    	}

    	return $return;
    }
    public static function get_transaction_description($wis_id)
    {
    	$info = Tbl_warehouse_issuance_report::acctg_trans()->where("wis_id", $wis_id)->first();
    	$return = "Not Found";
    	if($info)
    	{
    		$from_warehouse = Tbl_warehouse::where("warehouse_id",$info->transaction_warehouse_id)->value("warehouse_name");
    		$to_warehouse = Tbl_warehouse::where("warehouse_id",$info->destination_warehouse_id)->value("warehouse_name");

    		$return = "From ".$from_warehouse." to ".$to_warehouse;
    	}
    	return $return;
    }
}