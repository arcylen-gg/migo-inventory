<?php
namespace App\Globals;
use App\Models\Tbl_write_check_account_line;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_write_check_line;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_write_check;
use App\Models\Tbl_customer;
use App\Models\Tbl_vendor;
use App\Models\Tbl_bill;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_quantity_monitoring;

use App\Globals\AccountingTransaction;
use Carbon\Carbon;
use Validator;
use Session;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionWriteCheck
{
    /*public static function transactionStatus($shop_id)
    {
        $get = Tbl_write_check::whereNull('transaction_status')->where("wc_shop_id", $shop_id)->get();
        foreach ($get as $key => $value) 
        {
            $update_status['transaction_status'] = 'posted';
            Tbl_write_check::where("wc_shop_id", $shop_id)->where("wc_id", $value->wc_id)->update($update_status);
        }
    }*/
    public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->wcline_um);
            $total_qty = $value->wcline_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->wcline_um);
            if($value->itemline_ref_name == 'purchase_order')
            {
                $transactionline[$key]->ref = TransactionPurchaseOrder::refnum($this->user_info->shop_id, $value->itemline_ref_id);
            }
            elseif($value->wcline_ref_name == 'bill')
            {
                $data["_wcline"][$key]->ref = TransactionEnterBills::refnum($this->user_info->shop_id, $value->wcline_ref_id);
            }
            else
            {
                $transactionline[$key]->ref = "-";
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
            if($value->wcline_taxable == 1)
            {
                $total_tax += $value->wcline_amount * 0.12;
            }
        }
        return $total_tax;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->wcline_amount;
        }
        return $subtotal;
    }
    public static function total_account_amount($shop_id, $wcaccount)
    {
        if(count($wcaccount) > 0)
        {
            $total_account_amount = 0;
            foreach($wcaccount as $key => $value)
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
	public static function countTransaction($shop_id, $vendor_id)
	{
		return Tbl_bill::where('bill_shop_id',$shop_id)->where('bill_vendor_id', $vendor_id)->where('bill_is_paid', 0)->count();
	}
    public static function info($shop_id, $wc_id)
    {
        return Tbl_write_check::vendor()->customer()->where("wc_shop_id", $shop_id)->where("wc_id", $wc_id)->first();
    }
    public static function info_item($wc_id)
    {
        return Tbl_write_check_line::um()->item()->where("wcline_wc_id", $wc_id)->get();       
    }
    public static function acc_line($wc_id)
    {
        return Tbl_write_check_account_line::account()->where("accline_wc_id", $wc_id)->get();
    } 
    public static function count_tax($wc_id)
    {
        return Tbl_write_check_line::where("wcline_wc_id", $wc_id)->where('wcline_taxable', 1)->count();   
    }
    public static function get_all_customer_vendor($shop_id)
    {
        $_name = Tbl_customer::UnionVendorCustomer($shop_id)->get();
        $return = null;
        foreach ($_name as $key => $value) 
        {
            $return[$key] = $value;
            $return[$key]->ctr_wc = 0;
            if($value->reference == 'vendor')
            {
                $return[$key]->ctr_wc = TransactionPurchaseOrder::countOpenPOTransaction($shop_id, $value->id);
            }
        }

        return $return;
    }
	public static function get($shop_id, $paginate = null, $search_keyword = null)
	{
		$data = Tbl_write_check::vendor()->customer()->where('wc_shop_id', $shop_id)->groupBy("wc_id")->orderBy("wc_payment_date","desc");
		
        $data = AccountingTransaction::acctg_trans($shop_id, $data);
        
		if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {   
                $q->orWhere("vendor_company", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_last_name", "LIKE", "%$search_keyword%");

                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");

                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("wc_id", "LIKE", "%$search_keyword%");
                $q->orWhere("wc_total_amount", "LIKE", "%$search_keyword%");
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
        if($data)
        {
            foreach ($data as $key => $value)
            {
                $data[$key]['vendor_archived'] = 0;
                $data[$key]['customer_archived'] = 0;
                if($value->wc_reference_name == 'vendor')
                {
                    $vendor = Tbl_vendor::where('vendor_id', $value->vendor_id)->where('vendor_shop_id', $shop_id)->first();
                    $data[$key]['vendor_archived'] =  $vendor->archived;
                }
                if ($value->wc_reference_name == 'customer')
                {
                    $customer = Tbl_customer::where('customer_id', $value->customer_id)->where('shop_id', $shop_id)->first();
                    $data[$key]['customer_archived'] =  $customer->archived;
                }

            }
        }
        return $data;
	}
	public static function getAllWC($shop_id)
	{
		$data = Tbl_write_check::where("wc_shop_id", $shop_id)->get();
        
        foreach ($data as $key => $value) 
        {
            $v_data = Tbl_vendor::where("vendor_id",$value->wc_reference_id)->first();

            $name = isset($v_data) ? ($v_data->vendor_company != "" ? $v_data->vendor_company : $v_data->vendor_first_name." ".$v_data->vendor_last_name) : "";
            
            if($value->wc_reference_name == "customer")
            {
                $c_data = Tbl_customer::where("customer_id",$value->wc_reference_id)->first();
                
                $name = isset($c_data) ? ($c_data->company != "" ? $c_data->company : $c_data->first_name." ".$c_data->last_name) : "";
            }

            $data[$key]->name = $name;
        }
		
		return $data;
	}

	public static function postInsert($shop_id, $insert, $insert_item, $insert_acct = array())
	{

		$val = Self::writeCheckValidation($insert, $insert_item, $shop_id, $insert_acct, 'write_check');
		if(!$val)
		{
			$ins['wc_shop_id']				= $shop_id;
			$ins['transaction_refnum']		= $insert['transaction_refnumber'];
			$ins['wc_reference_id']         = $insert['vendor_id'];
	        $ins['wc_reference_name']       = $insert['wc_reference_name'];
	        $ins['wc_customer_vendor_email']= $insert['vendor_email'];
	        $ins['wc_mailing_address']      = $insert['wc_mailing_address'];
            $ins['wc_cash_account_id']      = $insert['wc_cash_account_id'];
	        $ins['wc_cash_account']         = 0;
	        $ins['wc_payment_date']         = date('Y-m-d', strtotime($insert['wc_payment_date']));
	        $ins['wc_memo']                 = $insert['wc_memo'];
            $ins['wc_remarks']              = $insert['wc_remarks'];
	        $ins['date_created']            = Carbon::now();
            $ins['wc_discount_type']        = $insert['vendor_discounttype'];
            $ins['wc_discount_value']       = $insert['vendor_discount'];
            $ins['taxable']                 = $insert['vendor_tax'];
            $ins['taxable']                 = $insert['vendor_tax'];
            //$ins['transaction_status']      = 'pending';

            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            $total_acct = collect($insert_acct)->sum('account_amount');

            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
            
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            /*ACCOUNT TOTAL*/
            $ins['wc_subtotal'] = $subtotal_price;
            $ins['wc_total_amount'] = $subtotal_price - $discount + $total_acct + $tax;

	        /*INSERT CV HERE*/
	        $write_check_id = Tbl_write_check::insertGetId($ins);

	        /* Transaction Journal */
	        $entry["reference_module"]  = "write-check";
	        $entry["reference_id"]      = $write_check_id;
            $entry["account_id"]        = $ins['wc_cash_account_id'];
	        $entry["name_id"]           = $insert['vendor_id'];
	        $entry["name_reference"]    = $insert['wc_reference_name'];
	        $entry["total"]             = $subtotal_price;
	        $entry["vatable"]           = $tax;
	        $entry["discount"]          = $discount;
	        $entry["ewt"]               = '';

	        $return = Self::insertLine($write_check_id, $insert_item, $entry, $insert_acct, false);
	        $return = $write_check_id;

            /* $settings_auto_post_transaction = AccountingTransaction::settings($shop_id, 'auto_post_transaction');
            if($settings_auto_post_transaction == 1)
            {
                $update_status['transaction_status'] = 'posted';
                Tbl_write_check::where('wc_id', $write_check_id)->update($update_status);*/

                $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
                AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'write_check', $write_check_id, 'Refill upon creating WRITE CHECK '.$ins['transaction_refnum']);
            /*}*/
		}
		else
		{
			$return = $val;
		}

        return $return;
	}
    public static function postUpdate($write_check_id, $shop_id, $insert, $insert_item, $insert_acct = array())
    {
        $val = Self::writeCheckValidation($insert, $insert_item, $shop_id, $insert_acct);
        if(!$val)
        {
            $ins['wc_shop_id']              = $shop_id;
            $ins['transaction_refnum']      = $insert['transaction_refnumber'];
            $ins['wc_reference_id']         = $insert['vendor_id'];
            $ins['wc_reference_name']       = $insert['wc_reference_name'];
            $ins['wc_customer_vendor_email']= $insert['vendor_email'];
            $ins['wc_cash_account_id']      = $insert['wc_cash_account_id'];
            $ins['wc_mailing_address']      = $insert['wc_mailing_address'];
            $ins['wc_cash_account']         = 0;
            $ins['wc_payment_date']         = date('Y-m-d', strtotime($insert['wc_payment_date']));
            $ins['wc_memo']                 = $insert['wc_memo'];
            $ins['wc_remarks']              = $insert['wc_remarks'];
            $ins['date_created']            = Carbon::now();
            $ins['wc_discount_type']        = $insert['vendor_discounttype'];
            $ins['wc_discount_value']       = $insert['vendor_discount'];
            $ins['taxable']                 = $insert['vendor_tax'];

            $subtotal_price = collect($insert_item)->sum('item_amount'); 
            
            /*ACCOUNT TOTAL*/
            $total_acct = collect($insert_acct)->sum('account_amount');
            
            /* DISCOUNT */
            $discount = $insert['vendor_discount'] != "" || $insert['vendor_discount'] != NULL ? $insert['vendor_discount'] : 0;
            if($insert['vendor_discounttype'] == 'percent')
            {
                $discount = (convertToNumber($insert['vendor_discount']) / 100) * ($subtotal_price);
            }
            
            /*INPUT VAT*/
            $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;
            
            $ins['wc_subtotal'] = $subtotal_price;
            $ins['wc_total_amount'] = $subtotal_price - $discount + $total_acct + $tax;

           
            /*INSERT CV HERE*/
            Tbl_write_check::where('wc_id', $write_check_id)->update($ins);

            /* Transaction Journal */
            $entry["reference_module"]  = "write-check"/*.$insert['wc_reference_name']*/;
            $entry["reference_id"]      = $write_check_id;
            $entry["account_id"]        = $ins['wc_cash_account_id'];
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["name_reference"]    = $insert['wc_reference_name'];
            $entry["total"]             = $subtotal_price;
            $entry["vatable"]           = $tax;
            $entry["discount"]          = $discount;
            $entry["ewt"]               = '';

            Tbl_write_check_line::where('wcline_wc_id', $write_check_id)->delete();
            Tbl_write_check_account_line::where('accline_wc_id', $write_check_id)->delete();

            $return = Self::insertLine($write_check_id, $insert_item, $entry, $insert_acct, true, $shop_id);
            $return = $write_check_id;

            
            /* UPDATE INVENTORY HERE */
            $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
            /*AccountingTransaction::inventory_refill_update($shop_id, $warehouse_id, $insert_item, 'write_check', $write_check_id); 
            AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'write_check', $write_check_id, 'Refill upon creating WRITE CHECK '.$ins['transaction_refnum']);*/
            Warehouse2::inventory_get_consume_data($shop_id, $warehouse_id, $insert_item, 'write_check', $write_check_id, 'Refill upon creating WRITE CHECK '.$ins['transaction_refnum']);
        }
        else
        {
            $return = $val;
        }

        return $return;
    }
    
	public static function insertLine($write_check_id, $insert_item, $entry, $insert_acct = array(), $for_update = '', $shop_id='')
    {
        $return = null;
        if(count($insert_acct) > 0)
        {
            $acct_line = null;
            foreach ($insert_acct as $key_acct => $value_acct)
            {
                if($value_acct)
                {
                    $acct_line[$key_acct]['accline_wc_id']       = $write_check_id;
                    $acct_line[$key_acct]['accline_coa_id']      = $value_acct['account_id'];
                    $acct_line[$key_acct]['accline_description'] = $value_acct['account_desc'];
                    $acct_line[$key_acct]['accline_amount']      = $value_acct['account_amount'];

                    $entry_data['a'.$key_acct]['account_id']        = $value_acct['account_id'];
                    $entry_data['a'.$key_acct]['entry_description'] = $value_acct['account_desc'];
                    $entry_data['a'.$key_acct]['entry_amount']      = $value_acct['account_amount'];
                    $entry_data['a'.$key_acct]['vatable']           = 0;
                    $entry_data['a'.$key_acct]['discount']          = 0;
                }
            } 

            Tbl_write_check_account_line::insert($acct_line); 
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
                //die(var_dump($value['item_ref_id']));
                $itemline['wcline_wc_id']       = $write_check_id;
                $itemline['wcline_item_id']     = $value['item_id'];
                $itemline['wcline_ref_id']      = $value['item_ref_id'] != NULL ? $value['item_ref_id'] : 0;
                $itemline['wcline_ref_name']    = $value['item_ref_name'] != NULL ? $value['item_ref_name'] : '';
                $itemline['wcline_description'] = $value['item_description'];
                $itemline['wcline_um']          = $value['item_um'];
                $itemline['wcline_qty']         = $value['item_qty'];
                $itemline['wcline_rate']        = $value['item_rate'];
                $itemline['wcline_amount']      = $value['item_amount'];   
                $itemline['wcline_taxable']      = $value['item_taxable'];
                $itemline['wcline_discount']     = $discount;
                $itemline['wcline_discounttype'] = $discount_type;
                $itemline['wcline_sub_wh_id']    = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;

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
                $itemline_id = Tbl_write_check_line::insert($itemline);

                array_push($id_not_delete, $itemline_id);

                $type = Item::get_item_type($value['item_id']);
                if($type == 1 || $type == 4 || $type == 5 )
                {
                    if($itemline['wcline_ref_id'] && $itemline['wcline_ref_name'])
                    {
                        $qty = Tbl_quantity_monitoring::where('qty_transaction_id', $write_check_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $user_info->shop_id)->first();

                        if($qty == null || $for_update == false)
                        {
                            $insert_qty_item['qty_item_id']              = $itemline['wcline_item_id'];
                            $insert_qty_item['qty_transaction_id']       = $write_check_id;
                            $insert_qty_item['qty_transaction_name']     = 'write_check';
                            $insert_qty_item['qty_transactionline_id']   = $itemline_id;
                            $insert_qty_item['qty_ref_id']               = $itemline['wcline_ref_id'];
                            $insert_qty_item['qty_ref_name']             = $itemline['wcline_ref_name'];
                            $insert_qty_item['qty_old']                  = $itemline['wcline_qty'];
                            $insert_qty_item['qty_new']                  = $itemline['wcline_qty'];
                            $insert_qty_item['qty_shop_id']              = $shop_id;
                            $insert_qty_item['created_at']               = Carbon::now();
                            Tbl_quantity_monitoring::insert($insert_qty_item);
                        }
                        else
                        {
                            $insert_qty_item['qty_old'] = $qty->qty_new;
                            $insert_qty_item['qty_new'] = $value['item_qty'];
                            $insert_qty_item['qty_transactionline_id'] = $itemline_id;
                            Tbl_quantity_monitoring::where('qty_transaction_id', $write_check_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
                        }
                    }
                }
            }
            if($id_not_delete != null)
            {
                Tbl_quantity_monitoring::where("qty_transaction_id", $write_check_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'write_check')->delete();
            }

            $return = $write_check_id;
        }
        return $return;
    }
    public static function writeCheckValidation($insert, $insert_item, $shop_id, $insert_acct, $transaction_type = '')
    {
        $return = null;
        if(!$insert['vendor_id'])
        {
            $return .= '<li style="list-style:none">Please Select Vendor.</li>';          
        }
        if(count($insert_acct) <= 0 && count($insert_item) <= 0)
        {
            $return .= '<li style="list-style:none">Please Select Item or Account.</li>';          
        }
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
    /*public static function checkPolineQty($po_id, $wc_id, $for_update = false)
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();
        $ctr = 0;
        foreach ($poline as $key => $value)
        {
            $monitoring_qty = tbl_quantity_monitoring::where('qty_transaction_id', $wc_id)->where('qty_item_id', $value->poline_item_id)->where('qty_ref_name', 'purchase_order')->where('qty_ref_id',$po_id)->first();
            $remaining = $value->poline_qty -$monitoring_qty->qty_new;
            if($for_update)
            {
                $remaining = $value->poline_qty - ($monitoring_qty->qty_new - $monitoring_qty->qty_old);
            }
            $update['poline_qty'] = $remaining;   
            Tbl_purchase_order_line::where('poline_id', $value->poline_id)->update($update);    

            if($update['poline_qty'] <= 0)
            {
                $ctr++;
            }
            if($value->poline_qty < $value->poline_orig_qty)
            {
                $update_po['po_is_billed'] = 0;
                Tbl_purchase_order::where("po_id",$po_id)->update($update_po);
            }
        }
        if($ctr >= count($poline))
        {
            $updates["po_is_billed"] = $wc_id;
            Tbl_purchase_order::where("po_id",$po_id)->update($updates);
        }
    }*/
    public static function appliedTransaction($shop_id, $wc_id, $for_update = false)
    {
        if($wc_id != null)
        {
            $applied_transaction = Session::get('applied_transaction');
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value)
                { 
                    if($wc_id)
                    {

                        AccountingTransaction::checkPolineQty($key, $wc_id, $for_update);
                    }
                } 
            }  

            $cmapplied_transaction = Session::get("applied_transaction_cm");
            if(count($cmapplied_transaction) > 0)
            {
                foreach ($cmapplied_transaction as $keycm => $valuecm)
                {
                    Self::update_cm($keycm, $wc_id);
                }                
            }
        }
        Self::insert_acctg_transaction($shop_id, $wc_id, $applied_transaction, $cmapplied_transaction);
    }
    public static function update_cm($cm_id, $wc_id)
    {
        if($cm_id && $wc_id)
        {
            $update['cm_used_ref_name'] = "write_check";
            $update['cm_used_ref_id'] = $wc_id;
            $update['cm_status'] = 1;
            Tbl_credit_memo::where("cm_id",$cm_id)->update($update);
        }
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array(), $cmapplied_transaction = array())
    {
        $get_transaction = Tbl_write_check::where("wc_shop_id", $shop_id)->where("wc_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "write_check";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->wc_payment_date;

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
            if(count($cmapplied_transaction) > 0)
            {
                foreach ($cmapplied_transaction as $keycm => $valuecm) 
                {
                    $get_data = Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_id", $keycm)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$keycm."cm"]['transaction_ref_name'] = "credit_memo";
                        $attached_transaction_data[$keycm."cm"]['transaction_ref_id'] = $keycm;
                        $attached_transaction_data[$keycm."cm"]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$keycm."cm"]['transaction_date'] = $get_data->cm_date;
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