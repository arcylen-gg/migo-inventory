<?php
namespace App\Globals;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_bill;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_vendor;
use App\Models\Tbl_quantity_monitoring;
use App\Globals\AccountingTransaction;
use App\Globals\Warehouse2;
use App\Globals\Item;
use Carbon\Carbon;
use Validator;
use Session;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionEnterBills
{
    public static function info_vendor($shop_id, $bill_id)
    {
        return Tbl_bill_item_line::vendor()->where("tbl_bill.bill_shop_id", $shop_id)->where("tbl_bill.bill_id", $bill_id)->first();
    }

    public static function get_info_item($bill_id)
    {
        return Tbl_bill_item_line::um()->item()->where("itemline_bill_id", $bill_id)->get();        
    }
    public static function getBill($shop_id, $vendor_id)
    {
        $data = Tbl_bill::where('bill_shop_id',$shop_id)->where('bill_vendor_id', $vendor_id)->orderby('bill_id','desc')->groupBy('bill_id')
        ->select('bill_id','transaction_refnum','bill_total_amount','taxable','bill_discount_value','bill_discount_type')->get();

        // $data['item_line'] = Tbl_bill::join('tbl_bill_item_line','tbl_bill_item_line.itemline_bill_id','=','tbl_bill.bill_id')
        // ->where('bill_shop_id',$shop_id)->where('bill_vendor_id', $vendor_id)->orderby('bill_id','desc')->get();
        
        foreach ($data as $key => $value)
        {
            //bill related data
            //tbl_bill
            //$param_bill['bill_id'] = $value->bill_id;
            $param_bill['transaction_discount'] = $value->bill_discount_type;
            $param_bill['transaction_discount_value'] = ($value->bill_discount_value);
            $param_bill['transaction_tax'] = $value->taxable;

            $data[$key]['item_line'] = Tbl_bill_item_line::where('itemline_bill_id',$value->bill_id)->get();

            $discount_total = 0;
            $sub_total = 0;
            $tax_total = 0;

            foreach ($data[$key]['item_line']  as $key_itemline => $value_itemline) 
            {
                $param_bill['tax'] = 0.12;

                //item related data
                //tbl_bill_item_line
                $param_bill['item_discount_type']= $value_itemline->itemline_discounttype;
                $param_bill['item_discount']= $value_itemline->itemline_discount;
                $param_bill['item_tax'] = $value_itemline->itemline_taxable;

                $param_bill['item_qty'] =   $value_itemline->itemline_orig_qty != 0 ? $value_itemline->itemline_orig_qty : 1;
                $param_bill['item_rate'] =  $value_itemline->itemline_rate;

                // $data[$key]['item_line'][$key_itemline]['computation_result'] = Self::bill_computation($param_bill);//total of computation
                $data[$key]['item_line'][$key_itemline]['computation_result'] = Self::item_computation($param_bill);//total of computation


                $total = $data[$key]['item_line'][$key_itemline]['computation_result'];//total of computation

                $sub_total += $total['comp_amount'];
                $discount_total += $total['comp_transaction_discount'];
                $tax_total += $total['comp_amount_tax'];

            }
            // dd($param_bill);

            //SUB TOTAL AMOUNT PER BILL
            $data[$key]['item_line']['total_item_computation'] = $sub_total;

            //TOTAL AMOUNT * DISCOUNT
            if($param_bill['transaction_discount'] == 'percent')
            {
                $data[$key]['item_line']['total_discount'] = $discount_total;
            }
            else
            {
                $data[$key]['item_line']['total_discount'] = $sub_total - $param_bill['transaction_discount_value'];
            }
            //TOTAL AMOUNT * TAX
            $data[$key]['item_line']['total_tax'] = $tax_total;

            //TOTAL AMOUNT PER BILL
            $data[$key]['item_line']['total_bill'] = $tax_total + $discount_total;

            // dd($data[$key]['item_line']);   
        }
// dd($data);
        return $data;
    }

    public static function bill_computation($param)
    {
        $param['sub_total'] += $param['comp_amount'];
        $param['discount_total'] += $param['comp_transaction_discount'];
        $param['tax_total'] += $param['comp_amount_tax'];
        $param['item_total'] += $param['item_orig_qty'];
        return $param;
    }

    public static function item_computation($param)
    {
        $param['comp_rateXqty'] = $param['item_qty'] * $param['item_rate']; 

        switch ($param['item_discount_type']) 
        {
            case 'percent':

                    $param['comp_transaction_discount'] = $param['comp_rateXqty'] * $param['item_discount'];

                    $param['comp_amount'] = $param['comp_rateXqty'] - $param['comp_transaction_discount'];

                    $param['comp_amount_tax'] = Self::tax_computation($param['item_tax'],$param['comp_amount'],$param['tax']);

                    $param['comp_discount_per_item'] = ($param['comp_amount'] + $param['comp_amount_tax'])  / $param['item_qty'];


                    return $param;

                break;
            
            case 'fixed':

                    $param['comp_transaction_discount'] = $param['item_discount'];

                    $param['comp_amount'] = $param['comp_rateXqty'] - $param['comp_transaction_discount'];

                    $param['comp_amount_tax'] = Self::tax_computation($param['item_tax'],$param['comp_amount'],$param['tax']);

                    $param['comp_discount_per_item'] = ($param['comp_amount'] + $param['comp_amount_tax'])  / $param['item_qty'];

                    return $param;

                break;  

            default:
                # code...
                break;
        }
    }

    public static function tax_computation($IsTaxable,$AmountWithoutTax,$tax_rate)
    {
        if($IsTaxable == 1)
        {
            $AmountWithoutTax = $AmountWithoutTax * $tax_rate;
        }
        else
        {
            $AmountWithoutTax = 0;
        }

        return $AmountWithoutTax;
    }

    public static function countOpenBillTransaction($shop_id, $vendor_id)
    {
        return Tbl_bill::where('bill_shop_id',$shop_id)->where('bill_vendor_id', $vendor_id)->count();
    }

    public static function get_reports_amount($param)
    {
        switch ($param['get_total_param'])
        {
            case 'sum_qty':
            $query_data = Tbl_bill::PurchasedByQueries_with_where($param)->sum('itemline_qty');
            return $query_data;
            break;

            case 'sum_amount':
            $query_data = Tbl_bill::PurchasedByQueries_with_where($param)->sum(DB::raw('itemline_qty * itemline_rate'));
            return $query_data;
            break;

            case 'sum_qty_detailed':
            $query_data = collect(Tbl_bill::PurchasedByQueries_with_where($param)->groupby('tbl_bill.transaction_refnum')->get())->sum('itemline_qty');
            return $query_data;

            case 'sum_amount_detailed':
            $query_data = Tbl_bill::PurchasedByQueries_with_where($param)->groupby('tbl_bill.transaction_refnum')->sum(DB::raw('itemline_qty * itemline_rate'));
              // $query_data = Tbl_bill::PurchasedByQueries_with_where($param)->groupby('tbl_bill.transaction_refnum')->get();
            return $query_data;
        }

    }

    public static function get_total_amount($shop_id, $tab)
    {
        $check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $bill = Tbl_bill::where("bill_shop_id",$shop_id);
        
        $total = 0;
        if($tab == 'all')
        {
            $bill = $bill->get();
        }
        elseif($tab == 'closed')
        {
            $bill = $bill->where("bill_is_paid",1)->get();
        }
        if(count($bill) > 0)
        {
            foreach ($bill as $key => $value) 
            {
               $total += $value->bill_total_amount;
            }
        }
        if($tab == 'open')
        {
            $bill = $bill->where("bill_is_paid", 0)->get();
            if(count($bill) > 0)
            {
                foreach ($bill as $key => $value) 
                {
                    $total += $value->bill_total_amount - $value->bill_applied_payment;
                }
            }
        }     
        if($check_allow_transaction == 1)
        {
            $bill = Tbl_bill::where("bill_shop_id",$shop_id);
            $bill = AccountingTransaction::acctg_trans($shop_id, $bill);

            $total = null;
            $data = null;
            if($tab == 'all')
            {
                $bill = $bill->get();
                if(count($bill) > 0)
                {
                    foreach ($bill as $key_bill => $value_bill)
                    {
                        $data[$value_bill->bill_id] = $value_bill->bill_total_amount;
                    }
                    foreach ($data as $key => $value)
                    {
                        $total += $value;
                    }
                }
            }
            elseif($tab == 'closed')
            {
                $bill = $bill->where("bill_is_paid",1)->get();
                if(count($bill) > 0)
                {
                    foreach ($bill as $key_bill => $value_bill)
                    {
                        $data[$value_bill->bill_id] = $value_bill->bill_total_amount;
                    }
                    foreach ($data as $key => $value)
                    {
                        $total += $value;
                    }
                }
            }
            elseif($tab == 'open')
            {
                $bill = $bill->where("bill_is_paid", 0)->get();
                if(count($bill) > 0)
                {
                    foreach ($bill as $key_bill => $value_bill)
                    {
                        $data[$value_bill->bill_id] = $value_bill->bill_total_amount - $value_bill->bill_applied_payment;
                    }
                    foreach ($data as $key => $value)
                    {
                        $total += $value;
                    }
                }
            }      
        } 
        return $total;
    }    
    public static function get_total_amount_perwh($shop_id, $tab, $warehouse_id)
    {
        $bill = Tbl_bill::billwarehouse()
                ->where('bill_shop_id' ,$shop_id)
                ->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id);
        
        
        return $total;
    }
    public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->itemline_um);
            $total_qty = $value->itemline_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->itemline_um);
            $transactionline[$key]->ref = "-";
            if($value->itemline_ref_name == 'purchase_order')
            {
                $transactionline[$key]->ref = TransactionPurchaseOrder::refnum($shop_id, $value->itemline_ref_id);
            }
        }
        return $transactionline;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->itemline_taxable == 1)
            {
                $total_tax += $value->itemline_amount * 0.12;
            }
        }
        return $total_tax;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->itemline_amount;
        }
        return $subtotal;
    }
    public static function countTransaction($shop_id, $vendor_id)
    {
        return Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_vendor_id', $vendor_id)->where('po_is_billed',0)->count();
    }
    public static function refnum($shop_id, $bill_id)
    {
        return Tbl_bill::where("bill_shop_id", $shop_id)->where('bill_id', $bill_id)->value('transaction_refnum');
    }
    public static function info($shop_id, $bill_id)
    {
        return Tbl_bill::vendor()->where("bill_shop_id", $shop_id)->where("bill_id", $bill_id)->first();
    }
    public static function account_line($bill_id)
    {
        return Tbl_bill_account_line::where("accline_bill_id",$bill_id)->get();
    }
    public static function total_account_amount($shop_id, $ebaccount)
    {
        if(count($ebaccount) > 0)
        {
            $total_account_amount = 0;
            foreach($ebaccount as $key => $value)
            {
                $total_account_amount += $value->accline_amount;
            }
        }
        else
        {
            $total_account_amount = 0;
        }
        return $total_account_amount;
    }
    public static function account($shop_id, $bill_id)
    {
        return Tbl_bill::account()->where("bill_shop_id", $shop_id)->where("bill_id", $bill_id)->get();
    }
    public static function count_account($shop_id, $bill_id)
    {
        return Tbl_bill::account()->where("bill_shop_id", $shop_id)->where("bill_id", $bill_id)->count();
    }
    public static function info_item($bill_id)
    {
        return Tbl_bill_item_line::um()->item()->where("itemline_bill_id", $bill_id)->get();        
    }
    /*public static function info_account($bill_id)
    {
        return Tbl_bill_account_line::where("accline_bill_id",$bill_id)->get();
    }*/
    public static function count_tax($po_id)
    {
        return Tbl_bill_item_line::where('itemline_bill_id', $po_id)->where('itemline_taxable', 1)->count();   
    }
   /* public static function ref($po_id)
    {
        return Tbl_bill_item_line::ref()->where("tbl_bill_item_line.itemline_ref_name", 'purchase_order')->where('tbl_bill_item_line.itemline_ref_id', $po_id)->first();      
    } */
    public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
    {
        $data = Tbl_bill::vendor()->where('bill_shop_id', $shop_id)->groupBy("bill_id")->orderBy("bill_date","desc");
       
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
                $q->orWhere("bill_id", "LIKE", "%$search_keyword%");
                $q->orWhere("bill_total_amount", "LIKE", "%$search_keyword%");
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
            $data->where('bill_is_paid',$tab);

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
    public static function postInsert($ri_id, $shop_id, $insert, $insert_item, $insert_acct = array())
    {
        $val = null;
        if(!$ri_id)
        {
           $val = Self::enterBillValidation($insert, $insert_item, $shop_id, 'enter_bills');
        }            

        if(!$val)
        {
            $ins['bill_shop_id']          = $shop_id;
            $ins['bill_ri_id']            = $ri_id;
            $ins['transaction_refnum']    = $insert['transaction_refnumber'];
            $ins['bill_vendor_id']        = $insert['vendor_id'];
            $ins['bill_mailing_address']  = $insert['vendor_address'];
            $ins['bill_vendor_email']     = $insert['vendor_email'];
            $ins['bill_terms_id']         = $insert['vendor_terms'];
            $ins['bill_date']             = date("Y-m-d", strtotime($insert['transaction_date']));
            $ins['bill_due_date']         = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $ins['bill_memo']             = $insert['vendor_memo'];
            $ins['bill_remarks']          = $insert['vendor_remarks'];
            $ins['date_created']          = Carbon::now();
            $ins['bill_discount_type']    = $insert['vendor_discounttype'];
            $ins['bill_discount_value']   = $insert['vendor_discount'];
            $ins['taxable']               = $insert['vendor_tax'];
            $ins['inventory_only']        = 0;
            //$ins['transaction_status']    = 'pending';

            $subtotal_price = collect($insert_item)->sum('item_amount'); 

             /*ACCOUNT TOTAL*/
            $total_acct = collect($insert_acct)->sum('account_amount');
            
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
            /*TOTAL*/
            $ins['bill_subtotal'] = $subtotal_price;
            $ins['bill_total_amount'] = $subtotal_price + $total_acct - $discount + $tax;
        
            /*INSERT RI HERE*/
            $enter_bills_id = Tbl_bill::insertGetId($ins);
            
            /* Transaction Journal */
            $entry["reference_module"]  = "bill";
            $entry["reference_id"]      = $enter_bills_id;
            $entry["name_reference"]    ='vendor';
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["total"]             = $subtotal_price;
            $entry["vatable"]           = $tax;
            $entry["discount"]          = $discount;
            $entry["ewt"]               = '';

            /*$settings_auto_post_transaction = AccountingTransaction::settings($shop_id, 'auto_post_transaction');
            if($settings_auto_post_transaction == 1)
            {
                $update_bill_status['transaction_status'] = 'posted';
                Tbl_bill::where('bill_id', $enter_bills_id)->update($update_bill_status);*/
                $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
                if(!$ri_id) // ENTER BILL
                {
                    AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'enter_bills', $enter_bills_id, 'Refill upon creating ENTER BILLS'.$ins['transaction_refnum']);
                }
                else // RECEIVE INVENTORY
                {
                    /*$update_ri_status['transaction_status'] = 'posted';
                    Tbl_receive_inventory::where('ri_id', $ri_id)->update($update_ri_status);*/
                    AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'receive_inventory', $ri_id, 'Refill upon RECEIVING INVENTORY '.$ins['transaction_refnum']);
                }  
           /* }*/
                      
            Self::insertLine($enter_bills_id, $insert_item, $entry, $insert_acct, false);
            $return = $enter_bills_id;
        }
        else
        {
            $return = $val;
        }

        return $return;
    }

    public static function postUpdate($enter_bills_id, $ri_id, $shop_id, $insert, $insert_item, $insert_acct = array())
    {
        $val = null;
        if(!$ri_id)
        {
            $val = Self::enterBillValidation($insert, $insert_item, $shop_id, null, $enter_bills_id);
        }
    
        if(!$val)
        {
            $update['bill_shop_id']          = $shop_id;
            $update['bill_ri_id']            = $ri_id;
            $update['transaction_refnum']    = $insert['transaction_refnumber'];
            $update['bill_vendor_id']        = $insert['vendor_id'];
            $update['bill_mailing_address']  = $insert['vendor_address'];
            $update['bill_vendor_email']     = $insert['vendor_email'];
            $update['bill_terms_id']         = $insert['vendor_terms'];
            $update['bill_date']             = date("Y-m-d", strtotime($insert['transaction_date']));
            $update['bill_due_date']         = date("Y-m-d", strtotime($insert['transaction_duedate']));
            $update['bill_memo']             = $insert['vendor_memo'];
            $insert['bill_remarks']          = $insert['vendor_remarks'];
            $update['date_created']          = Carbon::now();
            $update['inventory_only']        = 0;
            $update['bill_discount_type']    = $insert['vendor_discounttype'];
            $update['bill_discount_value']   = $insert['vendor_discount'];
            $update['taxable']               = $insert['vendor_tax'];

            $subtotal_price = collect($insert_item)->sum('item_amount'); 

             /*ACCOUNT TOTAL*/
            $total_acct = collect($insert_acct)->sum('account_amount');
            
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
            
            /*TOTAL*/
            $update['bill_subtotal'] = $subtotal_price;
            $update['bill_total_amount'] = $subtotal_price + $total_acct - $discount + $tax;

            /*INSERT RI HERE*/
            Tbl_bill::where('bill_id', $enter_bills_id)->update($update);
            
            /* Transaction Journal */
            $entry["reference_module"]  = "bill";
            $entry["reference_id"]      = $enter_bills_id;
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["total"]             = $subtotal_price;
            $entry["vatable"]           = $tax;
            $entry["discount"]          = $discount;
            $entry["ewt"]               = '';

            Tbl_bill_item_line::where("itemline_bill_id", $enter_bills_id)->delete();
            Tbl_bill_account_line::where("accline_bill_id", $enter_bills_id)->delete();

            $return = Self::insertLine($enter_bills_id, $insert_item, $entry, $insert_acct, true, $shop_id);
            $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
            /* UPDATE INVENTORY HERE */
            if(!$ri_id) // ENTER BILL
            { 
                //AccountingTransaction::inventory_refill_update($shop_id, $warehouse_id, $insert_item, 'enter_bills', $enter_bills_id); 
                Warehouse2::inventory_get_consume_data($shop_id, $warehouse_id, $insert_item, 'enter_bills', $enter_bills_id, 'Refill upon creating ENTER BILLS'.$update['transaction_refnum']);

            }
            else // RECEIVE INVENTORY
            {
                Warehouse2::inventory_get_consume_data($shop_id, $warehouse_id, $insert_item, 'receive_inventory', $ri_id, 'Refill upon RECEIVING INVENTORY '.$update['transaction_refnum']);
            }
            $return = $enter_bills_id;
        }
        else
        {
            $return = $val;
        }

        return $return;
    }


    public static function insertLine($enter_bills_id, $insert_item, $entry, $insert_acct = array(), $for_update = '', $shop_id = '')
    {
        $return = null;
        if(count($insert_acct) > 0)
        {
            $acct_line = null;
            foreach ($insert_acct as $key_acct => $value_acct)
            {
                $acct_line[$key_acct]['accline_bill_id']     = $enter_bills_id;
                $acct_line[$key_acct]['accline_coa_id']      = $value_acct['account_id'];
                $acct_line[$key_acct]['accline_description'] = $value_acct['account_desc'];
                $acct_line[$key_acct]['accline_amount']      = $value_acct['account_amount'];

                $entry_data['a'.$key_acct]['account_id']        = $value_acct['account_id'];
                $entry_data['a'.$key_acct]['entry_description'] = $value_acct['account_desc'];
                $entry_data['a'.$key_acct]['entry_amount']      = $value_acct['account_amount'];
                $entry_data['a'.$key_acct]['vatable']           = 0;
                $entry_data['a'.$key_acct]['discount']          = 0;
            }  
            Tbl_bill_account_line::insert($acct_line);
        }


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

                $itemline['itemline_bill_id']      = $enter_bills_id;
                $itemline['itemline_ref_id']       = $value['item_ref_id'] != '' ? $value['item_ref_id'] : 0;
                $itemline['itemline_ref_name']     = $value['item_ref_name'] != '' ? $value['item_ref_name'] : 0;
                $itemline['itemline_item_id']      = $value['item_id'];
                $itemline['itemline_description']  = $value['item_description'];
                $itemline['itemline_sub_wh_id']    = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
                $itemline['itemline_um']           = $value['item_um'];
                $itemline['itemline_qty']          = $value['item_qty'];
                $itemline['itemline_rate']         = $value['item_rate'];
                $itemline['itemline_amount']       = $value['item_amount'];   
                $itemline['itemline_taxable']        = $value['item_taxable'];
                $itemline['itemline_discount']       = $discount;
                $itemline['itemline_discounttype']   = $discount_type;

                $item_type = Item::get_item_type($value['item_id']);
                /* TRANSACTION JOURNAL */  
                if($item_type != 4 && $item_type != 5)
                {
                    $entry_data[$key]['item_id']            = $value['item_id'];
                    $entry_data[$key]['entry_qty']          = $value['item_qty'];
                    $entry_data[$key]['vatable']            = 0;
                    $entry_data[$key]['discount']           = 0;
                    $entry_data[$key]['entry_amount']       = $value['item_amount'];
                    $entry_data[$key]['entry_description']  = $value['item_description'];
                                       
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
                            $entry_data['b'.$key.$key_bundle]['entry_qty']          = $value['item_qty'] * (UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                            $entry_data['b'.$key.$key_bundle]['vatable']            = 0;
                            $entry_data['b'.$key.$key_bundle]['discount']           = 0;
                            $entry_data['b'.$key.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key.$key_bundle]['entry_qty'];
                            $entry_data['b'.$key.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                        }
                    }
                }

                $itemline_id = Tbl_bill_item_line::insert($itemline);

                array_push($id_not_delete, $itemline_id);
                $type = Item::get_item_type($value['item_id']);
                // if($type == 1 || $type == 4 || $type == 5 )
                // {
                    if($itemline['itemline_ref_id'] && $itemline['itemline_ref_name'])
                    {
                        $qty = Tbl_quantity_monitoring::where('qty_transaction_id', $enter_bills_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $shop_id)->first();

                        if($qty == null || $for_update == false)
                        {

                            $insert_qty_item['qty_item_id']              = $itemline['itemline_item_id'];
                            $insert_qty_item['qty_transaction_id']       = $enter_bills_id;
                            $insert_qty_item['qty_transaction_name']     = 'enter_bills';
                            $insert_qty_item['qty_transactionline_id']   = $itemline_id;
                            $insert_qty_item['qty_ref_id']               = $itemline['itemline_ref_id'];
                            $insert_qty_item['qty_ref_name']             = $itemline['itemline_ref_name'];
                            $insert_qty_item['qty_old']                  = $itemline['itemline_qty'];
                            $insert_qty_item['qty_new']                  = $itemline['itemline_qty'];
                            $insert_qty_item['qty_shop_id']              = $shop_id;
                            $insert_qty_item['created_at']               = Carbon::now();
                            Tbl_quantity_monitoring::insert($insert_qty_item);
                        }
                        else
                        {
                            $insert_qty_item['qty_old'] = $qty->qty_new;
                            $insert_qty_item['qty_new'] = $value['item_qty'];
                            $insert_qty_item['qty_transactionline_id'] = $itemline_id;
                            Tbl_quantity_monitoring::where('qty_transaction_id', $enter_bills_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
                        }
                    }
                // }
            }
            if($id_not_delete != null)
            {
                Tbl_quantity_monitoring::where("qty_transaction_id", $enter_bills_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'enter_bills')->delete();
            }

            Accounting::postJournalEntry($entry, $entry_data);        
            $return = $enter_bills_id;
        }
        return $return;
    }
    public static function enterBillValidation($insert, $insert_item, $shop_id, $transaction_type = '', $transaction_id = '')
    {
        $return = null;
        if(!$insert['vendor_id'])
        {
            $return .= '<li style="list-style:none">Please Select Vendor.</li>';          
        }

        /*if(count($insert_item) > 0)
        {
            foreach ($insert_item as $key => $value)
            {
                if($transaction_id)
                {
                    $remaining_qty = Self::get_old_qty($transaction_id, $value['item_ref_name'], $value['item_ref_id'], $value['item_id']);
                    dd($value['item_qty']." ".$remaining_qty);
                    if($value['item_qty'] > $remaining_qty)
                    {
                        $return .= "<li style='list-style:none'>Item ". $value['item_description']."'s quantity not match to PO!</li>";
                    }
                }
                else
                {
                    $poline = Tbl_purchase_order_line::where('poline_po_id', $value['item_ref_id'])->where("poline_item_id", $value['item_id'])->first();
                    $poline_qty = 0;
                    if($poline)
                    {
                        $poline_qty = UnitMeasurement::get_umqty($poline->poline_um_id) * $poline->poline_qty;

                        if($value['item_qty'] > $poline_qty)
                        {
                            $return .= "<li style='list-style:none'>Item ". $value['item_description']."'s quantity not match to PO!</li>";
                        }
                    }
                }
            }
        }*/

        if($transaction_type)
        {
            $return .= AccountingTransaction::check_transaction_ref_number($shop_id, $insert['transaction_refnumber'], $transaction_type);
        }

        $rules['transaction_refnumber'] = 'required';
        $rules['vendor_email']          = 'email';

        $validator = Validator::make($insert, $rules);
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li><br>') as $keys => $message)
            {
                $return .= $message;
            }
        }
        return $return;
    }


    public static function checkPolineQty($po_id, $eb_id, $for_update)
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();

        $ctr = 0;
        foreach ($poline as $key => $value)
        {
            if($insert_item)
            {
                foreach ($insert_item as $key_insert_item => $value_insert_item)
                {
                    $remaining = $value->poline_qty;
                    $update['poline_qty'] = $remaining - $value_insert_item['item_qty'];   
                    if($for_update)
                    {
                        $remaining = Self::get_itemline($insert_item, $eb_id);
                        $update['poline_qty'] = $value->poline_qty - ($value_insert_item['item_qty'] - $remaining);
                    }
                    Tbl_purchase_order_line::where('poline_id', $value->poline_id)->update($update);    

                    $poline_qty = $update['poline_qty'];
                    if($poline_qty <= 0)
                    {
                        $ctr++;
                    }
                    if($value->poline_qty < $value->poline_orig_qty)
                    {
                        $update_po['po_is_billed'] = 0;
                        Tbl_purchase_order::where("po_id",$po_id)->update($update_po);
                    }
                }
            }
        }
        if($ctr >= count($poline))
        {
            $updates["po_is_billed"] = $eb_id;
            Tbl_purchase_order::where("po_id",$po_id)->update($updates);
        }
    }
    public static function appliedTransaction($shop_id, $eb_id, $ri_applied = array(), $ri = null, $for_update = false)
    {
        if($eb_id != null)
        {
            $applied_transaction = Session::get('applied_transaction');
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value)
                { 
                    if(!$ri)
                    {
                        AccountingTransaction::checkPolineQty($key, $eb_id, $for_update);
                    }
                } 
            }
        }
        Self::insert_acctg_transaction($shop_id, $eb_id, $applied_transaction, $ri_applied);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array(), $ri_applied = array())
    {
        $get_transaction = Tbl_bill::where("bill_shop_id", $shop_id)->where("bill_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "enter_bills";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->bill_date;

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
            if(count($ri_applied) > 0)
            {
                foreach ($ri_applied as $keyri => $valueri)
                {
                    $get_ri = Tbl_receive_inventory::where("ri_id", $keyri)->where("ri_shop_id", $shop_id)->first();
                    if($get_ri)
                    {
                        $attached_transaction_data[$keyri.'ri']['transaction_ref_name'] = "receive_inventory";
                        $attached_transaction_data[$keyri.'ri']['transaction_ref_id'] = $keyri;
                        $attached_transaction_data[$keyri.'ri']['transaction_list_number'] = $get_ri->transaction_refnum;
                        $attached_transaction_data[$keyri.'ri']['transaction_date'] = $get_ri->ri_date;                        
                    }
                }
            }
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
        }
    }
    public static function get_item_vendor($shop_id, $item_id, $date_from = null, $date_to = null)
    {
        $_vendor = Tbl_vendor::where("vendor_shop_id", $shop_id)->get();
        $return = null;
        foreach ($_vendor as $key => $value) 
        {
            $get_item = Tbl_bill::item_line()
                                ->where("bill_vendor_id", $value->vendor_id)
                                ->where("itemline_item_id", $item_id)
                                ->where("bill_shop_id", $shop_id);
            if($date_from && $date_to)
            {
                $get_item = $get_item->whereBetween("bill_date",[$date_from, $date_to]);
            }
            $get_item = $get_item->get();

            $total_amount_purchase = 0;
            foreach ($get_item as $keys => $values) 
            {
                $qty = UnitMeasurement::um_qty($values->itemline_um) * $values->itemline_qty;
                $total_amount_purchase += $qty * $values->itemline_rate; 
            }
            if(count($get_item) > 0)
            {
                $return[$key]['vendor_name'] = $value->vendor_company != "" ? $value->vendor_company : $value->vendor_first_name ." ".$value->vendor_middle_name." ".$value->vendor_last_name;
                $return[$key]['times_purchase'] = count($get_item)." Times";
                $return[$key]['total_amount_purchase'] = $total_amount_purchase;                
            }

        }

        /*PARAM
            Vendor
            Times Purchase
            Total Amount Perchase
        */
        return $return;
    }
}