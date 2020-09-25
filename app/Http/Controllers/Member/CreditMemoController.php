<?php

namespace App\Http\Controllers\Member;

use App\Models\Tbl_customer;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_credit_memo_applied_payment;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_invoice;

use App\Globals\Pdf_global;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Customer;
use App\Globals\Purchasing_inventory_system;
use App\Globals\CreditMemo;
use App\Globals\Accounting;
use App\Globals\Warehouse;
use App\Globals\Invoice;
use App\Globals\Tablet_global;
use App\Globals\ItemSerial;
use App\Http\Controllers\Controller;
use Request;
use Redirect;

class CreditMemoController extends Member
{
    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function choose_type()
    {
        $data["cm_id"] = Request::input("cm_id");
        // if(Purchasing_inventory_system::check() != 0)
        // {
        //      $data["for_tablet"] = "false";
        // }

        return view("member.customer.credit_memo.cm_type",$data);
    }
    public function update_action()
    {
        $cm_id = Request::input("cm_id");
        $cm_type = Request::input("type");

        $data["cm_data"] = Tbl_credit_memo::where("cm_id",$cm_id)->first();
        $data["c_id"] = Tbl_credit_memo::where("cm_id",$cm_id)->value("cm_customer_id");
        $data['_nocredits'] = true;

        if($cm_type == "invoice")
        {
            $data["_customer"]      = Tbl_customer::where("customer_id",$data["c_id"])->first();
            $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
            $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
            $data['action']         = "/member/customer/receive_payment/add";
            $data["_invoice"]       = Invoice::getAllInvoiceByCustomer($data["c_id"]);

            $cm_amount = $data["cm_data"]->cm_amount;
            $total_inv = 0;

            if(count($data["_invoice"]) > 0)
            {  
            //     foreach ($data["_invoice"] as $key => $value)  
            //     {
            //         $total_inv += $value["inv_overall_price"];
            //         if($cm_amount > $total_inv)
            //         {
            //             $data["_invoice"][$key]["amount_applied"] = $value["inv_overall_price"];
            //             $data["_invoice"][$key]["rpline_amount"] = $value["inv_overall_price"];
            //         }
            //     }
            // if($data["_invoice"][0]["inv_overall_price"] > $cm_amount)
            // {
                $data["_invoice"][0]["amount_applied"] = $cm_amount;
                $data["_invoice"][0]["rpline_amount"] = $cm_amount;
            // }
            }

            $cm_data = CreditMemo::get_info($cm_id);
            $data['_rcvpayment_credit'][0]['ref_number'] =  $cm_data->transaction_refnum != "" ? $cm_data->transaction_refnum : $cm_data->cm_id;
            $data['_rcvpayment_credit'][0]['cm_id'] = $cm_id; 
            $data['_rcvpayment_credit'][0]['cm_amount'] = $cm_amount;

            return view("member.receive_payment.modal_receive_payment",$data);
        }
        else if($cm_type == "refund")
        {
            dd("Under Maintenance");
        }
        else if($cm_type == "invoice_tablet")
        {
            $data["_customer"]      = Tbl_customer::where("customer_id",$data["c_id"])->first();
            $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
            $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
            $data['action']         = "/tablet/receive_payment/add_submit";
            $data["_invoice"]       = Invoice::getAllInvoiceByCustomer($data["c_id"]);

            $cm_amount = $data["cm_data"]->cm_amount;
            $total_inv = 0;

            if(count($data["_invoice"]) > 0)
            {  
                $data["_invoice"][0]["amount_applied"] = $cm_amount;
                $data["_invoice"][0]["rpline_amount"] = $cm_amount;
            }
            return view("member.receive_payment.modal_receive_payment",$data);
        }
        else if($cm_type == "others")
        {
            $up["cm_type"] = 1;
            $up["cm_used_ref_name"] = "others";

            Tbl_credit_memo::where("cm_id",$cm_id)->update($up);

            return Redirect::to("/member/customer/credit_memo/list");
        }

        else if($cm_type == "others_tablet")
        {
            $up["cm_type"] = 1;
            $up["cm_used_ref_name"] = "others";

            Tbl_credit_memo::where("cm_id",$cm_id)->update($up);

            return Redirect::to("/tablet/credit_memo");
        }
        else
        {
            $up["cm_type"] = 1;
            $up["cm_used_ref_name"] = $cm_type;

            Tbl_credit_memo::where("cm_id",$cm_id)->update($up);

            return Redirect::to("/member/customer/credit_memo/list");            
        }
    }
    public function index()
    {
        $data["serial"]     = ItemSerial::check_setting();
        $data["page"]       = "Credit Memo";
        $data["_customer"]  = Customer::getAllCustomer();
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["action"]     = "/member/customer/credit_memo/create_submit";

        $data["total_applied_credit"] = 0;
        $id = Request::input('id');
        if($id)
        {
            $data["cm"]                    = Tbl_credit_memo::where("cm_id", $id)->first();
            $data["_cmline"]               = Tbl_credit_memo_line::um()->where("cmline_cm_id", $id)->get();
            $data["total_applied_credit"]  = $data["cm"]->cm_amount - Tbl_credit_memo_applied_payment::where("cm_id", $id)->sum("applied_amount");
            foreach ($data["_cmline"] as $key => $value) 
            {
                $data["_cmline"][$key]->serial_number = ItemSerial::get_serial_credited($value->cmline_item_id,"credit_memo-".$id);
            }
            $data["action"]         = "/member/customer/credit_memo/update";
        }

        if(Purchasing_inventory_system::check())
        {
            $data["pis"] = true;                
        }
        else
        {
            $data["pis"] = false;
        }

        return view("member.customer.credit_memo.credit_memo_add",$data);
    }
    public function cm_pdf($cm_id)
    {
        $date = date("F j, Y, g:i a");
        $first_name         = $this->user_info->user_first_name;
        $last_name         = $this->user_info->user_last_name;
        $footer ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

        $data['cm'] = Tbl_credit_memo::customer()->where("cm_id",$cm_id)->where("cm_shop_id",$this->user_info->shop_id)->first();
        $data["_cmline"] = Tbl_credit_memo_line::Cm_item()->where("cmline_cm_id",$cm_id)->get();
        //dd($data);
        // foreach($data["estimate_item"] as $key => $value) 
        // {
        //     $qty = UnitMeasurement::um_qty($value->estline_um);

        //     $total_qty = $value->estline_qty * $qty;
        //     $data["estimate_item"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
        // }

       $pdf = view('member.customer.credit_memo.cm_pdf', $data);
       return Pdf_global::show_pdf($pdf, null, $footer);

    }
    public function create_submit()
    {
        $btn_action = Request::input("action_button");
        //die(var_dump($btn_action));
        $use_credit = Request::input("use_credit");

        $customer_info[] = null;
        $customer_info["cm_customer_id"] = Request::input("cm_customer_id");
        $customer_info["cm_customer_email"] = Request::input("cm_customer_email");
        $customer_info["cm_date"] = datepicker_input(Request::input("cm_date"));
        $customer_info["cm_message"] = Request::input("cm_message");
        $customer_info["cm_memo"] = Request::input("cm_memo");
        $customer_info["cm_amount"] = Request::input("overall_price");

        $serial_number = Request::input("serial_number");

        $item_info[] = null;
        $item_returns = [];
        $item_serial = [];
        $_items = Request::input("cmline_item_id");
        $ctr_items = 0;
        foreach ($_items as $key => $value) 
        {   
            if($value)
            {
                $ctr_items++;
                $item_info[$key]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$key]);
                $item_info[$key]['item_id']            = Request::input('cmline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('cmline_description')[$key];
                $item_info[$key]['um']                 = Request::input('cmline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$key]);
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$key]);

                $item_type = Tbl_item::where("item_id",Request::input('cmline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$key]);
                    $item_returns[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                    $item_returns[$key]["product_id"] = Request::input('cmline_item_id')[$key];

                    $item_serial[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                    $item_serial[$key]["item_id"] = Request::input('cmline_item_id')[$key];
                    $item_serial[$key]["serials"] = $serial_number[$key];
                }
            }        
        }

        if($_items != null)
        {
             // --> for bundles
            foreach ($_items as $keyitem_cm => $value_item) 
            {
                if($value_item != null)
                {
                    $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                    if($item_bundle_info)
                    {
                        $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                        foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                        {
                            $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                            $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                            $_bundle[$key_bundle_cm]['product_id'] = $value_bundle_cm->bundle_item_id;
                            $_bundle[$key_bundle_cm]['quantity'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                            array_push($item_returns, $_bundle[$key_bundle_cm]);
                        }
                    }                 
                }
            }
            if(count($item_returns) > 0)
            {
                foreach ($item_returns as $key_items_cm => $value_items_cm) 
                {
                     $i = null;
                     foreach ($_items as $keyitemline_cm => $valueitemline)
                     {
                        $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                        if($type == 4)
                        {
                            if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['product_id'])
                            {
                                $i = "true";
                            }                    
                        }
                     }
                    if($i != null)
                    {
                        unset($item_returns[$key_items_cm]);
                    }           
                }            
            }
            // <-- end bundle            
        }
        // END CM/RETURNS 

        $data["status"] = null;
        $data["status_message"] = null;

        if(count($item_serial) > 0)
        {
            //CHECK IF SERIAL NUMBER IS EXISTING
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {

                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $data["status"] = "error";
                    $data["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_existing_to_credit($item_serial[$key_item_serial]))
                {
                    $data["status"] = "error";
                    $data["status_message"] .= ItemSerial::check_existing_to_credit($item_serial[$key_item_serial]);
                }
            }
        }
        if($data["status"] == null)
        {
            if($ctr_items != 0)
            {
                $customer_info['cm_used_ref_name'] = $use_credit;
                $customer_info["cm_type"] = 1;
                $cm_id = CreditMemo::postCM($customer_info, $item_info);
                
                if(count($item_returns) > 0)
                {
                    $cm_remarks            = "Credit Memo # ". $cm_id;
                    $cm_warehouse_id       = $this->current_warehouse->warehouse_id;
                    $cm_transaction_type   = "credit_memo";
                    $cm_transaction_id     = $cm_id;
                    $cm_data               = Warehouse::inventory_refill($cm_warehouse_id, $cm_transaction_type, $cm_transaction_id, $cm_remarks, $item_returns, 'array' ,"returns");

                    if(count($item_serial) > 0)
                    {
                        $transaction = "credit_memo-".$cm_id;
                        ItemSerial::update_refill_to_credit($item_serial, $transaction);
                    }
                }


                $data["status"] = "success";
                $data["id"] = $cm_id; 

                if($use_credit == "retain_credit")
                {
                    $data['call_function'] = "success_credit_memo";
                    //$data["redirect_to"] = "/member/customer/credit_memo/list";

                    if ($btn_action == 'save-and-close')
                    {
                        $data['redirect_to'] = '/member/customer/credit_memo/list';
                    }
                    elseif ($btn_action == 'save-and-edit')
                    {
                        $data['redirect_to'] = '/member/customer/credit_memo?id='.$cm_id;
                    }
                    elseif ($btn_action == 'save-and-new')
                    {
                        $data['redirect_to'] = '/member/customer/credit_memo';
                    }
                    elseif ($btn_action == 'save-and-print')
                    {
                        $data['redirect_to'] = '/member/customer/credit_memo/view_pdf/'.$cm_id;
                    }
                }
                elseif($use_credit == "refund")
                {
                    $data['call_function'] = "success_credit_memo_refund";
                    $data['redirect_to'] = "/member/customer/credit_memo/update_action?type=refund&cm_id=".$cm_id;

                }
                elseif($use_credit == "apply")
                {
                    $data['call_function'] = "success_credit_memo_apply";
                    $data['redirect_to'] = "/member/customer/credit_memo/update_action?type=invoice&cm_id=".$cm_id;
                }
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "Please Insert Item";
            }
        }

        return json_encode($data);
    }
    public function update_submit()
    {
        $cm_id = Request::input("credit_memo_id");

        $btn_action = Request::input("action_button");

        $ctr_items = 0;
        $customer_info[] = null;
        $customer_info["cm_customer_id"] = Request::input("cm_customer_id");
        $customer_info["cm_customer_email"] = Request::input("cm_customer_email");
        $customer_info["cm_date"] = datepicker_input(Request::input("cm_date"));
        $customer_info["cm_message"] = Request::input("cm_message");
        $customer_info["cm_memo"] = Request::input("cm_memo");
        $customer_info["cm_amount"] = Request::input("overall_price");

        $serial_number = Request::input("serial_number");

        $item_info[] = null;
        $item_returns = [];
        $item_serial = [];
        $_items = Request::input("cmline_item_id");
        foreach ($_items as $key => $value) 
        {  
            if($value)
            {
                $ctr_items++;
                $item_info[$key]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$key]);
                $item_info[$key]['item_id']            = Request::input('cmline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('cmline_description')[$key];
                $item_info[$key]['um']                 = Request::input('cmline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$key]);
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$key]);

                $item_type = Tbl_item::where("item_id",Request::input('cmline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$key]);
                    $item_returns[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                    $item_returns[$key]["product_id"] = Request::input('cmline_item_id')[$key];

                    $item_serial[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                    $item_serial[$key]["item_id"] = Request::input('cmline_item_id')[$key];
                    $item_serial[$key]["serials"] = $serial_number[$key];
                }
            }
        }
         if($_items != null)
        {
             // --> for bundles
            foreach ($_items as $keyitem_cm => $value_item) 
            {
                if($value_item != null)
                {
                    $item_bundle_info = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitem_cm])->where("item_type_id",4)->first();
                    if($item_bundle_info)
                    {
                        $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("cmline_item_id")[$keyitem_cm])->get();
                        foreach ($bundle as $key_bundle_cm => $value_bundle_cm) 
                        {
                            $qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keyitem_cm]);
                            $bundle_qty = UnitMeasurement::um_qty($value_bundle_cm->bundle_um_id);
                            $_bundle[$key_bundle_cm]['product_id'] = $value_bundle_cm->bundle_item_id;
                            $_bundle[$key_bundle_cm]['quantity'] = (Request::input('cmline_qty')[$keyitem_cm] * $qty) * ($value_bundle_cm->bundle_qty * $bundle_qty);

                            array_push($item_returns, $_bundle[$key_bundle_cm]);
                        }
                    }                 
                }
            }
            if(count($item_returns) > 0)
            {
                foreach ($item_returns as $key_items_cm => $value_items_cm) 
                {
                     $i = null;
                     foreach ($_items as $keyitemline_cm => $valueitemline)
                     {
                        $type = Tbl_item::where("item_id",Request::input("cmline_item_id")[$keyitemline_cm])->value("item_type_id");
                        if($type == 4)
                        {
                            if(Request::input("cmline_item_id")[$keyitemline_cm] == $value_items_cm['product_id'])
                            {
                                $i = "true";
                            }                    
                        }
                     }
                    if($i != null)
                    {
                        unset($item_returns[$key_items_cm]);
                    }           
                }            
            }
            // <-- end bundle            
        }
        $data["status"] = null;
        $data["status_message"] = null;

        if(count($item_serial) > 0)
        {
            //CHECK IF SERIAL NUMBER IS EXISTING
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {
                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $data["status"] = "error";
                    $data["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_existing_to_credit($item_serial[$key_item_serial], "credit_memo-".$cm_id))
                {
                    $data["status"] = "error";
                    $data["status_message"] .= ItemSerial::check_existing_to_credit($item_serial[$key_item_serial], "credit_memo-".$cm_id);
                }
            }
        }
        if($data["status"] == null)
        {
            if($ctr_items != 0)
            {
                CreditMemo::updateCM($cm_id, $customer_info, $item_info);
                
                if(count($item_returns) > 0)
                {
                    $transaction_id = $cm_id;
                    $transaction_type = "credit_memo";
                    $json = Warehouse::inventory_update_returns($transaction_id, $transaction_type, $item_returns, $return = 'array');

                    /*REFILL SERIAL THRU CREDIT MEMO*/
                    if(count($item_serial) > 0)
                    {
                        $transaction = "credit_memo-".$cm_id;
                        ItemSerial::return_original_serial_debit_credit($transaction);
                        ItemSerial::update_refill_to_credit($item_serial, $transaction);
                    }
                }

                $data["status"] = "success-credit-memo";
                //$data["redirect_to"] = "/member/customer/credit_memo?id=".$cm_id;

                if ($btn_action == 'save-and-close')
                {
                    $data['redirect_to'] = '/member/customer/credit_memo/list';
                }
                elseif ($btn_action == 'save-and-edit')
                {
                    $data['redirect_to'] = '/member/customer/credit_memo?id='.$cm_id;
                }
                elseif ($btn_action == 'save-and-new')
                {
                    $data['redirect_to'] = '/member/customer/credit_memo';
                }
                elseif ($btn_action == 'save-and-print')
                {
                    $data['redirect_to'] = '/member/customer/credit_memo/view_pdf/'.$cm_id;
                }
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "Please Insert Item";
            }
        }

        return json_encode($data);
    }
    public function cm_list()
    {
        $data["pis"] = Purchasing_inventory_system::check();

        $data["_cm"] = Tbl_credit_memo::inv()->manual_cm()->customer()->orderBy("tbl_credit_memo.cm_id","DESC")->where("tbl_customer.shop_id", $this->getShopId())->get();
        
        return view("member.customer.credit_memo.cm_list",$data);

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
