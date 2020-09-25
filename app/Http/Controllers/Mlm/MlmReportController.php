<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
use App\Globals\Mlm_member_report;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_matching_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_mlm_leadership_settings;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_matching;
use App\Models\Tbl_mlm_triangle_repurchase_slot;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_item_code;
use App\Models\Tbl_mlm_plan_binary_promotions;
use App\Models\Tbl_mlm_plan_binary_promotions_log;
use App\Models\Tbl_mlm_binary_report;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Globals\Warehouse;
use App\Globals\Mlm_voucher;
use App\Globals\Pdf_global;
use Carbon\Carbon;
use Session;
class MlmReportController extends Mlm
{
    public function index($complan)
    {
    	if(Self::$slot_id != null)
        {
        	return $this->$complan();
        }
        else
        {
            return Self::show_no_access();
        }
    }
    public static function product_code()
    {
        $data['_report']     = Tbl_item_code::where("customer_id",Self::$customer_id)->where("used",1)->item()->get();
        return view("mlm.report.product_code", $data);
    }
    public static function direct()
    {
        $data['report']     = Mlm_member_report::get_wallet('DIRECT', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('DIRECT', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        return view("mlm.report.report_direct", $data);
    }
    public static function indirect()
    {
        $data['report']     = Mlm_member_report::get_wallet('INDIRECT', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('INDIRECT', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        foreach($data['report'] as $key => $value)
        {
            $data['level'][$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->where('sponsor_tree_child_id', $value->wallet_log_slot_sponsor)->value('sponsor_tree_level');
        }
        return view("mlm.report.report_indirect", $data);
    }
    public static function binary()
    {
        // return Self::show_maintenance();
        $data['report']     = Mlm_member_report::get_wallet('BINARY', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('BINARY', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        foreach($data['report'] as $key => $value)
        {
            $data['level'][$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->where('sponsor_tree_child_id', $value->wallet_log_slot_sponsor)->value('sponsor_tree_level');
        }
        $data["page"] = "Report - Binary";

        $data['points_report'] = Tbl_mlm_binary_report::where('binary_report_slot', Self::$slot_id)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_binary_report.binary_report_slot_g')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->get();
        return view("mlm.report.report_binary", $data);
    }
    public static function membership_matching()
    {
        $data['report']     = Mlm_member_report::get_wallet('MEMBERSHIP_MATCHING', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('MEMBERSHIP_MATCHING', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);

        $data['matching_l'] = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_matching_log.matching_log_slot_1')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->where('matching_log_earning', '>=', 1)
        ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
        ->paginate(10);

        $whereIn = [];

        foreach ($data['matching_l']  as $key => $value) 
        {
            $whereIn[$value->matching_log_slot_2] = $value->matching_log_slot_2;
        }

        $data['matching_r'] = Tbl_mlm_slot::whereIn('slot_id', $whereIn)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
        ->get()->keyBy('slot_id');

        return view("mlm.report.report_membership_matching", $data);
    }
    public static function membership_matching_v1()
    {
        $data['report']     = Mlm_member_report::get_wallet('MEMBERSHIP_MATCHING', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('MEMBERSHIP_MATCHING', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['report_matching'] = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)->orderBy('matching_log', 'DESC')->paginate(10);
        $data['slot_1']  = [];
        $data['slot_2'] = [];
        foreach($data['report_matching'] as $key => $value)
        {
            $data['slot_1'][$key] = Tbl_mlm_slot::where('slot_id', $value->matching_log_slot_1)->customer()->first();
            $data['slot_2'][$key] = Tbl_mlm_slot::where('slot_id', $value->matching_log_slot_2)->customer()->first();
        }
        $data['matching_count'] = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)->count();
        $data['count'] = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)->get();


        $settings = 
        Tbl_mlm_matching::where('tbl_mlm_matching.shop_id', Self::$shop_id)
        // ->where('membership_id', Self::$slot_now->slot_membership)
        ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_matching.membership_id')
        ->get()
        ->toArray();
        foreach($settings as $key => $value)
        {
        	$slot_per_level[$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
        	->where('sponsor_tree_level', '>=', $value['matching_settings_start'])
        	->where('sponsor_tree_level', '<=', $value['matching_settings_end'])
        	->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_sponsor.sponsor_tree_child_id')
        	->where('slot_matched_membership', 0)
        	->get()->keyBy('slot_id');
        	$data['matched_list'][$key] = [];
        	$data['un_matched_list'][$key] = [];
        	foreach($slot_per_level[$key] as $key2 => $value2)
        	{
        		$matched = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)
        		->where('matching_log_slot_1', $key2)
        		// ->orWhere('matching_log_slot_2', $key2)
        		->count();
        		$matched_2 = Tbl_mlm_matching_log::where('matching_log_earner', Self::$slot_id)
        		->where('matching_log_slot_2', $key2)
        		// ->orWhere('matching_log_slot_2', $key2)
        		->count();
        		if($matched >= 1 || $matched_2 >= 1)
        		{
        			$data['matched_list'][$key][$key2] = $value2;
        		}
        		else
        		{
        			$data['un_matched_list'][$key][$key2] = $value2;
        		}
        	}
        }
        $data['settings_a'] = $settings;
        // dd($data);

        return view("mlm.report.report_membership_matching", $data);
    }
    public static function executive_bonus()
    {
        $data['report']     = Mlm_member_report::get_wallet('EXECUTIVE_BONUS', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('EXECUTIVE_BONUS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'EXECUTIVE_BONUS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_slot_points_log.points_log_Sponsor')
        ->get();
        return view("mlm.report.report_executive_bonus", $data);
    }
    public static function leadership_bonus()
    {
        if(Self::$slot_id != null)
        {
            $slot = Self::$slot_now;
            // dd($slot->membership_id);   
            $data['report']     = Mlm_member_report::get_wallet('LEADERSHIP_BONUS', Self::$slot_id); 
            $data['plan']       = Mlm_member_report::get_plan('LEADERSHIP_BONUS', Self::$shop_id); 
            
            // dd($data);
            $data['header'] = Mlm_member_report::header($data['plan']);
            $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'LEADERSHIP_BONUS')
            ->orderby('points_log_id', 'DESC')
            ->where('points_log_slot', Self::$slot_id)
            ->paginate(10);
            // dd($data);
            return view("mlm.report.report_leadership_bonus", $data);
        }
        else
        {
            return Self::show_no_access();
        }
        
    }
    public static function direct_points()
    {
        // return Self::show_maintenance();
        $data['plan']       = Mlm_member_report::get_plan('DIRECT_POINTS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'DIRECT_POINTS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->paginate(10);
        return view("mlm.report.report_direct_points", $data);
    }
    public static function indirect_points()
    {
        $data['plan']       = Mlm_member_report::get_plan('INDIRECT_POINTS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'INDIRECT_POINTS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->paginate(10);
        foreach($data['points_log'] as $key => $value)
        {
            $data['level'][$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->where('sponsor_tree_child_id', $value->points_log_Sponsor)->value('sponsor_tree_level');
      
        }
        return view("mlm.report.report_indirect_points", $data);
    }
    public static function unilevel()
    {
        $data['report']     = Mlm_member_report::get_wallet('UNILEVEL', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('UNILEVEL', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        return view("mlm.report.report_unilevel", $data);
    }
    public static function repurchase_points()
    {
        $data['report']     = Mlm_member_report::get_wallet('REPURCHASE_POINTS', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('REPURCHASE_POINTS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'REPURCHASE_POINTS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->paginate(10);
        return view("mlm.report.repurchase_points", $data);
    }
    public static function repurchase_cashback()
    {
        $data['report']     = Mlm_member_report::get_wallet('REPURCHASE_CASHBACK', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('REPURCHASE_CASHBACK', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        return view("mlm.report.report_repurchase_cashback", $data);
    }
    public static function unilevel_repurchase_points()
    {
        $data['report']     = Mlm_member_report::get_wallet('UNILEVEL_REPURCHASE_POINTS', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('UNILEVEL_REPURCHASE_POINTS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'UNILEVEL_REPURCHASE_POINTS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->paginate(10);
        foreach($data['points_log'] as $key => $value)
        {
            $data['level'][$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->where('sponsor_tree_child_id', $value->points_log_Sponsor)->value('sponsor_tree_level');
      
        }
        return view("mlm.report.report_unilevel_repurchase_points", $data);
    }
    public static function initial_points()
    {
        $data['report']     = Mlm_member_report::get_wallet('INITIAL_POINTS', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('INITIAL_POINTS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['points_log'] = Tbl_mlm_slot_points_log::where('points_log_complan', 'INITIAL_POINTS')
        ->orderby('points_log_id', 'DESC')
        ->where('points_log_slot', Self::$slot_id)
        ->paginate(10);
        foreach($data['points_log'] as $key => $value)
        {
            $data['level'][$key] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->where('sponsor_tree_child_id', $value->points_log_Sponsor)->value('sponsor_tree_level');
      
        }
        return view("mlm.report.report_initial_points", $data);
    }
    public static function discount_card()
    {
        $data['plan']       = Mlm_member_report::get_plan('DISCOUNT_CARD', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);

        $data['unused_discount_car'] = Tbl_mlm_discount_card_log::whereNull('discount_card_customer_holder')
        ->where('discount_card_slot_sponsor', Self::$slot_id)
        ->membership()->get();
        $data['used_discount_card'] = Tbl_mlm_discount_card_log::whereNotNull('discount_card_customer_holder')
        ->where('discount_card_slot_sponsor', Self::$slot_id)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_discount_card_log.discount_card_customer_holder')
        ->membership()->get();
        // dd($data);
        return view("mlm.report.report_discount_card", $data);
    }
    public function direct_promotions()
    {
        // $data['report']     = Mlm_member_report::get_wallet('DIRECT_PROMOTIONS', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('DIRECT_PROMOTIONS', Self::$shop_id); 
        $data['header'] = Mlm_member_report::header($data['plan']);
        $data['membership'] = Tbl_membership::where('shop_id', Self::$shop_id) 
        ->where('membership_id', '!=', 1)
        ->where('membership_archive', 0)->get();

        foreach($data['membership'] as $key => $value)
        {
            $data['direct_promotion'][$key] =   DB::table('tbl_mlm_plan_settings_direct_promotions')->where('shop_id', Self::$shop_id)->where('membership_id', $value->membership_id)->first();
            $data['count_direct'][$key] = Tbl_mlm_slot::where('slot_sponsor', Self::$shop_id)
                    ->where('slot_membership', $value->membership_id)
                    ->where('slot_matched_membership', 0)
                    ->count();
            $data['count_matched'][$key] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', Self::$slot_id)->where('wallet_log_plan', 'DIRECT_PROMOTIONS')
                        ->where('wallet_log_membership_filter', $value->membership_id)
                        ->count();
        }
        // dd($data);
        return view("mlm.report.report_direct_promotion", $data);
    }

    public function stairstep()
    {
        $data['report']     = Mlm_member_report::get_wallet('STAIRSTEP', Self::$slot_id); 
        $data['plan']       = Mlm_member_report::get_plan('STAIRSTEP', Self::$shop_id); 
        $data['header']     = Mlm_member_report::header($data['plan']);
        $data["page"]       = "Report - Stairstep";


        return view("mlm.report.report_stairstep", $data);
    }
    public function binary_repurchase()
    {
        return $this->show_maintenance();
    }
    public function triangle_repurchase()
    {
        $data['plan']       = Mlm_member_report::get_plan('TRIANGLE_REPURCHASE', Self::$shop_id); 
        $data['header']     = Mlm_member_report::header($data['plan']);
        $data["page"]       = "Report - Stairstep";
        $data['slots_tri'] = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_slot_id', Self::$slot_id)
        ->join('tbl_item_code_invoice', 'tbl_item_code_invoice.item_code_invoice_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_invoice_id')
        ->get();
        $data['invoice'] = Tbl_item_code_invoice::where('slot_id', Self::$slot_id)->get()->keyBy('item_code_invoice_id');
        foreach($data['invoice'] as $key => $value)
        {
            $data['repurchase'][$key] = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_invoice_id', $key)
        ->join('tbl_item_code_invoice', 'tbl_item_code_invoice.item_code_invoice_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_invoice_id')
        ->get();

            $data['invoice'][$key]->count = count( $data['repurchase'][$key]);
        }
        // dd($data['invoice']);
        return view("mlm.report.report_triangle_repurchase", $data);

    }
    public function binary_promotions()
    {
        $data['plan']       = Mlm_member_report::get_plan('BINARY_PROMOTIONS', Self::$shop_id); 
        $data['header']     = Mlm_member_report::header($data['plan']);
        $data["page"]       = "Report - Stairstep";
        $data['promotions'] =    Tbl_mlm_plan_binary_promotions::where('binary_promotions_archive', 0)
        ->where('binary_promotions_membership_id', Self::$slot_now->slot_membership)
        ->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_mlm_plan_binary_promotions.binary_promotions_item_id')
        ->get();
        foreach($data['promotions'] as $key => $value)
        {
            $date = Carbon::parse($value->binary_promotions_start_date)->format('Y-m-d');

            $data['current_l'][$key] = Tbl_mlm_binary_report::where('binary_report_slot', Self::$slot_id)
            ->where('binary_report_date', '>=',  $date)
            ->sum('binary_report_s_points_l'); 

            $data['current_r'][$key] = Tbl_mlm_binary_report::where('binary_report_slot', Self::$slot_id)
            ->where('binary_report_date', '>=',  $date)
            ->sum('binary_report_s_points_r');

            $data['req_count'][$key] = Tbl_mlm_plan_binary_promotions_log::where('promotions_request_slot', Self::$slot_id)
                    ->where('promotions_request_binary_promotions_id', $value->binary_promotions_id)
                    ->count();

            $data['direct_count_a'][$key] = Tbl_mlm_slot::where('slot_sponsor', Self::$slot_id)
                                            ->where('slot_created_date', '>=', $date)
                                            ->count(); 

            $data['claim_count_account'][$key] =  Tbl_mlm_plan_binary_promotions_log::join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_plan_binary_promotions_log.promotions_request_slot')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->where('customer_id', Self::$customer_id)
            ->count(); 
        }
        $status = Session::get('status');
        if($status != null)
        {
            $data['s'] = $status;
            $data['m'] = Session::get('message');
        }
        $data['now'] = Carbon::now();
        $data['direct_count'] = Tbl_mlm_slot::where('slot_sponsor', Self::$slot_id)->count();
        return view("mlm.report.report_binary_promotions", $data);
    }
    public function request_binary_promotions()
    {
        $binary_promotions_id = Request::input('binary_promotions_id');

        $request_item = Tbl_mlm_plan_binary_promotions::where('binary_promotions_id', $binary_promotions_id)->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_mlm_plan_binary_promotions.binary_promotions_item_id')->first();
        if($request_item)
        {
            // check inventory
            $warehouse = Tbl_warehouse::where('warehouse_shop_id', Self::$shop_id)->where('main_warehouse', 1)->first();
            if($warehouse)
            {
                $count_inv = Tbl_warehouse_inventory::check_inventory_single($warehouse->warehouse_id, $request_item->item_id)->value('inventory_count');
                if($count_inv >= 1)
                {
                    // check if already requested
                    $req_count = Tbl_mlm_plan_binary_promotions_log::where('promotions_request_slot', Self::$slot_id)
                    ->where('promotions_request_binary_promotions_id', $binary_promotions_id)
                    ->count();
                    if($req_count == 0)
                    {
                        // check points
                        $date = Carbon::parse($request_item->binary_promotions_start_date)->format('Y-m-d');
                        $points_l = Tbl_mlm_binary_report::where('binary_report_slot', Self::$slot_id)
                        ->where('binary_report_date', '>=',  $date)
                        ->sum('binary_report_s_points_l'); 
                        $points_r = Tbl_mlm_binary_report::where('binary_report_slot', Self::$slot_id)
                        ->where('binary_report_date', '>=',  $date)
                        ->sum('binary_report_s_points_r'); 
                        if($points_l >= $request_item->binary_promotions_required_left)
                        {
                            if($points_r >= $request_item->binary_promotions_required_right)
                            {
                                // consume inventory
                                $a = Warehouse::inventory_consume($warehouse->warehouse_id, 'Used for consuming of inventory in binary promotions', $request_item,Self::$customer_id, 'Used for consuming of inventory in binary promotions', 'array');
                                if($a['status'] == 'error')
                                {
                                     $send['response_status']      = "warning";
                                     $send['warning_validator'][0] = $a['status_message'];
                                     $data['status'] = 'Error';
                                     $data['message'] = $a['status_message'];
                                }
                                else
                                {
                                    // give voucher
                                    Mlm_voucher::give_voucher_binary_promotions($request_item, Self::$customer_id, Self::$slot_id);
                                    $data['status'] = 'success';
                                    $data['message'] = 'Success, you can now claim your item, just get the voucher code and pin in the voucher tab.';

                                    $update['binary_promotions_no_of_units_used'] = $request_item->binary_promotions_no_of_units_used + 1;
                                    Tbl_mlm_plan_binary_promotions::where('binary_promotions_id', $request_item->binary_promotions_id)->update($update);
                                    
                                    $insert['promotions_request_slot'] = Self::$slot_id;
                                    $insert['promotions_request_binary_promotions_id'] = $binary_promotions_id; 
                                    $insert['promotions_request_item_name'] = $request_item->item_name;
                                    $insert['promotions_request_date'] = Carbon::now();
                                    $insert['promotions_request_consume_l'] = $request_item->binary_promotions_required_left;
                                    $insert['promotions_request_consume_r'] = $request_item->binary_promotions_required_right;
                                    Tbl_mlm_plan_binary_promotions_log::insert($insert);

                                    $insert_report['binary_report_s_points_l'] = $request_item->binary_promotions_required_left * (-1);
                                    $insert_report['binary_report_s_points_r'] = $request_item->binary_promotions_required_right * (-1); 
                                    $insert_report['binary_report_reason'] = 'Binary Promotions';
                                    $insert_report['binary_report_date'] = Carbon::now();
                                    $insert_report['binary_report_slot'] = Self::$slot_id;
                                    $insert_report['binary_report_slot_g'] = Self::$slot_id;
                                    Tbl_mlm_binary_report::insert($insert_report);
                                }
                            }
                            else
                            {
                                $data['status'] = 'error';
                                $data['message'] = 'Insuficient right points';
                            }
                        }
                        else
                        {
                            $data['status'] = 'error';
                            $data['message'] = 'Insuficient left points';
                        }
                    }
                    else
                    {
                        $data['status'] = 'error';
                        $data['message'] = 'Sorry, you already claimed this promotions';
                    }
                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = $request_item->item_name . ' has 0 inventory, please contact the administrator';
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] = 'Invalid warehouse, please contact the administrator';
            }
        }
        else
        {
            $data['status'] = 'error';
            $data['message'] = 'Invalid Request';
        }
        return Redirect::back()->with($data);
        return $data;
    }
    public function school_wallet()
    {
        $data = [];
        $customer_id = Self::$customer_id;
        $data['reciept'] = DB::table('tbl_merchant_school_wallet')
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_merchant_school_wallet.merchant_school_custmer_id')
        ->where('merchant_school_custmer_id', $customer_id)
        ->get();
        $all_wallet = DB::table('tbl_merchant_school_wallet')->where('merchant_school_custmer_id', Self::$customer_id)->sum('merchant_school_amount');
        $data['current_school_wallet'] = $all_wallet;
        return view('mlm.report.report_school_wallet', $data);
    }
    public function merchant_school_get()
    {
        $id = Request::input('merchant_school_id');

        $data['reciept'] = DB::table('tbl_merchant_school_wallet')
        ->where('merchant_school_id', $id)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_merchant_school_wallet.merchant_school_custmer_id')
        ->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_owner', '=', 'tbl_customer.customer_id')
        ->first();
        $shop_id = Self::$shop_info->shop_id;
        $data["shop_address"]    = Self::$shop_info->shop_street_address;
        $data["shop_contact"]    = Self::$shop_info->shop_contact;
        $data['company_name']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_name')->value('value');
        $data['company_email']   = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'company_email')->value('value');
        $data['company_logo']    = DB::table('tbl_content')->where('shop_id', $shop_id)->where('key', 'receipt_logo')->value('value');
        if(Request::input('pdf') == 'true')
        {
            $view = view('member.merchant_school.reciept', $data);
            return Pdf_global::show_pdf($view);
        }
        else
        {
            return view('member.merchant_school.reciept', $data);
        }
    }
}