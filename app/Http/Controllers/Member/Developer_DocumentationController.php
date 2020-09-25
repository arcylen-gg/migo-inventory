<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Globals\Accounting;
use App\Globals\Category;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Vendor;

use App\Models\Tbl_mlm_slot;
use App\Globals\Pdf_global;
use PDF;
use App;
use Request;
use App\Models\Tbl_mlm_discount_card_log;

class Developer_DocumentationController extends Member
{
	public function index()
	{
		$data["page"] = "Documentation";
		$data["popup_code"] = view('member.developer.documentation_popup');
		$data["popup_code_submit_done"] = view('member.developer.documentation_popup_submit_done');
		$data["html_code_global_submit"] = view('member.developer.documentation_global_submit');
		$data["html_code_global_submit_controller"] = view('member.developer.documentation_global_submit_controller');
		$data["html_code_global_submit_done"] = view('member.developer.documentation_global_submit_done');
		$data["documentation_image_gallery"] = view('member.developer.documentation_image_gallery');
		$data["documentation_submit_image_done"] = view('member.developer.documentation_submit_image_done');
		$data["documentation_globalDropList_configuration"] = view('member.developer.documentation_globalDropList_configuration');
		$data["documentation_globalDropList_events"] = view('member.developer.documentation_globalDropList_events');
		$data["documentation_member_settings"] = view('member.developer.documentation_member_settings');
		$data["documentation_create_invoice"] = view('member.developer.documentation_create_invoice');
		$data["documentation_globalDropList_reload"] = view('member.developer.documentation_globalDropList_reload');
        $data["documentation_paginate_html"] = view('member.developer.documentation_paginate_html');

		$data['_account']           = Accounting::getAllAccount();
		$data['_category']          = Category::getAllCategory();
		$data['_customer']          = Customer::getAllCustomer();
		$data['_item']			    = Item::get_all_category_item();
		$data["_vendor"] 		    = Vendor::getAllVendor();
		$data["dropdown_vendor"] 	= view('member.developer.documentation_no_16.documentation_dropdown_vendor');
		$data["dropdown_customer"] 	= view('member.developer.documentation_no_16.documentation_dropdown_customer');
		$data["dropdown_coa"] 		= view('member.developer.documentation_no_16.documentation_dropdown_coa');
		$data["dropdown_item"] 		= view('member.developer.documentation_no_16.documentation_dropdown_item');
		$data["dropdown_category"] 	= view('member.developer.documentation_no_16.documentation_dropdown_category');

		return view('member.developer.documentation',$data);
	}
    public function all_slot()
    {
        $data = [];
        return view('member.developer.documentation',$data);
    }
	public function generate()
	{
		// for testing only showing of discount card
		$shop_id = $this->getShop_Id();
		$all_slot = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)->membership()->customer()->get();
        $card = null;
        foreach($all_slot as $key => $value)
        {
            if($value->membership_name == 'V.I.P Silver')
            {
                $color = 'silver';
            }
            else if($value->membership_name == 'V.I.P Gold')
            {
                $color = 'gold';
            }
            else if($value->membership_name == 'V.I.P Platinum ')
            {
                $color = 'red';
            }
            else
            {
                $color = 'discount';
            }
            $name = name_format_from_customer_info($value);
            $membership_code = $value->slot_no;
            $card .= $this->card_all($color, $name,  $membership_code);
            // $pdf = App::make('snappy.pdf.wrapper');
            // $pdf->loadHTML($card);
            // return $pdf->inline();
            // $card = $this->
        }
        // $discount_card = Tbl_mlm_discount_card_log::
        // ->join('tbl_customer', 'tbl_customer.custmer_id', 'Tbl_mlm_slot.')
        // ->get();
        // 
        if(Request::input('pdf') == 'true')
        {
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadHTML($card);
            return $pdf->inline();
            return Pdf_global::show_pdf($card);
        }
        else
        {
            return $card;
        }
	}
	public function card_all($color, $name,  $membership_code)
    {
        $data['color'] = $color;
        $data['name'] = $name;
        $data['membership_code'] = $membership_code;

        return view("card", $data);
    }
    public function card()
    {
        $data['color'] = Request::input("color");
        $data['name'] = Request::input("name");
        $data['membership_code'] = Request::input("membership_code");

        return view("card", $data);
    }
}