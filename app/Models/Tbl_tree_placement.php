<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_tree_placement extends Model
{
    protected $table = 'tbl_tree_placement';
	protected $primaryKey = "placement_tree_id";
    public $timestamps = false;
    
    public function scopeChild($query, $slot_id)
    {
        return $query->where("placement_tree_child_id", $slot_id);
    }
    public function scopeParent_info($query)
    {
        return $query->join("tbl_mlm_slot", "tbl_mlm_slot.slot_id", "=", "tbl_tree_placement.placement_tree_parent_id");
    }
    public function scopeLevel($query)
    {
        return $query->orderby("placement_tree_level", "desc");
    }
    public function scopeDistinct_level($query)
    {
        return $query->groupBy("placement_tree_level");
    }
    public function scopeParentSlot($query)
    {
        return $query->leftJoin("tbl_mlm_slot",'tbl_mlm_slot.slot_id','=','tbl_tree_placement.placement_tree_parent_id');
    }
    
    public function scopeChildSlot($query)
    {
        return $query->leftJoin("tbl_mlm_slot",'tbl_mlm_slot.slot_id','=','tbl_tree_placement.placement_tree_child_id');
    }
    public function scopeAccount($query)
    {
       return $query->leftJoin("tbl_customer",'tbl_mlm_slot.slot_owner','=','tbl_customer.customer_id'); 
    }
    public function scopeMembership($query)
    {
       return $query->leftJoin("tbl_membership",'tbl_membership.membership_id','=','tbl_mlm_slot.slot_membership'); 
    }
    public function scopeMembershipEntry($query)
    {
       return $query->leftJoin("tbl_membership",'tbl_membership.membership_id','=','tbl_mlm_slot.membership_entry_id'); 
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
}
