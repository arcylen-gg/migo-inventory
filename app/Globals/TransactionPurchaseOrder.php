<?php
namespace App\Globals;

use App\Models\Tbl_receive_inventory_line;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_admin_notification;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_shop;
use App\Models\Tbl_bill;
use App\Models\Tbl_bill_po;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_quantity_monitoring;
use App\Globals\Warehouse2;
use Carbon\Carbon;
use Session;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionPurchaseOrder
{
  
    public static function getPo($shop_id, $from, $to, $year = false)
    {
        $return = Tbl_purchase_order::where("po_shop_id", $shop_id);
                                    // ->whereBetween("tbl_purchase_order.po_date",[$from, $to])       
        if($year)
        {
            $return = $return->whereYear("tbl_purchase_order.po_date","=",date('Y',strtotime($from)));
        }
        else
        {
            $return = $return->whereMonth("tbl_purchase_order.po_date","=",date('m',strtotime($from))) 
                             ->whereYear("tbl_purchase_order.po_date","=",date('Y',strtotime($from)));
        }
        return $return->sum("po_overall_price");
    }
    public static function getAp($shop_id, $from, $to, $year = false)
    {        
        $return = Tbl_bill::where("bill_shop_id", $shop_id);
                          // ->whereBetween("bill_date",[$from, $to]);
        if($year)
        {
            $return = $return->whereYear("bill_date","=",date('Y',strtotime($from)));
        }
        else
        {
            $return = $return->whereMonth("bill_date","=",date('m',strtotime($from))) 
                             ->whereYear("bill_date","=",date('Y',strtotime($from)));
        }
        $return = $return->get();
        $ap = 0;
        foreach ($return as $key => $value) 
        {
            $ap += $value->bill_total_amount - $value->bill_applied_payment;
        }
        return $ap;
    }
    public static function getRi($shop_id, $from, $to, $year = false)
    {  
        $_po = Tbl_purchase_order::where("po_shop_id", $shop_id)
                                 ->where("po_is_billed","!=",0);
                                 // ->whereBetween("tbl_purchase_order.po_date",[$from, $to])
        if($year)
        {
            $_po = $_po->whereYear("tbl_purchase_order.po_date","=",date('Y',strtotime($from)));
        }
        else
        {
            $_po = $_po->whereMonth("tbl_purchase_order.po_date","=",date('m',strtotime($from))) 
                       ->whereYear("tbl_purchase_order.po_date","=",date('Y',strtotime($from)));
        }
        $_po = $_po->get();
        $ri = 0;                                    
        foreach ($_po as $key => $value) 
        {
            $ri += Tbl_bill_item_line::where("itemline_ref_name","purchase_order")
                                      ->where("itemline_ref_id", $value->po_id)
                                      ->sum("itemline_amount");
        }
        return $ri;
    }  
    public static function getPb($shop_id, $from, $to, $year = false)
    {      
        $return = Tbl_pay_bill::where("paybill_shop_id", $shop_id);
                                // ->whereBetween("paybill_date",[$from, $to]);
        if($year)
        {
            $return = $return->whereYear("paybill_date","=",date('Y',strtotime($from)));
        }
        else
        {
            $return = $return->whereMonth("paybill_date","=",date('m',strtotime($from))) 
                             ->whereYear("paybill_date","=",date('Y',strtotime($from)));
        }
        // $pb = 0;
        // foreach ($return as $key => $value) 
        // {
        //     $pb += $value->bill_applied_payment;
        // }
        return $return->sum('paybill_total_amount');
    }
    public static function get_total_amount($shop_id, $tab)
    {
        $check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $po = Tbl_purchase_order::where("po_shop_id",$shop_id);
        
        $total = 0;

        if($tab == 'all')
        {
            $po = $po->get();
        }
        elseif($tab == 'closed')
        {
            $po = $po->where("po_is_billed","!=", 0)->get();
        }
        elseif($tab == 'open')
        {
            $po = $po->where("po_is_billed", 0)->get();
        }
        if(count($po) > 0)
        {
            foreach ($po as $key => $value) 
            {
               $total += $value->po_overall_price;
            }
        } 
        if($check_allow_transaction == 1)
        {
            $po = Tbl_purchase_order::where("po_shop_id",$shop_id);
            $po = AccountingTransaction::acctg_trans($shop_id, $po);

            $total = null;
            $data = null;
            
            if($tab == 'all')
            {
                $po = $po->get();
            }
            elseif($tab == 'closed')
            {
                $po = $po->where("po_is_billed","!=", 0)->get();
            }
            elseif($tab == 'open')
            {
                $po = $po->where("po_is_billed", 0)->get();
            }

            if(count($po) > 0)
            {
                foreach ($po as $key_po => $value_po)
                {
                    $data[$value_po->po_id] = $value_po->po_overall_price;
                }
                foreach ($data as $key => $value)
                {
                    $total += $value;
                }
            }
        }      
        return $total;
    }
    public static function get_open_po_total_amount($shop_id)
    {
        $price = 0;
        $po = Tbl_purchase_order::where("po_shop_id",$shop_id)->where("po_is_billed",0)->get();
        if(isset($po))
        {
            foreach ($po as $key => $value) 
            {
               $price += $value->po_overall_price;
            }            
        }

        return $price;
    }
    public static function count_open_po($shop_id)
    {
         return Tbl_purchase_order::where("po_shop_id", $shop_id)->where("po_is_billed",0)->count();
    }
    public static function get_total_amount_perwh($shop_id, $warehouse_id)
    {
        $price = 0;
        $po = Tbl_purchase_order::perWarehouse("po_shop_id",$shop_id)
                                ->where("po_is_billed",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->get();
        if(isset($po))
        {
            foreach ($po as $key => $value) 
            {
               $price += $value->po_overall_price;
            }            
        }

        return $price;
    }
    public static function count_perwh($shop_id, $warehouse_id)
    {
         return Tbl_purchase_order::perWarehouse("po_shop_id", $shop_id)->where("po_is_billed",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
	public static function countTransaction($shop_id)
	{
        return Tbl_requisition_slip::where('shop_id',$shop_id)->where("requisition_slip_status","open")->count();
    }

    public static function countOpenPOTransaction($shop_id, $vendor_id)
    {
        return Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed', 0)->count();
    }
    public static function info($shop_id, $po_id)
    {
        return Tbl_purchase_order::vendor()->where("po_shop_id", $shop_id)->where("po_id", $po_id)->first();
    }
    public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->poline_um);

            $total_qty = $value->poline_orig_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->poline_um);
        }
        return $transactionline;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->poline_amount;
        }
        return $subtotal;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->taxable == 1)
            {
                $total_tax += $value->poline_amount * 0.12;
            }
        }
        return $total_tax;
    }
    public static function refnum($shop_id, $po_id)
    {
        return Tbl_purchase_order::where("po_shop_id", $shop_id)->where('po_id', $po_id)->value('transaction_refnum');
    }
    public static function info_item($po_id)
    {
        $data =  Tbl_purchase_order_line::um()->item()->where("poline_po_id", $po_id)->get();  
        foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->estline_um);
            $total_qty = $value->poline_orig_qty * $qty;
            $received = ($value->poline_orig_qty - $value->poline_qty) * $qty;
            $backorder = $value->poline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
            $data[$key]->received = UnitMeasurement::um_view($received,$value->item_measurement_id,$value->estline_um);
            $data[$key]->backorder = UnitMeasurement::um_view($backorder,$value->item_measurement_id,$value->estline_um);
        }      
        return $data;
    }
    public static function count_tax($po_id)
    {
        return Tbl_purchase_order_line::where('poline_po_id', $po_id)->where('taxable', 1)->count();   
    }
    public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
    {
        $data = Tbl_purchase_order::Vendor()->where('po_shop_id',$shop_id)->groupBy("po_id")->orderBy("po_date","desc");
        
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
                $q->orWhere("po_id", "LIKE", "%$search_keyword%");
                $q->orWhere("po_overall_price", "LIKE", "%$search_keyword%");
            });
        }
        if($status != 'all')
        {
            if($status == 'open')
            {
                $data->where('po_is_billed', 0);
            
            }
            if($status == 'closed')
            {
                $data->where('po_is_billed',"!=",'0');
            }            
        }

        if($paginate)
        {
            $data = $data->paginate($paginate);
        }
        else
        {
            $data = $data->first();
        }

        return $data;
    }

    public static function getClosePO($shop_id, $vendor_id)
    {
        return Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed','!=', '0')->get();
    }

    public static function getOpenPO($shop_id, $vendor_id)
    {
        $data = Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed', 0)->get();

        foreach ($data as $key => $value)
        {
            $data[$key]->po_balance = Self::getPOBalance($shop_id, $vendor_id, $value->po_id);
        }

        return $data;
    }
    public static function getPOBalance($shop_id, $vendor_id, $po_id)
    {        
        $data = Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed', 0)->where('po_id', $po_id)->first();

        $po_line = Tbl_purchase_order_line::where('poline_po_id', $data->po_id)->where('poline_item_status', 0)->get();
        
        $balance = null;
        $poline_amount = null;
        foreach ($po_line as $key => $value)
        {
            $orig_qty = $value->poline_orig_qty != 0 ? $value->poline_orig_qty : 1;
            if($data->po_discount_value != 0 || $data->po_discount_value != '')
            {
                if($data->po_discount_type == 'percent')//
                {
                    $po_disc = ($data->po_discount_value / 100) * $value->poline_amount;

                    //PER ITEM LESS DISCOUNT PER PO
                    $po_discount_per_item = $po_disc / $orig_qty;

                    //PER ITEM RATE LESS PER ITEM DISCOUNT
                    $rate = $value->poline_amount / $orig_qty;

                    if($value->taxable == '1')
                    {
                        //PER ITEM TAX AMOUNT
                        $tax_per_item = ($value->poline_amount * 0.12)  / $orig_qty;

                        //PER ITEM RATE - LESS DISCOUNT + TAX
                        $new_rate = $rate - $po_discount_per_item + $tax_per_item;
                        
                        
                        $balance = $new_rate * $value->poline_qty;

                    }
                    elseif($value->taxable == '0')
                    {
                        //PER ITEM RATE - LESS DISCOUNT
                        $new_rate = $rate - $po_discount_per_item;

                        $balance = $new_rate * $value->poline_qty;
                    }
                }
                elseif($data->po_discount_type == 'value')
                {
                    /*DISCOUNT PER PO_LINE_AMOUNT*/
                    $po_disc = ($data->po_discount_value / $data->po_subtotal_price) * $value->poline_amount;

                    /*DISCOUNT PER ITEM*/
                    $po_discount_per_item = $po_disc / $orig_qty;

                    //PER ITEM RATE LESS PER ITEM DISCOUNT
                    $rate = $value->poline_amount / $orig_qty;

                    if($value->taxable == '1')
                    {
                        /*TAX AMOUNT PER ITEM*/
                        $tax_per_item = ($value->poline_amount * 0.12) / $orig_qty;

                        /*DISCOUNTED PRICE PER ITEM + TAX */
                        $new_rate = ($rate - $po_discount_per_item) + $tax_per_item;
                        
                        /*DISCOUNTED AMOUNT + TAX PER PO_LINE*/
                        $balance = $new_rate * $value->poline_qty;
                    }
                    elseif($value->taxable == '0')
                    {
                        //PER ITEM RATE - LESS DISCOUNT + TAX
                        $new_rate = $rate - $po_discount_per_item ;
                        
                        /*DISCOUNTED AMOUNT PER PO_LINE*/
                        $balance = $new_rate * $value->poline_qty;
                    }
                }
                $poline_amount += $balance;
            }
            else
            {
                if($value->taxable == '1')
                {

                    $tax_per_item = ($value->poline_amount * 0.12)  / $orig_qty;
                    $balance = $tax_per_item * $value->poline_qty + $value->poline_amount;
                }
                elseif($value->taxable == '0')
                {
                    $per_item = $value->poline_amount / $orig_qty;
                    $balance = $per_item * $value->poline_qty;
                }

                $poline_amount += $balance;
            }

        }
        return $poline_amount;
    }
    public static function postInsert($shop_id, $insert, $insert_item)
	{
        $val = AccountingTransaction::vendorValidation($insert, $insert_item, 'purchase_order');
        if(!$val)
        {
            $ins['po_shop_id']         = $shop_id;
            $ins['transaction_refnum'] = $insert['transaction_refnumber'];
            $ins['po_vendor_id']       = $insert['vendor_id'];
            $ins['po_billing_address'] = $insert['vendor_address'];
            $ins['po_vendor_email']    = $insert['vendor_email'];
            $ins['po_terms_id']        = $insert['vendor_terms'];
            $ins['po_date']            = date("Y-m-d", strtotime($insert['transaction_date']));
            $ins['po_due_date']        = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $ins['po_delivery_date']   = date("Y-m-d", strtotime($insert['transaction_deliverydate']));
            $ins['po_message']         = $insert['vendor_message'];
            $ins['po_memo']            = $insert['vendor_memo'];
            $ins['ewt']                = $insert['vendor_ewt'];
            $ins['po_terms_id']        = $insert['vendor_terms'];
            $ins['po_discount_value']  = $insert['vendor_discount'];
            $ins['po_discount_type']   = $insert['vendor_discounttype'];
            $ins['taxable']            = $insert['vendor_tax'];
            $ins['date_created']       = Carbon::now();
            
             /* SUBTOTAL */
            $subtotal_price = collect($insert_item)->sum('item_amount'); 

            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }

            /* TAX */
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            /* OVERALL TOTAL */
            $overall_price  = convertToNumber($subtotal_price) - $discount + $tax;

            $ins['po_subtotal_price'] = $subtotal_price;
            $ins['po_overall_price']  = $overall_price;

            /* INSERT PO IN DATABASE */
            $purchase_order_id = Tbl_purchase_order::insertGetId($ins);
        
            $return = Self::insertline($purchase_order_id, $insert_item);


            $return = $purchase_order_id;
		}
        else
        {
            $return = $val;
        }  

        return $return;
	}
    public static function postUpdate($po_id, $shop_id, $insert, $insert_item)
    {
        $old = Tbl_purchase_order::where("po_id", $po_id);

        $val = AccountingTransaction::vendorValidation($insert, $insert_item);
        if(!$val)
        {
            $update['po_shop_id']         = $shop_id;
            $update['transaction_refnum'] = $insert['transaction_refnumber'];
            $update['po_vendor_id']       = $insert['vendor_id'];
            $update['po_billing_address'] = $insert['vendor_address'];
            $update['po_vendor_email']    = $insert['vendor_email'];
            $update['po_date']            = date("Y-m-d", strtotime($insert['transaction_date']));
            $update['po_due_date']        = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $update['po_delivery_date']   = date("Y-m-d", strtotime($insert['transaction_deliverydate']));
            $update['po_message']         = $insert['vendor_message'];
            $update['po_memo']            = $insert['vendor_memo'];
            $update['ewt']                = $insert['vendor_ewt'];
            $update['po_terms_id']        = $insert['vendor_terms'];
            $update['po_discount_value']  = $insert['vendor_discount'];
            $update['po_discount_type']   = $insert['vendor_discounttype'];
            $update['taxable']            = $insert['vendor_tax'];
            $update['date_created']       = Carbon::now();
            
             /* SUBTOTAL */
            $subtotal_price = collect($insert_item)->sum('item_amount'); 

            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }

            /* TAX */
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            /* OVERALL TOTAL */
            $overall_price  = convertToNumber($subtotal_price) - $discount + $tax;
            
            $update['po_subtotal_price'] = $subtotal_price;
            $update['po_overall_price']  = $overall_price;

            /*UPDATE PO IN DATABASE */
            Tbl_purchase_order::where("po_id", $po_id)->update($update);
            
            Tbl_purchase_order_line::where("poline_po_id", $po_id)->delete();
            Self::insertLine($po_id, $insert_item, $shop_id, true);

            $return = $po_id;
        }
        else
        {
            $return = $val;
        }  

        return $return;
    } 
    public static function update_transaction_status($po_id) 
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();
        $ref = Tbl_quantity_monitoring::where('qty_ref_name', 'purchase_order')->where('qty_ref_id', $po_id)->orderBy('qty_monitoring_id', 'DESC')->first();
        if($poline)
        {
            $item_status=null;
            foreach ($poline as $key => $value)
            {
                $item_status += $value->poline_item_status;
            }
            if(count($poline) == $item_status && $ref)
            {
                $update_po['po_is_billed'] = $ref->qty_transaction_id;
            }
            elseif(count($poline) == $item_status)
            {
                $update_po['po_is_billed'] = $item_status;
            }   
            else
            {
                $update_po['po_is_billed'] = 0;
            }
            Tbl_purchase_order::where("po_id", $po_id)->update($update_po);
        }
    }
    public static function insertLine($purchase_order_id, $insert_item, $shop_id = '', $for_update = '')
    {
        $return = null;

        $itemline = null;
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

            /*FROM DATABASE*/                        /*FROM CONTROLLER*/
            $itemline[$key]['poline_po_id']          = $purchase_order_id;
            $itemline[$key]['poline_service_date']   = $value['item_servicedate']; 
            $itemline[$key]['poline_item_id']        = $value['item_id'];
            $itemline[$key]['poline_description']    = $value['item_description'];
            $itemline[$key]['poline_sub_wh_id']      = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
            $itemline[$key]['poline_um']             = $value['item_um'];
            if($for_update && $value['item_ref_id'])
            {
                $sales_order_id = $value['item_ref_id'];
                $received_qty = Warehouse2::update_remaining_qty($sales_order_id, $value['item_id'], $user_info->shop_id);
                $itemline[$key]['poline_orig_qty']         = $value['item_qty'];
                $itemline[$key]['poline_received_qty']     = $received_qty;
                $itemline[$key]['poline_qty']              = $value['item_qty'] - $received_qty;
            }
            else
            {
                $itemline[$key]['poline_qty']              = $value['item_remaining'];
                $itemline[$key]['poline_orig_qty']         = $value['item_qty'];
                $itemline[$key]['poline_received_qty']     = $value['item_qty'] - $value['item_remaining'];
            }
            $itemline[$key]['poline_item_status']    = $value['item_status'];
            $itemline[$key]['poline_rate']           = $value['item_rate'];
            $itemline[$key]['poline_discount']       = $discount;
            $itemline[$key]['poline_discounttype']   = $discount_type;
            $itemline[$key]['poline_discount_remark']= $value['item_remark'];    
            $itemline[$key]['taxable']               = $value['item_taxable']; 
            $itemline[$key]['poline_amount']         = $value['item_amount'];
            $itemline[$key]['poline_refname']        = $value['item_ref_name'];   
            $itemline[$key]['poline_refid']          = $value['item_ref_id']; 
            $itemline[$key]['date_created']          = Carbon::now();
        }
        if(count($itemline) > 0)
        {
            /*INSERTING ITEMS TO DATABASE*/
            $return = Tbl_purchase_order_line::insert($itemline);
        }

        return $return;
    }
    public static function applied_transaction($shop_id, $transaction_id = 0)
    {
        $applied_transaction = Session::get('applied_transaction');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                if($value == 'sales_order')
                {
                    $update_so['est_status'] = 'closed';
                    Tbl_customer_estimate::where("est_id", $key)->where('est_shop_id', $shop_id)->update($update_so);
                }
                elseif($value == 'invoice')
                {
                    $update_inv['replenished'] = '1';
                    Tbl_customer_invoice::where("inv_id", $key)->where('inv_shop_id', $shop_id)->update($update_inv);
                }
            }
        }

        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_purchase_order::where("po_shop_id", $shop_id)->where("po_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "purchase_order";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->po_date;

            $attached_transaction_data = null;
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value) 
                {
                    $get_data = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $key)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$key]['transaction_ref_name'] = "sales_order";
                        $attached_transaction_data[$key]['transaction_ref_id'] = $key;
                        $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$key]['transaction_date'] = $get_data->est_date;
                    }
                }
            }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }
    public static function insert_notification()
    {
        $_shop = DB::table("tbl_shop")->get();

        foreach ($_shop as $keyshop => $valueshop) 
        {
            $check = AccountingTransaction::settings($valueshop->shop_id, "notification_bar");

            if($check)
            {
                $datenow = date('Y-m-d',strtotime(Carbon::now()->subDays(1)));
                $date_three = date('Y-m-d',strtotime(Carbon::now()->addDays(3)));
                $get = Tbl_purchase_order::acctg_trans()
                                        ->warehouse()
                                        ->where("po_shop_id", $valueshop->shop_id)
                                        ->where("po_delivery_date","<=", $date_three)
                                        ->where("po_is_billed",0)
                                        ->groupBy("tbl_purchase_order.po_id")
                                        ->get();
                $insert = null;
                foreach ($get as $key => $value) 
                {
                    $check = Tbl_admin_notification::where("transaction_refname",'purchase_order')
                                                   ->where("transaction_refid", $value->po_id)
                                                   ->where("notification_shop_id", $valueshop->shop_id)
                                                   ->first();
                    if(!$check)
                    {
                        $insert[$key]['notification_shop_id'] = $valueshop->shop_id;
                        $insert[$key]['warehouse_id'] = $value->transaction_warehouse_id;
                        $insert[$key]['notification_description'] = 'The <strong>"'.$value->warehouse_name.'"</strong> is about to receive <strong>Purchase Order No. "'.$value->transaction_refnum.'"</strong> on '.date("F d, Y",strtotime($value->po_delivery_date));
                        $insert[$key]['transaction_refname'] = "purchase_order";
                        $insert[$key]['transaction_refid'] = $value->po_id;
                        $insert[$key]['transaction_status'] = "pending";
                        $insert[$key]['transaction_date'] = $value->po_delivery_date;
                        $insert[$key]['created_date'] = Carbon::now();
                    }
                }
                if(count($insert) > 0)
                {
                    Tbl_admin_notification::insert($insert);
                }
            }
        }
    }
    public static function getBalancePerPO($shop_id, $po_id)
    {        
        $data = Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_id', $po_id)->first();

        $po_line = Tbl_purchase_order_line::where('poline_po_id', $data->po_id)->get();
        
        $balance = null;
        $poline_amount = null;
        foreach ($po_line as $key => $value)
        {
            $po_orig_qty = $value->poline_orig_qty < 1 ? 1 : $value->poline_orig_qty;
            if($data->po_discount_value != 0 || $data->po_discount_value != '')
            {
                if($value->poline_qty != 0)
                {
                    if($data->po_discount_type == 'percent')//
                    {
                        //PER ITEM LESS DISCOUNT PER PO
                        $po_disc = ($data->po_discount_value / 100) * $value->poline_amount;
                        //dd($po_disc);

                        $po_discount_per_item = $po_disc / $value->poline_orig_qty;

                        //PER ITEM RATE LESS PER ITEM DISCOUNT
                        $rate = $value->poline_amount / $value->poline_orig_qty;

                        if($value->taxable == '1')
                        {
                            //PER ITEM TAX AMOUNT
                            $tax_per_item = ($value->poline_amount * 0.12)  / $value->poline_orig_qty;

                            //PER ITEM RATE - LESS DISCOUNT + TAX
                            $new_rate = $rate - $po_discount_per_item + $tax_per_item;
                            
                            
                            $balance = $new_rate * $value->poline_qty;

                        }
                        elseif($value->taxable == '0')
                        {
                            //PER ITEM RATE - LESS DISCOUNT
                            $new_rate = $rate - $po_discount_per_item;

                            $balance = $new_rate * $value->poline_qty;
                        }
                    }
                    elseif($data->po_discount_type == 'value')
                    {
                        /*DISCOUNT PER PO_LINE_AMOUNT*/
                        $po_disc = ($data->po_discount_value / $data->po_subtotal_price) * $value->poline_amount;

                        /*DISCOUNT PER ITEM*/
                        $po_discount_per_item = $po_disc / $value->poline_orig_qty;

                        //PER ITEM RATE LESS PER ITEM DISCOUNT
                        $rate = $value->poline_amount / $value->poline_orig_qty;

                        if($value->taxable == '1')
                        {
                            /*TAX AMOUNT PER ITEM*/
                            $tax_per_item = ($value->poline_amount * 0.12) / $value->poline_orig_qty;

                            /*DISCOUNTED PRICE PER ITEM + TAX */
                            $new_rate = ($rate - $po_discount_per_item) + $tax_per_item;
                            
                            /*DISCOUNTED AMOUNT + TAX PER PO_LINE*/
                            $balance = $new_rate * $value->poline_qty;
                        }
                        elseif($value->taxable == '0')
                        {
                            //PER ITEM RATE - LESS DISCOUNT + TAX
                            $new_rate = $rate - $po_discount_per_item ;
                            
                            /*DISCOUNTED AMOUNT PER PO_LINE*/
                            $balance = $new_rate * $value->poline_qty;
                        }
                    }
                    $poline_amount += $balance;
                }
                else
                {
                    $poline_amount = 0;
                }
            }
            else
            {
                if($value->taxable == '1')
                {
                    $tax_per_item = ($value->poline_amount * 0.12)  / $po_orig_qty;

                    $balance = $tax_per_item * $value->poline_qty + $value->poline_amount;
                }
                elseif($value->taxable == '0')
                {
                    $per_item = $value->poline_amount / $po_orig_qty;
                    $balance = $per_item * $value->poline_qty;
                }

                $poline_amount += $balance;
            }

        }
        //dd($poline_amount);
        return $poline_amount;
    }
}
