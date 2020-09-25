<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_triangle_repurchase_tree extends Model
{
    protected $table = 'tbl_mlm_triangle_repurchase_tree';
	protected $primaryKey = "tree_repurchase_id";
    public $timestamps = false;
    
    public function scopeChild($query, $slot_id)
    {
        return $query->where("tree_repurchase_slot_child", $slot_id);
    }
    public function scopeParent_info($query)
    {
        return $query->join("tbl_mlm_triangle_repurchase_slot", "tbl_mlm_triangle_repurchase_slot.repurchase_slot_id", "=", "tbl_mlm_triangle_repurchase_tree.tree_repurchase_slot_sponsor");
    }
    public function scopeLevel($query)
    {
        return $query->orderby("tree_repurchase_tree_level", "desc");
    }
    public function scopeDistinct_level($query)
    {
        return $query->groupBy("tree_repurchase_tree_level");
    }
    public function scopeParentSlot($query)
    {
        return $query->join("tbl_mlm_triangle_repurchase_slot", "tbl_mlm_triangle_repurchase_slot.repurchase_slot_id", "=", "tbl_mlm_triangle_repurchase_tree.tree_repurchase_slot_sponsor");
    }
    
    public function scopeChildSlot($query)
    {
        return $query->leftJoin("tbl_mlm_triangle_repurchase_slot",'tbl_mlm_triangle_repurchase_slot.repurchase_slot_id','=','tbl_mlm_triangle_repurchase_tree.tree_repurchase_slot_child');
    }
    public function scopeAccount($query)
    {
       return $query->leftJoin("tbl_customer",'tbl_mlm_triangle_repurchase_slot.repurchase_slot_owner','=','tbl_customer.customer_id'); 
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_owner');
	    return $query;
    }
}
