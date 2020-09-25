<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer_invoice extends Model
{
	protected $table = 'tbl_customer_invoice';
	protected $primaryKey = "inv_id";
    public $timestamps = true;

    public function scopeRpline($query)
    {
        return $query->leftjoin("tbl_receive_payment_line", "rpline_reference_id", "=", "inv_id")
                     ->leftjoin("tbl_receive_payment","rp_id","=","rpline_rp_id");
        
    }
    public static function scopePaymentMethod($query)
    {
        return $query->leftjoin("tbl_payment_method","tbl_payment_method.payment_method_id","=","tbl_customer_invoice.inv_payment_method");
    }

    public static function scopePM($query)
    {
        return $query->leftjoin("tbl_customer_invoice_pm","invoice_id","=","inv_id");
    }

	public static function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_customer_invoice.inv_customer_id")
                     ->leftjoin('tbl_customer_other_info',"tbl_customer_other_info.customer_id","=","tbl_customer.customer_id");
    }
    public static function scopeC_m($query)
    {
        return $query->leftJoin("tbl_credit_memo","cm_id","=","tbl_customer_invoice.credit_memo_id");
    }
    public static function scopeReturns_item($query)
    {
        return $query->leftjoin("tbl_credit_memo_line","tbl_credit_memo_line.cmline_cm_id","=","tbl_customer_invoice.credit_memo_id")->leftjoin("tbl_item","cmline_item_id","=","item_id");;
    }
    public static function scopeManual_invoice($query)
    {
        return $query->leftJoin("tbl_manual_invoice","tbl_manual_invoice.inv_id","=","tbl_customer_invoice.inv_id")
                    ->selectRaw("*, tbl_customer_invoice.inv_id as inv_id");
    }
    public static function scopeInvoice_item($query)
    {
    	return $query->leftjoin("tbl_customer_invoice_line","tbl_customer_invoice_line.invline_inv_id","=","tbl_customer_invoice.inv_id")
    				->leftjoin("tbl_item","tbl_item.item_id","=","invline_item_id");
    }

    public static function scopeAppliedPayment($query, $shop_id = 0)
    {
        return $query->leftJoin(DB::raw("(select sum(rpline_amount) as amount_applied, 
            rpline_reference_id from tbl_receive_payment_line as rpline
            inner join tbl_receive_payment rp on rp_id = rpline_rp_id where rp_shop_id = ".$shop_id." 
            and rpline_reference_name = 'invoice' group by concat(rpline_reference_name,'-',rpline_reference_id)) pymnt"), "pymnt.rpline_reference_id", "=", "inv_id");
    }
    public static function scopeByReceivePayment($query, $shop_id, $rp_id)
    {
        return $query->leftjoin("tbl_receive_payment_line","tbl_receive_payment_line.rpline_reference_id","=","inv_id")->where("inv_shop_id", $shop_id)->where('tbl_receive_payment_line.rpline_rp_id', $rp_id);
    }
    public static function scopeByCustomer($query, $shop_id, $customer_id)
    {
        return $query->where("inv_shop_id", $shop_id)->where("inv_customer_id", $customer_id);
    }
    public static function scopeAcctg_trans($query, $trans_type = '')
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","inv_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->leftjoin('tbl_warehouse','transaction_warehouse_id',"=",'warehouse_id');
        if($trans_type)
        {
            $return = $return->where("transaction_ref_name", $trans_type);            
        }

        return $return;
    }

    public static function scopeRcvPayment($query, $rcvpayment_id, $invoice_id)
    {
        return $query->leftJoin(DB::raw("(select * from tbl_receive_payment_line where rpline_rp_id =" .$rcvpayment_id ." and rpline_reference_name = 'invoice') rp"),"rp.rpline_reference_id","=","inv_id")
                     ->where(function($query) use ($invoice_id)
                     {
                        $query->where("inv_is_paid", 0);
                        $query->orWhere(function($query) use ($invoice_id)
                        {
                            $query->whereIn("inv_id", $invoice_id);
                        });
                     });
    }
    public static function scopePerWarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","inv_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "sales_invoice");
        return $return;
    }
}