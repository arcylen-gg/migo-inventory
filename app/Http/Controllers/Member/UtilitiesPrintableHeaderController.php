<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\AccountingTransaction;

use App\Models\Tbl_settings;
use App\Models\Tbl_payroll_paper_sizes;
use Crypt;

class UtilitiesPrintableHeaderController extends Member
{
	public function getIndex()
	{
		$data['page'] = "Printable Settings";
		$data["_old_settings"] = Tbl_settings::where("shop_id", $this->user_info->shop_id)->get();
        $data['_settings'] = null;
        foreach ($data['_old_settings'] as $key => $value) 
        {
            $data['_settings'][$value->settings_key] = $value->settings_value;
        }

        $data["_papersize_settings"] = Tbl_settings::where("shop_id", $this->user_info->shop_id)->where("settings_setup_done",4)->get();
        $data['_papersize'] = null;
        foreach ($data['_papersize_settings'] as $key => $value) 
        {
            $explode = explode("-", $value->settings_value);
            $data['_papersize'][$value->settings_key][0] = isset($explode[0]) ? $explode[0] : '';
            $data['_papersize'][$value->settings_key][1] = isset($explode[1]) ? $explode[1] : '';
        }
        $cust["eq"] = "Estimate & Quotation";
        $cust["so"] = "Sales Order";
        $cust["si"] = "Sales Invoice";
        $cust["sr"] = "Sales Receipt";
        $cust["wis_dr"] = "WIS/DR";
        $cust["rp"] = "Receive Payment";
        $cust["cm"] = "Credit Memo";
        $data['_customer_transaction'] = $cust;
        $ven["pr"] = "Purchase Requisition";
        $ven["po"] = "Purchase Order";
        $ven["ri"] = "Receive Inventory";
        $ven["eb"] = "Enter Bills";
        $ven["pb"] = "Paybill";
        $ven["wc"] = "Write Check";
        $ven["dm"] = "Debit Memo";
        $data['_vendor_transaction'] = $ven;
        $wrh["wt_rr"] = "WT/RR";
        $wrh["adj"] = "Adjust Inventory";
        $data['_warehouse_transaction'] = $wrh;

        $data['_paper_size'] = Tbl_payroll_paper_sizes::where("shop_id", $this->user_info->shop_id)->orderBy("payroll_paper_sizes_id","DESC")->get();
		return view("member.accounting_transaction.printable_header.printable_header", $data);
	}
    public function postPaperSizeSubmit(Request $request)
    {
        $ins = null;
        $val = null;

        foreach ($request->transaction as $keys => $value) 
        {
            if($request->size_h_w[$keys])
            {
                if($request->size_w[$keys] == "" || $request->size_h[$keys] == "")
                {
                    $val = "Please fill the fields for width and height.";
                }
            }
        }
        if(!$val)
        {
            Tbl_settings::where("settings_setup_done",4)->where("shop_id", $this->user_info->shop_id)->delete();
            if(count($request->transaction) > 0)
            {
                foreach ($request->transaction as $key => $value) 
                {
                    if($request->size_h_w[$key] == 1)
                    {
                        $paper_size = $request->size_w[$key]."/".$request->size_h[$key];
                    }
                    else
                    {
                        $paper_size = $request->paper_size[$key];
                    }

                    $ins[$key]['settings_setup_done'] = 4;
                    $ins[$key]['settings_key'] = "printable_".$value;
                    $ins[$key]['settings_value'] = $paper_size."-".$request->width[$key];
                    $ins[$key]['shop_id'] = $this->user_info->shop_id;
                }
            }
            if(count($ins) > 0)
            {
                Tbl_settings::insert($ins);
            }

            $data['message'] = "Success";
            $data['response_status'] = "success_update";
            $data['call_function'] = "success_settings";            
        }
        else
        {
            $data['message'] = $val;
            $data['status'] = "error";
        }


        return json_encode($data);
    }
}