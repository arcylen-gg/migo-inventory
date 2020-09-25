<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use App\Models\Tbl_voucher;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_membership_code;
use Crypt;
use Redirect;
use Request;
use View;

use App\Models\Tbl_mlm_gc;

class MlmVouchersController extends Mlm
{
    public function index()
    {
    	$customer_id		= Self::$customer_id;
        $data["page"] 		= "Vouchers";
        $data["_voucher"]	= Tbl_voucher::where("voucher_customer",$customer_id)->get();
        return view("mlm.vouchers", $data);
    }

    public function view_voucher()
    {
    	$customer_id		        = Self::$customer_id;
    	$invoice_id			        = Request::input("voucher_id");
        $data["voucher"]	        = Tbl_membership_code_invoice::where("membership_code_invoice_id",$invoice_id)->where("customer_id",$customer_id)->first();
        $data["_voucher_product"]   = null;
        $data["subtotal"]   		= 0;
        $data["discount"]   		= 0;
        $data["total"]   			= 0;

        if($data["voucher"])
        {
        	$data["_voucher_product"] = Tbl_membership_code::package()->where("membership_code_invoice_id",$invoice_id)->get();
        	$data["subtotal"] 		  = Tbl_membership_code::package()->where("membership_code_invoice_id",$invoice_id)->sum("membership_code_price");
        	$data["discount"] 		  = 0;
        	$data["total"] 			  = $data["subtotal"] - $data["discount"];
        }

        // dd($data);
        return view("mlm.view_voucher", $data);
    }
    public function gc()
    {
        if(Self::$slot_id != null)
        {
            $data['gc'] = Tbl_mlm_gc::where('mlm_gc_slot', Self::$slot_id)->get();
            // $data["page"] = "Transfer";
            // $data['break_down'] = Mlm_member::breakdown_wallet(Self::$slot_id);
            return view("mlm.gc.index", $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
}