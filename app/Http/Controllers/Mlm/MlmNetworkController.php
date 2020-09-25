<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_binary_setttings;
class MlmNetworkController extends Mlm
{
    public function index()
    {

    }
    public function unilevel()
    {
        if(Self::$slot_id != null)
        {
            $data = [];
            $data['tree_level'] = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)->distinct_level()->paginate(1);
            $page = Request::input('page');
            if($page == null)
            {
                $page = 1;
            }

            $tree = Tbl_tree_sponsor::where('sponsor_tree_parent_id', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_sponsor.sponsor_tree_child_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
            // ->join('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id')
            ->orderBy('tbl_tree_sponsor.sponsor_tree_level', 'ASC')
            ->where('sponsor_tree_level', $page)
            ->paginate(20, ['*'], 'tree');
            // ->get();
            $data['tree_p'] = $tree;
            $data['tree'] = [];
            foreach($tree as $key => $value)
            {
                $data['tree'][$value->sponsor_tree_level][$value->slot_id] = $value;
            }
            // dd($data);
            return view('mlm.network.unilevel', $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
    public function binary()
    {
        if(Self::$slot_id != null)
        {
            $data = [];
            $binary_advance_pairing = Tbl_mlm_binary_setttings::where('shop_id', Self::$shop_id)->first();
            if(isset($binary_advance_pairing->binary_settings_max_tree_level))
            {
                $binary_settings_max_tree_level = $binary_advance_pairing->binary_settings_max_tree_level;
            }
            else
            {
                $binary_settings_max_tree_level = 999;
            }

            $data['tree_level'] = Tbl_tree_placement::where('placement_tree_parent_id', Self::$slot_id)
            ->distinct_level()
            ->where('placement_tree_level', '<=', $binary_settings_max_tree_level)
            ->paginate(1);

            $page = Request::input('page');
            if($page == null)
            {
                $page = 1;
            }
            $tree = Tbl_tree_placement::where('placement_tree_parent_id', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_placement.placement_tree_child_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
            // ->leftjoin('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id')
            ->where('placement_tree_position', 'left')
            ->where('placement_tree_level', $page)
            
            ->orderBy('tbl_tree_placement.placement_tree_level', 'ASC')
            ->get();
            $data['tree_left'] = [];
            $data['tree_right'] = [];
            foreach($tree as $key => $value)
            {
                $data['tree_left'][$value->placement_tree_level][$value->slot_id] = $value;
            }

            $tree = Tbl_tree_placement::where('placement_tree_parent_id', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_placement.placement_tree_child_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
            // ->leftjoin('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id')
            ->where('placement_tree_position', 'right')
            ->where('placement_tree_level', '<=', $binary_settings_max_tree_level)
            ->where('placement_tree_level', $page)
            ->get();
            foreach($tree as $key => $value)
            {
                $data['tree_right'][$value->placement_tree_level][$value->slot_id] = $value;
            }
            // dd($data);

            $data['left_count'] = Tbl_tree_placement::where('placement_tree_parent_id', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_placement.placement_tree_child_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
            // ->leftjoin('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id')
            ->where('placement_tree_position', 'left')
            ->where('placement_tree_level', $page)
            ->orderBy('tbl_tree_placement.placement_tree_level', 'ASC')
            ->count();

            $data['right_count'] = Tbl_tree_placement::where('placement_tree_parent_id', Self::$slot_id)
            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_tree_placement.placement_tree_child_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
            ->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership')
            // ->leftjoin('tbl_membership_code', 'tbl_membership_code.slot_id', '=', 'tbl_mlm_slot.slot_id')
            ->where('placement_tree_position', 'right')
            ->where('placement_tree_level', $page)
            ->orderBy('tbl_tree_placement.placement_tree_level', 'ASC')
            ->count();
            return view('mlm.network.binary', $data);
        }
        else
        {
            return Self::show_no_access();
        }
    }
    
}