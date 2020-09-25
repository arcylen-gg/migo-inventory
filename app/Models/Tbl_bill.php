<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_bill extends Model
{
    protected $table = 'tbl_bill';
    protected $primaryKey = "bill_id";
    public $timestamps = false;

    public function scopePurchasedByQueries_with_where($query,$param)
    {
        switch ($param['module_used'])
        {
            case 'reports':

                switch ($param['where_used']) 
                {
                    case 'purchased_by_vendor':
                        return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_bill.bill_vendor_id',$param['vendor_id'])
                        ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                        ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])->orderBy('tbl_bill.bill_date', "DESC");  
                        break;

                    case 'purchased_by_vendor_sum_amount':
                        return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_bill.bill_shop_id',$param['shop_id'])
                        ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                        ->orderBy('tbl_bill.bill_date', "DESC");
                    break;

                    case 'purchased_by_item_summary':
                        $query = DB::select('select item_id, item_name, sum(itemline_qty) as item_sum ,multi_name, sum(itemline_rate * itemline_qty) as item_amount from ( select tbl_item.item_id, tbl_item.item_name,tbl_bill_item_line.itemline_qty, tbl_unit_measurement_multi.multi_name, tbl_bill_item_line.itemline_rate from tbl_bill inner join tbl_vendor on tbl_vendor.vendor_id = tbl_bill.bill_vendor_id left join tbl_bill_item_line on tbl_bill_item_line.itemline_bill_id = tbl_bill.bill_id left join tbl_unit_measurement_multi on tbl_bill_item_line.itemline_um = tbl_unit_measurement_multi.multi_id left join tbl_item on tbl_bill_item_line.itemline_item_id = tbl_item.item_id left join tbl_item_type on tbl_item_type.item_type_id = tbl_item.item_type_id left join tbl_category on tbl_category.type_id = tbl_item.item_category_id where tbl_item.item_type_id = '.$param['item_type_id'].' and tbl_bill.bill_shop_id = '.$param['shop_id'].' and tbl_bill.bill_date between "'.$param['date_from'].'" and "'.$param['date_to'].'" order by tbl_bill.bill_date desc) as tbl_search group by item_name, multi_name');

                        return $query;

                    break;

                    case 'purchased_by_item_summary_total':
                        return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_item.item_type_id',$param['item_type_id'])
                        ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                        ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                        ->orderBy('tbl_bill.bill_date', "DESC");
                    break;

                     case 'purchased_by_item_summary_total_all':
                        return $query->PurchasedByQueries($param['join_parameter'])
                        ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                        ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                        ->orderBy('tbl_bill.bill_date', "DESC");
                    break;

                    case 'purchased_by_item_detail_total_group':
                        if($param['item_id'] != 0 || $param['item_id'] != null)
                        {
                            return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_item.item_type_id',$param['item_type_id'])
                            ->where('tbl_item.item_id',$param['item_id'])
                            ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                            ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                            ->orderBy('tbl_bill.bill_date', "DESC")
                            ->groupby('item_name');
                        }
                        else
                        {
                            return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_item.item_type_id',$param['item_type_id'])
                            ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                            ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                            ->orderBy('tbl_bill.bill_date', "DESC")
                            ->groupby('item_name');
                        }
                    break;

                    case 'purchased_by_item_detail_total':
                    // dd($query->PurchasedByQueries($param['join_parameter'])->where('tbl_item.item_type_id',$param['item_type_id'])
                    //             ->where('tbl_item.item_id',$param['item_id'])
                    //             ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                    //             ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                    //             ->orderBy('tbl_bill.bill_date', "DESC")->toSql());
                        return $query->PurchasedByQueries($param['join_parameter'])->where('tbl_item.item_type_id',$param['item_type_id'])
                                ->where('tbl_item.item_id',$param['item_id'])
                                ->where('tbl_bill.bill_shop_id',$param['shop_id'])
                                ->whereBetween("tbl_bill.bill_date",[$param['date_from'], $param['date_to']])
                                ->orderBy('tbl_bill.bill_date', "DESC");
                    break;

                    
                    default:
                        # code...
                        break;
                }
                               
            break;

            default:
                # code...
                break;
        }

    }

    
    public function scopePurchasedByQueries($query,$join_select)
    {
        switch ($join_select) {
            case '1':  
            //1 = vendor
                    return $query->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                    ->leftjoin("tbl_bill_item_line","tbl_bill_item_line.itemline_bill_id","=","tbl_bill.bill_id")
                    ->leftjoin("tbl_unit_measurement_multi","tbl_bill_item_line.itemline_um","=","tbl_unit_measurement_multi.multi_id")
                    ->leftjoin("tbl_item","tbl_bill_item_line.itemline_item_id","=","tbl_item.item_id");
            break;

             case '2':  
            //2 = item
                    return $query->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                    ->leftjoin("tbl_bill_item_line","tbl_bill_item_line.itemline_bill_id","=","tbl_bill.bill_id")
                    ->leftjoin("tbl_unit_measurement_multi","tbl_bill_item_line.itemline_um","=","tbl_unit_measurement_multi.multi_id")
                    ->leftjoin("tbl_item","tbl_bill_item_line.itemline_item_id","=","tbl_item.item_id")
                    ->leftjoin("tbl_item_type","tbl_item_type.item_type_id","=","tbl_item.item_type_id")
                    ->leftjoin("tbl_category","tbl_category.type_id","=","tbl_item.item_category_id");
            break;

            case '3':  
            //2 = item 09082018
                    return $query->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                    ->leftjoin("tbl_bill_item_line","tbl_bill_item_line.itemline_bill_id","=","tbl_bill.bill_id")
                    ->leftjoin("tbl_unit_measurement_multi","tbl_bill_item_line.itemline_um","=","tbl_unit_measurement_multi.multi_id")
                    ->leftjoin("tbl_item","tbl_bill_item_line.itemline_item_id","=","tbl_item.item_id")
                    ->leftjoin("tbl_item_type","tbl_item_type.item_type_id","=","tbl_item.item_type_id")
                    ->leftjoin("tbl_category","tbl_category.type_id","=","tbl_item.item_category_id")
                    ->leftjoin("tbl_debit_memo_line","tbl_debit_memo_line.dbline_item_id","=","tbl_item.item_id")
                    ->leftjoin("tbl_debit_memo","tbl_debit_memo_line.dbline_db_id","=","tbl_debit_memo.db_id");
            break;

            case '4':

                // return $query->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                //     ->leftjoin("tbl_bill_item_line","tbl_bill_item_line.itemline_bill_id","=","tbl_bill.bill_id")
                //     ->leftjoin("tbl_unit_measurement_multi","tbl_bill_item_line.itemline_um","=","tbl_unit_measurement_multi.multi_id")
                //     ->leftjoin("tbl_item","tbl_bill_item_line.itemline_item_id","=","tbl_item.item_id")
                //     ->leftjoin("tbl_item_type","tbl_item_type.item_type_id","=","tbl_item.item_type_id")
                //     ->leftjoin("tbl_category","tbl_category.type_id","=","tbl_item.item_category_id")
                //     ->leftjoin("tbl_purchase_order","tbl_purchase_order.po_id","=","tbl_bill_item_line.itemline_ref_id")
                //     ->leftjoin("tbl_purchase_order_line","tbl_purchase_order_line.poline_po_id","=","tbl_purchase_order.po_id")
                //     ->leftjoin("tbl_debit_memo_line","tbl_debit_memo_line.dbline_refid","=","tbl_purchase_order.po_id")
                //     ->leftjoin("tbl_debit_memo","tbl_debit_memo.db_id","=","tbl_debit_memo_line.dbline_db_id")
                //     ->addSelect('*','tbl_bill.transaction_refnum as bill_refnum','tbl_debit_memo.transaction_refnum as dbt_memo_refnum');

                return $query->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                    ->leftjoin("tbl_bill_item_line","tbl_bill_item_line.itemline_bill_id","=","tbl_bill.bill_id")
                    ->leftjoin("tbl_unit_measurement_multi","tbl_bill_item_line.itemline_um","=","tbl_unit_measurement_multi.multi_id")
                    ->leftjoin("tbl_item","tbl_bill_item_line.itemline_item_id","=","tbl_item.item_id")
                    ->leftjoin("tbl_item_type","tbl_item_type.item_type_id","=","tbl_item.item_type_id")
                    ->leftjoin("tbl_category","tbl_category.type_id","=","tbl_item.item_category_id")
                    ->leftjoin("tbl_debit_memo_line","tbl_debit_memo_line.dbline_refid","=","tbl_bill_item_line.itemline_id")
                    ->leftjoin("tbl_debit_memo","tbl_debit_memo.db_id","=","tbl_debit_memo_line.dbline_db_id")
                    ->addSelect('*','tbl_bill.transaction_refnum as bill_refnum','tbl_debit_memo.transaction_refnum as dbt_memo_refnum');

           
                
            break;

        }
    }

    public static function scopeTerms($query)
    {
        return $query->join("tbl_terms","tbl_terms.terms_id","=","tbl_bill.bill_terms_id");
    }
    public function scopeAccount_line($query)
    {
         return $query->join('tbl_bill_account_line', 'tbl_bill_account_line.accline_bill_id', '=', 'tbl_bill.bill_id');
    }
    public function scopeAccount($query)
    {
        $query->join('tbl_bill_account_line', 'tbl_bill_account_line.accline_bill_id', '=', 'tbl_bill.bill_id')
              ->join('tbl_chart_of_account', 'tbl_chart_of_account.account_id', '=', 'tbl_bill_account_line.accline_coa_id');
        return $query;
    }
    public function scopeItem_line($query)
    {
    	 return $query->join('tbl_bill_item_line', 'tbl_bill_item_line.itemline_bill_id', '=', 'tbl_bill.bill_id')->leftjoin("tbl_item","tbl_item.item_id","=","itemline_item_id");
    }
    public static function scopeByVendor($query, $shop_id, $vendor_id)
    {
        return $query->where("bill_shop_id", $shop_id)->where("bill_vendor_id", $vendor_id);
    }
    public static function scopeAppliedPayment($query, $shop_id)
    {
        return $query->leftJoin(DB::raw("(select sum(pbline_amount) as amount_applied, pbline_reference_id from tbl_pay_bill_line as pbline inner join tbl_pay_bill pb on paybill_id = pbline_pb_id where paybill_shop_id = ".$shop_id." and pbline_reference_name = 'bill' group by concat(pbline_reference_name,'-',pbline_reference_id)) pymnt"), "pymnt.pbline_reference_id", "=", "bill_id");
    }
    public static function scopeByPayBill($query, $shop_id, $pb_id)
    {
        return $query->leftjoin("tbl_pay_bill_line","tbl_pay_bill_line.pbline_reference_id","=","bill_id")->where("bill_shop_id", $shop_id)->where('tbl_pay_bill_line.pbline_pb_id', $pb_id);
    }
    public function scopeVendor($query)
    {
         return $query->leftjoin('tbl_vendor', 'tbl_bill.bill_vendor_id', '=', 'tbl_vendor.vendor_id');
    }
    public static function scopePayBill($query, $pb_id, $bill_id)
    {
        return $query->leftJoin(DB::raw("(select * from tbl_pay_bill_line where pbline_pb_id =" .$pb_id ." and pbline_reference_name = 'bill') pb"),"pb.pbline_reference_id","=","bill_id")
                     ->where(function($query) use ($bill_id)
                     {
                        $query->where("bill_is_paid", 0);
                        $query->orWhere(function($query) use ($bill_id)
                        {
                            $query->whereIn("bill_id", $bill_id);
                        });
                     });
    }

    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","bill_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "enter_bills");

        return $return;
    }
    public static function scopebillwarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","bill_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "enter_bills");
        return $return;
    }
    public static function scopereceivewarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","bill_ri_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "receive_inventory");
        return $return;
    }
    public static function scopeReceive($query)
    {
        return $query->leftjoin('tbl_receive_inventory','bill_ri_id','=','ri_id');
    }
}
