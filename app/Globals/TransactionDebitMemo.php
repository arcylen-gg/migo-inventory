<?php
namespace App\Globals;

use App\Models\Tbl_debit_memo;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_debit_memo_line;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_requisition_slip_item;
use App\Models\Tbl_purchase_order_line;
use App\Globals\AccountingTransaction;
use App\Globals\Warehouse2;
use App\Models\Tbl_quantity_monitoring;

use Carbon\Carbon;
use Session;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
  
class TransactionDebitMemo
{
    /*public static function transactionStatus($shop_id)
    {
        $get = Tbl_debit_memo::whereNull('transaction_status')->where("db_shop_id", $shop_id)->get();
        foreach ($get as $key => $value) 
        {
            $update_status['transaction_status'] = 'posted';
            Tbl_debit_memo::where("db_shop_id", $shop_id)->where("db_id", $value->db_id)->update($update_status);
        }
    }*/
    public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->dbline_um);
            $total_qty = $value->dbline_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->dbline_um);
            if($value->itemline_ref_name == 'purchase_order')
            {
                $transactionline[$key]->ref = TransactionPurchaseOrder::refnum($shop_id, $value->dbline_refid);
            }
            else
            {
                $transactionline[$key]->ref = "-";
            }
        }
        return $transactionline;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->dbline_amount;
        }
        return $subtotal;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->dbline_taxable == 1)
            {
                $total_tax += $value->dbline_amount * 0.12;
            }
        }
        return $total_tax;
    }
	public static function getOpenDM($shop_id, $vendor_id)
    {
        return Tbl_debit_memo::where('db_shop_id',$shop_id)->where('db_vendor_id', $vendor_id)->get();
    }
    public static function info($shop_id, $dm_id)
    {
        return Tbl_debit_memo::vendor()->where('db_shop_id',$shop_id)->where('db_id', $dm_id)->first();
    }
    public static function info_item($dm_id)
    {
        return Tbl_debit_memo_line::um()->db_item()->binLocation()->where('dbline_db_id', $dm_id)->get();
    }
    public static function count_tax($dm_id)
    {
        return Tbl_debit_memo_line::where('dbline_db_id', $dm_id)->where('dbline_taxable', 1)->count();   
    }
    public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
    {
        $data = Tbl_debit_memo::Vendor()->where('db_shop_id',$shop_id)->groupBy("db_id")->orderBy("db_date","desc");
        
        $data = AccountingTransaction::acctg_trans($shop_id, $data);

        if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {   
                $q->orWhere("vendor_company", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_last_name", "LIKE", "%$search_keyword%");
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("db_id", "LIKE", "%$search_keyword%");
                $q->orWhere("db_amount", "LIKE", "%$search_keyword%");
            });
        }
        if($status != 'all')
        {
            $tab = 0;
            if($status == 'open')
            {
                $tab = 0;
            }
            if($status == 'closed')
            {
                $tab = 1;
            }
            $data->where('db_memo_status',$tab);
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

    public static function postInsert($shop_id, $insert, $insert_item)
    {
        $val = AccountingTransaction::vendorValidation($insert, $insert_item, 'debit_memo');
        if(!$val)
        {
            $ins['db_shop_id']          = $shop_id;
            $ins['transaction_refnum']  = $insert['transaction_refnumber'];
            $ins['db_vendor_id']        = $insert['vendor_id'];
            $ins['db_vendor_email']     = $insert['vendor_email'];
            $ins['db_date']             = date('Y-m-d', strtotime($insert['transaction_date']));
            $ins['db_message']          = $insert['vendor_message'];
            $ins['db_memo']             = $insert['vendor_memo'];
            $ins['date_created']        = Carbon::now();
            $ins['db_discount_type']    = $insert['vendor_discounttype'];
            $ins['db_discount_value']   = $insert['vendor_discount'];
            $ins['taxable']             = $insert['vendor_tax'];
            //$ins['transaction_status']      = 'pending';

            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
            
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;

            $ins['db_subtotal'] = $subtotal_price;
            $ins['db_amount'] = $subtotal_price - $discount + $tax;

            /* INSERT DM IN DATABASE */
            $dm_id = Tbl_debit_memo::insertGetId($ins);

            /* Transaction Journal */
            $entry["reference_module"]  = "debit-memo";
            $entry["reference_id"]      = $dm_id;
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["total"]             = $ins['db_amount'];
            $entry["vatable"]           = $tax;
            $entry["discount"]          = $discount;
            $entry["ewt"]               = '';
            
            $return = Self::insertLine($dm_id, $insert_item, $entry, false, $shop_id);
            $return = $dm_id;

            /*$settings_auto_post_transaction = AccountingTransaction::settings($shop_id, 'auto_post_transaction');
            if($settings_auto_post_transaction == 1)
            {
                $update_status['transaction_status'] = 'posted';
                Tbl_debit_memo::where('db_id', $dm_id)->update($update_status);*/
                $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
                AccountingTransaction::consume_inventory($shop_id, $warehouse_id, $insert_item, 'debit_memo', $dm_id, 'Consume upon creating DEBIT MEMO '.$ins['transaction_refnum']);
            /*}*/
        }
        else
        {
            $return = $val;
        }  
        return $return;
    }

    public static function postUpdate($dm_id, $shop_id, $insert, $insert_item)
    {
        $val = AccountingTransaction::vendorValidation($insert, $insert_item);
        if(!$val)
        {
            $ins['db_shop_id']          = $shop_id;
            $ins['transaction_refnum']  = $insert['transaction_refnumber'];
            $ins['db_vendor_id']        = $insert['vendor_id'];
            $ins['db_vendor_email']     = $insert['vendor_email'];
            $ins['db_date']             = date('Y-m-d', strtotime($insert['transaction_date']));
            $ins['db_message']          = $insert['vendor_message'];
            $ins['db_memo']             = $insert['vendor_memo'];
            $ins['date_created']        = Carbon::now();
            $ins['db_discount_type']    = $insert['vendor_discounttype'];
            $ins['db_discount_value']   = $insert['vendor_discount'];
            $ins['taxable']             = $insert['vendor_tax'];

            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
                     
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;

            $ins['db_subtotal'] = $subtotal_price;
            $ins['db_amount'] = $subtotal_price - $discount + $tax;

            /* INSERT DM IN DATABASE */
            Tbl_debit_memo::where('db_id', $dm_id)->update($ins);

            /* Transaction Journal */
            $entry["reference_module"]  = "debit-memo";
            $entry["reference_id"]      = $dm_id;
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["total"]             = $ins['db_amount'];
            $entry["vatable"]           = $tax;
            $entry["discount"]          = $discount;
            $entry["ewt"]               = '';
            Tbl_debit_memo_line::where('dbline_db_id', $dm_id)->delete();

            $return = Self::insertLine($dm_id, $insert_item, $entry, true, $shop_id);
            $return = $dm_id;

            /* UPDATE INVENTORY HERE */
            $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
            AccountingTransaction::inventory_consume_update($shop_id, $warehouse_id, 'debit_memo', $dm_id); 
            AccountingTransaction::consume_inventory($shop_id, $warehouse_id, $insert_item, 'debit_memo', $dm_id, 'Consume upon creating DEBIT MEMO '.$ins['transaction_refnum']);
        }
        else
        {
            $return = $val;
        }  
        return $return;
    }

    public static function insertLine($dm_id, $insert_item, $entry, $for_update = '', $shop_id)
    {

        if(count($insert_item) > 0)
        {
            $id_not_delete = array();
            $itemline = null;
            $return = null;

            foreach ($insert_item as $key => $value) 
            {   
                /* DISCOUNT PER LINE */
                $discount       = $value['item_discount'];
                $discount_type  = 'fixed';

                if(strpos($discount, '%'))
                {
                    $discount       = substr($discount, 0, strpos($discount, '%')) / 100;
                    $discount_type  = 'percent';
                } 

                $itemline['dbline_db_id']        = $dm_id;
                $itemline['dbline_item_id']      = $value['item_id'];
                $itemline['dbline_description']  = $value['item_description'];
                $itemline['dbline_sub_wh_id']    = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
                $itemline['dbline_um']           = $value['item_um'];
                $itemline['dbline_qty']          = $value['item_qty'];
                $itemline['dbline_rate']         = $value['item_rate'];
                $itemline['dbline_amount']       = $value['item_amount'];
                $itemline['dbline_refname']      = $value['item_ref_name'];
                $itemline['dbline_refid']        = $value['item_ref_id'];   
                $itemline['dbline_taxable']      = $value['item_taxable'];
                $itemline['dbline_discount']     = $discount;
                $itemline['dbline_discounttype'] = $discount_type;

                $itemline_id = Tbl_debit_memo_line::insert($itemline);

                array_push($id_not_delete, $itemline_id);
                $type = Item::get_item_type($value['item_id']);
                //get item type
                // if($type == 1 || $type == 4 || $type == 5 )
                // {
                    if($itemline['dbline_refid'] && $itemline['dbline_refname'])
                    {
                        $qty = Tbl_quantity_monitoring::where('qty_transaction_id', $dm_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $user_info->shop_id)->first();

                        if($qty == null || $for_update == false)
                        {
                            $insert_qty_item['qty_item_id']              = $itemline['dbline_item_id'];
                            $insert_qty_item['qty_transaction_id']       = $dm_id;
                            $insert_qty_item['qty_transaction_name']     = 'debit_memo';
                            $insert_qty_item['qty_transactionline_id']   = $itemline_id;
                            $insert_qty_item['qty_ref_id']               = $itemline['dbline_refid'];
                            $insert_qty_item['qty_ref_name']             = $itemline['dbline_refname'];
                            $insert_qty_item['qty_old']                  = $itemline['dbline_qty'];
                            $insert_qty_item['qty_new']                  = $itemline['dbline_qty'];
                            $insert_qty_item['qty_shop_id']              = $shop_id;
                            $insert_qty_item['created_at']               = Carbon::now();
                            Tbl_quantity_monitoring::insert($insert_qty_item);
                        }
                        else
                        {
                            $insert_qty_item['qty_old'] = $qty->qty_new;
                            $insert_qty_item['qty_new'] = $value['item_qty'];
                            $insert_qty_item['qty_transactionline_id'] = $itemline_id;
                            Tbl_quantity_monitoring::where('qty_transaction_id', $dm_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
                        }
                    }
                // }
                if($id_not_delete != null)
                {
                    Tbl_quantity_monitoring::where("qty_transaction_id", $dm_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'debit_memo')->delete();
                }
            }

            $return = AccountingTransaction::entry_data($entry, $insert_item);
            $return = $dm_id;

        }
        return $return;
    }

    public static function appliedTransaction_Debit_memo($shop_id, $dm_id, $for_update = false)
    {
        if($dm_id != null)
        {
            $applied_transaction = Session::get('applied_transaction');
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value)
                { 
                    if($dm_id)
                    {
                        AccountingTransaction::checkItemLineQty($key, $dm_id, $for_update);
                    }
                } 
            }  
        }
        Self::insert_acctg_transaction($shop_id, $dm_id, $applied_transaction);
    }

    public static function appliedTransaction($shop_id, $dm_id, $for_update = false)
    {
        if($dm_id != null)
        {
            $applied_transaction = Session::get('applied_transaction');
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value)
                { 
                    if($dm_id)
                    {
                        AccountingTransaction::checkPolineQty($key, $dm_id, $for_update);
                    }
                } 
            }  
        }
        Self::insert_acctg_transaction($shop_id, $dm_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_debit_memo::where("db_shop_id", $shop_id)->where("db_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "debit_memo";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->db_date;

            $attached_transaction_data = null;
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value) 
                {
                    $get_data = Tbl_purchase_order::where("po_shop_id", $shop_id)->where("po_id", $key)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$key]['transaction_ref_name'] = "purchase_order";
                        $attached_transaction_data[$key]['transaction_ref_id'] = $key;
                        $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$key]['transaction_date'] = $get_data->po_date;
                    }
                }
            }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }
   
}