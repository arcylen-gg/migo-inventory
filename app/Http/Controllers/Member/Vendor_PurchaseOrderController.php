<?php
namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Billing;
use App\Globals\Pdf_global;
use App\Globals\Utilities;
use App\Globals\Purchasing_inventory_system;

use App\Models\Tbl_customer;
use App\Models\Tbl_warehousea;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_vendor;
use App\Globals\Vendor;
use App\Globals\AuditTrail;
use App\Globals\Purchase_Order;
use App\Globals\ItemSerial;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_terms;

use Carbon\Carbon;
use Session;
use Redirect;
use PDF;
class Vendor_PurchaseOrderController extends Member
{

    public function add_item($po_id)
    {
        $po_data = Tbl_purchase_order_line::um()->where("poline_po_id",$po_id)->get();
        
        foreach ($po_data as $key => $value) 
        {
            Session::push('po_item',collect($value)->toArray());
        }
        $data["ctr_item"] = count(Session::get("po_item"));

        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["serial"] = ItemSerial::check_setting();

        return view('member.load_ajax_data.load_po_session_item',$data);

    }
    public function remove_items($po_id)
    {
        $items = Session::get("po_item");

        foreach($items as $key => $value) 
        {
            if($value["poline_po_id"] == $po_id)
            {
                unset($items[$key]);
            }
        }

        Session::put("po_item",$items);

        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();

        return view('member.load_ajax_data.load_po_session_item',$data);
    }
    public function index()
    {
        $access = Utilities::checkAccess('vendor-purchase-order', 'access_page');
        if($access == 1)
        { 
            $data["page"]       = "Purchase order";
            $data['pis']        = Purchasing_inventory_system::check();
            
            $data["_vendor"]    = Vendor::getAllVendor('active');
            $data["_terms"]     = Tbl_terms::where("archived", 0)->where("terms_shop_id", Purchase_Order::getShopId())->get();
            $data['_item']      = Item::get_all_category_item();
            $data['_um']        = UnitMeasurement::load_um_multi();

            $data["action"]     = "/member/vendor/purchase_order/create_po";
            $data["v_id"]       = Request::input("vendor_id");
            $data["terms"]       = Request::input("term_id");

            //$data["print"] = Tbl_purchase_order::vendor()->where("po_shop_id",Purchase_Order::getShopId())->get();
            //dd($data["_po"]);
            $id = Request::input('id');

            if($id)
            {
                $data["po"]            = Tbl_purchase_order::where("po_id", $id)->first();
                /*$data['discount'] = */
                $data["_poline"]       = Tbl_purchase_order_line::um()->where("poline_po_id", $id)->get();
                $data["action"]         = "/member/vendor/purchase_order/update_po";
            }
             //dd($data);

            return view('member.purchase_order.purchase_order',$data);
        }
        else
        {
             return $this->show_no_access();
        }
    }
    public function po_list()
    {
        $access = Utilities::checkAccess('vendor-purchase-order', 'access_page');
        if($access == 1)
        { 
            $data["_po"] = Tbl_purchase_order::vendor()->orderBy("po_id","DESC")->where("po_shop_id",Purchase_Order::getShopId())->get();

            $data["_po_open"] = Tbl_purchase_order::vendor()->orderBy("po_id","DESC")->where("po_shop_id",Purchase_Order::getShopId()) ->where("po_is_billed",0) ->get();
            //dd($data["_po_open"]);

            $data["_po_close"] = Tbl_purchase_order::vendor()->orderBy("po_id","DESC")->where("po_shop_id",Purchase_Order::getShopId()) ->where("po_is_billed","!=",0)->get();
            //dd($data["_po_close"]);
            return view("member.purchase_order.purchase_order_list",$data);
        }
        else
        {
             return $this->show_no_access();            
        }
    }

    public function view_po_pdf($po_id)
    {
        $data["action"] = "/member/vendor/purchase_order/pdf";
        $data["po_id"] = $po_id;

        return view("member.vendor_list.view_po_pdf",$data);
    }
    public function po_pdf($po_id)
    {
        $access = Utilities::checkAccess('vendor-purchase-order', 'access_page');
        if($access == 1)
        { 
            $date       = date("F j, Y, g:i a");
            $first_name = $this->user_info->user_first_name;
            $last_name  = $this->user_info->user_last_name;
            $footer     ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

            $data["po"] = Tbl_purchase_order::vendor()->where("po_id",$po_id)->first();
            $data["_poline"] = Tbl_purchase_order_line::um()->item()->where("poline_po_id",$po_id)->get();
            foreach($data["_poline"] as $key => $value) 
            {
                $qty = UnitMeasurement::um_qty($value->poline_um);

                $total_qty = $value->poline_orig_qty * $qty;
                $data["_poline"][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->poline_um);
            }
            $pdf = view("member.vendor_list.po_pdf",$data);
            return Pdf_global::show_pdf($pdf, null, $footer);
        }
        else
        {
             return $this->show_no_access();            
        }
    }
    public function create_po()
    {

        //dd(Request::input());

        $button_action = Request::input('button_action');

        $vendor_info                        = [];
        $vendor_info['po_vendor_id']        = Request::input('po_vendor_id');;
        $vendor_info['po_vendor_email']     = Request::input('po_vendor_email');

        $po_info                            = [];
        $po_info['po_terms_id']             = Request::input('po_terms_id');
        $po_info['po_date']                 = datepicker_input(Request::input('po_date'));
        $po_info['po_due_date']             = datepicker_input(Request::input('po_due_date'));
        $po_info['billing_address']         = Request::input('po_billing_address');

        $po_other_info                      = [];
        $po_other_info['po_message']        = Request::input('po_message');
        $po_other_info['po_memo']           = Request::input('po_memo');

        $total_info                         = [];
        $total_info['po_subtotal_price']    = str_replace(",","",Request::input('subtotal_price'));
        $total_info['ewt']                  = str_replace(",","",Request::input('ewt'));
        $total_info['po_discount_value']    = str_replace(",","",Request::input('po_discount_value'));
        $total_info['po_discount_type']     = str_replace(",","",Request::input('po_discount_type'));
        $total_info['taxable']              = str_replace(",","",Request::input('taxable'));
        $total_info['po_overall_price']     = str_replace(",","",Request::input('overall_price'));

        $item_info                          = [];
        $_itemline                          = Request::input('poline_item_id');

        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $item_info[$key]['item_service_date']  = Request::input('poline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('poline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('poline_description')[$key];
                $item_info[$key]['um']                 = Request::input('poline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('poline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('poline_rate')[$key]);
                $item_info[$key]['discount']           = str_replace(",","",Request::input('poline_discount')[$key]);
                $item_info[$key]['discount_remark']    = str_replace(",","",Request::input('poline_discount_remark')[$key]);
                $item_info[$key]['taxable']            = str_replace(",","",Request::input('poline_taxable')[$key]);
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('poline_amount')[$key]);
            }
        }

        $po_id = Purchase_Order::postOrder($vendor_info, $po_info, $po_other_info, $item_info, $total_info);
        
        $json["status"]         = "success-po";
        if($button_action == "save-and-edit")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order?id=".$po_id;
        }
        elseif($button_action == "save-and-new")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order";
        }
        elseif($button_action == "save-and-close")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order/list";
        }
        elseif($button_action == "save-and-print")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order/pdf/".$po_id;
        }

        return json_encode($json);

    }

    public function update_po()
    {
        $po_id = Request::input("po_id");
        $button_action = Request::input('button_action');
        
        $vendor_info                        = [];
        $vendor_info['po_vendor_id']       = Request::input('po_vendor_id');;
        $vendor_info['po_vendor_email']    = Request::input('po_vendor_email');

        $po_info                            = [];
        $po_info['po_terms_id']             = Request::input('po_terms_id');
        $po_info['po_date']                 = datepicker_input(Request::input('po_date'));
        $po_info['po_due_date']             = datepicker_input(Request::input('po_due_date'));
        $po_info['billing_address']         = Request::input('po_billing_address');

        $po_other_info                      = [];
        $po_other_info['po_message']        = Request::input('po_message');
        $po_other_info['po_memo']           = Request::input('po_memo');

        $total_info                         = [];
        $total_info['po_subtotal_price']    = str_replace(",","",Request::input('subtotal_price'));
        $total_info['ewt']                  = str_replace(",","",Request::input('ewt'));
        $total_info['po_discount_value']    = str_replace(",","",Request::input('po_discount_value'));
        $total_info['po_discount_type']     = str_replace(",","",Request::input('po_discount_type'));
        $total_info['taxable']              = str_replace(",","",Request::input('taxable'));
        $total_info['po_overall_price']     = str_replace(",","",Request::input('overall_price'));

        $item_info                          = [];
        $_itemline                          = Request::input('poline_item_id');

        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {               
                $item_info[$key]['item_service_date']  = Request::input('poline_service_date')[$key];
                $item_info[$key]['item_id']            = Request::input('poline_item_id')[$key];
                $item_info[$key]['item_description']   = Request::input('poline_description')[$key];
                $item_info[$key]['um']                 = Request::input('poline_um')[$key];
                $item_info[$key]['quantity']           = str_replace(',', "",Request::input('poline_qty')[$key]);
                $item_info[$key]['rate']               = str_replace(',', "", Request::input('poline_rate')[$key]);
                $item_info[$key]['discount']           = str_replace(",","",Request::input('poline_discount')[$key]);
                $item_info[$key]['discount_remark']    = str_replace(",","",Request::input('poline_discount_remark')[$key]);
                $item_info[$key]['taxable']            = str_replace(",","",Request::input('poline_taxable')[$key]);
                $item_info[$key]['amount']             = str_replace(',', "", Request::input('poline_amount')[$key]);

                $um_info = UnitMeasurement::um_info(Request::input("po_um")[$key]);
                $product_consume[$key]["quantity"] = isset($um_info->unit_qty) ? $um_info->unit_qty : 1 * $item_info[$key]['quantity'];
                $product_consume[$key]["product_id"] = Request::input('invline_item_id')[$key];
            }
        }

        $po_id = Purchase_Order::updatePurchase($po_id, $vendor_info, $po_info, $po_other_info, $item_info, $total_info);

        $json["status"]         = "success-po";
        //$json["redirect_to"]    = "/member/vendor/purchase_order?id=".$po_id;

        if($button_action == "save-and-edit")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order?id=".$po_id;
        }
        elseif($button_action == "save-and-new")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order";
        }
        elseif($button_action == "save-and-close")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order/list";
        }
        elseif($button_action == "save-and-print")
        {
            $json["redirect_to"]    = "/member/vendor/purchase_order/pdf/".$po_id;
        }

        return json_encode($json);

    }
    
}