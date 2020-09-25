<?php
namespace App\Http\Controllers\Member;

use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Pdf_global;
use App\Globals\CreditMemo;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Transaction;
use App\Globals\Customer;
use App\Globals\Estimate;
use App\Globals\ItemSerial;
use App\Globals\ReceivePayment;

use App\Models\Tbl_customer;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user;
use App\Models\Tbl_terms;
use App\Models\Tbl_customer_address;

use Request;
use Carbon\Carbon;
use Session;
use Redirect;
use PDF;

class Customer_InvoiceController extends Member
{
    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    /* arcy 8/17/17 */
    public function index()
    {
        Session::forget('est_item');

        $data["serial"] = ItemSerial::check_setting();

        $data["page"]       = "Customer Invoice";
        $data["pis"]        = Purchasing_inventory_system::check();
        //dd($data["pis"]);
        $data["_customer"]  = Customer::getAllCustomer();
        $data["_terms"]     = Customer::getTerms($this->user_info->shop_id,0);

        $data['_item']      = Item::get_all_category_item();
        $data['_cm_item']   = Item::get_returnable_item();

        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["action"]     = "/member/customer/invoice/create";
        $data["new_inv_id"] = Transaction::get_last_number("tbl_customer_invoice","new_inv_id","inv_shop_id"); 
        $data["c_id"] = Request::input("customer_id");
        $data["_estimate"] = Tbl_customer_estimate::where("est_customer_id",$data["c_id"])->where("est_status",'accepted')->get();
        $id = Request::input('id');
        if($id)
        {
            $data["inv"]            = Tbl_customer_invoice::where("inv_id", $id)->first();
            $data["_estimate"] = Tbl_customer_estimate::where("est_customer_id",$data["inv"]->inv_customer_id)->where("est_status",'accepted')->get();
            
            $data["_invline"]       = Tbl_customer_invoice_line::um()->where("invline_inv_id", $id)->get();

            foreach ($data["_invline"] as $key => $value) 
            {
                $data["_invline"][$key]->serial_number = ItemSerial::get_consume_serial("invoice",$id,$value->invline_item_id);
            }

            $data["_cmline"]       = Tbl_customer_invoice::returns_item()->where("inv_id", $id)->get();
            $data["action"]         = "/member/customer/invoice/update";

            // dd($data["inv"]);

            $sir = Tbl_manual_invoice::where("inv_id",$id)->first();
            if($sir)
            {
                $data["sir_id"] = $sir->sir_id;
                $data["action"] = "/member/customer/invoice/manual_invoice_update";
                $data['_item'] = Item::get_all_item_sir($sir->sir_id);
            }
        }
        
        foreach ($data["_customer"] as $key => $value) 
        {
            $address = Tbl_customer_address::where("customer_id", $value->customer_id)->where("purpose", "billing")->first();
            $data["_customer"][$key]->billing_address = isset($address->customer_street) ? $address->customer_street : "None";
        }
        return view('member.customer_invoice.customer_invoice', $data);
    }

    public function invoice_list()
    {
        $data["_invoices"] = Tbl_customer_invoice::manual_invoice()->customer()->orderBy("tbl_customer_invoice.inv_id","DESC")->where("inv_shop_id",$this->user_info->shop_id)
            ->where("is_sales_receipt",0)->get();
        $data["_invoices_unpaid"] = Tbl_customer_invoice::manual_invoice()->customer()->orderBy("tbl_customer_invoice.inv_id","DESC")->where("inv_shop_id",$this->user_info->shop_id)
            ->where("is_sales_receipt",0)->where("inv_is_paid",0)->get();
        
        $data["_invoices_paid"] = Tbl_customer_invoice::manual_invoice()->customer()->orderBy("tbl_customer_invoice.inv_id","DESC")->where("inv_shop_id",$this->user_info->shop_id)
            ->where("is_sales_receipt",0)->where("inv_is_paid",1)->get();

        foreach ($data["_invoices"] as $key => $value) 
        {
            $orig_price = $value->inv_overall_price;
            $cm = Tbl_credit_memo::where("cm_id",$value->credit_memo_id)->first();
            if($cm)
            {
              $data["_invoices"][$key]->inv_overall_price = $value->inv_overall_price - $cm->cm_amount;
            }
            $data["_invoices"][$key]->inv_balance = ReceivePayment::getBalance($this->user_info->shop_id, $value->inv_id, $orig_price);
        }
        foreach ($data["_invoices_unpaid"] as $key1 => $value1) 
        {
            $orig_price = $value1->inv_overall_price;
            $cm = Tbl_credit_memo::where("cm_id",$value1->credit_memo_id)->first();
            if($cm)
            {
              $data["_invoices_unpaid"][$key1]->inv_overall_price = $value1->inv_overall_price - $cm->cm_amount;
            }
            $data["_invoices_unpaid"][$key1]->inv_balance = ReceivePayment::getBalance($this->user_info->shop_id, $value1->inv_id, $orig_price);

        }
        foreach ($data["_invoices_paid"] as $key2 => $value2) 
        {
            $orig_price = $value2->inv_overall_price;
            $cm = Tbl_credit_memo::where("cm_id",$value2->credit_memo_id)->first();
            if($cm)
            {
              $data["_invoices_paid"][$key2]->inv_overall_price = $value2->inv_overall_price - $cm->cm_amount;
            }
            $data["_invoices_paid"][$key2]->inv_balance = ReceivePayment::getBalance($this->user_info->shop_id, $value2->inv_id, $orig_price);
        }


        $data['check_user'] = Purchasing_inventory_system::check();

        return view("member.customer_invoice.customer_invoice_list",$data);
    }
    public function create_invoice()
    {
        // dd(Request::input()); //INVOICE
        $button_action = Request::input('button_action');
        $serial_number = Request::input('serial_number');

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
        $invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $product_consume = [];
        $item_serial = [];

        $check_single = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $item_info[$key]['item_service_date']  = Request::input('invline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(",", "", Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = convertToNumber(Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = isset(Request::input('invline_discount')[$key]) ? Request::input('invline_discount')[$key] : 0;
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['amount']             = convertToNumber(Request::input('invline_amount')[$key]);
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['ref_name']           = Request::input('invline_ref_name')[$key];
                $item_info[$key]['ref_id']             = Request::input('invline_ref_id')[$key];
                
                $item_type = Tbl_item::where("item_id",Request::input('invline_item_id')[$key])->value("item_type_id");

                if ($item_type == 1) 
                {
                    $check_single[$key] = $item_info;
                }
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("invline_um")[$key]);
                    $product_consume[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                    $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];

                    if($serial_number[$key])
                    {
                        $item_serial[$key]["quantity"] = $um_qty * $item_info[$key]['quantity'];
                        $item_serial[$key]["item_id"] = Request::input('invline_item_id')[$key];
                        $item_serial[$key]["serials"] = $serial_number[$key];                        
                    }
                }
            }
        }
        //START if bundle inventory_consume arcy
        $check_bundle = [];
        foreach ($_itemline as $keyitem => $value_item) 
        {
            $item_bundle_info = Tbl_item::where("item_id",Request::input("invline_item_id")[$keyitem])->where("item_type_id",4)->first();
            if($item_bundle_info)
            {
                $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("invline_item_id")[$keyitem])->get();
                $check_bundle[$keyitem] = $bundle;
                foreach ($check_bundle[$keyitem] as $key0 => $value0) 
                {
                    $check_bundle[$keyitem][$key0]->bundle_quantity = str_replace(",", "", Request::input('invline_qty')[$keyitem]);
                }
                foreach ($bundle as $key_bundle => $value_bundle) 
                {
                    $qty = UnitMeasurement::um_qty(Request::input("invline_um")[$keyitem]);
                    $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                    $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                    $_bundle[$key_bundle]['quantity'] = (str_replace(",", "", Request::input('invline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                    array_push($product_consume, $_bundle[$key_bundle]);
                }
            } 
        }

        foreach ($product_consume as $key_items => $value_items) 
        {
             $i = null;
             foreach ($_itemline as $keyitemline => $valueitemline)
             {
                $type = Tbl_item::where("item_id",Request::input("invline_item_id")[$keyitemline])->value("item_type_id");
                if($type == 4)
                {
                    if(Request::input("invline_item_id")[$keyitemline] == $value_items['product_id'])
                    {
                        $i = "true";
                    }                    
                }
             }
            if($i != null)
            {
                unset($product_consume[$key_items]);
            }         
        }
        //END if bundle inventory_consume arcy
        $check_stock = $this->check_stock($check_single, $check_bundle);

        if ($check_stock) 
        {
            return json_encode($check_stock);
        }

        $json["status"] = null;
        $json["status_message"] = null;
        if(count($item_serial) > 0)
        {
            //CHECK IF SERIAL NUMBER IS EXISTING
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {

                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $json["status"] = "error";
                    $json["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_existing($item_serial[$key_item_serial]))
                {
                    $json["status"] = "error";
                    $json["status_message"] .= ItemSerial::check_existing($item_serial[$key_item_serial]);
                }
            }
        }

        //CREDIT MEMO / RETURNS
        $cm_customer_info[] = null;
        $cm_item_info = null;
        $item_returns = null;    
        if(Request::input("returns") != null && Purchasing_inventory_system::check() != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            $cm_item_info[] = null;
            $_cm_items = Request::input("cmline_item_id");
            if($_cm_items != null)
            {
                foreach ($_cm_items as $keys => $values) 
                { 
                    if($values != null)
                    {
                        $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                        $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                        $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                        $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                        $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                        $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                        $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                
                        $item_type = Tbl_item::where("item_id",Request::input('cmline_item_id')[$keys])->value("item_type_id");
                        if($item_type == 4 || $item_type == 1)
                        {
                            $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                            $item_returns[$keys]["quantity"] = $um_qty * $cm_item_info[$keys]['quantity'];
                            $item_returns[$keys]["product_id"] = Request::input('cmline_item_id')[$keys];                    
                        }
                    }          
                } 
                // --> for bundles
                foreach ($_cm_items as $keyitem_cm => $value_item) 
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
                if($item_returns != null)
                {
                    foreach ($item_returns as $key_items_cm => $value_items_cm) 
                    {
                         $i = null;
                         foreach ($_cm_items as $keyitemline_cm => $valueitemline)
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

        }
        // END CM/RETURNS
        $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'));

        if(!$json["status"])
        {
            if($inv == 0 || Request::input("keep_val") == "keep")
            {

                $inv_id = Invoice::postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);
                
                if(count(Session::get('est_item')) > 0)
                {
                    Estimate::update_all_estimate(Session::get('est_item'), $inv_id);
                }
                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $inv_id);

                    $cm_remarks            = "Returns Items with Invoice # ". Request::input('new_invoice_id');
                    $cm_warehouse_id       = $this->current_warehouse->warehouse_id;
                    $cm_transaction_type   = "credit_memo";
                    $cm_transaction_id     = $cm_id;
                    $cm_data               = Warehouse::inventory_refill($cm_warehouse_id, $cm_transaction_type, $cm_transaction_id, $cm_remarks, $item_returns, 'array' ,"returns");
                }
                
                if(count($product_consume) > 0)
                {
                    if (Purchasing_inventory_system::check()) 
                    {
                        $allow_out_of_stock = false;
                    }
                    else
                    {
                        $allow_out_of_stock = true;
                    }

                    $remarks            = "Consume by Invoice #".Request::input('new_invoice_id');
                    $warehouse_id       = $this->current_warehouse->warehouse_id;
                    $transaction_type   = "invoice";
                    $transaction_id     = $inv_id;
                    $data               = Warehouse::inventory_consume($warehouse_id, $remarks, $product_consume, 0, '' ,  'array', $transaction_type, $transaction_id, $allow_out_of_stock, $item_serial);
                }

                if (isset($data["status"]) && isset($data["status_message"]) && $data["status_message"] > 0) 
                {
                    $json["status"]         = "error";
                    $json["status_message"] = $data["status_message"];

                    $json["redirect"]       = "/member/customer/invoice?id=" . $inv_id;
                    
                    Request::session()->flash('error', $data["status_message"]);
                }
                else
                {
                    $json["status"]         = "success-invoice";

                    /*if($button_action == "save-and-edit")
                    {
                        $json["redirect"]    = "/member/customer/invoice_list";
                    }
                    elseif($button_action == "save-and-new")
                    {
                        $json["redirect"]   = '/member/customer/invoice';
                    }*/

                    if($button_action == "save-and-close")
                    {
                        $json["redirect"]    = "/member/customer/invoice_list";
                    }
                    elseif($button_action == "save-and-new")
                    {
                        $json["redirect"]   = '/member/customer/invoice';
                    }
                    elseif($button_action == "save-and-edit")
                    {
                        $json["redirect"]   = '/member/customer/invoice?id='.$inv_id;
                    }
                    elseif($button_action == "save-and-print")
                    {
                        $json["redirect"]   = '/member/customer/customer_invoice_pdf/'.$inv_id;
                    }



                    Request::session()->flash('success', 'Invoice Successfully Created');
                }
            }
            else
            {
                $json["inv_id"] = Request::input("new_invoice_id");            
                $json["status"] = "error-inv-no";
            }

        }

        return json_encode($json);
    }
    public function error_inv_no($id)
    {
        $data["inv_id"] = $id;

        return view("member.customer_invoice.confirm_invoice_number",$data);
    }
    public function update_invoice()
    {
        $invoice_id     = Request::input("invoice_id");
        $button_action  = Request::input('button_action');
        $serial_number = Request::input('serial_number');

        $customer_info                      = [];
        $customer_info['customer_id']       = Request::input('inv_customer_id');;
        $customer_info['customer_email']    = Request::input('inv_customer_email');

        $invoice_info                       = [];
        $invoice_info['invoice_terms_id']   = Request::input('inv_terms_id');
        $invoice_info['invoice_date']       = datepicker_input(Request::input('inv_date'));
        $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $invoice_info['invoice_due']        = datepicker_input(Request::input('inv_due_date'));
        $invoice_info['billing_address']    = Request::input('inv_customer_billing_address');

        $invoice_other_info                 = [];
        $invoice_other_info['invoice_msg']  = Request::input('inv_message');
        $invoice_other_info['invoice_memo'] = Request::input('inv_memo');

        $total_info                         = [];
        $total_info['ewt']                  = Request::input('ewt');
        $total_info['total_discount_type']  = Request::input('inv_discount_type');
        $total_info['total_discount_value'] = Request::input('inv_discount_value');
        $total_info['taxable']              = Request::input('taxable');

        $item_info                          = [];
        $_itemline                          = Request::input('invline_item_id');

        $product_consume = [];
        $item_serial = [];

        $check_single = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $item_info[$key]['item_service_date']  = Request::input('invline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('invline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('invline_description')[$key];
                $item_info[$key]['um']                 = Request::input('invline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(",", "", Request::input('invline_qty')[$key]);
                $item_info[$key]['rate']               = convertToNumber(Request::input('invline_rate')[$key]);
                $item_info[$key]['discount']           = isset(Request::input('invline_discount')[$key]) ? Request::input('invline_discount')[$key] : 0;
                $item_info[$key]['discount_remark']    = Request::input('invline_discount_remark')[$key];
                $item_info[$key]['taxable']            = Request::input('invline_taxable')[$key];
                $item_info[$key]['amount']             = convertToNumber(Request::input('invline_amount')[$key]);
                $item_info[$key]['ref_name']           = Request::input('invline_ref_name')[$key];
                $item_info[$key]['ref_id']             = Request::input('invline_ref_id')[$key];

                $item_type = Tbl_item::where("item_id",Request::input('invline_item_id')[$key])->value("item_type_id");

                if ($item_type == 1) 
                {
                    $check_single[$key] = $item_info;
                }
                if($item_type == 4 || $item_type == 1)
                {
                    $qty = UnitMeasurement::um_qty(Request::input("invline_um")[$key]);
                    $product_consume[$key]["quantity"] = $qty * $item_info[$key]['quantity'];
                    $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];

                    if($serial_number[$key])
                    {
                        $item_serial[$key]["quantity"] = $qty * $item_info[$key]['quantity'];
                        $item_serial[$key]["item_id"] = Request::input('invline_item_id')[$key];
                        $item_serial[$key]["serials"] = $serial_number[$key];                        
                    }
                }
            }
        }

        //START if bundle inventory_consume arcy
        $check_bundle = [];
        foreach ($_itemline as $keyitem => $value_item) 
        {
            $item_bundle_info = Tbl_item::where("item_id", Request::input("invline_item_id")[$keyitem])->where("item_type_id",4)->first();
            if($item_bundle_info)
            {
                $bundle = Tbl_item_bundle::where("bundle_bundle_id", Request::input("invline_item_id")[$keyitem])->get();
                $check_bundle[$keyitem] = $bundle;
                foreach ($check_bundle[$keyitem] as $key0 => $value0) 
                {
                    $check_bundle[$keyitem][$key0]->bundle_quantity = str_replace(",", "", Request::input('invline_qty')[$keyitem]);
                }
                foreach ($bundle as $key_bundle => $value_bundle) 
                {
                    $qty = UnitMeasurement::um_qty(Request::input("invline_um")[$keyitem]);
                    $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                    $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                    $_bundle[$key_bundle]['quantity'] = (str_replace(",", "", Request::input('invline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                    array_push($product_consume, $_bundle[$key_bundle]);
                }
            } 
        }
        foreach ($product_consume as $key_items => $value_items) 
        {
             $i = null;
             foreach ($_itemline as $keyitemline => $valueitemline)
             {
                $type = Tbl_item::where("item_id",Request::input("invline_item_id")[$keyitemline])->value("item_type_id");
                if($type == 4)
                {
                    if(Request::input("invline_item_id")[$keyitemline] == $value_items['product_id'])
                    {
                        $i = "true";
                    }                    
                }
             }
            if($i != null)
            {
                unset($product_consume[$key_items]);
            }           
        }
        //END if bundle inventory_consume arcy
        $check_stock = $this->check_stock($check_single, $check_bundle);

        if ($check_stock) 
        {
            return json_encode($check_stock);
        }

        //CREDIT MEMO / RETURNS
        $cm_customer_info[] = null;
        $item_returns = null; 
        $_cm_items = Request::input("cmline_item_id");
        $cm_item_info = null;
        if(Request::input("returns") != null && Purchasing_inventory_system::check() != 0)
        {
            $cm_customer_info["cm_customer_id"] = Request::input('inv_customer_id');
            $cm_customer_info["cm_customer_email"] = Request::input('inv_customer_email');
            $cm_customer_info["cm_date"] = datepicker_input(Request::input('inv_date'));
            $cm_customer_info["cm_message"] = "";
            $cm_customer_info["cm_memo"] = "";
            $cm_customer_info["cm_amount"] = str_replace(",","",Request::input("subtotal_price_returns"));

            foreach ($_cm_items as $keys => $values) 
            {  
                if($values != "")
                {      
                    $cm_item_info[$keys]['item_service_date']  = datepicker_input(Request::input('cmline_service_date')[$keys]);
                    $cm_item_info[$keys]['item_id']            = Request::input('cmline_item_id')[$keys];
                    $cm_item_info[$keys]['item_description']   = Request::input('cmline_description')[$keys];
                    $cm_item_info[$keys]['um']                 = Request::input('cmline_um')[$keys];
                    $cm_item_info[$keys]['quantity']           = str_replace(',', "",Request::input('cmline_qty')[$keys]);
                    $cm_item_info[$keys]['rate']               = str_replace(',', "", Request::input('cmline_rate')[$keys]);
                    $cm_item_info[$keys]['amount']             = str_replace(',', "", Request::input('cmline_amount')[$keys]);
                   
                    $item_type = Tbl_item::where("item_id",Request::input('cmline_item_id')[$key])->value("item_type_id");
                    if($item_type == 4 || $item_type == 1)
                    {
                        $um_qty = UnitMeasurement::um_qty(Request::input("cmline_um")[$keys]);
                        $item_returns[$keys]["quantity"] = $um_qty * $cm_item_info[$keys]['quantity'];
                        $item_returns[$keys]["product_id"] = Request::input('cmline_item_id')[$keys];
                    }
                }   
            }            
        }
        if($_cm_items != null)
        {
             // --> for bundles
            foreach ($_cm_items as $keyitem_cm => $value_item) 
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
            if($item_returns != null)
            {
                foreach ($item_returns as $key_items_cm => $value_items_cm) 
                {
                     $i = null;
                     foreach ($_cm_items as $keyitemline_cm => $valueitemline)
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


        $json["status"] = null;
        $json["status_message"] = null;
        if(count($item_serial) > 0)
        {
            //CHECK IF SERIAL NUMBER IS EXISTING
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {

                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $json["status"] = "error";
                    $json["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_existing($item_serial[$key_item_serial],"invoice",$invoice_id))
                {
                    $json["status"] = "error";
                    $json["status_message"] .= ItemSerial::check_existing($item_serial[$key_item_serial],"invoice",$invoice_id);
                }
            }
        }

        if(!$json["status"])
        {
            Invoice::updateIsPaid($invoice_id);
            $inv = Transaction::check_number_existense("tbl_customer_invoice","new_inv_id","inv_shop_id",Request::input('new_invoice_id'));
             if($inv <= 1 || Request::input("keep_val") == "keep")
            {
                $inv_id = Invoice::updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info);
                if(count(Session::get('est_item')) > 0)
                {
                    Estimate::update_all_estimate(Session::get('est_item'), $inv_id);
                }

                if($cm_customer_info != null && $cm_item_info != null)
                {
                    $credit_memo_id = Tbl_customer_invoice::where("inv_id",$inv_id)->value("credit_memo_id");
                    if($credit_memo_id != null)
                    {
                        $cm_id = CreditMemo::updateCM($credit_memo_id, $cm_customer_info, $cm_item_info);
                        $transaction_id = $credit_memo_id;
                        $transaction_type = "credit_memo";
                        $json = Warehouse::inventory_update_returns($transaction_id, $transaction_type, $item_returns, $return = 'array');
                    }
                    else
                    {
                        //
                        $cm_id = CreditMemo::postCM($cm_customer_info, $cm_item_info, $inv_id);

                        $cm_remarks            = "Returns Items with Invoice # ". Request::input('new_invoice_id');
                        $cm_warehouse_id       = $this->current_warehouse->warehouse_id;
                        $cm_transaction_type   = "credit_memo";
                        $cm_transaction_id     = $cm_id;
                        $cm_data               = Warehouse::inventory_refill($cm_warehouse_id, $cm_transaction_type, $cm_transaction_id, $cm_remarks, $item_returns, 'array' ,"returns");

                    }
                }
                if(count($product_consume) > 0)
                {
                    if (Purchasing_inventory_system::check()) 
                    {
                        $allow_out_of_stock = false;
                    }
                    else
                    {
                        $allow_out_of_stock = true;
                    }

                    $transaction_id = $inv_id;
                    $transaction_type = "invoice";
                    $json = Warehouse::inventory_update($transaction_id, $transaction_type, $product_consume, $return = 'array', $allow_out_of_stock, $item_serial);
                }

                if($json["status"] == "success")
                {
                    $json["status"]         = "success-invoice";
                    $json["invoice_id"]     = $inv_id;
                    $json["redirect"]           = "/member/customer/invoice_list";

                    if($button_action == "save-and-new")
                    {
                        $json["redirect"]   = '/member/customer/invoice';
                    }
                    if($button_action == "save-and-close")
                    {
                        $json["redirect"]    = "/member/customer/invoice_list";
                    }
                    elseif($button_action == "save-and-edit")
                    {
                        $json["redirect"]   = '/member/customer/invoice?id='.$inv_id;
                    }
                    elseif($button_action == "save-and-print")
                    {
                        $json["redirect"]   = '/member/customer/customer_invoice_pdf/'.$inv_id;
                    }
                    Request::session()->flash('success', 'Invoice Successfully Updated');
                }
                elseif($json["status"] == "error" && count($json["status_message"]) > 0)
                {
                    $json["status"]         = "error";
                    $json["invoice_id"]     = $inv_id;
                    $json["redirect"]       = "/member/customer/invoice?id=" . $inv_id;

                    Request::session()->flash('error', $json["status_message"]);
                }
            }
            else
            {
                $json["inv_id"] = Request::input("new_invoice_id");            
                $json["status"] = "error-inv-no";
            }
        }


       
        return json_encode($json);
    }

    public function  return_to_number($number = '0.00'){
    	$num = explode($number);
    	$return = '';
    	foreach($num as $n){
    		$return .= ''.$n;
    	}
    	return $return;
    }
    public function invoice_view($invoice_id)
    {
        $data["invoice_id"] = $invoice_id;

        $data["transaction_type"] = "INVOICE";
        $invoice_data = Tbl_customer_invoice::where("inv_id",$invoice_id)->first();
        if($invoice_data)
        {
            if($invoice_data->is_sales_receipt != 0)
            {
                $data["transaction_type"] = "Sales Receipt";
            }
            $data['new_inv_id'] = $invoice_data->new_inv_id;
        }

        $data["action_load"] = "/member/customer/customer_invoice_pdf";
        return view("member.customer_invoice.invoice_view",$data);
    }
    public function invoice_view_pdf($inv_id)
    {
        $date = date("F j, Y, g:i a");
        $first_name         = $this->user_info->user_first_name;
        $last_name         = $this->user_info->user_last_name;

        $footer ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

        $data["invoice"] = Tbl_customer_invoice::customer()->where("inv_id",$inv_id)->first();
        $data["transaction_type"] = "INVOICE";
        if(Tbl_customer_invoice::where("inv_id",$inv_id)->value("is_sales_receipt") != 0)
        {
            $data["transaction_type"] = "Sales Receipt";            
        }

        $data["invoice_item"] = Tbl_customer_invoice_line::invoice_item()->where("invline_inv_id",$inv_id)->get();
        foreach($data["invoice_item"] as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->invline_um);

            $total_qty = $value->invline_qty * $qty;
            $data["invoice_item"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }

        $data["cm"] = null;
        $data["_cmline"] = null;
        if($data["invoice"] != null)
        {
            $data["cm"] = Tbl_credit_memo::where("cm_id",$data["invoice"]->credit_memo_id)->first();
            $data["_cmline"] = Tbl_credit_memo_line::cm_item()->where("cmline_cm_id",$data["invoice"]->credit_memo_id)->get();

            foreach ($data["_cmline"] as $keys => $values)
            {
                $qtys = UnitMeasurement::um_qty($values->cmline_um);

                $total_qtys = $values->cmline_qty * $qtys;
                $data["_cmline"][$keys]->cm_qty = UnitMeasurement::um_view($total_qtys,$values->item_measurement_id,$values->cmline_um);
            }
        }
        $pdf = view('member.customer_invoice.invoice_pdf', $data);
        return Pdf_global::show_pdf($pdf, null, $footer);
    }

    public function check_stock($check_single, $check_bundle)
    {
        $product_consume['single'] = $check_single;
        $product_consume['bundle'] = $check_bundle;

        if (Purchasing_inventory_system::check()) 
        {
            $stock_validation = Warehouse::checkStock($product_consume, $this->current_warehouse->warehouse_id);
            
            if ($stock_validation["status"] == "error") 
            {
                $json["status"]         = "error";
                $json["status_message"] = $stock_validation["status_message"];
                $json["redirect"]       = "/member/customer/invoice";

                Request::session()->flash('error', $stock_validation["status_message"]);

                return $json;
            }
        }
    }
}