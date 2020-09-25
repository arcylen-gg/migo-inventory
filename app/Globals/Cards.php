<?php
namespace App\Globals;
use DB;
use Carbon\Carbon;
use App\Models\Tbl_warehouse_inventory_record_log;
class Cards
{
    public static function show_card($info)
    {
        if($info->membership_name == 'V.I.P Silver')
        {
            $color = 'silver';
        }
        else if($info->membership_name == 'V.I.P Gold')
        {
            $color = 'gold';
        }
        else if($info->membership_name == 'V.I.P Platinum ')
        {
            $color = 'red';
        }
        else
        {
            $color = 'discount';
        }
        $name = name_format_from_customer_info($info);
        $membership_code = $info->slot_no;    
        $data['color'] = $color;
        $data['name'] = $name;
        $data['membership_code'] = $membership_code;
        $data['info'] = $info;
        $data['number'] = phone_number($info);
        // $data
        $data['address'] = address_customer_info($info);
        if($info->slot_card_printed == 0)
        {
            $data['now'] = Carbon::now()->format('m/d/Y');
        }
        else
        {
            $data['now'] = Carbon::parse($info->slot_card_issued)->format('m/d/Y');
        }
        return view("mlm.card.card", $data);
    }
    public static function discount_card($info)
    {
        // dd($info);
        $data['color'] = 'discount';
        $data['name'] = name_format_from_customer_info($info);
        $data['membership_code'] = $info->discount_card_log_code;    
        $data['info'] = $info;
        $data['number'] = phone_number($info);
        $data['address'] = address_customer_info($info);
        $data['now'] = Carbon::now()->format('m/d/Y');
        // $data['now'] = Carbon::parse($info->slot_card_issued)->format('m/d/Y');
        return view("mlm.card.discount_card", $data);
    }
    public static function discount_card2($info)
    {
        // dd($info);
        $data['color'] = 'discount';
        $data['name'] = name_format_from_customer_info($info);
        $data['membership_code'] = $info->slot_no;    
        $data['info'] = $info;
        $data['number'] = phone_number($info);
        $data['address'] = address_customer_info($info);
        $data['now'] = Carbon::now()->format('m/d/Y');
        // $data['now'] = Carbon::parse($info->slot_card_issued)->format('m/d/Y');
        return view("member.card.discount_card", $data);
    }
    public static function card_all($info)
    {
        if($info->membership_name == 'V.I.P Silver')
        {
            $color = 'silver';
        }
        else if($info->membership_name == 'V.I.P Gold')
        {
            $color = 'gold';
        }
        else if($info->membership_name == 'V.I.P Platinum ')
        {
            $color = 'red';
        }
        else
        {
            $color = 'discount';
        }
        $name = name_format_from_customer_info($info);
        $membership_code = $info->slot_no;    
        $data['color'] = $color;
        $data['name'] = $name;
        $data['membership_code'] = $membership_code;
        $data['info'] = $info;
        $data['number'] = phone_number($info);
        $data['address'] = address_customer_info($info);
        if($info->slot_card_printed == 0)
        {
            $data['now'] = Carbon::now()->format('m/d/Y');
        }
        else
        {
            $data['now'] = Carbon::parse($info->slot_card_issued)->format('m/d/Y');
        }
        // dd(1);
        return view("member.card.card", $data);
    }
    public static function table($info)
    {
        if($info->membership_name == 'V.I.P Silver')
        {
            $color = 'silver';
        }
        else if($info->membership_name == 'V.I.P Gold')
        {
            $color = 'gold';
        }
        else if($info->membership_name == 'V.I.P Platinum ')
        {
            $color = 'red';
        }
        else
        {
            $color = 'discount';
        }

        $name = name_format_from_customer_info($info);
        $membership_code = $info->slot_no;    
        $data['color'] = $color;
        $data['name'] = $name;
        $data['membership_code'] = $membership_code;
        $data['info'] = $info;
        $data['number'] = phone_number($info);
        $data['address'] = address_customer_info($info);

        if($info->slot_card_printed == 0)
        {
            $data['now'] = Carbon::now()->format('m/d/Y');
        }
        else
        {
            $data['now'] = Carbon::parse($info->slot_card_issued)->format('m/d/Y');
        }
        
        if ($info->slot_no) 
        {
            $data["membership_id"] = $info->membership_id;
            $data["used"] = 1;
        }
        else
        {
            $data["membership_id"] = $info->record_log_id;
            $data["used"] = 0;
        }

        return view("member.card.table", $data);
    }
    public static function pre_printing($shop_id, $membership_id)
    {
        return Tbl_warehouse_inventory_record_log::where("tbl_warehouse_inventory_record_log.record_shop_id", $shop_id)
                                                 ->where("tbl_warehouse_inventory_record_log.item_in_use", "unused")
                                                 ->where("tbl_membership.membership_id", $membership_id)
                                                 ->item()
                                                 ->membership()
                                                 ->slotInfo()
                                                 ->get();
    }
}