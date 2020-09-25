<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Globals\Customer;
use App\Globals\Item;
use App\Globals\Invoice;
use App\Globals\SalesAgent;
use App\Globals\CommissionCalculator;
use App\Globals\Accounting;
use App\Globals\ReceivePayment;
use App\Globals\AccountingTransaction;
use Session;
use Carbon\Carbon;
use Excel;
use Redirect;
class CommissionCalculatorController extends Member
{
    /**
     * Display a listing of the resource.
     * @author ARCYLEN
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $data['page'] = "Commission Calculator";
        $data['_list'] = CommissionCalculator::list($this->user_info->shop_id);
        return view('member.cashier.commission_calculator.commission_calculator_list',$data);
    }
    public function getCreate()
    {
        $data['page'] = "Commission Calculator";
        $data['_customer'] = Customer::getAllCustomer();
        $data['_agent'] = SalesAgent::get_list($this->user_info->shop_id);
        $data['_item'] = Item::get_all_category_item([2]);

        return view('member.cashier.commission_calculator.create',$data);        
    }
    public function postCreateSubmit(Request $request)
    {
        $shop_id = $this->user_info->shop_id;
        $comm['customer_id'] = $request->customer_id;
        $comm['customer_email'] = $request->customer_email;
        $comm['agent_id'] = $request->agent_id;
        $comm['date'] = datepicker_input($request->date);
        $comm['due_date'] = datepicker_input($request->due_date);
        $comm['total_selling_price'] = str_replace(',', '', $request->total_selling_price);
        $comm['total_contract_price'] = $request->total_contract_price;
        $comm['total_commission'] = $request->total_commission;
        $comm['loanable_amount'] = $request->loanable_amount;
        $comm['date_created'] = Carbon::now();

        $comm_item['item_id'] = $request->item_id;
        $comm_item['downpayment_percent'] = str_replace('%', '', $request->downpayment_percent);
        $comm_item['discount'] = str_replace(',', '',$request->discount);
        $comm_item['monthly_amort'] = $request->monthly_amort;
        $comm_item['misceleneous_fee_percent'] = str_replace('%', '', $request->misceleneous_fee_percent);
        $comm_item['ndp_commission'] = str_replace('%', '', $request->ndp_commission);
        $comm_item['tcp_commission'] = str_replace('%', '', $request->tcp_commission);

        $return = CommissionCalculator::create($shop_id, $comm, $comm_item);

        // die(var_dump('success'));
        if($return == 'success')
        {
            $data['status'] = $return;
            $data['call_function'] = 'success_commission';
            return json_encode($data);
        }
    }
    public function getLoadItem(Request $request)
    {
        $data['_item'] = Item::get_all_category_item([2]);
        return view('member.load_ajax_data.load_item_category',$data);
    }
    public function getImport(Request $request)
    {
        return view('member.cashier.commission_calculator.import_transaction');        
    }

    /* Do not Remove */
    public function postImportUrl()
    {

    }
    public function postImportReadFile(Request $request)
    {
        Session::forget("import_item_error");
        $value     = $request->value;

        $ctr            = $request->ctr;
        $data_length    = $request->data_length;
        $error_data     = $request->error_data;
        $json = null;
        if($ctr != $data_length)
        {
            $data['name']         = isset($value["Name"])                    ? $value["Name"] : '';
            $data['type']         = isset($value["Type"])                    ? $value["Type"] : '';

            $data['date']         = isset($value["Date"])                    ? $value["Date"] : '';
            $data['num']          = isset($value["Num"])                     ? $value["Num"] : '';
            $data['account']      = isset($value["Account"])                 ? $value["Account"] : '';
            $data['rep']          = isset($value["Rep"])                     ? $value["Rep"] : '';
            $data['amount']       = isset($value["Amount"])                  ? $value["Amount"] : 0;

            $data['tsp']          = isset($value["Total Selling Price"])     ? $value["Total Selling Price"] : 0;
            $data['downpayment']  = isset($value["Downpayment"])             ? str_replace('%', '',$value["Downpayment"])  : 0;
            $data['discount']     = isset($value["Discount"])                ? $value["Discount"] : 0;
            $data['mon_amort']    = isset($value["Monthly Amort"])           ? $value["Monthly Amort"] : 0;
            $data['misc_fee']     = isset($value["Miscellaneous Fee"])       ? str_replace('%', '',$value["Miscellaneous Fee"]) : 0;
            $data['ndp']          = isset($value["NDP Commission"])          ? str_replace('%', '',$value["NDP Commission"]) : 0;
            $data['tcp']          = isset($value["TCP Commission"])          ? str_replace('%', '',$value["TCP Commission"]) : 0;

            $json["status"]     = null;
            $json["message"]    = null;
            if($data['name'])
            {
                Session::put('customer_name', $data['name']);
                $json["status"]     = "success";
                $json["message"]    = "Success";
            }
            if($data['type'])
            {
                if($data['num'])
                {                    
                    /* check */ 
                    if(strtolower($data['type']) == 'invoice')
                    {
                        
                        $total_comm = $data["ndp"] + $data['tcp'];
                        if($total_comm == 100)
                        {                   
                            $check_inv = Invoice::check_inv($this->user_info->shop_id, $data['num']);
                            $error_message = null;

                            if(!$check_inv)
                            {
                                $coa = explode('·', $data['account']);
                                $account_id = 0;
                                if($data['account'])
                                {
                                    $account_id = AccountingTransaction::check_coa_exist($this->user_info->shop_id, 0000000, $data['account']);
                                }
                                else
                                {
                                    $error_message = "Account Unknown";
                                }
                                if(!$error_message)
                                {
                                    $item_customer = explode(',', Session::get('customer_name'));
                                    $customer_id = 0;
                                    $item_id = 0;
                                    if(isset($item_customer[1]))
                                    {
                                        $ins['customer_first_name'] = $item_customer[1];
                                        $ins['customer_company'] = $item_customer[1];
                                        $customer_id = Customer::createCustomer($this->user_info->shop_id, $ins);

                                        $ins_item['item_name'] = $item_customer[0];
                                        $ins_item['item_sku'] = $item_customer[0];
                                        $ins_item['item_price'] = $data['tsp'];
                                        $ins_item['item_cost'] = $data['tsp'];
                                        $ins_item['item_quantity'] = 0;
                                        $item_id = Item::create($this->user_info->shop_id, 2, $ins_item)["item_id"];
                                    }
                                    else
                                    {
                                        $error_message = "Customer not Found";
                                    }  
                                }
                                $agent_id = 0;
                                if(!$error_message)
                                {
                                    $sales_rep = SalesAgent::get_info($this->user_info->shop_id, $data['rep']);
                                    $comm_data = null;
                                    if($sales_rep)
                                    {
                                        $comm_data = CommissionCalculator::get_actual_computation($data['tsp'], $data['downpayment'], $data['discount'], $data['mon_amort'], $data['misc_fee'], $data['ndp'], $data['tcp'], $sales_rep->commission_percent);

                                        if($comm_data['amount_tcp'] != $data['amount'])
                                        {
                                            $error_message = "The total contract price is not equal on the Amount";
                                        }
                                        $agent_id = $sales_rep->employee_id;
                                    }
                                    else
                                    {
                                        $error_message = "Sales Rep not Found";
                                    }
                                }
                                              
                                if(!$error_message && $comm_data)
                                {                            
                                    /* ============================================= */
                                    $comm['customer_id'] = $customer_id;
                                    $comm['customer_email'] = "";
                                    $comm['agent_id'] = $agent_id;
                                    $comm['refnum'] = $data['num'];
                                    $comm['date'] = datepicker_input($data['date']);
                                    $comm['due_date'] = "";
                                    $comm['total_selling_price'] = str_replace(',', '', $data['tsp']);
                                    $comm['total_contract_price'] = $comm_data['amount_tcp'];
                                    $comm['total_commission'] = $comm_data['amount_tc'];
                                    $comm['loanable_amount'] = $comm_data['amount_loanable'];
                                    $comm['date_created'] = Carbon::now();

                                    $comm_item['item_id'] = $item_id;
                                    $comm_item['downpayment_percent'] = str_replace('%', '', $data['downpayment']);
                                    $comm_item['discount'] = str_replace(',', '',$data['discount']);
                                    $comm_item['monthly_amort'] = $data['mon_amort'];
                                    $comm_item['misceleneous_fee_percent'] = str_replace('%', '', $data['misc_fee']);
                                    $comm_item['ndp_commission'] = str_replace('%', '', $data['ndp']);
                                    $comm_item['tcp_commission'] = str_replace('%', '', $data['tcp']);

                                    $return = CommissionCalculator::create($this->user_info->shop_id, $comm, $comm_item);
                                    Session::put('invoice_id', $return);
                                    Session::put('customer_id', $customer_id);

                                    $json["status"]     = "success";
                                    $json["message"]    = "Success";
                                }
                                else
                                {
                                    $json["status"]     = "error";
                                    $json["message"]    = $error_message;
                                }
                            }
                            else
                            {
                                Session::put('invoice_id', $check_inv->inv_id);
                                Session::put('customer_id', $check_inv->inv_customer_id);
                                $json["status"]     = "success";
                                $json["message"]    = "Success";
                            }
                            
                        }
                        else
                        {
                            $json["status"]     = "error";
                            $json["message"]    = "The commission is not equal to 100%";
                        }
                    }
                    
                    if(strtolower($data['type']) == 'payment')
                    {
                        $customer_id = Session::get("customer_id");
                        $invoice_id = Session::get('invoice_id');
                        $check_rp = ReceivePayment::check_rp($this->user_info->shop_id, $data['num']);
                        if(!$check_rp && $customer_id && $invoice_id)
                        {
                            $insert_data['customer_id'] = $customer_id;
                            $insert_data['date'] = datepicker_input($data['date']);
                            $insert_data['total_payment_amount'] = str_replace("-", '', $data['amount']);
                            $insert_data['memo'] = "Payment Imported";
                            $insert_data['transaction_refnum'] = $data['num'];
                            $insert_data['ar_account'] = Accounting::get_default_coa("accounting-cash-in-bank");

                            $insert_item[0]['ref_name'] = "invoice";
                            $insert_item[0]['ref_id'] = $invoice_id;
                            $insert_item[0]['payment_amount'] = str_replace("-", '', $data['amount']);

                            $return = ReceivePayment::insert_payment($this->user_info->shop_id, $insert_data, $insert_item);

                            $json["status"]     = "success";
                            $json["message"]    = "Success";
                        }
                        else
                        {
                            if($check_rp)
                            {
                                $error_message = "Payment already exist based on Reference Number.";
                            }
                            if(!$customer_id)
                            {
                                $error_message = "Customer not found";
                            }
                            if(!$invoice_id)
                            {
                                $error_message = "Invoice not found";
                            }

                            $json["status"]     = "error";
                            $json["message"]    = $error_message;                        
                        }
                    }
                }
                else
                {
                    $json["status"]     = "success";
                    $json["message"]    = "Reference number is required";                    
                } 

            }
            if(strpos($data['name'],'Total')  !== false)
            {
                Session::forget('customer_name');
                Session::forget("customer_id");
                Session::forget("invoice_id");
                $json["status"]     = "success";
                $json["message"]    = "Success";
            }

            $status_color       = $json["status"] == 'success' ? 'green' : 'red';
            $json["tr_data"]    = "<tr>";
            $json["tr_data"]   .= "<td class='$status_color'>".$json["status"]."</td>";
            $json["tr_data"]   .= "<td nowrap>".$json["message"]."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['name']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['type']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['date']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['num']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['account']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['rep']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['amount']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['tsp']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['downpayment']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['discount']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['mon_amort']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['misc_fee']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['ndp']."</td>";
            $json["tr_data"]   .= "<td nowrap>".$data['tcp']."</td>";
            $json["tr_data"]   .= "</tr>";

            $json["value_data"] = $value;
            $length             = sizeOf($json["value_data"]);

            foreach($json["value_data"] as $key=>$value)
            {
                $json["value_data"]['Error Description'] = $json["message"];
            }
        }
        else /* DETERMINE IF LAST IN CSV */
        {
            Session::put("import_item_error", $error_data);
            $json["status"] = "end";
        }

        return json_encode($json);
    }
    public function getExportError(Request $request)
    {
        $_value = Session::get("import_item_error");

        if($_value)
        {
            Excel::create("ImportTransactionError", function($excel) use($_value)
            {
                // Set the title
                $excel->setTitle('Digimahouse');

                // Chain the setters
                $excel->setCreator('DigimaWebSolutions')
                      ->setCompany('DigimaWebSolutions');

                $excel->sheet('Template', function($sheet) use($_value) {
                    $header = [
                                'Name',
                                'Type',
                                'Date',
                                'Num',
                                'Account',
                                'Rep',
                                'Amount',
                                'Total Selling Price',
                                'Downpayment',
                                'Discount',
                                'Monthly Amort',
                                'Miscellaneous Fee',
                                'NDP Commission',
                                'TCP Commission',
                                'Error_Description'
                                ];
                    $sheet->freezeFirstRow();
                    $sheet->row(1, $header);
                    foreach($_value as $key=>$value)
                    {
                        $sheet->row($key+2, $value);
                    }

                });


            })->download('csv');
        }
        else
        {
            return Redirect::back();
        }
    }
}
