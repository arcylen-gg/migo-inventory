<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_slot_wallet_log extends Model
{
	protected $table = 'tbl_mlm_slot_wallet_log';
	protected $primaryKey = "wallet_log_id";
    public $timestamps = false;

    // wallet_log_status
    // n_ready = not ready for releasing
    // realeased
    
    public function scopeSponsorslot($query)
    {
    	return $query->leftjoin("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_mlm_slot_wallet_log.wallet_log_slot_sponsor");
    }
    public function scopeSlot($query)
    {
        return $query->leftjoin("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_mlm_slot_wallet_log.wallet_log_slot");
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner');
        return $query;
    }
    public function scopeRecaptcha($query)
    {
        $query->leftjoin('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_slot_wallet_log.wallet_log_slot');
        return $query;
    }
}
