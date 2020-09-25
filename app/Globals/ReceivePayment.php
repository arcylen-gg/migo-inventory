<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Globals\CommissionCalculator;
use App\Globals\Invoice;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo;
use App\Globals\AuditTrail;
use App\Globals\CreditMemo;
use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\Carbon;

/**
 * Receive Payment Module - all payment related module
 *
 * @author Bryan Kier Aradanas
 */

class ReceivePayment
{

    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public static function updateCM($cm_id,$receive_payment_id)
    {
    	$up["cm_used_ref_name"] = "receive_payment";
    	$up["cm_used_ref_id"] = $receive_payment_id;
    	$up["cm_type"] = 1;

    	Tbl_credit_memo::where("cm_id",$cm_id)->update($up);
    }
    public function postPayment()
    {
    	
    }
    public static function getBalance($shop_id, $invoice_id, $invoice_over_all_amount = 0)
    {
        $details = Tbl_receive_payment::rpline()->where('rp_shop_id', $shop_id)->where("rpline_reference_name","invoice")->where("rpline_reference_id",$invoice_id)->first();
        $amount_paid = 0;
        $cm_amount = CreditMemo::cm_amount($invoice_id);
        $balance = $invoice_over_all_amount - $cm_amount;
        if($details)
        {
            $balance = ($balance - $details->rpline_amount) + $cm_amount;
            if($balance < 0)
            {
                $balance = 0;
            }
        }
        return $balance;
    }

    public static function getArbalance($shop_id, $mfrom, $mto)
    {
        $amount_paid = 0;
        $get = Tbl_customer_invoice::where("inv_shop_id", $shop_id)
                                    ->where("is_sales_receipt",0)
                                    ->where("inv_is_paid", 0)
                                    ->whereBetween("tbl_customer_invoice.inv_date",[$mfrom, $mto])
                                    ->orderBy("transaction_refnum","desc")
                                    ->get();
        $balance = 0;
        foreach ($get as $key => $value)
        {
            if($value)
            {
                $balance +=  $value->inv_overall_price - $value->inv_payment_applied;
            }
        }
        return $balance;
    }
    public static function check_rp($shop_id, $transaction_refnum)
    {
        return Tbl_receive_payment::where('rp_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
    }
    public static function insert_payment($shop_id, $insert_data, $insert_item)
    {
        $insert["rp_shop_id"]           = $shop_id;
        $insert["rp_customer_id"]       = $insert_data['customer_id'];
        $insert["transaction_refnum"]       = $insert_data['transaction_refnum'];
        $insert["rp_ar_account"]        = $insert_data['ar_account'];
        $insert["rp_date"]              = $insert_data['date'];
        $insert["rp_total_amount"]      = $insert_data['total_payment_amount'];
        $insert["rp_payment_method"]    = "";
        $insert["rp_memo"]              = $insert_data['memo'];
        $insert["date_created"]         = Carbon::now();

        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

        foreach($insert_item as $key => $value)
        {
            if($value)
            {
                $insert_line["rpline_rp_id"]            = $rcvpayment_id;
                $insert_line["rpline_reference_name"]   = $value['ref_name'];
                $insert_line["rpline_reference_id"]     = $value['ref_id'];
                $insert_line["rpline_amount"]           = $value['payment_amount'];

                Tbl_receive_payment_line::insert($insert_line);

                if($insert_line["rpline_reference_name"] == 'invoice')
                {
                    $ret = Invoice::updateAmountApplied($insert_line["rpline_reference_id"]);

                    $check = CommissionCalculator::check_settings($shop_id);
                    if($check == 1)
                    {
                        CommissionCalculator::update_commission($insert_line["rpline_reference_id"], $rcvpayment_id);
                    }
                }
            }
        }

        /* Transaction Journal */
        $entry["reference_module"]      = "receive-payment";
        $entry["reference_id"]          = $rcvpayment_id;
        $entry["name_id"]               = $insert["rp_customer_id"];
        $entry["total"]                 = $insert["rp_total_amount"];
        $entry_data[0]['account_id']    = $insert["rp_ar_account"];
        $entry_data[0]['vatable']       = 0;
        $entry_data[0]['discount']      = 0;
        $entry_data[0]['entry_amount']  = $insert["rp_total_amount"];
        $inv_journal = Accounting::postJournalEntry($entry, $entry_data);

        return $rcvpayment_id;
    }
}