<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_lead extends Model
{
	protected $table = 'tbl_mlm_lead';
	protected $primaryKey = "lead_id";
    public $timestamps = false;

    public function scopeCustomer($query)
    {
    	return $query->leftjoin('tbl_customer', 'tbl_customer.customer_id', '=' , 'tbl_mlm_lead.lead_customer_id_lead');
    }
    public function scopeCustomer_address($query)
    {
    	return $query->leftjoin('tbl_customer_address', 'tbl_customer_address.customer_address_id', '=' , 'tbl_mlm_lead.lead_customer_id_lead');
    }
     public function scopeCustomer_other_info($query)
    {
    	return $query->leftjoin('tbl_customer_other_info', 'tbl_customer_other_info.customer_other_info_id', '=' , 'tbl_mlm_lead.lead_customer_id_lead');
    }
     public function scopeMlm_slot($query)
    {
    	return $query->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=' , 'tbl_mlm_lead.lead_slot_id_lead');
    }
}