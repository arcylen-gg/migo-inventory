<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_customer;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Invoice;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Transaction;
use App\Globals\Customer;
class ManualInvoiceController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data["_sir"] = Purchasing_inventory_system::select_sir_status($this->user_info->shop_id,'array',1,0,Request::input("sir_id"));
       $data["count_sir"] = Purchasing_inventory_system::count_sir($this->user_info->shop_id,'array',1,0);

        return view("member.purchasing_inventory_system.manual_invoicing.manual_invoice",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function view_invoices($sir_id)
    {
        $data["_invoices"] = Tbl_manual_invoice::sir()->customer_invoice()->where("tbl_sir.sir_id",$sir_id)->orderBy("tbl_customer_invoice.inv_id","DESC")->where("inv_shop_id",$this->user_info->shop_id)->get();
        $data["sir_id"] = $sir_id;
        return view("member.customer_invoice.customer_invoice_list",$data);
    }
    public function manual_invoice_add($sir_id)
    {    
        $data["_customer"]  = Customer::getAllCustomer();
        // $data["_item"]      = Tbl_item::where("archived", 0)->get();
        $data['_item']      = Item::get_all_item_sir($sir_id);
        $data["new_inv_id"] = Transaction::get_last_number("tbl_customer_invoice","new_inv_id","inv_shop_id"); 
        $data["sir_id"] = $sir_id;
        // dd($data["sir_id"]);
        $data["action"] = "/member/pis/manual_invoice/add_submit";
        // dd($data["_item"]);
        return view('member.customer_invoice.customer_invoice', $data);
    }
    public function manual_invoice_edit_submit()
    {                
        $invoice_id = Request::input("invoice_id");
        $sir_id = Request::input("sir_id");

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = Request::input('inv_date');
        $invoice_info['invoice_due']        = Request::input('inv_due_date');
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = Request::input('subtotal_price');
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $item_name = '';
        $return = 0;

        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {               
                $item_info[$key]['item_service_date']  = Request::input('invline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);


                $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key]);
                if($return != 0)
                {
                    $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                }

                $um_info = UnitMeasurement::um_info(Request::input("invline_um")[$key]);
                $product_consume[$key]["quantity"] = (isset($um_info->unit_qty) ? $um_info->unit_qty : 1) * $item_info[$key]['quantity'];
                $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];
            }
        }

        if($return == 0)
        {

            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'));

            if($inv <= 1 || Request::input("keep_val") == "keep")
            {
                $inv_item = Tbl_customer_invoice_line::where("invline_inv_id",$invoice_id)->get();
                // dd($inv_item);
                foreach ($inv_item as $keys => $value) 
                {                 
                    Purchasing_inventory_system::return_qty($sir_id, $value->invline_item_id, $value->invline_um, $value->invline_qty); 
                }

                $inv_id = Invoice::updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);


                foreach($_itemline as $key => $item_line)
                {
                    if($item_line)
                    {
                        Purchasing_inventory_system::mark_as_sold($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key]); 
                    }
                }

                $data["status"] = "success-sir";
            }
            else
            {
                $data["inv_id"] = Request::input("new_invoice_id");            
                $data["status"] = "error-inv-no";
            }
        }
        else
        {
            $data["status"] = "error";
            foreach ($item_name as $key_item => $value_item) 
            {
                $data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
            }
        }

        return json_encode($data);
    }
    public function manual_invoice_add_submit()
    {
        $sir_id = Request::input("sir_id");
        $data["status"] = "";
        $data["status_message"] = "";

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = Request::input('inv_date');
        $invoice_info['invoice_due']        = Request::input('inv_due_date');
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['total_subtotal_price'] = str_replace(',', "", Request::input('subtotal_price'));
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');
        $total_info['total_overall_price']  = Request::input('overall_price');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $return = 0;
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $item_info[$key]['item_service_date']  = Request::input('invline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('invline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('invline_amount')[$key]);

                $return += Purchasing_inventory_system::check_qty_sir($sir_id, Request::input('invline_item_id')[$key],Request::input('invline_um')[$key],Request::input('invline_qty')[$key]);
                if($return != 0)
                {
                    $item_name[$key] = Tbl_item::where("item_id",Request::input("invline_item_id")[$key])->value("item_name");
                }

                $um_info = UnitMeasurement::um_qty(Request::input("invline_um")[$key]);
                $product_consume[$key]["quantity"] = $um_info * $item_info[$key]['quantity'];
                $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];
            }
        }

        if($return == 0)
        {

            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'));

            if($inv == 0 || Request::input("keep_val") == "keep")
            {
               $invoice_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);
               // $remarks = "Manual Invoice consume";
               // $warehouse_id = Tbl_warehouse::where("warehouse_shop_id",$this->user_info->shop_id)->where("main_warehouse",1)->value("warehouse_id");
               // $transaction_type = "invoice";
               // $transaction_id = $invoice_id;
               // $data = Warehouse::inventory_consume($warehouse_id, $remarks, $product_consume,$consumer_id = 0, $consume_cause = '', $return = 'array', $transaction_type, $transaction_id);
                  
               if($sir_id != null && $invoice_id != null)
               {
                    $insert_manual_invoice["sir_id"] = $sir_id;
                    $insert_manual_invoice["inv_id"] = $invoice_id;

                    Tbl_manual_invoice::insert($insert_manual_invoice);

                    foreach($_itemline as $keys => $item_line)
                    {
                        if($item_line)
                        {
                            Purchasing_inventory_system::mark_as_sold($sir_id, Request::input('invline_item_id')[$keys],Request::input('invline_um')[$keys],Request::input('invline_qty')[$keys]);
                        }
                    }
                    $data["status"] = "success-sir";
               }
               else
               {
                    $data["status"] = "error";
                    $data["status_message"] = "error";
               }
           }
           else
           {                
                $data["inv_id"] = Request::input("new_invoice_id");            
                $data["status"] = "error-inv-no";
           }
        }
        else
        {
            $data["status"] = "error";
            foreach ($item_name as $key_item => $value_item) 
            {
                $data["status_message"] .= "<li style='list-style:none'>The quantity of ".$value_item." is not enough.</li>";
            }
        }
       return json_encode($data);
    }

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
