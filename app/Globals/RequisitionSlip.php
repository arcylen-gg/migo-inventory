<?php
namespace App\Globals;
use App\Models\Tbl_shop;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_requisition_slip_item;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_item;
use App\Models\Tbl_admin_notification;
use App\Globals\AccountingTransaction;
use App\Globals\Warehouse2;
use Carbon\Carbon;
use Validator;
use Session;
use Request;
use DB;

/**
 * Requisition Slip
 *
 * @author Arcylen
 */
 
class RequisitionSlip
{
    public static function get_total_amount($shop_id, $tab)
    {
        $check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');

        $pr = Tbl_requisition_slip::where("tbl_requisition_slip.shop_id",$shop_id);

        $total = 0;
        
        if($tab != 'all')
        {
            $pr = $pr->where("requisition_slip_status", $tab)->get();
        }
        else 
        {
            $pr = $pr->get();
        }
        if(count($pr) > 0)
        {
            foreach ($pr as $key => $value) 
            {
               $total += $value->total_amount;
            }  
        }

        if($check_allow_transaction == 1)
        {
            $pr = Tbl_requisition_slip::where("tbl_requisition_slip.shop_id",$shop_id);
            $pr = AccountingTransaction::acctg_trans($shop_id, $pr);

            $total = null;
            $data = null;
            
            if($tab != 'all')
            {
                $pr = $pr->where("requisition_slip_status", $tab)->get();
            }
            else
            {
                $pr = $pr->get();
            }
            if(count($pr) > 0)
            {
                foreach ($pr as $key_pr => $value_pr)
                {
                    $data[$value_pr->requisition_slip_id] = $value_pr->total_amount;
                }
                foreach ($data as $key => $value)
                {
                    $total += $value;
                }
            }
        }
        return $total;
    }
    public static function get_open_pr_total_amount($shop_id)
    {
        $price = 0;
        $pr = Tbl_requisition_slip::where("shop_id",$shop_id)
                                ->where("requisition_slip_status", 'open')->get();
        if(isset($pr))
        {
            foreach ($pr as $key => $value) 
            {
               $price += $value->total_amount;
            }            
        }
        return $price;
    }
    public static function count_open_pr($shop_id)
    {
         return Tbl_requisition_slip::where("shop_id", $shop_id)->where("requisition_slip_status", 'open')->count();
    }
    public static function get_total_amount_perwh($shop_id, $warehouse_id)
    {
        $price = 0;
        $pr = Tbl_requisition_slip::PerWarehouse("shop_id",$shop_id)
                                ->where("requisition_slip_status", 'open')->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->get();
        if(isset($pr))
        {
            foreach ($pr as $key => $value) 
            {
               $price += $value->total_amount;
            }            
        }

        return $price;
    }
    public static function count_perwh($shop_id, $warehouse_id)
    {
         return Tbl_requisition_slip::PerWarehouse("shop_id", $shop_id)->where("requisition_slip_status", 'open')->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
    public static function get($shop_id, $status = null, $paginate = null, $search_keyword = null)
    {
        $data = Tbl_requisition_slip::where('tbl_requisition_slip.shop_id', $shop_id)
                                    ->groupBy("requisition_slip_id")
                                    ->orderBy("requisition_slip_date","DESC");
      
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
                $q->orWhere("requisition_slip_id", "LIKE", "%$search_keyword%");
                $q->orWhere("rs_item_amount", "LIKE", "%$search_keyword%");
            });
        }

        if($status != 'all')
        {
            $data = $data->where('requisition_slip_status',$status);
        }
        if($paginate)
        {
            $data = $data->paginate($paginate);
            //dd($paginate);
        }
        else
        {
            $data = $data->get();
        }
        return $data;
    }
    public static function get_slip($shop_id, $slip_id)
    {
        return Tbl_requisition_slip::where('shop_id',$shop_id)->where('requisition_slip_id', $slip_id)->first();
    }
    public static function get_slip_item($slip_id, $warehouse_id)
    {
        $data = Tbl_requisition_slip_item::vendor()->um()->item()->where('rs_id', $slip_id)->get();
        foreach($data as $key => $value) 
        {
            $data[$key]->invty_count = Tbl_item::recordloginventory($warehouse_id)->where('item_id', $value->rs_item_id)->value('inventory_count');
        }
        //die(var_dump($data));
        return $data;
    }
    public static function update_status($shop_id, $slip_id, $update)
    {
        return Tbl_requisition_slip::where('shop_id',$shop_id)->where('requisition_slip_id', $slip_id)->update($update);
    }
    public static function countTransaction($shop_id, $vendor_id)
    {
        /*$count_so = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_status","accepted")->where('is_sales_order', 1)->count();
        $count_pr = Tbl_requisition_slip_item::PRInfo('shop_id',$shop_id)->where("requisition_slip_status","closed")->count();
        
        $return = $count_so + $count_pr;*/
        return Tbl_requisition_slip_item::PRInfo('shop_id',$shop_id)->where("requisition_slip_status","closed")->count();
    }
    public static function check_pr_vendor($pr_id)
    {
        $validate = null;
        $get = Tbl_requisition_slip_item::where("rs_id", $pr_id)->get();
        foreach ($get as $key => $value) 
        {
            if($value->rs_vendor_id == "" )
            {
                $validate .= 'Some item in this PR have no vendor, Kindly select a vendor to be able to generate Purchase Order.<br> ';
            }
        }
        return $validate;
    }
    public static function create_po($pr_id, $shop_id)
    {
        $pr = Tbl_requisition_slip::where('requisition_slip_id', $pr_id)->first();
        $prline = Tbl_requisition_slip_item::where('rs_id', $pr_id)->get();

        $vendor = null;
        foreach ($prline as $value)
        {
            $vendor[$value['rs_vendor_id']][] = $value;
        }

        $poline = null;
        $applied_transaction[$pr_id] = $pr_id;
        $asd = [];
        foreach ($vendor as $key => $value)
        {
            $ins['po_date'] = $pr->requisition_slip_date;
            $ins['po_vendor_id'] = $key;
            $vendata = Vendor::vendor_data($key);
            $ins['po_billing_address'] = $vendata ? $vendata->ven_billing_street." ".$vendata->ven_billing_city : '' ;
            $ins['transaction_refnum'] = AccountingTransaction::get_ref_num($pr->shop_id, 'purchase_order');;
            $ins['po_shop_id'] = $pr->shop_id;
            $ins['date_created'] = Carbon::now();
            $po_id = Tbl_purchase_order::insertGetId($ins);
            Self::create_po_line($pr_id, $key, $po_id);
            /*FOR APPLIED TRANSACTION*/
            if(count($applied_transaction) > 0)
            {
                Self::insert_acctg_transaction_po($shop_id, $po_id, $applied_transaction);
                $transaction_data = AccountingTransaction::audit_trail($shop_id, $po_id, 'purchase_order');
                AuditTrail::record_logs('Added', 'purchase_order', $po_id, "", serialize($transaction_data));
            }
        }
    }
    public static function create_po_line($pr_id, $vendor_id, $po_id)
    {
        $data_line = Tbl_requisition_slip_item::where('rs_id', $pr_id)->where('rs_vendor_id', $vendor_id)->get();

        $poline = null;
        foreach ($data_line as $key_line => $value_line)
        {
            $po_line[$key_line]['poline_po_id']       = $po_id;
            $po_line[$key_line]['poline_item_id']     = $value_line->rs_item_id;
            $po_line[$key_line]['poline_description'] = $value_line->rs_item_description;
            $po_line[$key_line]['poline_orig_qty']    = $value_line->rs_item_qty;
            $po_line[$key_line]['poline_qty']         = $value_line->rs_item_qty;
            $po_line[$key_line]['poline_rate']        = $value_line->rs_item_rate;
            $po_line[$key_line]['poline_amount']      = $value_line->rs_item_amount;
            $po_line[$key_line]['poline_refname']     = 'sales_order';
            $po_line[$key_line]['poline_refid']       = $pr_id;
        }
        $return = Tbl_purchase_order_line::insert($po_line); 

        $update['po_overall_price'] = collect($po_line)->sum('poline_amount'); 

        Tbl_purchase_order::where('po_id', $po_id)->update($update);

        return $return;
    }
	public static function create($shop_id, $user_id, $input, $transaction_type ='')
	{
        $btn_action = Request::input('button_action');

		$validate = null;
		$insert['shop_id']                  = $shop_id;
		$insert['user_id']                  = $user_id;
		$insert['transaction_refnum']       = $input->requisition_slip_number;
		$insert['requisition_slip_remarks'] = $input->requisition_slip_remarks;
        $insert['requisition_slip_date']    = date("Y-m-d",strtotime($input->transaction_date));
        $insert['requisition_slip_memo']    = $input->vendor_memo;
		$insert['requisition_slip_date_created'] = Carbon::now();

        $validate .= AccountingTransaction::check_transaction_ref_number($shop_id, $insert['transaction_refnum'], 'purchase_requisition');
        
	    $rule["transaction_refnum"] = "required";
        $rule["requisition_slip_remarks"] = "required";

        $validator = Validator::make($insert, $rule);
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $validate .= $message;
            }
        }
        $_item = null;
        $ctr = 0;
        foreach ($input->rs_item_id as $key1 => $value) 
        {
            if($value)
            {
                $ctr++;
                if($input->rs_item_qty[$key1] <= 0)
                {
                    $validate .= 'The quantity of <b>'.str_replace("&", "and",Item::info($value)->item_name).'</b> is less than zero.<br>';
                }

                // if($input->rs_vendor_id[$key1] =="" )
                // {
                //     $validate .= 'Please select vendor.';
                // }
            }
        }

        if($ctr <= 0)
        {
            $validate .= "Please insert Item";
        }

        if(!$validate)
        {
            $total_amount = 0;
        	$rs_id = Tbl_requisition_slip::insertGetId($insert);
            foreach ($input->rs_item_id as $key => $value) 
            {
                if($value)
                {
                    $_item[$key]['rs_id']               = $rs_id;
                    $_item[$key]['rs_item_id']          = $value;
                    $_item[$key]['rs_item_description'] = $input->rs_item_description[$key];
                    $_item[$key]['rs_item_um']          = isset($input->rs_item_um[$key]) ? $input->rs_item_um[$key] : null;
                    $_item[$key]['rs_rem_qty']          = isset($input->rs_rem_qty[$key]) ? $input->rs_rem_qty[$key] : 0;
                    $_item[$key]['rs_item_qty']         = $input->rs_item_qty[$key];
                    $_item[$key]['rs_item_rate']        = str_replace(",", "", $input->rs_item_rate[$key]);
                    $_item[$key]['rs_item_amount']      = str_replace(",", "", $input->rs_item_amount[$key]);
                    $_item[$key]['rs_vendor_id']        = $input->rs_vendor_id[$key] != '' ? $input->rs_vendor_id[$key] : null;
                    $_item[$key]['rs_item_refname']     = $input->item_ref_name[$key];
                    $_item[$key]['rs_item_refid']       = $input->item_ref_id[$key];
                    $total_amount += $_item[$key]['rs_item_amount'];
                }
            }
            
            $insert['total_amount'] = $total_amount;
            Tbl_requisition_slip::where('requisition_slip_id', $rs_id)->update($insert);

            if(count($_item) > 0)
            {
                Tbl_requisition_slip_item::insert($_item);

                $transaction_data = AccountingTransaction::audit_trail($shop_id,$rs_id, 'purchase_requisition');
                AuditTrail::record_logs('Added', 'purchase_requisition', $rs_id, "", serialize($transaction_data));
            }
        
            $validate = $rs_id;
            Self::applied_transaction($shop_id, $validate);

        }
        
		if(is_numeric($validate))
		{   
            $return['status'] = 'success';
            $return['call_function'] = 'success_create_rs';

            if($btn_action == "sclose")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition';
            }
            elseif($btn_action == "sedit")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/create?id='.$validate;
            }
            elseif($btn_action == "snew")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/create';
            }
            elseif($btn_action == "sprint")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/print?id='.$validate;
            }

		}
		else
		{
            $return['status'] = 'error';
            $return['status_message'] = $validate;			
		}

        return $return;
	}
    public static function update($shop_id, $user_id, $input, $transaction_type ='')
    {

        $btn_action = Request::input('button_action');
        $pr_id = Request::input('pr_id');
        
        $old_transaction_data = AccountingTransaction::audit_trail($shop_id, $pr_id, 'purchase_requisition');

        $validate = null;
        $insert['shop_id']                  = $shop_id;
        $insert['user_id']                  = $user_id;
        $insert['transaction_refnum']  = $input->requisition_slip_number;
        $insert['requisition_slip_remarks'] = $input->requisition_slip_remarks;
        $insert['requisition_slip_date']    = date("Y-m-d",strtotime($input->transaction_date));
        $insert['requisition_slip_memo']    = $input->vendor_memo;
        $insert['requisition_slip_date_created'] = Carbon::now();

        $rule["transaction_refnum"] = "required";
        $rule["requisition_slip_remarks"] = "required";

        $validator = Validator::make($insert, $rule);
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $validate .= $message;
            }
        }
        $_item = null;
        $_po = null;
        $ctr = 0;
        foreach ($input->rs_item_id as $key1 => $value) 
        {
            if($value)
            {
                $ctr++;
                if($input->rs_item_qty[$key1] <= 0)
                {
                    $validate .= 'The quantity of <b>'.Item::info($value)->item_name.'</b> is less than zero.';
                }

                // if($input->rs_vendor_id[$key1] =="" )
                // {
                //     $validate .= 'Please select vendor.';
                // }
            }
        }

        if($ctr <= 0)
        {
            $validate .= "Please insert Item";
        }

        if(!$validate)
        {
            Tbl_requisition_slip::where('requisition_slip_id', $pr_id)->update($insert);
            Tbl_requisition_slip_item::where('rs_id', $pr_id)->delete();

            $total_amount = 0;
            foreach ($input->rs_item_id as $key => $value) 
            {
                if($value)
                {
                    $_item[$key]['rs_id']               = $pr_id;
                    $_item[$key]['rs_item_id']          = $value;
                    $_item[$key]['rs_item_description'] = $input->rs_item_description[$key];
                    $_item[$key]['rs_item_um']          = isset($input->rs_item_um[$key]) ? $input->rs_item_um[$key] : null;
                    $_item[$key]['rs_rem_qty']          = isset($input->rs_rem_qty[$key]) ? $input->rs_rem_qty[$key] : 0;
                    $_item[$key]['rs_item_qty']         = $input->rs_item_qty[$key];
                    $_item[$key]['rs_item_rate']        = str_replace(",", "", $input->rs_item_rate[$key]);
                    $_item[$key]['rs_item_amount']      = str_replace(",", "", $input->rs_item_amount[$key]);
                    $_item[$key]['rs_vendor_id']        = $input->rs_vendor_id[$key] != '' ? $input->rs_vendor_id[$key] : null;
                    $_item[$key]['rs_item_refname']     = $input->item_ref_name[$key];
                    $_item[$key]['rs_item_refid']       = $input->item_ref_id[$key];
                    $total_amount += $_item[$key]['rs_item_amount'];
                }
            }

            $insert['total_amount'] = $total_amount;

            Tbl_requisition_slip::where('requisition_slip_id', $pr_id)->update($insert);

            if(count($_item) > 0)
            {
                Tbl_requisition_slip_item::insert($_item);

                $transaction_data = AccountingTransaction::audit_trail($shop_id, $pr_id, 'purchase_requisition');
                AuditTrail::record_logs('Edited', 'purchase_requisition', $pr_id, serialize($old_transaction_data), serialize($transaction_data));
            }
            $validate = $pr_id;
        }
        
        if(is_numeric($validate))
        {   
            $return['status'] = 'success';
            $return['call_function'] = 'success_create_rs';

            if($btn_action == "sclose")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition';
            }
            elseif($btn_action == "sedit")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/create?id='.$validate;
            }
            elseif($btn_action == "snew")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/create';
            }
            elseif($btn_action == "sprint")
            {
                $return['status_redirect'] = '/member/transaction/purchase_requisition/print?id='.$validate;
            }

        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;          
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
                $update['est_status'] = 'closed';
                Tbl_customer_estimate::where("est_id", $key)->where('est_shop_id', $shop_id)->update($update);
            }
        }

        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
        $get_transaction = Tbl_requisition_slip::where("shop_id", $shop_id)->where("requisition_slip_id", $transaction_id)->first();
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "purchase_requisition";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->requisition_slip_date;

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

    public static function insert_acctg_transaction_po($shop_id, $transaction_id, $applied_transaction = array())
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
                    $get_data = Tbl_requisition_slip::where("shop_id", $shop_id)->where("requisition_slip_id", $key)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$key]['transaction_ref_name'] = "purchase_requisition";
                        $attached_transaction_data[$key]['transaction_ref_id'] = $key;
                        $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$key]['transaction_date'] = $get_data->requisition_slip_date;
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
                $datenow = date('Y-m-d',strtotime(Carbon::now()));
                $date_three = date('Y-m-d',strtotime(Carbon::now()->addDays(3)));
                $get = Tbl_requisition_slip::acctg_trans()
                                        ->warehouse()
                                        ->where("tbl_requisition_slip.shop_id", $valueshop->shop_id)
                                        ->where("requisition_slip_date","<=", $date_three)
                                        ->where("requisition_slip_status","open")
                                        ->groupBy("tbl_requisition_slip.requisition_slip_id")
                                        ->get();
                
                $insert = null;
                foreach ($get as $key => $value) 
                {
                    $check = Tbl_admin_notification::where("transaction_refname",'purchase_requisition')
                                                   ->where("transaction_refid", $value->requisition_slip_id)
                                                   ->where("notification_shop_id", $valueshop->shop_id)
                                                   ->first();
                    if(!$check)
                    {
                        $insert[$key]['notification_shop_id'] = $valueshop->shop_id;
                        $insert[$key]['warehouse_id'] = $value->transaction_warehouse_id;
                        $insert[$key]['notification_description'] = 'The <strong>"'.$value->warehouse_name.'"</strong> is about to approve <strong>Requisition Slip No. "'.$value->transaction_refnum.'"</strong> on '.date("F d, Y",strtotime($value->requisition_slip_date));
                        $insert[$key]['transaction_refname'] = "purchase_requisition";
                        $insert[$key]['transaction_refid'] = $value->requisition_slip_id;
                        $insert[$key]['transaction_status'] = "pending";
                        $insert[$key]['transaction_date'] = $value->requisition_slip_id;
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

}