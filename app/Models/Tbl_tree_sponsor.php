<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_tree_sponsor extends Model
{
    protected $table = 'tbl_tree_sponsor';
	protected $primaryKey = "sponsor_tree_id";
    public $timestamps = false;
    
    public function scopeChild($query, $slot_id)
    {
        return $query->where("sponsor_tree_child_id", $slot_id);
    }
    public function scopeParent_info($query)
    {
        return $query->join("tbl_mlm_slot", "tbl_mlm_slot.slot_id", "=", "tbl_tree_sponsor.sponsor_tree_parent_id");
    }
    public function scopeChild_info($query)
    {
        return $query->join("tbl_mlm_slot", "tbl_mlm_slot.slot_id", "=", "tbl_tree_sponsor.sponsor_tree_child_id");
    }
    public function scopeLevel($query)
    {
        return $query->orderby("sponsor_tree_level", "desc");
    }
    public function scopeDistinct_level($query)
    {
        return $query->groupBy("sponsor_tree_level");
    }
    public function scopeLevel_up($query)
    {
        return $query->orderby("sponsor_tree_level", "asc");
    }
    public function scopeParentSlot($query)
    {
        return $query->leftJoin("tbl_mlm_slot",'tbl_mlm_slot.slot_id','=','tbl_tree_sponsor.sponsor_tree_parent_id');
    }
    public function scopeMembership($query)
    {
        $query->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_mlm_slot.slot_membership');
        return $query;
    }
    public function scopeMembership_points($query)
    {
        $query->leftjoin('tbl_membership_points', 'tbl_membership_points.membership_id', '=', 'tbl_membership.membership_id');
        return $query;
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner');
        return $query;
    }
    
    // no code yet do not use
    public function scopeStair($query)
    {
        return $query->leftJoin('tbl_stairstep','tbl_stairstep.stairstep_slot_id','=','tbl_slot.slot_id');
    }
    public function scopeSettings($query)
    {
        return $query->leftJoin('tbl_stairstep_settings', 'tbl_stairstep_settings.stairstep_level', '=', 'tbl_stairstep.stairstep_settings_id');
    }
    // end
    
}
