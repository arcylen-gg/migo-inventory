<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_customer;
use App\Models\Tbl_warehousea;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_bill_po;
use App\Models\Tbl_vendor;
use App\Models\Tbl_terms;

use App\Globals\Purchasing_inventory_system;
use App\Globals\Vendor;
use App\Globals\ItemSerial;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Purchase_Order;
use App\Globals\Billing;
use App\Globals\Item;
use App\Globals\Pdf_global;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\UnitMeasurement;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_bill;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_bill_item_line;
use Carbon\Carbon;
use Session;
use PDF;

class Vendor_ReceiveInventoryController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            $data["_bill_list"] = Tbl_bill::vendor()->where("bill_shop_id",Billing::getShopId())->orderby("bill_id","DESC")->where("inventory_only",1)->get();

            
            foreach ($data["_bill_list"] as $key => $value) 
            {
               $price = 0;
               $item = Tbl_bill_item_line::where("itemline_bill_id",$value->bill_id)->get();

               foreach ($item as $key_item => $value_item) 
               {             
                    $price += (UnitMeasurement::um_qty($value_item->itemline_um) * $value_item->itemline_qty) * $value_item->itemline_rate;
               }

               $data["_bill_list"][$key]->bill_price = $price;

            }

            return view("member.receive_inventory.receive_inventory_list",$data);
        }
        else
        {            
            return $this->show_no_access();
        }
    }    

    public function ri_pdf($ri_id)
    {
        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            $date       = date("F j, Y, g:i a");
            $first_name = $this->user_info->user_first_name;
            $last_name  = $this->user_info->user_last_name;
            $footer     ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

            $data["ri"] = Tbl_bill::vendor()->where("bill_id",$ri_id)->first();
            $data["_riline"] = Tbl_bill_item_line::um()->item()->where("itemline_bill_id",$ri_id)->get();
            //dd($data["_riline"]);
            /*foreach($data["_poline"] as $key => $value) 
            {
                $qty = UnitMeasurement::um_qty($value->poline_um);

                $total_qty = $value->poline_orig_qty * $qty;
                $data["_poline"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->poline_um);
            }*/
            $pdf = view("member.vendor_list.ri_pdf",$data);
            return Pdf_global::show_pdf($pdf, null, $footer);
        }
        else
        {
             return $this->show_no_access();            
        }
    }
    public function receive_inventory()
    {
        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            // dd(Session::get("po_item"));
            Session::forget("po_item");
            $data['pis']        = Purchasing_inventory_system::check();
            $data["_vendor"]    = Vendor::getAllVendor('active');
            $data['_item']      = Item::get_all_category_item();
            $data['_account']   = Accounting::getAllAccount();
            $data['_um']        = UnitMeasurement::load_um_multi();
            $data["_terms"]     = Tbl_terms::where("archived", 0)->where("terms_shop_id", Billing::getShopId())->get();
            $data['action']     = "/member/vendor/receive_inventory/add";
            $data['vendor_id']     = Request::input("vendor_id");
            $data['pis']        = Purchasing_inventory_system::check();
            
            $data["_po"] = Tbl_purchase_order::where("po_vendor_id",Request::input("vendor_id"))->where("po_is_billed",0)->get();

            $id = Request::input("id");
            if($id)
            {
               $data["bill"] = Tbl_bill::where("bill_id",$id)->first();
               $data["_po"] = Tbl_purchase_order::where("po_vendor_id",$data["bill"]->bill_vendor_id)->where("po_is_billed",0)->get();
               $data["_bill_item_line"] = Tbl_bill_item_line::um()->where("itemline_bill_id",$id)->get();
               //dd($data["_bill_item_line"]);
               foreach ($data["_bill_item_line"] as $key => $value) 
               {
                    $data["_bill_item_line"][$key]->serial_number = ItemSerial::get_serial("receive_inventory",$id,$value->itemline_item_id);
               }
               $data['_item']      = Item::get_all_category_item();
               $data['_account']   = Accounting::getAllAccount();
               $data['action']     = "/member/vendor/receive_inventory/update";
            }
            

            $data["serial"] = ItemSerial::check_setting();
            
            return view("member.receive_inventory.receive_inventory",$data);
        }
        else
        {
            return $this->show_no_access();            
        }
    }
    public function load_purchase_order($vendor_id)
    {
        $data["_po"] = Tbl_purchase_order::where("po_vendor_id",$vendor_id)->where("po_is_billed",0)->get();

        return view("member.load_ajax_data.load_purchase_order",$data);
    }
    public function load_po_item()
    {
        $po_id = Request::input("po_id");
        $data = Tbl_purchase_order_line::um()->where("poline_po_id",$po_id)->get();

        return json_encode($data);
    }
    public function add_receive_inventory()
    {
        $button_action = Request::input('button_action');

        $serial_number = Request::input("serial_number");
        
        $vendor_info                         = [];
        $vendor_info['bill_vendor_id']       = Request::input('bill_vendor_id');
        $vendor_info['bill_vendor_email']    = Request::input('bill_vendor_email');
        $vendor_info['bill_mailing_address'] = Request::input('bill_mailing_address');

        $bill_info                          = [];
        $bill_info['bill_terms_id']         = Request::input('bill_terms_id');
        // $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $bill_info['bill_date']             = datepicker_input(Request::input('bill_date'));
        $bill_info['bill_due_date']         = datepicker_input(Request::input('bill_due_date'));
        $bill_info['inventory_only']        = 1;

        $bill_other_info                    = [];
        $bill_other_info['bill_memo']       = Request::input('bill_memo');

        $total_info                         = [];
        $total_info['bill_total_amount']    = Request::input('bill_total_amount');

        $item_info                          = [];
        $_itemline                          = Request::input('itemline_item_id');

        $ctr_items = 0;
        $item_refill = [];
        $item_serial = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr_items++;
                $item_info[$key]['itemline_ref_name']     = Request::input('itemline_ref_name')[$key];
                $item_info[$key]['itemline_ref_id']       = Request::input('itemline_ref_id')[$key];
                
                $item_info[$key]['itemline_description']  = Request::input('itemline_description')[$key];
                $item_info[$key]['itemline_um']           = Request::input('itemline_um')[$key];
                $item_info[$key]['itemline_item_id']      = Request::input('itemline_item_id')[$key];
                $item_info[$key]['itemline_qty']          = str_replace(",","", Request::input('itemline_qty')[$key]);
                $item_info[$key]['itemline_rate']         = str_replace(",","", Request::input('itemline_rate')[$key]);
                $item_info[$key]['itemline_amount']       = str_replace(",","", Request::input('itemline_amount')[$key]);
            
                $item_type = Tbl_item::where("item_id",Request::input('itemline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$key]);
                    $item_refill[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_refill[$key]["product_id"] = Request::input('itemline_item_id')[$key];   
                    
                    // $serial_number[$key]
                    $item_serial[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_serial[$key]["item_id"] = Request::input('itemline_item_id')[$key];
                    $item_serial[$key]["serials"] = $serial_number[$key];
                }
            }
        }
        // --> for bundles
        foreach ($_itemline as $keyitem => $value_item) 
        {
            if($value_item != null)
            {
                $item_bundle_info = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitem])->where("item_type_id",4)->first();
                if($item_bundle_info)
                {
                    $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("itemline_item_id")[$keyitem])->get();
                    foreach ($bundle as $key_bundle => $value_bundle) 
                    {
                        $qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$keyitem]);
                        $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                        $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                        $_bundle[$key_bundle]['quantity'] = (str_replace(",","",Request::input('itemline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                        array_push($item_refill, $_bundle[$key_bundle]);
                    }
                }                 
            }
        }
        if($item_refill != null)
        {
            foreach ($item_refill as $key_items => $value_items) 
            {
                 $i = null;
                 foreach ($_itemline as $keyitemline => $valueitemline)
                 {
                    $type = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitemline])->value("item_type_id");
                    if($type == 4)
                    {
                        if(Request::input("itemline_item_id")[$keyitemline] == $value_items['product_id'])
                        {
                            $i = "true";
                        }                    
                    }
                 }
                if($i != null)
                {
                    unset($item_refill[$key_items]);
                }           
            }
        }
        // <-- end bundle

        $json["status"] = null;
        $json["status_message"] = null;
        if(count($item_serial) > 0)
        {
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {
                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $json["status"] = "error";
                    $json["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_duplicate_serial($value_item_serial["item_id"],$value_item_serial["serials"]))
                {
                    $json["status"] = "error";
                    $json["status_message"] .= ItemSerial::check_duplicate_serial($value_item_serial["item_id"],$value_item_serial["serials"]);
                }
            }

        }

        if($json["status"] == null)
        {
            if($ctr_items != 0)
            {
                $bill_id = Billing::postBill($vendor_info, $bill_info, $bill_other_info, $item_info, $total_info);

                if(count(Session::get("po_item")) > 0)
                {
                    Billing::insertPotoBill($bill_id, Session::get("po_item"));
                }
                
                if(count($item_refill) > 0)
                {
                    $remarks            = "Refill Items with RECEIVE INVENTORY #". $bill_id;
                    $warehouse_id       = $this->current_warehouse->warehouse_id;
                    $transaction_type   = "receive_inventory";
                    $transaction_id     = $bill_id;
                    $data               = Warehouse::inventory_refill($warehouse_id, $transaction_type, $transaction_id, $remarks, $item_refill, 'array', null, $item_serial);
                }

                $json["status"]         = "success-receive-inventory";
                if($button_action == "save-and-edit")
                {
                    $json["redirect"]    = "/member/vendor/receive_inventory?id=".$bill_id;
                }
                elseif($button_action == "save-and-new")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory';
                }
                elseif($button_action == "save-and-close")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory/list';
                }
                elseif($button_action == "save-and-print")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory/view_pdf/'.$bill_id;
                }
                Request::session()->flash('success', 'Successfully Created');
            }
            else
            {
                $json["status"] = "error";
                $json["status_message"] = "Please insert Item.";
            }

        }


        return json_encode($json);

    }
    public function update_receive_inventory()
    {
        $bill_id = Request::input("bill_id");
        $button_action = Request::input('button_action');
        $serial_number = Request::input('serial_number');

        $vendor_info                         = [];
        $vendor_info['bill_vendor_id']       = Request::input('bill_vendor_id');
        $vendor_info['bill_vendor_email']    = Request::input('bill_vendor_email');
        $vendor_info['bill_mailing_address'] = Request::input('bill_mailing_address');

        $bill_info                          = [];
        $bill_info['bill_terms_id']         = Request::input('bill_terms_id');
        // $invoice_info['new_inv_id']         = Request::input('new_invoice_id');
        $bill_info['bill_date']             = datepicker_input(Request::input('bill_date'));
        $bill_info['bill_due_date']         = datepicker_input(Request::input('bill_due_date'));
        $bill_info['inventory_only']        = 1;

        $bill_other_info                    = [];
        $bill_other_info['bill_memo']       = Request::input('bill_memo');

        $total_info                         = [];
        $total_info['bill_total_amount']    = Request::input('bill_total_amount');

        $item_info                          = [];
        $_itemline                          = Request::input('itemline_item_id');

        $ctr_items = 0;
        $item_refill = [];
        $item_serial = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr_items++;
                $item_info[$key]['itemline_ref_name']     = Request::input('itemline_ref_name')[$key];
                $item_info[$key]['itemline_ref_id']       = Request::input('itemline_ref_id')[$key];

                $item_info[$key]['itemline_description']  = Request::input('itemline_description')[$key];
                $item_info[$key]['itemline_um']           = Request::input('itemline_um')[$key];
                $item_info[$key]['itemline_item_id']      = Request::input('itemline_item_id')[$key];
                $item_info[$key]['itemline_qty']          = str_replace(",","", Request::input('itemline_qty')[$key]);
                $item_info[$key]['itemline_rate']         = str_replace(",","", Request::input('itemline_rate')[$key]);
                $item_info[$key]['itemline_amount']       = str_replace(",","", Request::input('itemline_amount')[$key]);

                $item_type = Tbl_item::where("item_id",Request::input('itemline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$key]);
                    $item_refill[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_refill[$key]["product_id"] = Request::input('itemline_item_id')[$key];
                 
                    $item_serial[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_serial[$key]["item_id"] = Request::input('itemline_item_id')[$key];
                    $item_serial[$key]["serials"] = $serial_number[$key];
                }
            }
        }
          // --> for bundles
        foreach ($_itemline as $keyitem => $value_item) 
        {
            if($value_item != null)
            {
                $item_bundle_info = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitem])->where("item_type_id",4)->first();
                if($item_bundle_info)
                {
                    $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("itemline_item_id")[$keyitem])->get();
                    foreach ($bundle as $key_bundle => $value_bundle) 
                    {
                        $qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$keyitem]);
                        $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                        $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                        $_bundle[$key_bundle]['quantity'] = (str_replace(",","",Request::input('itemline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                        array_push($item_refill, $_bundle[$key_bundle]);
                    }
                }                 
            }
        }

        if(count($item_refill) > 0)
        {
            foreach ($item_refill as $key_items => $value_items) 
            {
                 $i = null;
                 foreach ($_itemline as $keyitemline => $valueitemline)
                 {
                    $type = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitemline])->value("item_type_id");
                    if($type == 4)
                    {
                        if(Request::input("itemline_item_id")[$keyitemline] == $value_items['product_id'])
                        {
                            $i = "true";
                        }                    
                    }
                 }
                if($i != null)
                {
                    unset($item_refill[$key_items]);
                }           
            }
        }
        // <-- end bundle

        $json["status"] = null;
        $json["status_message"] = null;
        if(count($item_serial) > 0)
        {
            foreach ($item_serial as $key_item_serial => $value_item_serial)
            {
                $check_qty_serial = ItemSerial::check_item_serial($value_item_serial);

                if($check_qty_serial)
                {
                    $json["status"] = "error";
                    $json["status_message"] .= "The item ".Item::get_item_details($value_item_serial["item_id"])->item_name." has more serial than the quantity <br>";
                }

                if(ItemSerial::check_duplicate_serial($value_item_serial["item_id"],$value_item_serial["serials"]))
                {
                    $json["status"] = "error";
                    $json["status_message"] .= ItemSerial::check_duplicate_serial($value_item_serial["item_id"],$value_item_serial["serials"]);
                }

            }

        }

        if($json["status"] == null)
        {
            if($ctr_items != 0)
            {
                $bill_id = Billing::updateBill($bill_id, $vendor_info, $bill_info, $bill_other_info, $item_info, $total_info);

                if(count(Request::input("itemline_ref_id")) > 0)
                {
                    Billing::updatePotoBill($bill_id, Request::input("itemline_ref_id"));
                }
                if(count($item_refill) > 0)
                {
                    $transaction_id = $bill_id;
                    $transaction_type = "receive_inventory";
                    $json = Warehouse::inventory_update_returns($transaction_id, $transaction_type, $item_refill, $return = 'array', $item_serial);
                }

                $json["status"]         = "success-receive-inventory";
                /*if($button_action == "save-and-edit")
                {
                    $json["redirect"]    = "/member/vendor/receive_inventory/list";
                }
                elseif($button_action == "save-and-new")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory';
                }*/
                if($button_action == "save-and-edit")
                {
                    $json["redirect"]    = "/member/vendor/receive_inventory?id=".$bill_id;
                }
                elseif($button_action == "save-and-new")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory';
                }
                elseif($button_action == "save-and-close")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory/list';
                }
                elseif($button_action == "save-and-print")
                {
                    $json["redirect"]   = '/member/vendor/receive_inventory/view_pdf/'.$bill_id;
                }
                Request::session()->flash('success', 'Successfully Created');
            }
            else
            {
                $json["status"] = "error";
                $json["status_message"] = "Please insert Item.";
            }            
        }

        return json_encode($json);

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
