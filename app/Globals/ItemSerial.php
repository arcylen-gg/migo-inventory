<?php
namespace App\Globals;

use App\Models\Tbl_variant;
use App\Models\Tbl_user;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_category;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_settings;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_warehouse_inventory;

use Carbon\Carbon;
use App\Globals\Item;
use Session;

/* Author - Arcylen Gutierrez */
class ItemSerial
{
	public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public static function getItemSerial($item_id)
    {
    	$return = Tbl_inventory_serial_number::item()->where("tbl_item.item_id",$item_id)->where("tbl_inventory_serial_number.archived",0)->get();
    	return $return;
    }
    public static function check_setting()
    {
    	return Tbl_settings::where("settings_key","item_serial")->where("settings_value",'enable')->where("shop_id",ItemSerial::getShopId())->first();
    }
 	public static function check_item_serial($item_serial)
    {
    	$return = null;
    	$serials = explode(",", $item_serial["serials"]);

    	foreach ($serials as $key => $value) 
    	{
    		if($value == "" || $value == null)
    		{
    			unset($serials[$key]);
    		}
    	}

    	if(count($serials) > $item_serial["quantity"])
		{
			$return = "serial_are_more_than_quantity";
		}
		return $return;
    }

    /* REFILLING SERIAL NUMBER */
    public static function insert_item_serial($item_serial = array(), $inventory_id = 0)
    {
    	if($inventory_id)
    	{
    		$serials = explode(",", $item_serial["serials"]);
    		if(count($serials) <= $item_serial["quantity"])
    		{
	    		foreach ($serials as $key => $value) 
	    		{
	    			if($value)
	    			{
	    				$ins["serial_inventory_id"] = $inventory_id;
	    				$ins["item_id"] = $item_serial["item_id"];
	    				$ins["serial_number"] = trim($value);
	    				$ins["serial_created"] = Carbon::now();
	    				$ins["item_count"] = $key + 1;

	    				Tbl_inventory_serial_number::insert($ins);
	    			}
	    		}    			
    		}
    	}
    }
    public static function check_duplicate_serial($item_id = 0 ,$serial_number = "")
    {
    	$serials = explode(",", $serial_number);
    	$return = "";
    	foreach ($serials as $key => $value) 
    	{
    		if($value)
    		{
    			$chk = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id","!=",$item_id)->where("tbl_inventory_serial_number.archived",0)->where("serial_number",trim($value))->first();
    			if($chk)
    			{
    				$return .= "The serial number ".$value." was duplicate to the serial number of item ".Item::get_item_details($chk->serial_item_id)->item_name ."<br>";
    			}
    		}
    	}
    	return $return;
    }
    public static function get_serial($soure_reason = "", $soure_id = 0, $item_id = 0)
    {
    	$slip = Tbl_inventory_slip::where("inventroy_source_reason",$soure_reason)->where("inventory_source_id",$soure_id)->first();

    	$return = "";
    	if($slip)
    	{
    		$w_inventory = Tbl_warehouse_inventory::where("inventory_slip_id",$slip->inventory_slip_id)->where("inventory_item_id",$item_id)->first();
    		if($w_inventory)
    		{
    			$serials = Tbl_inventory_serial_number::where("serial_inventory_id",$w_inventory->inventory_id)->where("item_id",$item_id)->where("tbl_inventory_serial_number.archived",0)->get();

    			foreach ($serials as $key => $value) 
    			{
    				$return .= $value->serial_number .",";
    			}
    		}
    	}

    	return $return;
    }
    /* END REFILLING SERIAL NUMBER */

   
    /* CONSUMING SERIAL NUMBER */
    public static function get_consume_serial($source_reason = "", $source_id = 0, $item_id = 0)
    {    	
		$serials = Tbl_inventory_serial_number::where("consume_source",$source_reason)->where("consume_source_id",$source_id)->where("item_id",$item_id)->get();
		$return = "";
		foreach ($serials as $key => $value) 
		{
			$return .= $value->serial_number .",";
		}
    	
    	return $return;
    }
    public static function check_existing($item_serial = array() ,$transaction_type = "", $transaction_id = "")
    {
    	$return = "";
    	$serials = explode(",", $item_serial["serials"]);
    	foreach ($serials as $key => $value) 
    	{
    		if($value)
    		{
    			$check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();
    			if($check == null)
    			{
    				$return .= "The serial number ".$value." does not exist in inventory <br>";
    			}
    			else if($check->item_consumed == 1 && $check->sold == 1 && $check->consume_source != $transaction_type && $check->consume_source_id != $transaction_id )
    			{
	    			$check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();

    				$return .= "The serial number ".$value." was already consume <br>";
    			}
    		}
    	}
    	return $return;
    }
  
    public static function consume_item_serial($item_serial = array(), $transaction_type = '', $transaction_id = 0)
    {
    	$up["sold"] = 1;
    	$up["item_consumed"] = 1;
    	$up["consume_source"] = $transaction_type;
    	$up["consume_source_id"] = $transaction_id;

    	$serials = explode(",", $item_serial["serials"]);
    	foreach ($serials as $key => $value) 
    	{
    		if($value)
    		{
		    	$check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();
		    	if($check)
		    	{
		    		Tbl_inventory_serial_number::where("item_id",$item_serial["item_id"])->where("serial_number",trim($value))->update($up);
		    	}    			
    		}
	    }
    }
    public static function return_original_serial($transaction_type = "", $transaction_id = 0)
    {
    	if($transaction_type && $transaction_id)
    	{
	    	$up["sold"] = 0;
	    	$up["item_consumed"] = 0;
	    	$up["consume_source"] = "";
	    	$up["consume_source_id"] = "";

	    	Tbl_inventory_serial_number::where("consume_source",$transaction_type)->where("consume_source_id",$transaction_id)->update($up);
    	}
    }
    /* END CONSUMING SERIAL NUMBER */    


    /* CREDIT SERIAL NUMBER*/
    public static function check_existing_to_credit($item_serial = array() ,$transaction = "")
    {
        $return = "";
        $serials = explode(",", $item_serial["serials"]);
        foreach ($serials as $key => $value) 
        {
            if($value)
            {
                $check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();
                if($check == null)
                {
                    $return .= "The serial number ".$value." does not exist in inventory <br>";
                }
                else if($check->item_consumed == 0 && $check->sold == 0 && $check->consume_source == null && $check->consume_source_id == null && $check->serial_has_been_credit != $transaction)
                {
                    $check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();

                    $return .= "The serial number ".$value." was not consume to credit <br>";
                }
            }
        }
        return $return;
    }
    public static function update_refill_to_credit($item_serial, $transaction = "")
    {
        foreach ($item_serial as $key => $value) 
        {
            $serials = explode(",", $value["serials"]);

            foreach ($serials as $keys => $values)
            {
                $up["serial_has_been_credit"] = $transaction;
                $check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$value["item_id"])->where("serial_number",trim($values))->first();
                if($check)
                {
                    Tbl_inventory_serial_number::where("item_id",$value["item_id"])->where("serial_number",trim($values))->update($up);
                }    
            }
        }
    }
    public static function get_serial_credited($item_id, $transaction = "")
    {
        $serials = Tbl_inventory_serial_number::where("serial_has_been_credit",$transaction)->where("item_id",$item_id)->get();
        $return = "";
        foreach ($serials as $key => $value) 
        {
            $return .= $value->serial_number .",";
        }
        
        return $return;
    }
    /* END CREDIT SERIAL NUMBER*/

    /* DEBIT SERIAL NUMBER */
    public static function get_consume_debited($item_id, $transaction = "")
    {       
        $serials = Tbl_inventory_serial_number::where("serial_has_been_debit",$transaction)->where("item_id",$item_id)->get();
        $return = "";
        foreach ($serials as $key => $value) 
        {
            $return .= $value->serial_number .",";
        }
        
        return $return;
    }
    public static function check_existing_to_debit($item_serial = array() ,$transaction = "")
    {
        $return = "";
        $serials = explode(",", $item_serial["serials"]);
        foreach ($serials as $key => $value) 
        {
            if($value)
            {
                $check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$item_serial["item_id"])->where("serial_number",trim($value))->first();
                if($check == null)
                {
                    $return .= "The serial number ".$value." does not exist in inventory <br>";
                }
            }
        }
        return $return;
    }
    public static function update_consume_to_debit($item_serial, $transaction = "")
    {
        foreach ($item_serial as $key => $value) 
        {
            $serials = explode(",", $value["serials"]);

            foreach ($serials as $keys => $values)
            {
                $up["serial_has_been_debit"] = $transaction;
                $check = Tbl_inventory_serial_number::item()->where("shop_id",ItemSerial::getShopId())->where("tbl_item.item_id",$value["item_id"])->where("serial_number",trim($values))->first();
                if($check)
                {
                    Tbl_inventory_serial_number::where("item_id",$value["item_id"])->where("serial_number",trim($values))->update($up);
                }    
            }
        }
    }
    /* END DEBIT SERIAL*/


    public static function return_original_serial_debit_credit($transaction = "")
    {
        if($transaction)
        {
            $tr = explode("-", $transaction);

            if($tr[0] == "credit_memo")
            {
                $up["serial_has_been_credit"] = "";
                Tbl_inventory_serial_number::where("serial_has_been_credit",$transaction)->update($up);
            }
            else
            {
                $up["serial_has_been_debit"] = "";
                Tbl_inventory_serial_number::where("serial_has_been_debit",$transaction)->update($up);
            }

        }
    }

    public static function archived_item_serial($item_id = 0)
    {
        if($item_id != 0)
        {
            $update["archived"] = 1;

            Tbl_inventory_serial_number::where("item_id",$item_id)
                                        ->where("item_consumed",0)
                                        ->where("sold",0)
                                        ->where("consume_source_id",0)
                                        ->update($update);
        }
    }
    public static function archived_serial()
    {
        $all_item_archived = Tbl_item::where("archived",1)->get();

        foreach ($all_item_archived as $key => $value) 
        {
            Tbl_inventory_serial_number::where("item_consumed",0)
                                       ->where("sold",0)
                                       ->where("consume_source_id",0)
                                       ->update(['archived' => 0]);
        }

        foreach ($all_item_archived as $key => $value) 
        {
            Tbl_inventory_serial_number::where("item_consumed",0)
                                       ->where("sold",0)
                                       ->where("consume_source_id",0)
                                       ->where("item_id",$value->item_id)
                                       ->update(['archived' => 1]);
        }
        dd("success");
    }
}
