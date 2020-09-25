<?php

namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;
use Redirect;

use App\Http\Controllers\Controller;
use App\Globals\TransactionEstimateQuotation;
use App\Globals\Purchasing_inventory_system;
use App\Models\Tbl_customer_wis_item_line;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionSalesReceipt;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionSalesOrder;
use App\Globals\AdminNotification;
use App\Globals\WarehouseTransfer;
use App\Globals\Truck;
use App\Globals\UnitMeasurement;
use App\Globals\CustomerWIS;
use App\Globals\Warehouse2;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use App\Globals\AuditTrail;
use App\Globals\Customer;
use App\Globals\Vendor;
use App\Globals\Item;
use Carbon\Carbon;
use Validator;
use Session;
use Excel;
use View;
use DB;
class CustomerWarehouseIssuanceSlipController extends Member
{
    /**
     * Display a listing of the resource.
     * @author EDEN
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $data['page'] = 'WIS';
        //CustomerWIS::transactionStatus($this->user_info->shop_id);
        $data['status'] = isset($request->status) ? $request->status : 'pending';
        $current_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $data['optimize_wiswt'] = AccountingTransaction::settings($this->user_info->shop_id, "optimize_wiswt");
        $data['_cust_wis'] = CustomerWIS::get_all_customer_wis($this->user_info->shop_id, $data['status'], $current_warehouse);
        $data['project'] = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
        //dd($data['_cust_wis']);
        return view('member.warehousev2.customer_wis.customer_wis_list',$data);
    }
    public function getCustomerLoadWisTable(Request $request)
    {
        $current_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['status'] = isset($request->status) ? $request->status : 'pending';
        $data['optimize_wiswt'] = AccountingTransaction::settings($this->user_info->shop_id, "optimize_wiswt");
        $data['_cust_wis'] = CustomerWIS::get_all_customer_wis($this->user_info->shop_id, $data['status'], $current_warehouse,null,null,10, $request->search_keyword);
        $data['project'] = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");

        return view('member.warehousev2.customer_wis.load_customer_wis_table',$data);
    }

    public function getCreate(Request $request)
    {
        $data['page'] = 'CREATE - CUSTOMER WIS';
        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $data["_customer"]  = Customer::getAllCustomer();
        $data['action']     = "/member/transaction/wis/create-submit";
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['transaction_refnum'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'warehouse_issuance_slip');
        $data['_truck'] = Truck::get($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['project'] = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");

        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $data['c_id'] = $request->customer_id;
        Session::forget('applied_transaction_wis');
        if($request->si_id || $request->sr_id || $request->ids)
        {
            $sales_id = $request->si_id == '' ? $request->sr_id : $request->si_id;
            $sales_id = $sales_id == '' ? $request->ids : $sales_id;
            $sess[$sales_id] = $sales_id;
            $data['si'] = TransactionSalesInvoice::info($this->user_info->shop_id, $sales_id);
            $data['c_id'] = $data['si'] != null ? $data['si']->inv_customer_id : '';

            Session::put("applied_transaction_wis",$sess);
        }

        $cust_wis_id = $request->id;
        if($cust_wis_id)
        {
            $data["wis"]  = CustomerWIS::get_customer_wis_data($cust_wis_id);
            $data["_wisline"] = CustomerWIS::get_wis_line($cust_wis_id);
            $data["wis_monthly"]  = CustomerWIS::get_wis_monthly_budget($cust_wis_id);
            foreach ($data["_wisline"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->itemline_sub_wh_id);
            }
            $data['action']     = "/member/transaction/wis/update-submit";
        }
        if($request->ids)
        {
            $data['applied'] = CustomerWIS::get_inv($this->user_info->shop_id, $request->ids);
        }

        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
        $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, 'monthly_budget');

        return view('member.warehousev2.customer_wis.customer_wis_create',$data);
    }
    public function postCreateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;
        
        $remarks = $request->cust_wis_remarks;
        $items = Session::get('cust_wis_item');
        $items = $request->item_id;
        $shop_id = $this->user_info->shop_id;
      
        $ins_wis['cust_wis_shop_id']                = $shop_id;
        $ins_wis['transaction_refnum']              = $request->cust_wis_number;
        $ins_wis['cust_wis_from_warehouse']         = Warehouse2::get_current_warehouse($shop_id);
        $ins_wis['cust_email']                      = $request->customer_email;
        $ins_wis['total_amount']                    = str_replace(',', '', $request->overall_price);
        $ins_wis['cust_wis_remarks']                = $remarks;
        $ins_wis['cust_wis_truck_id']               = $request->truck_id;
        $ins_wis['destination_customer_id']         = $request->customer_id;
        $ins_wis['destination_customer_address']    = $request->customer_address;
        $ins_wis['cust_receiver_code']              = $this->user_info->user_id;
        $ins_wis['cust_delivery_date']              = date("Y-m-d", strtotime($request->delivery_date));
        $ins_wis['created_at']                      = Carbon::now();
        $ins_wis['transaction_status']              = 'pending';

        $_item = null;
        $insert_item = null;
        foreach ($items as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id'] = $value;
                $insert_item[$key]['item_description'] = $request->item_description[$key];
                $insert_item[$key]['item_sub_warehouse'] = null;
                if(isset($request->item_sub_warehouse[$key]))
                {
                    $insert_item[$key]['item_sub_warehouse'] = $request->item_sub_warehouse[$key] != '' ? $request->item_sub_warehouse[$key] : null;
                }
                $insert_item[$key]['item_qty'] = $request->item_qty[$key];
                $insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_discount'] = 0;
                $insert_item[$key]['item_refname'] = $request->item_refname[$key];
                $insert_item[$key]['item_refid'] = $request->item_refid[$key];

                $_item[$key] = null;
                $_item[$key]['item_id'] = $value;
                $_item[$key]['quantity'] = $request->item_qty[$key] * UnitMeasurement::get_umqty($insert_item[$key]['item_um']);
                $_item[$key]['remarks'] = $request->item_description[$key];
                $_item[$key]['bin_location'] = $request->item_sub_warehouse[$key];
                $_item[$key]['item_rate'] = $request->item_rate[$key];
            }
        }
        $val = null;
        $val .= CustomerWIS::validate_item_against_sales($this->user_info->shop_id, $request, $_item);
        // $check = AccountingTransaction::settings($shop_id, "monthly_budget");
        // if($check)
        // {
            // $_budgetitem = $request->budgetline_item_id;
            // if(count($_budgetitem) > 0)
            // {
            //     foreach ($_budgetitem as $keybudget => $valuebudget)
            //     {
            //         $_item['b'.$keybudget]['item_id'] = $valuebudget;
            //         $_item['b'.$keybudget]['quantity'] = 1;
            //         $_item['b'.$keybudget]['remarks'] = "";
            //         $_item['b'.$keybudget]['bin_location'] = null;
            //     }
            // }
        // }
        $warehouse_id = $ins_wis['cust_wis_from_warehouse'];
        foreach ($_item as $key => $value) 
        {
            $serial = isset($value['serial']) ? $value['serial'] : null;
            $val .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'], $serial, 'customer_wis');
        }
        if(!$val)
        {
            $val = CustomerWIS::customer_create_wis($shop_id, $remarks, $ins_wis, $_item, $insert_item);
            $data = null;
        }
        if(is_numeric($val))
        {
            CustomerWIS::insert_for_budgeting($shop_id, $val, $request);
            CustomerWIS::applied_transaction($shop_id, $val);
            $data['status'] = 'success';
            $data['call_function'] = 'success_create_customer_wis';
            $data['status_message'] = 'Success creating WIS';
            $data['redirect_to'] = AccountingTransaction::get_redirect('wis', $val ,$btn_action);

            Session::forget('applied_transaction_wis');
            if($btn_action == 'sconfirm')
            {
                /*FOR CONFIRMATION*/
                $up['cust_wis_status'] = "confirm";
                CustomerWIS::update_wis($this->user_info->shop_id, $val, $up);
            }
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $val, 'warehouse_issuance_slip');
            AuditTrail::record_logs('Added', 'warehouse_issuance_slip', $val, "", serialize($transaction_data));
        }  
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $val;
        }
        
        return json_encode($data);
    }
    public function postUpdateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;
        $wis_id  = $request->cust_wis_id;
        $remarks = $request->cust_wis_remarks;
        $items = Session::get('cust_wis_item');
        $items = $request->item_id;
        $shop_id = $this->user_info->shop_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_issuance_slip');

        
        //die(var_dump($wis_id));
        $ins_wis['cust_wis_shop_id']                = $shop_id;
        $ins_wis['transaction_refnum']              = $request->cust_wis_number;
        $ins_wis['cust_wis_from_warehouse']         = Warehouse2::get_current_warehouse($shop_id);
        $ins_wis['cust_email']                      = $request->customer_email;
        $ins_wis['total_amount']                    = str_replace(',', '', $request->overall_price);
        $ins_wis['cust_wis_remarks']                = $remarks;
        $ins_wis['destination_customer_id']         = $request->customer_id;
        $ins_wis['cust_wis_truck_id']               = $request->truck_id;
        $ins_wis['destination_customer_address']    = $request->customer_address;
        $ins_wis['cust_receiver_code']              = $this->user_info->user_id;
        $ins_wis['cust_delivery_date']              = date("Y-m-d", strtotime($request->delivery_date));
        $ins_wis['created_at']                      = Carbon::now();

        $_item = null;
        $insert_item = null;

        $return_wis = null;
        foreach ($items as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id'] = $value;
                $insert_item[$key]['item_description'] = $request->item_description[$key];
                $insert_item[$key]['item_sub_warehouse'] = null;
                if(isset($request->item_sub_warehouse[$key]))
                {
                    $insert_item[$key]['item_sub_warehouse'] = $request->item_sub_warehouse[$key] != '' ? $request->item_sub_warehouse[$key] : null;
                }
                $insert_item[$key]['item_qty'] = $request->item_qty[$key];
                $insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_discount'] = 0;
                $insert_item[$key]['item_refname'] = $request->item_refname[$key];
                $insert_item[$key]['item_refid'] = $request->item_refid[$key];

                $_item[$key] = null;
                $_item[$key]['item_id'] = $value;
                $_item[$key]['quantity'] = $request->item_qty[$key] * UnitMeasurement::get_umqty($insert_item[$key]['item_um']);
                $_item[$key]['remarks'] = $request->item_description[$key];
                $_item[$key]['bin_location'] = $request->item_sub_warehouse[$key];
                $_item[$key]['item_rate'] = $request->item_rate[$key];

                if($insert_item[$key]['item_refid'])
                {
                    $return_wis[$insert_item[$key]['item_refid']] = '';
                }
            }
        }
        if(count($return_wis) > 0)
        {
            Session::put('applied_transaction_wis',$return_wis);
        }

        $val = CustomerWIS::customer_update_wis($wis_id, $shop_id, $remarks, $ins_wis, $_item, $insert_item);

        $data = null;
        if(is_numeric($val))
        {
            CustomerWIS::insert_for_budgeting($shop_id, $val, $request);
            CustomerWIS::applied_transaction($shop_id, $val, true);
            $data['status'] = 'success';
            $data['call_function'] = 'success_create_customer_wis';
            $data['status_message'] = 'Success udpating WIS';
            $data['redirect_to'] = AccountingTransaction::get_redirect('wis', $val ,$btn_action);
            if($btn_action == 'sconfirm')
            {
                /*FOR CONFIRMATION*/
                $up['cust_wis_status'] = "confirm";
                CustomerWIS::update_wis($this->user_info->shop_id, $val, $up);
            }
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_issuance_slip');
            AuditTrail::record_logs('Edited', 'warehouse_issuance_slip', $wis_id, serialize($old_transaction_data), serialize($transaction_data));
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $val;
        }
        
        return json_encode($data);
    }

    public function getConfirm(Request $request, $cust_wis_id)
    {
        $data['cust_wis_id'] = $cust_wis_id;
        $data['cust_wis'] = CustomerWIS::get_customer_wis_data($cust_wis_id);
        $data['action'] = $request->action;

        return view('member.warehousev2.customer_wis.customer_wis_confirm', $data);
    }

    public function postConfirmSubmit(Request $request)
    {
        $cust_wis_id = $request->customer_wis_id;
        $update['cust_wis_status'] = $request->confirm_status;
        $update['cust_confirm_image'] = $request->confirm_image;
        $update['cust_receiver_code'] = $this->user_info->user_id;

        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $cust_wis_id, 'warehouse_issuance_slip');
        $return = CustomerWIS::update_customer_wis($this->user_info->shop_id, $cust_wis_id, $update);
        if($request->confirm_status == "delivered")
        {
            AdminNotification::update_notification($this->user_info->shop_id,'wis', $cust_wis_id, $this->user_info->user_id);
        }
        
        $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $cust_wis_id, 'warehouse_issuance_slip');
        AuditTrail::record_logs('Edited', 'warehouse_issuance_slip', $cust_wis_id, serialize($old_transaction_data), serialize($transaction_data));

        $data = null;
        if($return)
        {
            $data['status'] = 'success';
            $data['call_function'] = 'success_confirm'; 
        }

        return json_encode($data);
    }
    public function getPrint(Request $request)
    {
        $wis_id = $request->id;
        $data['type'] = $request->type;
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data['wis'] = CustomerWIS::get_customer_wis_data($wis_id);        
        $data['wis_item'] = CustomerWIS::customer_wis_itemline($wis_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "wisdr");
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');

        $data['monthly_budget'] = CustomerWIS::get_monthly_budget($this->user_info->shop_id, $wis_id);
        $data['monthly_budget_line'] = CustomerWIS::get_monthly_budgetline($this->user_info->shop_id, $wis_id);
        
        if($data['wis'])
        {
            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_wis_dr");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $data['transaction_type'] = "Warehouse Issuance Slip";
            $data['delivery_msg'] = '';
            if($data['wis']->cust_wis_status != "pending")
            {
                $data['transaction_type'] = "Delivery Receipt";
                $data['delivery_msg'] = date('F, Y');
            }

            if(!$request->picking)
            {
                $pdf = view('member.warehousev2.customer_wis.customer_wis_print', $data);
            }
            else
            {
                $data['footer'] = $footer;
                $pdf = view('member.warehousev2.customer_wis.picking_slip', $data);
                $footer = null;
            }
            $monthly_budget = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
            if($monthly_budget && $data['wis']->fix_monthly_budget > 0)
            {
                $pdf = view('member.warehousev2.customer_wis.monthly_budget_pdf', $data);                
            }
            if($data['type'] == 'wo_amount')
            {
                $pdf = view('member.warehousev2.customer_wis.print_wo_amount', $data);          
            }
            return Pdf_global::show_pdf($pdf,null,$footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
    }
    public function getCountTransaction(Request $request)
    {
        $customer_id = $request->customer_id;
        return CustomerWIS::countTransaction($this->user_info->shop_id, $customer_id);
    }
    public function getLoadTransaction(Request $request)
    {
        $data['page'] = "Open Transaction";

        $data['_si'] = TransactionSalesInvoice::getUndeliveredSalesInvoice($this->user_info->shop_id, $request->c);
        $data['_sr'] = TransactionSalesReceipt::getUndeliveredSalesReceipt($this->user_info->shop_id, $request->c);
        $data['customer_name'] = Customer::get_name($this->user_info->shop_id, $request->c);

        $data['applied'] = Session::get('applied_transaction_wis');
        $data['action'] = '/member/transaction/wis/apply-transaction';
        return view('member.warehousev2.customer_wis.load_transaction', $data);
    }
    public function postApplyTransaction(Request $request)
    {
        $_transaction = $request->apply_transaction;
        Session::put('applied_transaction_wis', $_transaction);

        $return['call_function'] = "success_apply_transaction";
        $return['status'] = "success";

        return json_encode($return);
    }
    public function getAjaxApplyTransaction(Request $request)
    {
        $_transaction[$request->apply_transaction] = $request->apply_transaction;
        Session::put('applied_transaction_wis', $_transaction);

        return json_encode('success');
    }
    public function getLoadAppliedTransaction(Request $request)
    {
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
        $data['project'] = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");

        $_ids = Session::get('applied_transaction_wis');

        $return = null;
        $remarks = null;
        if(count($_ids) > 0)
        {
            foreach ($_ids as $key => $value) 
            {
                $get = CustomerWIS::get_inv_item($this->user_info->shop_id, $key);
                $info = CustomerWIS::get_inv($this->user_info->shop_id, $key);

                foreach ($get as $key_item => $value_item)
                {
                    $type = Item::get_item_type($value_item->invline_item_id);
                    if($type == 1 || $type == 4 || $type == 5 )
                    {
                        $return[$key.'i'.$key_item]['item_id'] = $value_item->invline_item_id;
                        $return[$key.'i'.$key_item]['item_description'] = $value_item->invline_description;
                        $return[$key.'i'.$key_item]['item_sub_warehouse'] = $value_item->invline_sub_wh_id;
                        $return[$key.'i'.$key_item]['multi_um_id'] = $value_item->multi_um_id;
                        $return[$key.'i'.$key_item]['item_um'] = $value_item->invline_um;
                        $return[$key.'i'.$key_item]['item_qty'] = $value_item->invline_qty;
                        // $return[$key.'i'.$key_item]['item_rate'] = $value_item->item_cost * (UnitMeasurement::get_umqty($value_item->invline_um));
                        $return[$key.'i'.$key_item]['item_rate'] = $value_item->invline_rate * (UnitMeasurement::get_umqty($value_item->invline_um));
                        $return[$key.'i'.$key_item]['item_amount'] =  $value_item->invline_rate *  $value_item->invline_qty;
                        $return[$key.'i'.$key_item]['_bin_item_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value_item->invline_sub_wh_id);

                        $refname = "sales_invoice";
                        if($info)
                        {
                            if($info->is_sales_receipt == 1)
                            {
                                $refname = "sales_receipt";
                            }
                        }

                        $return[$key.'i'.$key_item]['refname'] = $refname;
                        $return[$key.'i'.$key_item]['refid'] = $key;

                    }
                }
                if($info)
                {
                    $con = 'SR#';
                    if($info->is_sales_receipt == 0)
                    {
                        $con = 'SI#';
                    }
                    $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : $con.$info->inv_id.', ';
                }
            }
        }
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);
        
        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $data['_transactions'] = $return;
        $data['remarks'] = $remarks;
        $data['_um']        = UnitMeasurement::load_um_multi();

        return view('member.warehousev2.customer_wis.applied_transaction', $data);
    }
    public function getInTransit(Request $request)
    {   
        $data['item'] = Item::info($request->d);
        $data['_intransit_breakdown'] = Item::intransit_breakdown($this->user_info->shop_id, $request->d, $request->from, $request->to, $this->user_info->user_id);
        return view('member.warehousev2.customer_wis.in_transit', $data);  

    }
}
