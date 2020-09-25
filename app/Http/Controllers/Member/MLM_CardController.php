<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Globals\Accounting;
use App\Globals\Category;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Vendor;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Globals\Pdf_global;
use PDF;
use App;
use Request;
use Carbon\Carbon;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_membership;

use SnappyImage;
use Response;
use App\Globals\Cards;
class MLM_CardController extends Member
{
    public function view()
    {
        $data["used"] = Request::input("used");

        if (Request::input("used") == 0) 
        {
            $slot = Tbl_warehouse_inventory_record_log::where("tbl_warehouse_inventory_record_log.record_shop_id", $this->user_info->shop_id)
                                                 ->where("record_log_id", Request::input("id"))
                                                 ->where("tbl_warehouse_inventory_record_log.item_in_use", "unused")
                                                 ->item()
                                                 ->membership()
                                                 ->first();

            $slot->slot_id = $slot->record_log_id;
        }
        else
        {
            $slot = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $this->user_info->shop_id)
                            ->where("tbl_mlm_slot.slot_id", Request::input("id"))
                            ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                            ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                            ->membership()->customer()->first();
        }

        if ($slot) 
        {
            $data["membership"] = $slot;
            $data["card"] = Cards::card_all($slot);

            if($slot->membership_name == 'V.I.P Silver')
            {
                $color = 'silver';
            }
            else if($slot->membership_name == 'V.I.P Gold')
            {
                $color = 'gold';
            }
            else if($slot->membership_name == 'V.I.P Platinum ')
            {
                $color = 'red';
            }
            else
            {
                $color = 'discount';
            }

            $name = name_format_from_customer_info($slot);
            $membership_code = $slot->slot_no;    
            $data['color'] = $color;
            $data['name'] = $name;
            $data['membership_code'] = $membership_code;
            $data['info'] = $slot;
            $data['number'] = phone_number($slot);
            $data['address'] = address_customer_info($slot);
            if($slot->slot_card_printed == 0)
            {
                $data['now'] = Carbon::now()->format('m/d/Y');
            }
            else
            {
                $data['now'] = Carbon::parse($slot->slot_card_issued)->format('m/d/Y');
            }
        }

        return view("member.card.view_table", $data);
    }
    public function all_slot()
    {
        $data = [];
        $shop_id = $this->getShop_Id();
        $data['membership'] = Tbl_membership::where('shop_id', $shop_id)->get();
        return view('member.card.index',$data);
    }
    public function filter()
    {
        // return $_POST;
        $shop_id = $this->getShop_Id();
        $membership_id = Request::input('membership_id');
        $ret = null;
        // if($membership_id == 1)
        // {
        // 	$slot_card_printed = Request::input('card_status');
        //     $card_info = Tbl_mlm_discount_card_log::membership()
        //     // ->whereNotNull('tbl_mlm_discount_card_log.discount_card_customer_holder')
        //     ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_sponsor')
        //     // ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        //     // ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        //     // ->where('tbl_customer_address.purpose', 'billing')
        //     ->where('discount_card_log_issued', $slot_card_printed)
        //     ->get();
        //     foreach ($card_info as $key => $value) {
        //         # code...
        //         $ret .= Cards::discount_card($value);
        //     }
        // }
        // else
        // {
            $slot_card_printed = Request::input('card_status');
            if ($slot_card_printed == 2) 
            {
                $all_slot = Cards::pre_printing($this->user_info->shop_id, $membership_id);
                $ret = null;
                foreach ($all_slot as $key => $value) 
                {
                    $ret .= Cards::table($value);
                }
            }
            else
            {
                $all_slot = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)
                ->where('slot_card_printed', $slot_card_printed)
                ->where('tbl_membership.membership_id', $membership_id)
                ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                ->membership()->customerv2()->get();
                $ret = null;
                foreach ($all_slot as $key => $value) 
                {
                    // $ret .= Cards::card_all($value);
                    $value->membership_id = $value->slot_id;
                    $ret .= Cards::table($value);
                }
            }
        // }
        // dd($all_slot);
        // return json_encode($all_slot);
        $data['status'] = 'succes';
        $data['append'] = $ret;
        
        return json_encode($data);
    }
	public function generate($slot_id)
	{
        $shop_id = $this->getShop_Id();

        if (Request::input("used") == 0) 
        {
            $slot = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)
                                ->where('tbl_mlm_slot.slot_id', $slot_id)
                                ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                                ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_slot.slot_owner')
                                ->membership()->customer()->first();
        }
        else
        {
            $slot = Tbl_warehouse_inventory_record_log::where("tbl_warehouse_inventory_record_log.record_shop_id", $this->user_info->shop_id)
                                                      ->where("record_log_id", $slot_id)
                                                      ->where("tbl_warehouse_inventory_record_log.item_in_use", "unused")
                                                      ->item()
                                                      ->membership()
                                                      ->first();
        }

		


        if ($slot->membership_name == "Privilege Card") 
        {
            $card = Cards::discount_card2($slot);
        }
        else
        {
            $card = Cards::card_all($slot);
        }

        return Pdf_global::show_image($card);
	}
	
    public function card()
    {
        $data['color'] = Request::input("color");
        $data['name'] = Request::input("name");
        $data['membership_code'] = Request::input("membership_code");

        return view("card", $data);
    }
    public function done()
    {
        // return $_POST;
        $slot_id = Request::input('slot_id');
        $update['slot_card_printed'] = 1;
        $update['slot_card_issued'] = Carbon::now();
        Tbl_mlm_slot::where('slot_id', $slot_id)->update($update);

        $data['status'] = 'success_done';

        return json_encode($data);
    }
    public function pending()
    {
        // return $_POST;
        $slot_id = Request::input('slot_id');
        $update['slot_card_printed'] = 0;
        $update['slot_card_issued'] = Carbon::now();
        Tbl_mlm_slot::where('slot_id', $slot_id)->update($update);

        $data['status'] = 'success_done';

        return json_encode($data);
    }
    public function done_discount()
    {
    	$discount_card_log_id = Request::input('discount_card_log_id');
        $update['discount_card_log_issued'] = 1;
        $update['discount_card_log_issued_date'] = Carbon::now();
        Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->update($update);

        $data['status'] = 'success_done';

        return json_encode($data);
    }
	public function pending_discount()
	{
		$discount_card_log_id = Request::input('discount_card_log_id');
        $update['discount_card_log_issued'] = 0;
        $update['discount_card_log_issued_date'] = Carbon::now();
        Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->update($update);

        $data['status'] = 'success_done';

        return json_encode($data);
	}
	public function generate_discount($discount_card_log_id)
	{
		$shop_id = $this->getShop_Id();

		$card = Tbl_mlm_discount_card_log::membership()
            // ->whereNotNull('tbl_mlm_discount_card_log.discount_card_customer_holder')
            // ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
            // ->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
            // ->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
            // ->where('tbl_customer_address.purpose', 'billing')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_sponsor')
            ->where('discount_card_log_id', $discount_card_log_id)
            ->first();
        $card = Cards::discount_card($card);
        //return $card;
        return Pdf_global::show_image($card);
	}
}