<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_employee;
use App\Models\Tbl_position;
use App\Models\Tbl_sir;
use App\Models\Tbl_manual_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_manual_credit_memo;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_manual_invoice;
use App\Globals\Employee;
use App\Globals\Pdf_global;
use Validator;
use Carbon\Carbon;
use App\Globals\Utilities;
use Crypt;

class AgentTransactionController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function agents_transaction($agent_id)
    {
        $data["agent_id"] = $agent_id;
        $data["day"] = Carbon::now()->subMonths(1);

        $data["_sir_id"] = Tbl_sir::where("sales_agent_id",$agent_id)->whereIn("ilr_status",[1,2])->get();
        // dd(Request::input("start_date"));
        // $start_date = date("Y-m-d H:i:s",strtotime(Carbon::now()->subDays(1)->format("Y-m-d") . " 00:00:00"));
        // $end_date = date("Y-m-d H:i:s",strtotime(Carbon::now()));    
        // if(Request::input("start_date") != null && Request::input("end_date") != null)
        // {
        //    $start_date = date("Y-m-d H:i:s",strtotime(Request::input("start_date")));
        //    $end_date   = date("Y-m-d H:i:s", strtotime(Request::input("end_date")));   
        // }
        $data["agent"] = Tbl_employee::position()->where("employee_id",$agent_id)->first();
    
        $data["_sir"] = Tbl_sir::where("sales_agent_id",$agent_id)->whereIn("ilr_status",[1,2])->get();

        $data["__transaction"] = [];
        if(Request::input("sir_id") == null)
        {
            foreach ($data["_sir"] as $key => $value) 
            {
                //for invoice
                $data["invoices"] = Tbl_manual_invoice::customer_invoice()->where("sir_id",$value->sir_id)->get(); 

                //union of invoice and receive payment
                foreach ($data["invoices"] as $inv_key => $inv_value) 
                {
                    $_transaction = null;
                    $cm = Tbl_credit_memo::where("cm_id",$inv_value->credit_memo_id)->first();
                    $cm_amt = 0;
                    if($cm != null)
                    {
                      $cm_amt = $cm->cm_amount;  
                    }
                    $_transaction[$inv_key]['date'] = $inv_value->inv_date;
                    $_transaction[$inv_key]['transaction_code'] = '';
                    if($inv_value->is_sales_receipt == 0)
                    {
                        $_transaction[$inv_key]['type'] = 'Credit Sales';
                        $_transaction[$inv_key]['reference_name'] = 'invoice';
                    $_transaction[$inv_key]['transaction_code'] = 'AR';

                        $chk = Tbl_receive_payment_line::where("rpline_reference_name",'invoice')->where("rpline_reference_id",$inv_value->inv_id)->first();
                        if($chk)
                        {
                            $_transaction[$inv_key]['transaction_code'] = "AR Payment #".$chk->rpline_rp_id;
                        }

                    }
                    else
                    {                
                        $_transaction[$inv_key]['type'] = 'Cash Sales';
                        $_transaction[$inv_key]['reference_name'] = 'sales_receipt';
                    $_transaction[$inv_key]['transaction_code'] = 'Paid Cash';
                    }
                    $_transaction[$inv_key]['customer_name'] = $inv_value->title_name." ".$inv_value->first_name." ".$inv_value->last_name." ".$inv_value->suffix_name;
                    $_transaction[$inv_key]['no'] = $inv_value->inv_id;
                    $_transaction[$inv_key]['balance'] = $inv_value->inv_overall_price - $inv_value->inv_payment_applied;
                    $_transaction[$inv_key]['due_date'] = $inv_value->inv_due_date;
                    $_transaction[$inv_key]['total'] = $inv_value->inv_overall_price - $cm_amt;
                    $_transaction[$inv_key]['status'] = $inv_value->inv_is_paid;
                    $_transaction[$inv_key]['date_created'] = $inv_value->manual_invoice_date;

                    array_push($data['__transaction'], $_transaction);
                }
                
                $data["rcv_payment"] = Tbl_manual_receive_payment::customer_receive_payment()->selectRaw("*, tbl_manual_receive_payment.rp_date as manual_rp_date")->where("sir_id",$value->sir_id)->get();
                foreach ($data["rcv_payment"] as $rp_key => $rp_value) 
                {
                    $_transaction = null;
                    $_transaction[$rp_key]['date'] = $rp_value->rp_date;
                    $_transaction[$rp_key]['type'] = 'Payment';
                    $_transaction[$rp_key]['reference_name'] = 'receive_payment';
                    $_transaction[$rp_key]['customer_name'] = $rp_value->title_name." ".$rp_value->first_name." ".$rp_value->last_name." ".$rp_value->suffix_name;
                    $_transaction[$rp_key]['no'] = $rp_value->rp_id;
                    $_transaction[$rp_key]['balance'] = 0;
                    $_transaction[$rp_key]['due_date'] = $rp_value->rp_date;
                    $_transaction[$rp_key]['total'] = $rp_value->rp_total_amount;
                    $_transaction[$rp_key]['status'] = 'status';
                    $_transaction[$rp_key]['date_created'] = $rp_value->manual_rp_date;

                    array_push($data['__transaction'], $_transaction);
                }
                $data["credit_memo"] = Tbl_manual_credit_memo::customer_cm()->where("sir_id",$value->sir_id)->get();
                foreach ($data["credit_memo"] as $cm_key => $cm_value) 
                {
                    $_transaction = null;
                    $_transaction[$cm_key]['transaction_code'] = 'Credit Memo';
                    $_transaction[$cm_key]['date'] = $cm_value->cm_date;
                    $_transaction[$cm_key]['type'] = 'Credit Memo';
                    $_transaction[$cm_key]['reference_name'] = 'credit_memo';
                    $_transaction[$cm_key]['customer_name'] = $cm_value->title_name." ".$cm_value->first_name." ".$cm_value->last_name." ".$cm_value->suffix_name;
                    $_transaction[$cm_key]['no'] = $cm_value->cm_id;
                    $_transaction[$cm_key]['balance'] = 0;
                    $_transaction[$cm_key]['due_date'] = $cm_value->cm_date;
                    $_transaction[$cm_key]['total'] = $cm_value->cm_amount;
                    $_transaction[$cm_key]['status'] = 'status';
                    $_transaction[$cm_key]['date_created'] = $cm_value->manual_cm_date;

                    array_push($data['__transaction'], $_transaction);
                }
            }
        }
        else
        {
            $start_date = '';
            $end_date = '';
            //for invoice
            $data["invoices"] = Tbl_manual_invoice::customer_invoice()->where("sir_id",Request::input("sir_id"))->get(); 

            //union of invoice and receive payment
            foreach ($data["invoices"] as $inv_key => $inv_value) 
            {
                $_transaction = null;
                $cm = Tbl_credit_memo::where("cm_id",$inv_value->credit_memo_id)->first();
                $cm_amt = 0;
                if($cm != null)
                {
                  $cm_amt = $cm->cm_amount;  
                }
                $_transaction[$inv_key]['date'] = $inv_value->inv_date;
                if($inv_value->is_sales_receipt == 0)
                {
                    $_transaction[$inv_key]['type'] = 'Credit Sales';
                    $_transaction[$inv_key]['reference_name'] = 'invoice';     
                    $_transaction[$inv_key]['transaction_code'] = "AR";

                    $chk = Tbl_receive_payment_line::where("rpline_reference_name",'invoice')->where("rpline_reference_id",$inv_value->inv_id)->first();
                    if($chk)
                    {
                        $_transaction[$inv_key]['transaction_code'] = "AR Payment #".$chk->rpline_rp_id;
                    }               
                }
                else
                {                
                    $_transaction[$inv_key]['type'] = 'Cash Sales';
                    $_transaction[$inv_key]['reference_name'] = 'sales_receipt';
                    $_transaction[$inv_key]['transaction_code'] = "Paid Cash";
                }
                $_transaction[$inv_key]['customer_name'] = $inv_value->title_name." ".$inv_value->first_name." ".$inv_value->last_name." ".$inv_value->suffix_name;
                $_transaction[$inv_key]['no'] = $inv_value->inv_id;
                $_transaction[$inv_key]['balance'] = $inv_value->inv_overall_price - $inv_value->inv_payment_applied;
                $_transaction[$inv_key]['due_date'] = $inv_value->inv_due_date;
                $_transaction[$inv_key]['total'] = $inv_value->inv_overall_price - $cm_amt;
                $_transaction[$inv_key]['status'] = $inv_value->inv_is_paid;
                $_transaction[$inv_key]['date_created'] = $inv_value->manual_invoice_date;

                array_push($data['__transaction'], $_transaction);
            }
            
            $data["rcv_payment"] = Tbl_manual_receive_payment::customer_receive_payment()->selectRaw("*, tbl_manual_receive_payment.rp_date as manual_rp_date")->where("sir_id",Request::input("sir_id"))->get();
            foreach ($data["rcv_payment"] as $rp_key => $rp_value) 
            {
                $_transaction = null;
                $_transaction[$rp_key]['date'] = $rp_value->rp_date;
                $_transaction[$rp_key]['type'] = 'Payment';
                $_transaction[$rp_key]['reference_name'] = 'receive_payment';
                $_transaction[$rp_key]['customer_name'] = $rp_value->title_name." ".$rp_value->first_name." ".$rp_value->last_name." ".$rp_value->suffix_name;
                $_transaction[$rp_key]['no'] = $rp_value->rp_id;
                $_transaction[$rp_key]['balance'] = 0;
                $_transaction[$rp_key]['due_date'] = $rp_value->rp_date;
                $_transaction[$rp_key]['total'] = $rp_value->rp_total_amount;
                $_transaction[$rp_key]['status'] = 'status';
                $_transaction[$rp_key]['date_created'] = $rp_value->manual_rp_date;

                array_push($data['__transaction'], $_transaction);
            }
            $data["credit_memo"] = Tbl_manual_credit_memo::customer_cm()->where("sir_id",Request::input("sir_id"))->get();
            foreach ($data["credit_memo"] as $cm_key => $cm_value) 
            {
                $_transaction = null;
                $_transaction[$cm_key]['transaction_code'] = 'Credit Memo';
                $_transaction[$cm_key]['date'] = $cm_value->cm_date;
                $_transaction[$cm_key]['type'] = 'Credit Memo';
                $_transaction[$cm_key]['reference_name'] = 'credit_memo';
                $_transaction[$cm_key]['customer_name'] = $cm_value->title_name." ".$cm_value->first_name." ".$cm_value->last_name." ".$cm_value->suffix_name;
                $_transaction[$cm_key]['no'] = $cm_value->cm_id;
                $_transaction[$cm_key]['balance'] = 0;
                $_transaction[$cm_key]['due_date'] = $cm_value->cm_date;
                $_transaction[$cm_key]['total'] = $cm_value->cm_amount;
                $_transaction[$cm_key]['status'] = 'status';
                $_transaction[$cm_key]['date_created'] = $cm_value->manual_cm_date;

                array_push($data['__transaction'], $_transaction);
            }

        }
        $data["tr"] = [];
        foreach ($data['__transaction'] as $key => $value) 
        {
            foreach ($value as $key1 => $value1) 
            {
                array_push($data['tr'], $value1);
            }
        
        }
        $data["total"] = 0;
        foreach ($data['tr'] as $key2 => $value2)
        {
            if($value2['reference_name'] == "receive_payment" || $value2['reference_name'] == "sales_receipt")
            {
                $data['total'] += $value2['total'];
            }
            if($value2['reference_name'] == "receive_payment")
            {
                unset($data['tr'][$key2]);
            }
        }
        $data["total"] = currency("Php",$data['total']);

        usort($data['tr'], function($a, $b)
        {
            $t1 = strtotime($a['date_created']);
            $t2 = strtotime($b['date_created']);
            return $t1 - $t2;
        });
        // dd($data['tr']);
        $data['__transaction'] = $data['tr'];
        return view("member.purchasing_inventory_system.agent_transactions.agent_transactions",$data);
    }

    public function print_transaction($agent_id)
    {
        $sir_id = Request::input('sir_id');
         $data["agent"] = Tbl_employee::position()->where("employee_id",$agent_id)->first();
    
        $data["_sir"] = Tbl_sir::where("sales_agent_id",$agent_id)->whereIn("ilr_status",[1,2])->get();
        $data["sir_id"] = $sir_id;
        $data["__transaction"] = [];
        if($sir_id == null)
        {
            foreach ($data["_sir"] as $key => $value) 
            {
                //for invoice
                $data["invoices"] = Tbl_manual_invoice::customer_invoice()->where("sir_id",$value->sir_id)->get(); 

                //union of invoice and receive payment
                foreach ($data["invoices"] as $inv_key => $inv_value) 
                {
                    $_transaction = null;
                    $cm = Tbl_credit_memo::where("cm_id",$inv_value->credit_memo_id)->first();
                    $cm_amt = 0;
                    if($cm != null)
                    {
                      $cm_amt = $cm->cm_amount;  
                    }
                    $_transaction[$inv_key]['date'] = $inv_value->inv_date;
                    if($inv_value->is_sales_receipt == 0)
                    {
                        $_transaction[$inv_key]['type'] = 'Credit Sales';
                        $_transaction[$inv_key]['reference_name'] = 'invoice';     
                        $_transaction[$inv_key]['transaction_code'] = "AR";

                        $chk = Tbl_receive_payment_line::where("rpline_reference_name",'invoice')->where("rpline_reference_id",$inv_value->inv_id)->first();
                        if($chk)
                        {
                            $_transaction[$inv_key]['transaction_code'] = "AR Payment #".$chk->rpline_rp_id;
                        }                   
                    }
                    else
                    {                
                        $_transaction[$inv_key]['type'] = 'Cash Sales';
                        $_transaction[$inv_key]['reference_name'] = 'sales_receipt';
                        $_transaction[$inv_key]['transaction_code'] = "Paid Cash";
                    }
                    $_transaction[$inv_key]['customer_name'] = $inv_value->title_name." ".$inv_value->first_name." ".$inv_value->last_name." ".$inv_value->suffix_name;
                    $_transaction[$inv_key]['no'] = $inv_value->inv_id;
                    $_transaction[$inv_key]['balance'] = $inv_value->inv_overall_price - $inv_value->inv_payment_applied;
                    $_transaction[$inv_key]['due_date'] = $inv_value->inv_due_date;
                    $_transaction[$inv_key]['total'] = $inv_value->inv_overall_price - $cm_amt;
                    $_transaction[$inv_key]['status'] = $inv_value->inv_is_paid;
                    $_transaction[$inv_key]['date_created'] = $inv_value->manual_invoice_date;

                    array_push($data['__transaction'], $_transaction);
                }
                
                $data["rcv_payment"] = Tbl_manual_receive_payment::customer_receive_payment()->selectRaw("*, tbl_manual_receive_payment.rp_date as manual_rp_date")->where("sir_id",$value->sir_id)->get();
                foreach ($data["rcv_payment"] as $rp_key => $rp_value) 
                {
                    $_transaction = null;
                    $_transaction[$rp_key]['date'] = $rp_value->rp_date;
                    $_transaction[$rp_key]['type'] = 'Payment';
                    $_transaction[$rp_key]['reference_name'] = 'receive_payment';
                    $_transaction[$rp_key]['customer_name'] = $rp_value->title_name." ".$rp_value->first_name." ".$rp_value->last_name." ".$rp_value->suffix_name;
                    $_transaction[$rp_key]['no'] = $rp_value->rp_id;
                    $_transaction[$rp_key]['balance'] = 0;
                    $_transaction[$rp_key]['due_date'] = $rp_value->rp_date;
                    $_transaction[$rp_key]['total'] = $rp_value->rp_total_amount;
                    $_transaction[$rp_key]['status'] = 'status';
                    $_transaction[$rp_key]['date_created'] = $rp_value->manual_rp_date;

                    array_push($data['__transaction'], $_transaction);
                }
                $data["credit_memo"] = Tbl_manual_credit_memo::customer_cm()->where("sir_id",$value->sir_id)->get();
                foreach ($data["credit_memo"] as $cm_key => $cm_value) 
                {
                    $_transaction = null;
                    $_transaction[$cm_key]['transaction_code'] = "Credit Memo";
                    $_transaction[$cm_key]['date'] = $cm_value->cm_date;
                    $_transaction[$cm_key]['type'] = 'Credit Memo';
                    $_transaction[$cm_key]['reference_name'] = 'credit_memo';
                    $_transaction[$cm_key]['customer_name'] = $cm_value->title_name." ".$cm_value->first_name." ".$cm_value->last_name." ".$cm_value->suffix_name;
                    $_transaction[$cm_key]['no'] = $cm_value->cm_id;
                    $_transaction[$cm_key]['balance'] = 0;
                    $_transaction[$cm_key]['due_date'] = $cm_value->cm_date;
                    $_transaction[$cm_key]['total'] = $cm_value->cm_amount;
                    $_transaction[$cm_key]['status'] = 'status';
                    $_transaction[$cm_key]['date_created'] = $cm_value->manual_cm_date;

                    array_push($data['__transaction'], $_transaction);
                }
            }
        }
        else
        {
            $start_date = '';
            $end_date = '';
            //for invoice
            $data["invoices"] = Tbl_manual_invoice::customer_invoice()->where("sir_id",$sir_id)->get(); 

            //union of invoice and receive payment
            foreach ($data["invoices"] as $inv_key => $inv_value) 
            {
                $_transaction = null;
                $cm = Tbl_credit_memo::where("cm_id",$inv_value->credit_memo_id)->first();
                $cm_amt = 0;
                if($cm != null)
                {
                  $cm_amt = $cm->cm_amount;  
                }
                $_transaction[$inv_key]['date'] = $inv_value->inv_date;
                if($inv_value->is_sales_receipt == 0)
                {
                    $_transaction[$inv_key]['type'] = 'Creedit Sales';
                    $_transaction[$inv_key]['reference_name'] = 'invoice';                    
                }
                else
                {                
                    $_transaction[$inv_key]['type'] = 'Cash Sales';
                    $_transaction[$inv_key]['reference_name'] = 'sales_receipt';
                }
                $_transaction[$inv_key]['customer_name'] = $inv_value->title_name." ".$inv_value->first_name." ".$inv_value->last_name." ".$inv_value->suffix_name;
                $_transaction[$inv_key]['no'] = $inv_value->inv_id;
                $_transaction[$inv_key]['balance'] = $inv_value->inv_overall_price - $inv_value->inv_payment_applied;
                $_transaction[$inv_key]['due_date'] = $inv_value->inv_due_date;
                $_transaction[$inv_key]['total'] = $inv_value->inv_overall_price - $cm_amt;
                $_transaction[$inv_key]['status'] = $inv_value->inv_is_paid;
                $_transaction[$inv_key]['date_created'] = $inv_value->manual_invoice_date;

                array_push($data['__transaction'], $_transaction);
            }
            
            $data["rcv_payment"] = Tbl_manual_receive_payment::customer_receive_payment()->selectRaw("*, tbl_manual_receive_payment.rp_date as manual_rp_date")->where("sir_id",$sir_id)->get();
            foreach ($data["rcv_payment"] as $rp_key => $rp_value) 
            {
                $_transaction = null;
                $_transaction[$rp_key]['date'] = $rp_value->rp_date;
                $_transaction[$rp_key]['type'] = 'Payment';
                $_transaction[$rp_key]['reference_name'] = 'receive_payment';
                $_transaction[$rp_key]['customer_name'] = $rp_value->title_name." ".$rp_value->first_name." ".$rp_value->last_name." ".$rp_value->suffix_name;
                $_transaction[$rp_key]['no'] = $rp_value->rp_id;
                $_transaction[$rp_key]['balance'] = 0;
                $_transaction[$rp_key]['due_date'] = $rp_value->rp_date;
                $_transaction[$rp_key]['total'] = $rp_value->rp_total_amount;
                $_transaction[$rp_key]['status'] = 'status';
                $_transaction[$rp_key]['date_created'] = $rp_value->manual_rp_date;

                array_push($data['__transaction'], $_transaction);
            }
            $data["credit_memo"] = Tbl_manual_credit_memo::customer_cm()->where("sir_id",$sir_id)->get();
            foreach ($data["credit_memo"] as $cm_key => $cm_value) 
            {
                $_transaction = null;
                $_transaction[$cm_key]['date'] = $cm_value->cm_date;
                $_transaction[$cm_key]['type'] = 'Credit Memo';
                $_transaction[$cm_key]['reference_name'] = 'credit_memo';
                $_transaction[$cm_key]['customer_name'] = $cm_value->title_name." ".$cm_value->first_name." ".$cm_value->last_name." ".$cm_value->suffix_name;
                $_transaction[$cm_key]['no'] = $cm_value->cm_id;
                $_transaction[$cm_key]['balance'] = 0;
                $_transaction[$cm_key]['due_date'] = $cm_value->cm_date;
                $_transaction[$cm_key]['total'] = $cm_value->cm_amount;
                $_transaction[$cm_key]['status'] = 'status';
                $_transaction[$cm_key]['date_created'] = $cm_value->manual_cm_date;

                array_push($data['__transaction'], $_transaction);
            }

            $sir_data = Tbl_sir::where("sir_id",$sir_id)->first();
            $data["rem_amount"] = $sir_data->agent_collection;
            $data["rem_remarks"] = $sir_data->agent_collection_remarks;
        }
        $data["tr"] = [];
        foreach ($data['__transaction'] as $key => $value) 
        {
            foreach ($value as $key1 => $value1) 
            {
                array_push($data['tr'], $value1);
            }
        
        }
        $data["total"] = 0;
        foreach ($data['tr'] as $key2 => $value2)
        {
            if($value2['reference_name'] == "receive_payment" || $value2['reference_name'] == "sales_receipt")
            {
                $data['total'] += $value2['total'];
            }
            if($value2['reference_name'] == "receive_payment")
            {
                unset($data['tr'][$key2]);
            }
        }
        $data["total"] = currency("Php",$data['total']);
        usort($data['tr'], function($a, $b)
        {
            $t1 = strtotime($a['date_created']);
            $t2 = strtotime($b['date_created']);
            return $t1 - $t2;
        });
        // dd($data['tr']);
        $data['_transaction'] = $data['tr'];

        $data['ctr_tr'] = count($data['_transaction']);
        $data['sdate'] = date('m/d/Y');
        $pdf = view('member.purchasing_inventory_system.agent_transactions.agent_transaction_pdf', $data);
        return Pdf_global::show_pdf($pdf);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
