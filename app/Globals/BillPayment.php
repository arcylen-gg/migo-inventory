<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_user;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_pay_bill;
 
use App\Globals\WriteCheck;
use App\Globals\Accouting;
use Carbon\carbon;
use DB;

/**
 * Chart of Account Module - all account related module
 *
 * @author Arcylen Gutierrez
 */

class BillPayment
{
	public static function postPaybill($pb_data, $pbline_data)
	{
		$insert["paybill_shop_id"]           = BillPayment::getShopId();
        $insert["paybill_vendor_id"]         = $pb_data["paybill_vendor_id"];
        $insert["paybill_ap_id"]             = $pb_data["paybill_ap_id"];
        $insert["paybill_date"]              = $pb_data["paybill_date"];
        $insert["paybill_total_amount"]      = $pb_data["paybill_total_amount"];
        $insert["paybill_payment_method"]    = $pb_data["paybill_payment_method"];
        $insert["paybill_memo"]              = $pb_data["paybill_memo"];
        $insert["paybill_date_created"]      = $pb_data["paybill_date_created"];

		$paybill_id  = Tbl_pay_bill::insertGetId($insert);
        
        BillPayment::insert_paybill_line($paybill_id, $pbline_data);

        WriteCheck::create_check_from_paybill($paybill_id);

        /* Transaction Journal */
        $entry["reference_module"]      = "bill-payment";
        $entry["reference_id"]          = $paybill_id;
        $entry["name_id"]               = $pb_data["paybill_vendor_id"];
        $entry["total"]                 = $pb_data["paybill_total_amount"];
        $entry_data[0]['account_id']    = $pb_data["paybill_ap_id"];
        $entry_data[0]['vatable']       = 0;
        $entry_data[0]['discount']      = 0;
        $entry_data[0]['entry_amount']  = $pb_data["paybill_total_amount"];
        $inv_journal = Accounting::postJournalEntry($entry, $entry_data);
        
        return $paybill_id;
	}
	public static function updatePaybill($paybill_id, $pb_data, $pbline_data)
	{
		$update["paybill_shop_id"]           = BillPayment::getShopId();
        $update["paybill_vendor_id"]         = $pb_data["paybill_vendor_id"];
        $update["paybill_ap_id"]             = $pb_data["paybill_ap_id"];
        $update["paybill_date"]              = $pb_data["paybill_date"];
        $update["paybill_total_amount"]      = $pb_data["paybill_total_amount"];
        $update["paybill_payment_method"]    = $pb_data["paybill_payment_method"];
        $update["paybill_memo"]              = $pb_data["paybill_memo"];

		Tbl_pay_bill::where("paybill_id",$paybill_id)->update($update);
        
        Tbl_pay_bill_line::where("pbline_pb_id",$paybill_id)->delete();

        WriteCheck::delete_bill_in_check($paybill_id);

        BillPayment::insert_paybill_line($paybill_id, $pbline_data);

        WriteCheck::update_check_from_paybill($paybill_id);

        /* Transaction Journal */
        $entry["reference_module"]      = "bill-payment";
        $entry["reference_id"]          = $paybill_id;
        $entry["name_id"]               = $pb_data["paybill_vendor_id"];
        $entry["total"]                 = $pb_data["paybill_total_amount"];
        $entry_data[0]['account_id']    = $pb_data["paybill_ap_id"];
        $entry_data[0]['vatable']       = 0;
        $entry_data[0]['discount']      = 0;
        $entry_data[0]['entry_amount']  = $pb_data["paybill_total_amount"];
        $inv_journal = Accounting::postJournalEntry($entry, $entry_data);

        return $paybill_id;
	}
	public static function insert_paybill_line($paybill_id, $pbline_data)
	{
		foreach ($pbline_data as $key => $value) 
		{
			if($value["line_is_checked"] == 1)
			{
                $insert_line["pbline_pb_id"]            = $paybill_id;
                $insert_line["pbline_reference_name"]   = $value["pbline_reference_name"];
                $insert_line["pbline_reference_id"]     = $value["pbline_reference_id"];
                $insert_line["pbline_amount"]           = $value["pbline_amount"];

                Tbl_pay_bill_line::insert($insert_line);

                if($insert_line["pbline_reference_name"] == 'bill')
                {
                    Billing::updateAmountApplied($insert_line["pbline_reference_id"]);
                }				
			}
			else
			{
				Billing::updateAmountApplied($value["pbline_reference_id"]);
			}
		}
	}
	public static function getShopId()
    {
    	return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
}
