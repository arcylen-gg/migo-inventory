<?php
namespace App\Globals;


use App\Models\Tbl_receive_inventory_line;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_bill;
use App\Models\Tbl_quantity_monitoring;
use App\Globals\Warehouse2;
use App\Globals\Item;
use Carbon\Carbon;
use Session;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionReceiveInventory
{
    /*public static function transactionStatus($shop_id)
    {
        $get = Tbl_receive_inventory::whereNull('transaction_status')->where("ri_shop_id", $shop_id)->get();
        foreach ($get as $key => $value) 
        {
            $update_status['transaction_status'] = 'posted';
            Tbl_receive_inventory::where("ri_shop_id", $shop_id)->where("ri_id", $value->ri_id)->update($update_status);
        }
    }*/
    public static function countTransaction($shop_id, $vendor_id)
    {
        $debit_memo   = Tbl_debit_memo::where('db_shop_id',$shop_id)->where('db_vendor_id', $vendor_id)->count();
        $purchase_order = Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed', 0)->count();

        $count = $debit_memo + $purchase_order;
        return $count;
    }
    public static function info($shop_id, $ri_id)
    {
        return Tbl_receive_inventory::vendor()->where("ri_shop_id", $shop_id)->where("ri_id", $ri_id)->first();
    }
    public static function info_item($ri_id)
    {
        return Tbl_receive_inventory_line::um()->item()->where("riline_ri_id", $ri_id)->get();        
    }
    public static function count_tax($ri_id)
    {
        return Tbl_receive_inventory_line::where('riline_ri_id', $ri_id)->where('riline_taxable', 1)->count();
    }
    public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->riline_um);

            $total_qty = $value->riline_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->riline_um);
        }
        return $transactionline;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->riline_taxable == 1)
            {
                $total_tax += $value->riline_amount * 0.12;
            }
        }
        return $total_tax;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->riline_amount;
        }
        return $subtotal;
    }
    public static function get($shop_id, $paginate = null, $search_keyword = null)
    {
        $data = Tbl_receive_inventory::vendor()->where('ri_shop_id', $shop_id)->groupBy("ri_id")->orderBy("ri_date",'DESC');
        $settings = AccountingTransaction::settings($shop_id, "allow_transaction");
        
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
                $q->orWhere("ri_id", "LIKE", "%$search_keyword%");
                $q->orWhere("ri_total_amount", "LIKE", "%$search_keyword%");
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
	public static function postInsert($shop_id, $insert, $insert_item)
	{
        $val = AccountingTransaction::vendorValidation($insert, $insert_item, 'receive_inventory');
        if(!$val)
        {
    		$ins['ri_shop_id']          = $shop_id;
    		$ins['transaction_refnum']  = $insert['transaction_refnumber'];
            $ins['ri_vendor_id']        = $insert['vendor_id'];
            $ins['ri_mailing_address']  = $insert['vendor_address'];
            $ins['ri_vendor_email']     = $insert['vendor_email'];
            $ins['ri_terms_id']         = $insert['vendor_terms'];
            $ins['ri_date']         	= date("Y-m-d", strtotime($insert['transaction_date']));
            $ins['ri_due_date']         = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $ins['ri_remarks']          = $insert['vendor_remarks'] != null ? $insert['vendor_remarks'] : '';
            $ins['ri_memo']             = $insert['vendor_memo'];
            $ins['date_created']		= Carbon::now();
            $ins['ri_discount_type']    = $insert['vendor_discounttype'];
            $ins['ri_discount_value']   = $insert['vendor_discount'];
            $ins['taxable']             = $insert['vendor_tax'];
            $ins['ri_subtotal']         = $insert['vendor_subtotal'];
            $ins['inventory_only']		= 1;
            //$ins['transaction_status']    = 'pending';
            
            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }

            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;

            $ins['ri_subtotal'] = $subtotal_price;
            $ins['ri_total_amount'] = $subtotal_price - $discount + $tax;
            /*INSERT RI HERE*/
            $receive_inventory_id = Tbl_receive_inventory::insertGetId($ins);

            /*INSERT ENTER BILL HERE*/
            $bill = TransactionEnterBills::postInsert($receive_inventory_id, $shop_id, $insert, $insert_item);

            $return = $receive_inventory_id;
            if($bill)
            {
                Self::insertLine($shop_id, $receive_inventory_id, $insert_item, false);
                $_bill[$bill] = $bill;
                TransactionEnterBills::appliedTransaction($shop_id, $bill, $_bill, $receive_inventory_id);
                
            }

        }
        else
        {
            $return = $val;
        }
        return $return;
	}

    public static function postUpdate($receive_inventory_id, $shop_id, $insert, $insert_item)
    {
        $val = AccountingTransaction::vendorValidation($insert, $insert_item, 'receive_inventory', $receive_inventory_id);
        if(!$val)
        {
            $update['ri_shop_id']          = $shop_id;
            $update['transaction_refnum']  = $insert['transaction_refnumber'];
            $update['ri_vendor_id']        = $insert['vendor_id'];
            $update['ri_mailing_address']  = $insert['vendor_address'];
            $update['ri_vendor_email']     = $insert['vendor_email'];
            $update['ri_terms_id']         = $insert['vendor_terms'];
            $update['ri_date']             = date("Y-m-d", strtotime($insert['transaction_date']));
            $update['ri_due_date']         = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $update['ri_remarks']          = $insert['vendor_remarks'] != null ? $insert['vendor_remarks'] : '';
            $update['ri_memo']             = $insert['vendor_memo'];
            $update['date_created']        = Carbon::now();
            $update['ri_discount_type']    = $insert['vendor_discounttype'];
            $update['ri_discount_value']   = $insert['vendor_discount'];
            $update['inventory_only']      = 1;
            $update['taxable']             = $insert['vendor_tax'];

            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            
           /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }

            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            $update['ri_subtotal'] = $subtotal_price;
            //dd($update['po_subtotal_price']);
            $update['ri_total_amount'] = $subtotal_price - $discount + $tax;

            /*INSERT RI HERE*/
            Tbl_receive_inventory::where('ri_id',$receive_inventory_id)->update($update);
            //TransactionReceiveInventory::appliedTransaction($shop_id, $receive_inventory_id, 0 , true, $insert_item);

            Tbl_receive_inventory_line::where('riline_ri_id', $receive_inventory_id)->delete();

            /*INSERT ENTER BILL HERE*/
            $bill = Tbl_bill::where('bill_ri_id', $receive_inventory_id)->first();
            
            if($bill)
            {
                TransactionEnterBills::postUpdate($bill->bill_id, $receive_inventory_id, $shop_id, $insert, $insert_item);

                $_bill[$bill->bill_id] = $bill->bill_id;
                TransactionEnterBills::appliedTransaction($shop_id, $bill, $_bill, $receive_inventory_id);
            }

            $return = Self::insertLine($shop_id, $receive_inventory_id, $insert_item, true);
            $return = $receive_inventory_id;
        }
        else
        {
            $return = $val;
        }
        return $return;
    }

    public static function insertLine($shop_id, $receive_inventory_id, $insert_item, $for_update = '')
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
                $itemline['riline_ri_id']        = $receive_inventory_id;
                $itemline['riline_item_id']      = $value['item_id'];
                $itemline['riline_ref_name']     = $value['item_ref_name'];
                $itemline['riline_ref_id']       = $value['item_ref_id'];
                $itemline['riline_description']  = $value['item_description'];
                $itemline['riline_sub_wh_id']    = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
                $itemline['riline_um']           = $value['item_um'];
                $itemline['riline_qty']          = $value['item_qty'];
                $itemline['riline_rate']         = $value['item_rate'];
                $itemline['riline_amount']       = $value['item_amount'];    
                $itemline['riline_taxable']      = $value['item_taxable'];
                $itemline['riline_discount']     = $discount;
                $itemline['riline_discounttype'] = $discount_type;

                $ri_line_id = Tbl_receive_inventory_line::insert($itemline);   

                array_push($id_not_delete, $ri_line_id);

                $type = Item::get_item_type($value['item_id']);
                // if($type == 1 || $type == 4 || $type == 5 )
                // {
                if($itemline['riline_ref_id'] && $itemline['riline_ref_name'])
                {
                    $qty = Tbl_quantity_monitoring::where('qty_transaction_id', $receive_inventory_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $shop_id)->first();

                    if($qty == null || $for_update == false)
                    {
                        $insert_qty_item['qty_item_id']              = $itemline['riline_item_id'];
                        $insert_qty_item['qty_transaction_id']       = $receive_inventory_id;
                        $insert_qty_item['qty_transaction_name']     = 'receive_inventory';
                        $insert_qty_item['qty_transactionline_id']   = $ri_line_id;
                        $insert_qty_item['qty_ref_id']               = $itemline['riline_ref_id'];
                        $insert_qty_item['qty_ref_name']             = $itemline['riline_ref_name'];
                        $insert_qty_item['qty_old']                  = $itemline['riline_qty'];
                        $insert_qty_item['qty_new']                  = $itemline['riline_qty'];
                        $insert_qty_item['qty_shop_id']              = $shop_id;
                        $insert_qty_item['created_at']               = Carbon::now();
                        Tbl_quantity_monitoring::insert($insert_qty_item);
                    }
                    else
                    {
                        $insert_qty_item['qty_old'] = $qty['qty_new'];
                        $insert_qty_item['qty_new'] = $value['item_qty'];
                        $insert_qty_item['qty_transactionline_id'] = $ri_line_id;
                        Tbl_quantity_monitoring::where('qty_transaction_id', $receive_inventory_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
                    }
                }
                // }
            }

            if($id_not_delete != null)
            {
                Tbl_quantity_monitoring::where("qty_transaction_id", $receive_inventory_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'receive_inventory')->delete();
            }
            $return = $receive_inventory_id;
        }
        return $return;
    }
    public static function appliedTransaction($shop_id, $ri_id, $user_id = 0, $for_update = false)
    {
        if($ri_id != null)
        {
            $applied_transaction = Session::get('applied_transaction');
            if($applied_transaction > 0)
            {
                foreach ($applied_transaction as $key => $value)
                { 
                    AccountingTransaction::checkPolineQty($key, $ri_id, $for_update);
                    AdminNotification::update_notification($shop_id,'purchase_order', $key, $user_id);
                } 
            }  
        }

        Self::insert_acctg_transaction($shop_id, $ri_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_receive_inventory::where("ri_shop_id", $shop_id)->where("ri_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "receive_inventory";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->ri_date;

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