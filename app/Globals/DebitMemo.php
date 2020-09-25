<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_item;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_cart;
use App\Models\Tbl_user;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_settings;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_debit_memo_line;
use App\Models\Tbl_debit_memo;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\AuditTrail;
use DB;
use Session;
use Carbon\Carbon;

class DebitMemo
{
	public static function check_setting($type = "")
    {
    	$return = false;
    	$settings = Tbl_settings::where("shop_id",DebitMemo::getShopId())->where("settings_key",$type)->where("settings_value","enable")->first();

    	if($settings)
    	{
    		$return = true;
    	}
    	return $return;
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
	public static function postDB($vendor_info, $item_info, $inv_id = 0)
	{
		$insert_db["db_shop_id"] = DebitMemo::getShopId();

		$insert_db["db_vendor_id"] = $vendor_info["db_vendor_id"];
		$insert_db["db_vendor_email"] = $vendor_info["db_vendor_email"];
		$insert_db["db_date"] = $vendor_info["db_date"];
		$insert_db["db_message"] = $vendor_info["db_message"];
		$insert_db["db_memo"] = $vendor_info["db_memo"];
		$insert_db["db_amount"] = $vendor_info["db_amount"];
		$insert_db["is_bad_order"] = $vendor_info["type"];
		$insert_db["date_created"] = Carbon::now();

		$db_id = Tbl_debit_memo::insertGetId($insert_db);
		/* Transaction Journal */
        $entry["reference_module"]  = "debit-memo";
        $entry["reference_id"]      = $db_id;
        $entry["name_id"]           = $vendor_info["db_vendor_id"];
        $entry["total"]             = $vendor_info["db_amount"];

		DebitMemo::insert_dbline($db_id, $item_info, $entry);

        $db_data = AuditTrail::get_table_data("tbl_debit_memo","db_id",$db_id);
        AuditTrail::record_logs("Added","debit_memo",$db_id,"",serialize($db_data));


		return $db_id;
	}

	public static function updateDB($db_id, $vendor_info, $item_info)
	{
        $old_data = AuditTrail::get_table_data("tbl_debit_memo","db_id",$db_id);

		$update_db["db_shop_id"] = DebitMemo::getShopId();


		$update_db["db_vendor_id"] = $vendor_info["db_vendor_id"];
		$update_db["db_vendor_email"] = $vendor_info["db_vendor_email"];
		$update_db["db_date"] = $vendor_info["db_date"];
		$update_db["db_message"] = $vendor_info["db_message"];
		$update_db["db_memo"] = $vendor_info["db_memo"];
		$update_db["db_amount"] = $vendor_info["db_amount"];
		$update_db["date_created"] = Carbon::now();

		Tbl_debit_memo::where("db_id",$db_id)->update($update_db);

		Tbl_debit_memo_line::where("dbline_db_id",$db_id)->delete();

		/* Transaction Journal */
        $entry["reference_module"]  = "debit-memo";
        $entry["reference_id"]      = $db_id;
        $entry["name_id"]           = $vendor_info["db_vendor_id"];
        $entry["total"]             = $vendor_info["db_amount"];

		DebitMemo::insert_dbline($db_id, $item_info, $entry);

        $db_data = AuditTrail::get_table_data("tbl_debit_memo","db_id",$db_id);
        AuditTrail::record_logs("Edited","debit_memo",$db_id,serialize($old_data),serialize($db_data));

	}

	public static function insert_dbline($db_id, $item_info, $entry)
	{
		foreach ($item_info as $key => $value) 
		{
			$insert_dbline["dbline_db_id"] = $db_id;
			// $insert_dbline["dbline_service_date"] = $value["item_service_date"];
			$insert_dbline["dbline_um"] = $value["um"];
			$insert_dbline["dbline_item_id"] = $value["item_id"];
			$insert_dbline["dbline_description"] = $value["item_description"];
			$insert_dbline["dbline_qty"] = $value["quantity"];
			$insert_dbline["dbline_rate"] = $value["rate"];
			$insert_dbline["dbline_amount"] = $value["amount"];

			Tbl_debit_memo_line::insert($insert_dbline);

		 	$item_type = Item::get_item_type($value['item_id']);
            /* TRANSACTION JOURNAL */  
            if($item_type != 4)
            {
				/* TRANSACTION JOURNAL */   
	            $entry_data[$key]['item_id']            = $value["item_id"];
	            $entry_data[$key]['entry_qty']          = $value["quantity"];
	            $entry_data[$key]['vatable']            = 0;
	            $entry_data[$key]['discount']           = 0;
	            $entry_data[$key]['entry_amount']       = $value["amount"];
	            $entry_data[$key]['entry_description']  = $value["item_description"];
	        }
	        else
	        {
	        	$item_bundle = Item::get_item_in_bundle($value['item_id']);
                if(count($item_bundle) > 0)
                {
                    foreach ($item_bundle as $key_bundle => $value_bundle) 
                    {
                        $item_data = Item::get_item_details($value_bundle->bundle_item_id);
                        $entry_data['b'.$key.$key_bundle]['item_id']            = $value_bundle->bundle_item_id;
                        $entry_data['b'.$key.$key_bundle]['entry_qty']          = $value['quantity'] * (UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                        $entry_data['b'.$key.$key_bundle]['vatable']            = 0;
                        $entry_data['b'.$key.$key_bundle]['discount']           = 0;
                        $entry_data['b'.$key.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key.$key_bundle]['entry_qty'];
                        $entry_data['b'.$key.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                    }
                }
	        }

		}

		$debit_memo_journal = Accounting::postJournalEntry($entry, $entry_data);
	}
}
