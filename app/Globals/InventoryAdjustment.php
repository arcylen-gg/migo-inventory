<?php
namespace App\Globals;

use App\Models\Tbl_inventory_adjustment;
use App\Models\Tbl_inventory_adjustment_line;
use App\Models\Tbl_monitoring_inventory;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Globals\Warehouse2;
use Carbon\Carbon;
use DB;

/**
 * 
 *
 * @author Arcylen
 */

class InventoryAdjustment
{
	public static function info($shop_id, $adj_id)
	{
		return Tbl_inventory_adjustment::warehouse()->where('adj_shop_id', $shop_id)->where('inventory_adjustment_id', $adj_id)->first();
	}
	public static function check_dup_refnum($shop_id, $refnum, $adj_id = '')
	{
		$check = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->where('transaction_refnum', $refnum);		
		if($adj_id)
		{
			$check = $check->where("inventory_adjustment_id","!=",$adj_id);
		}
		$ret = null;
		if($check->first())
		{
			$ret = 'Reference Number already exist';
		}
		return $ret;
	}
	public static function get($shop_id, $paginate = null, $search_keyword = null)
	{
		$data = Tbl_inventory_adjustment::warehouse()->where('adj_shop_id', $shop_id)->orderBy("date_created","desc");
		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("warehouse_name", "LIKE", "%$search_keyword%");
            });
		}
		
		if($paginate)
		{
			$data = $data->paginate($paginate);
		}
		else
		{
			$data = $data->get();
		}

		return $data;
	}
	public static function info_item($adj_id)
	{
		$data = Tbl_inventory_adjustment_line::item()->um()->where('itemline_ia_id', $adj_id)->get();
		foreach($data as $key => $value) 
        {
            $new_qty = UnitMeasurement::um_qty($value->itemline_item_um);
            $actual_qty = UnitMeasurement::um_qty($value->itemline_item_um);
            $diff_qty = UnitMeasurement::um_qty($value->itemline_item_um);

            $total_actual_qty = $value->itemline_actual_qty * $new_qty;
            $total_new_qty = $value->itemline_new_qty * $actual_qty;
            $total_diff_qty = $value->itemline_diff_qty * $diff_qty;

            $data[$key]->actual_qty = UnitMeasurement::um_view($total_actual_qty, $value->item_measurement_id,$value->itemline_item_um);
            $data[$key]->new_qty = UnitMeasurement::um_view($total_new_qty, $value->item_measurement_id,$value->itemline_item_um);
            $data[$key]->diff_qty = UnitMeasurement::um_view($total_diff_qty, $value->item_measurement_id,$value->itemline_item_um);
        }

		return $data;
	}
	public static function postInsert($shop_id, $insert, $insert_item)
	{
		$total_amount = collect($insert_item)->sum('item_amount');
		$insert['adj_shop_id'] = $shop_id;
		$insert['created_at'] = Carbon::now();
		$insert['adjustment_amount'] = $total_amount;

		$adj_id = Tbl_inventory_adjustment::insertGetId($insert);

		Self::insertline($adj_id, $insert_item);

		$ref['name'] = 'adjust_inventory';
		$ref['id'] = $adj_id;
   
		// Warehouse2::adjust_inventory_bulk($shop_id, $insert['adj_warehouse_id'], $insert_item,'Adjust Inventory', $ref);
		foreach ($insert_item as $key => $value)
		{
			$qty = $value['item_diff_qty'];
			// $item[$key] = $value;
			$_item[0] = $value;
			if($qty > 0)
			{
				//AccountingTransaction::inventory_refill_update($shop_id, $insert['adj_warehouse_id'], $insert_item, 'adjust_inventory', $adj_id); 
				AccountingTransaction::refill_inventory($shop_id, $insert['adj_warehouse_id'], $_item, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
			}
			else
			{
				//AccountingTransaction::inventory_consume_update($shop_id, $insert['adj_warehouse_id'], 'adjust_inventory', $adj_id); 
				AccountingTransaction::consume_inventory($shop_id, $insert['adj_warehouse_id'], $_item, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
			}
		}
		Self::insert_acctg_transaction($shop_id, $adj_id);
		
		return $adj_id;
	}
	public static function insert_acctg_transaction($shop_id, $transaction_id)
	{
		$get_transaction = Tbl_inventory_adjustment::where("adj_shop_id", $shop_id)->where("inventory_adjustment_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "inventory_adjustment";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->date_created;

		 	$attached_transaction_data = null;
    	}

    	if($transaction_data)
		{
			AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
		}
	}

	public static function postUpdate($adj_id, $shop_id, $insert, $insert_item)
	{
		$total_amount = collect($insert_item)->sum('item_amount');
		$insert['adj_shop_id'] = $shop_id;
		$insert['adjustment_amount'] = $total_amount;

		Tbl_inventory_adjustment::where('inventory_adjustment_id', $adj_id)->update($insert);
		Tbl_inventory_adjustment_line::where('itemline_ia_id', $adj_id)->delete();

		Self::insertline($adj_id, $insert_item);
		/*foreach ($insert_item as $key => $value)
		{
			if($value['item_actual_qty'] > $value['item_new_qty'])
			{
				$ref['name'] = 'adjust_inventory_in';
			}
			else
			{
				$ref['name'] = 'adjust_inventory_out';
			}
		}*/
		$ref['name'] = 'adjust_inventory';
		$ref['id'] = $adj_id;
		//Warehouse2::adjust_inventory_update_bulk($shop_id, $insert['adj_warehouse_id'], $insert_item,'Adjust Inventory', $ref);
		/*DELETE RECORD WHERE NOT CONSUMED YET*/
        // $delete = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $insert['adj_warehouse_id'])
        //                                          ->where("record_source_ref_name", 'adjust_inventory')
        //                                          ->where("record_source_ref_id", $adj_id)
        //                                          ->whereNull("record_consume_ref_name")
        //                                          ->where("record_consume_ref_id", 0)
        //                                          ->delete(); 

	    $delete = Tbl_monitoring_inventory::where('invty_shop_id',$shop_id)
		                            ->where('invty_warehouse_id',$insert['adj_warehouse_id'])
		                            ->where('invty_transaction_name', 'adjust_inventory')
		                            ->where('invty_transaction_id', $adj_id)
		                            ->delete();
        $_refill_item = null;
        $_consume_item = null;
		foreach ($insert_item as $key => $value)
		{
			$qty = $value['item_diff_qty'];
			$update[0] = $value;
			if($qty > 0)
			{
				$_refill_item[$key] = $value;
				// Warehouse2::inventory_get_consume_data($shop_id, $insert['adj_warehouse_id'], $update, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
				// AccountingTransaction::inventory_refill_update($shop_id, $insert['adj_warehouse_id'], $insert_item, 'adjust_inventory', $adj_id); 
				// AccountingTransaction::refill_inventory($shop_id, $insert['adj_warehouse_id'], $update, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
			}
			else
			{
				$_consume_item[$key] = $value;
				// AccountingTransaction::inventory_consume_update($shop_id, $insert['adj_warehouse_id'], 'adjust_inventory', $adj_id); 
				// AccountingTransaction::consume_inventory($shop_id, $insert['adj_warehouse_id'], $update, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
			}
		}
		if(count($_consume_item) > 0)
		{
			AccountingTransaction::inventory_consume_update($shop_id, $insert['adj_warehouse_id'], 'adjust_inventory', $adj_id); 
			AccountingTransaction::consume_inventory($shop_id, $insert['adj_warehouse_id'], $_consume_item, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
		}
		if(count($_refill_item) > 0)
		{
            Warehouse2::inventory_get_consume_data($shop_id, $insert['adj_warehouse_id'], $_refill_item, 'adjust_inventory', $adj_id, 'Adjust Inventory '.$insert['transaction_refnum']);
		}

		Self::insert_acctg_transaction($shop_id, $adj_id);

		return $adj_id;

	}
	public static function insertline($inventory_adjustment_id, $insert_item, $entry = array())
	{
		$itemline = null;
		foreach ($insert_item as $key => $value) 
		{
			$itemline[$key]['itemline_ia_id'] 			 = $inventory_adjustment_id;
			$itemline[$key]['itemline_item_id'] 		 = $value['item_id'];
			$itemline[$key]['itemline_item_description'] = $value['item_description'];
            $itemline[$key]['itemline_sub_wh_id'] 	 	 = $value['item_sub_warehouse'];
			$itemline[$key]['itemline_item_um'] 		 = $value['item_um'];
			$itemline[$key]['itemline_actual_qty'] 		 = $value['item_actual_qty'];
			$itemline[$key]['itemline_new_qty'] 		 = $value['item_new_qty'];
			$itemline[$key]['itemline_diff_qty'] 		 = $value['item_diff_qty'];
			$itemline[$key]['itemline_rate'] 			 = $value['item_rate'];
			$itemline[$key]['itemline_amount'] 			 = $value['item_amount'];
		}

		if(count($itemline) > 0)
		{
			Tbl_inventory_adjustment_line::insert($itemline);
		}

		return $inventory_adjustment_id;
	}
}