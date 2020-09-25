<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_triangle_repurchase_slot extends Model
{
    protected $table = 'tbl_mlm_triangle_repurchase_slot';
    protected $primaryKey = "repurchase_slot_id";
    public $timestamps = false;
    
    public function scopeId($query, $slot_id)
    {
        return $query->where("repurchase_slot_id", $slot_id);
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_owner');
        return $query;
    }
}
