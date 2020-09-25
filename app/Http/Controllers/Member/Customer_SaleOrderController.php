<?php

namespace App\Http\Controllers\Member;

use App\Globals\Estimate;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Pdf_global;
use App\Globals\CreditMemo;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Transaction;
use App\Globals\Customer;

use App\Models\Tbl_customer;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user;

use Request;
use Carbon\Carbon;
use Session;
use Redirect;
use PDF;

class Customer_SaleOrderController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function index()
    {

        $data["_estimates"] = Tbl_customer_estimate::customer()->where("est_shop_id",$this->user_info->shop_id)->where("is_sales_order",1)->get();

        return view("member.customer.sales_order.sales_order_list",$data);
    }
    public function so_pdf($est_id)
    {
        $date = date("F j, Y, g:i a");
        $first_name         = $this->user_info->user_first_name;
        $last_name         = $this->user_info->user_last_name;
        $footer ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

        $data['estimate'] = Tbl_customer_estimate::customer()->where("est_id",$est_id)->where("est_shop_id",$this->user_info->shop_id)->first();
        $data["transaction_type"] = "SALES ORDER";
        $data["estimate_item"] = Tbl_customer_estimate_line::estimate_item()->where("estline_est_id",$est_id)->get();
        foreach($data["estimate_item"] as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->estline_um);

            $total_qty = $value->estline_qty * $qty;
            $data["estimate_item"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
        }

       $pdf = view('member.customer.estimate.estimate_pdf', $data);
       return Pdf_global::show_pdf($pdf, null, $footer);
    }
    public function update_status($id)
    {
        $data["est"] = Tbl_customer_estimate::where("est_id",$id)->first();
        $data["estimate_id"] = $id;

        $stat[0] = "pending";
        $stat[1] = "accepted";
        $stat[2] = "closed";
        $stat[3] = "rejected";

        $data["status"] = $stat;

        return view("member.customer.estimate.estimate_update_status",$data);
    }
    public function update_status_submit()
    {
        $update["est_status"] = Request::input("status");
        $update["est_accepted_by"] = Request::input("accepted_by");
        $update["est_accepted_date"] = date('Y:m:d h:i:s',strtotime(Request::input("accepted_date")));
        Tbl_customer_estimate::where("est_id",Request::input("estimate_id"))->update($update);

        $data["status"] = "success-update";

        Request::session()->flash('success', 'Success');

        return json_encode($data);
    }
    public function sales_order()
    {
        $data["page"] = "Customer Sales Receipt"; 
        $data["_customer"]  = Customer::getAllCustomer();
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["action"]     = "/member/customer/sales_order/create";
        $data["c_id"] = Request::input("customer_id");
        $id = Request::input('id');
        if($id)
        {
            $data["est"]            = Tbl_customer_estimate::where("est_id", $id)->first();
            
            $data["_estline"]       = Tbl_customer_estimate_line::um()->where("estline_est_id", $id)->get();
            $data["action"]         = "/member/customer/sales_order/update";
        }
        return view("member.customer.sales_order.create_sales_order",$data);
    }
    public function create_submit()
    {
        // dd(Request::input()); //estimate
        $button_action = Request::input('button_action');

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('est_customer_id');
        $customer_info['customer_email']    = Request::input('est_customer_email');

        $estimate_info                       = [];
        $estimate_info['estimate_date']       = datepicker_input(Request::input('est_date'));
        $estimate_info['estimate_due']        = datepicker_input(Request::input('est_exp_date'));
        $estimate_info['billing_address']    = Request::input('est_customer_billing_address');

        $estimate_other_info                 = [];
        $estimate_other_info['estimate_msg']  = Request::input('est_message');
        $estimate_other_info['estimate_memo'] = Request::input('est_memo');

        $total_info                         = [];
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('est_discount_type');
        $total_info['total_discount_value'] = Request::input('est_discount_value');
        $total_info['taxable']              = Request::input('taxable');

        $item_info                          = [];
        $_itemline                          = Request::input('estline_item_id');

        $product_consume = null;
        $ctr = 0;
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr++;
                $item_info[$key]['item_service_date']  = Request::input('estline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('estline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('estline_description')[$key];
                $item_info[$key]['um']                 = Request::input('estline_um')[$key];
                $item_info[$key]['quantity']           = Request::input('estline_qty')[$key];
                $item_info[$key]['rate']               = convertToNumber(Request::input('estline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('estline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('estline_discount_remark')[$key];
                $item_info[$key]['amount']             = convertToNumber(Request::input('estline_amount')[$key]);
                $item_info[$key]['taxable']            = Request::input('estline_taxable')[$key];
            }
        }
        if($ctr != 0)
        {
             $est_id = Estimate::postEstimate($customer_info, $estimate_info, $estimate_other_info, $item_info, $total_info, true);

            $data["status"] = "success-estimate";
            if($button_action == "save-and-edit")
            {
                $data["redirect"] = "/member/customer/sales_order?id=".$est_id;
            }
            elseif($button_action == "save-and-close")
            {
                $data["redirect"] = "/member/customer/sales_order_list";
            }
            elseif($button_action == "save-and-new")
            {
                $data["redirect"] = "/member/customer/sales_order";
            }
            elseif($button_action == "save-and-print")
            {
                $data["redirect"] = "/member/customer/customer_sales_order_view/".$est_id;
            }
        }
        else
        {
            $data["status"] = "error";
            $data["status_message"] = "Please Insert Item";
        }

        return json_encode($data);
    }
    public function update_submit()
    {

        $estimate_id = Request::input('estimate_id');

        $button_action = Request::input('button_action');

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('est_customer_id');
        $customer_info['customer_email']    = Request::input('est_customer_email');

        $estimate_info                       = [];
        $estimate_info['estimate_date']       = datepicker_input(Request::input('est_date'));
        $estimate_info['estimate_due']        = datepicker_input(Request::input('est_exp_date'));
        $estimate_info['billing_address']    = Request::input('est_customer_billing_address');

        $estimate_other_info                 = [];
        $estimate_other_info['estimate_msg']  = Request::input('est_message');
        $estimate_other_info['estimate_memo'] = Request::input('est_memo');

        $total_info                         = [];
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('est_discount_type');
        $total_info['total_discount_value'] = Request::input('est_discount_value');
        $total_info['taxable']              = Request::input('taxable');

        $item_info                          = [];
        $_itemline                          = Request::input('estline_item_id');

        $product_consume = null;
        $ctr = 0;
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr++;
                $item_info[$key]['item_service_date']  = Request::input('estline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('estline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('estline_description')[$key];
                $item_info[$key]['um']                 = Request::input('estline_um')[$key];
                $item_info[$key]['quantity']           = Request::input('estline_qty')[$key];
                $item_info[$key]['rate']               = convertToNumber(Request::input('estline_rate')[$key]);
                $item_info[$key]['discount']           = Request::input('estline_discount')[$key];
                $item_info[$key]['discount_remark']    = Request::input('estline_discount_remark')[$key];
                $item_info[$key]['amount']             = convertToNumber(Request::input('estline_amount')[$key]);
                $item_info[$key]['taxable']            = Request::input('estline_taxable')[$key];
            }
        }
        if($ctr != 0)
        {
             $est_id = Estimate::updateEstimate($estimate_id, $customer_info, $estimate_info, $estimate_other_info, $item_info, $total_info, true);

            $data["status"] = "success-estimate";
            if($button_action == "save-and-edit")
            {
                $data["redirect"] = "/member/customer/sales_order?id=".$est_id;
            }
            elseif($button_action == "save-and-close")
            {
                $data["redirect"] = "/member/customer/sales_order_list";
            }
            elseif($button_action == "save-and-new")
            {
                $data["redirect"] = "/member/customer/sales_order";
            }
            elseif($button_action == "save-and-print")
            {
                $data["redirect"] = "/member/customer/customer_sales_order_view/".$est_id;
            }
        }
        else
        {
            $data["status"] = "error";
            $data["status_message"] = "Please Insert Item";
        }

        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
